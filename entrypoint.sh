#!/bin/bash
set -eu

if [[ -f wp-config.php ]]; then
    echo >&2 "wp-config.php is already present. Database will not be overridden and files are kept as is."

elif [[ "$1" == apache2* ]] || [ "$1" == php-fpm ] || [[ "$1" == localrun ]]; then

	echo >&2 ' Wait 10 seconds for DataBase'
	sleep 20
	: ${WP_DOMAIN:=${WP_DOMAIN:-localhost}}
	: ${WP_URL:=${WP_URL:-http://localhost}}
	: ${WP_LOCALE:=${WP_LOCALE:-en_US}}
	: ${WP_SITE_TITLE:=${WP_SITE_TITLE:-WordPress for development}}
	: ${WP_ADMIN_USER:=${WP_ADMIN_USER:-admin}}
	: ${WP_ADMIN_PASSWORD:=${WP_ADMIN_PASSWORD:-admin}}
	: ${WP_ADMIN_EMAIL:=${WP_ADMIN_EMAIL:-admin@example.com}}
	: ${HTTPS:=off}
	: "${WP_DB_HOST:=mysql}"
	# if we're linked to MySQL and thus have credentials already, let's use them
	: ${WP_DB_USER:=${MYSQL_ENV_MYSQL_USER:-root}}
	if [ "$WP_DB_USER" = 'root' ]; then
		: ${WP_DB_PASSWORD:=$MYSQL_ENV_MYSQL_ROOT_PASSWORD}
	fi
	: ${WP_DB_PASSWORD:=$MYSQL_ENV_MYSQL_PASSWORD}
	: ${WP_DB_NAME:=${MYSQL_ENV_MYSQL_DATABASE:-wordpress}}

	if [ -z "$WP_DB_PASSWORD" ]; then
		echo >&2 'error: missing required WP_DB_PASSWORD environment variable'
		echo >&2 '  Did you forget to -e WP_DB_PASSWORD=... ?'
		echo >&2
		echo >&2 '  (Also of interest might be WP_DB_USER and WORDPRESS_DB_NAME.)'
		exit 1
	fi

	wp cli --allow-root update --nightly --yes

	# Generate the wp-config file for debugging.
	wp core --allow-root config \
		--dbhost="$WP_DB_HOST" \
		--dbname="$WP_DB_NAME" \
		--dbuser="$WP_DB_USER" \
		--dbpass="$WP_DB_PASSWORD" \
		--locale="$WP_LOCALE" \
		--path="/var/www/html/" \
		--extra-php <<PHP
define( 'WP_USE_EXT_MYSQL', false);

if ((isset(\$_ENV["HTTPS"]) && ("on" == \$_ENV["HTTPS"]))
|| (isset(\$_SERVER["HTTP_X_FORWARDED_SSL"]) && (strpos(\$_SERVER["HTTP_X_FORWARDED_SSL"], "1") !== false))
|| (isset(\$_SERVER["HTTP_X_FORWARDED_SSL"]) && (strpos(\$_SERVER["HTTP_X_FORWARDED_SSL"], "on") !== false))
|| (isset(\$_SERVER["HTTP_CF_VISITOR"]) && (strpos(\$_SERVER["HTTP_CF_VISITOR"], "https") !== false))
|| (isset(\$_SERVER["HTTP_CLOUDFRONT_FORWARDED_PROTO"]) && (strpos(\$_SERVER["HTTP_CLOUDFRONT_FORWARDED_PROTO"], "https") !== false))
|| (isset(\$_SERVER["HTTP_X_FORWARDED_PROTO"]) && (strpos(\$_SERVER["HTTP_X_FORWARDED_PROTO"], "https") !== false))
|| (isset(\$_SERVER["HTTP_X_PROTO"]) && (strpos(\$_SERVER["HTTP_X_PROTO"], "SSL") !== false))
) {
   \$_SERVER["HTTPS"] = "on";
}
define('FS_METHOD','direct');
PHP
	# Check/Create the database.
	wp db --allow-root check || wp db --allow-root create

	# Install WordPress.
	wp core --allow-root install \
		--url="${WP_URL}" \
		--title="${WP_SITE_TITLE}" \
		--admin_user="${WP_ADMIN_USER}" \
		--admin_password="${WP_ADMIN_PASSWORD}" \
		--admin_email="${WP_ADMIN_EMAIL}" \
		--skip-email

   	wp theme --allow-root activate teachme
	wp plugin --allow-root activate --all

	# Add domain to hosts file. Required for Boot2Docker.
	echo "127.0.0.1 ${WP_DOMAIN}" >> /etc/hosts

	chmod -R 0755 /var/www/html/wp-content/uploads
	chown -R www-data:www-data /var/www/html

	if [[ "$1" == 'localrun' ]]; then

		wp --allow-root db import teachme.sql
	    # rm -f name_bd.sql

		wp --allow-root user update admin --user_pass=${WP_ADMIN_PASSWORD} --user_email=${WP_ADMIN_EMAIL} --display_name=Admin --nickname=${WP_ADMIN_USER} --first_name="Admin" --last_name="PupKin" --description="Generated user"

   		wp --allow-root search-replace 'old_dom' 'localhost' --skip-columns=guid
		wp --allow-root search-replace 'https://' 'http://' --skip-columns=guid

		echo >&2 "Access the WordPress admin panel here ${WP_URL}"
		apache2-foreground
   	else
		rm -f teachme.sql
	fi
fi

exec "$@"
