  <div class="product-item-inner grid">
    <div class="grid_top">
    <div class="product-image">
        <?php 
            /**
        	 * Hook: woocommerce_before_shop_loop_item.
        	 *
        	 * @hooked woocommerce_template_loop_product_link_open - 10
        	 */
        	do_action( 'woocommerce_before_shop_loop_item' );
        
        	/**
        	 * Hook: woocommerce_before_shop_loop_item_title.
        	 *
        	 * @hooked woocommerce_show_product_loop_sale_flash - 10
        	 * @hooked woocommerce_template_loop_product_thumbnail - 10
        	 */
      
        	do_action( 'woocommerce_before_shop_loop_item_title' );
            $size = isset($image_size) && !empty($image_size) ? $image_size : 'woocommerce_thumbnail';
            echo woocommerce_get_product_thumbnail($size);
            
        	/**
        	 * Hook: woocommerce_after_shop_loop_item.
        	 *
        	 * @hooked woocommerce_template_loop_product_link_close - 5
        	 * @hooked woocommerce_template_loop_add_to_cart - 10
        	 */
        	do_action( 'woocommerce_after_shop_loop_item' );
            ?>
      
    </div>
  
	<?php

    /**
	 * Hook: woocommerce_shop_loop_item_title.
	 *
	 * @hooked woocommerce_template_loop_product_title - 10
	 */
 
	do_action( 'woocommerce_shop_loop_item_title' );
    if(isset($settings['show_text_starting']) && $settings['show_text_starting'] == 'true') {
        echo '<div class="starting">'.esc_html__('Starting at','meathouse').'</div>';
    }
    echo '<div class="price-attr">';
        product_attribute();
        woocommerce_template_loop_price();
    echo '</div>';
    $points = get_post_meta( get_the_ID(), '_box_points', true );
	?>
    </div>
    <div class="grid_bottom">
    <div class="box_action">
        <span class="points">
            <?php
               $pts = jws_theme_get_option('pts_text','pts');
               echo !empty($points) ? esc_html($points.$pts) : '0'.$pts;
             ?>
        </span>
        <a href="javascript:void(0);" class="add_to_box btn-main" product-id="<?php echo get_the_ID(); ?>"><i class="jws-icon-glyph-23"></i><span><?php echo jws_add_to_box_text(get_the_ID()) ;?></span></a>
    </div>
    </div>
  </div>  