<?php
$args = array(
    'post_type' => 'news',
    'order'  => 'DESC',
    'publish' => true,
    'posts_per_page' => 3,
);
?>
<?php
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
} ?>

<?php if ($the_query->max_num_pages > 1) { ?>
    <div class="news_loadmore"><?php _e('Load More...'); ?></div>
<?php } ?>