<div class="jws_wishlist">
    <div class="jws-wishlist-nav">
        <a href="<?php echo esc_url($url); ?>" <?php echo esc_attr($target.$nofollow); ?>>
             <span class="wishlist_icon">
                 <?php \Elementor\Icons_Manager::render_icon( $settings['icon'], [ 'aria-hidden' => 'true' ] );  ?>
                 <span class="jws_wishlist_count"><?php ?><?php echo YITH_WCWL()->count_products(); ?></span>
             </span> 
             <?php  if(!empty($settings['wishlist_text'])) : ?>
                 <span class="wishlist_text">
                    <?php echo esc_html($settings['wishlist_text']); ?>
                 </span>
             <?php endif; ?>
        </a>
    </div>
</div>
