<?php get_header(); ?>
<main>
    <section class="hero">
        <div class="container">
            <div class="hero_title">
                <h1><?php the_title(); ?> </h1>
                <a href="/post/" target="_blank" class="hero_btn"><?php _e('Click me '); ?></a>
            </div>
        </div>
    </section>
</main>
<?php get_footer(); ?>