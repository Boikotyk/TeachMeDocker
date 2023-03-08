<?php
/* Template Name:  News Page */ ?>

<?php get_header();
global $wp_query; ?>

<main>
    <section class="news">
        <div class="container">
            <div id="response">
                <?php get_template_part('templates/post', 'list'); ?>
            </div>
        </div>
        </div>
    </section>
</main>
<?php get_footer(); ?>