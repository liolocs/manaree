<?php 
    global $jws_option; 
    $meta_layout = get_post_meta( get_the_ID(), 'shop_single_layout', true );
    $layout = ($meta_layout) ? $meta_layout : ( jws_theme_get_option('shop_single_layout') ? jws_theme_get_option('shop_single_layout') : 'default' );
?>
<div class="container">
<div class="row shop-single-nav"> 
    <div class="col-xl-8 col-lg-8 col-12">
        <?php if(jws_theme_get_option('product-single-breadcrumb')) echo '<div class="breadcrumb">'.jws_page_breadcrumb('/').'</div>'; ?>
    </div>
    <div class="col-xl-4 col-lg-4 col-12">
        <?php jws_product_nav_single();  ?>
    </div>
</div> 
<div class="row">
        <div class="col-xl-6 col-lg-6 col-12">
        	<?php
            	/**
            	 * Hook: woocommerce_before_single_product_summary.
            	 *
            	 * @hooked woocommerce_show_product_sale_flash - 10
            	 * @hooked woocommerce_show_product_images - 20
            	 */
            	do_action( 'woocommerce_before_single_product_summary' );
        	?>
        </div>
        <div class="col-xl-6 col-lg-6 col-12">
        	<div class="summary entry-summary">
                
        		<?php
                 /**
                 * woocommerce_single_product_summary hook
                 *
                 * @hooked jws_qv_product_summary_open - 1
                 * @hooked woocommerce_template_single_title - 5
                 * @hooked woocommerce_template_single_price - 10
                 * @hooked jws_qv_product_summary_divider - 15
                 * @hooked woocommerce_template_single_excerpt - 20
                 * @hooked woocommerce_template_single_rating - 21
                 * @hooked woocommerce_template_single_add_to_cart - 30
                 * @hooked jws_qv_product_summary_actions - 30
                 * @hooked woocommerce_template_single_sharing - 50
                 * @hooked jws_qv_product_summary_close - 100
                 */
                do_action('woocommerce_single_product_summary');
        		?>
        	</div>
        </div>
    </div>
    </div>
    <div class="row product-tabs">
        <div class="col-xl-12 col-lg-12 col-12">
            <?php woocommerce_output_product_data_tabs(); ?>
        </div>  
    </div>
    <div class="container">
    	<?php
        
    	/**
    	 * Hook: woocommerce_after_single_product_summary.
    	 *
    	 * @hooked woocommerce_output_product_data_tabs - 10
    	 * @hooked woocommerce_upsell_display - 15
    	 * @hooked woocommerce_output_related_products - 20
    	 */
    	do_action( 'woocommerce_after_single_product_summary' );
        ?>
    </div>
    <?php 
    if ( is_active_sidebar( 'sidebar-single-shop-popup' ) ) { ?>
        <a class="product-search-single toggle-this" href="javascript:void(0)">
            <i class="jws-icon-icon_search2"></i>
            <span><span><?php echo esc_html__('Find perfect fit','meathouse'); ?></span></span>
        </a>
        <div class="single-form-popp">
        <div class="overlay toggle-this"></div>
        <div class="widget-inner">
        <span class="jws-icon-icon_close close-search-popup toggle-this"></span>
        <?php dynamic_sidebar( 'sidebar-single-shop-popup' ); ?>
        </div>
        </div>   
    <?php } 