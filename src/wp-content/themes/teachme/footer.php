    <footer class="footer">
      <div class="container">
        <div class="footer_inner">
          <?php $custom_logo_url = wp_get_attachment_image_src(get_theme_mod('custom_logo'), 'full'); ?>
          <div class="footer_logo">
            <?php if (!empty($custom_logo_url[0])) { ?>
              <a href="<?php echo home_url('/'); ?>" class="logo_link">
                <img src="<?php echo $custom_logo_url[0]; ?>" alt="company logo" class="logo_img">
              </a>
            <?php } else {
              echo 'TeachMe';
            } ?>
          </div>
        </div>
      </div>
    </footer>
    <?php wp_footer(); ?>
    </body>

    </html>