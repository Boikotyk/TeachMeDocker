<?php
require_once("assets/inc/scripts-and-styles.php");
require_once('assets/inc/post-types.php');

function theme_setup()
{
    add_theme_support('post-thumbnails');
    add_theme_support('custom-logo');
    add_theme_support('title-tag');
    register_nav_menus(array(
        'top'    => 'Top Menu',
    ));
}
add_action('after_setup_theme', 'theme_setup');

function my_deregister_scripts()
{
    wp_deregister_script('wp-embed');
}


/*
 ==================
 Ajax Loadmore
======================	 
*/
function news_loadmore_ajax_handler()
{

    $args = json_decode(stripslashes($_POST['query']), true);
    $args['paged'] = $_POST['page'] + 1;
    $args['post_status'] = 'publish';
    $args['post_type'] = 'news';

    $the_query = new WP_Query($args);
    if ($the_query->have_posts()) { ?>
        <div class="news_wrapper">
            <?php while ($the_query->have_posts()) {
                $the_query->the_post(); ?>
                <div class="news_item">
                    <a href="<?php echo get_permalink(); ?>">
                        <img src="<?php echo get_the_post_thumbnail_url(get_the_ID(), 'large'); ?>" alt="<?php the_title(); ?>" class="news_img">
                        <div class="text_box">
                            <div class="news_title"><?php the_title(); ?></div>
                            <div class="news_cat">
                                <?php
                                $terms = get_the_terms($post->ID, 'category_news');
                                foreach ($terms as $term) {
                                    echo $term->name;
                                }
                                ?>
                            </div>
                        </div>

                    </a>
                </div>
            <?php }
            wp_reset_postdata();
            ?>
        </div>
<?php  } else {
    }
    die;
}

add_action('wp_ajax_loadmore', 'news_loadmore_ajax_handler');
add_action('wp_ajax_nopriv_loadmore', 'news_loadmore_ajax_handler');
