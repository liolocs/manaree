<?php

 if ( !class_exists( 'jws_Jws_Demo' ) ) :

	class jws_Jws_Demo {


		function __construct() {
	       add_action( 'wp_enqueue_scripts', array( $this, 'styles_and_scripts' ) );
	       add_action ('wp_footer',array( $this, 'jws_show_demo' ));
           
           add_action('wp_ajax_jws_view_demo_content', array( $this,  'jws_view_demo_content' ) );
           add_action('wp_ajax_nopriv_jws_view_demo_content', array( $this,  'jws_view_demo_content' ));
            
		}
        
        
         
        public function styles_and_scripts(  ) {
            wp_enqueue_style( 'jws_demo_css', plugin_dir_url(__FILE__) . '/demo_assets/demo_view1.css' );
			wp_enqueue_script( 'jws_demo_js', plugin_dir_url(__FILE__) . "/demo_assets/demo_view.js", array(), '1', true );
            
            wp_localize_script(
        			'jws_demo_js',
        			'jws_script',
        			array(
        				'ajax_url'  => admin_url( 'admin-ajax.php' ),
        	   )
        	);   
            
		}
        
        
        public function jws_view_demo_content() {
            
            ?>      
                    <div class="demo_item">
                        <a target="_blank" href="https://meathouse.jwsuperthemes.com/">
                        <img  src="<?php echo plugin_dir_url(__FILE__).'demo_assets/images/demo1.jpg'; ?>"/>
                        <span>Meat Box Home</span>
                        </a>
                    </div> 
                    <div class="demo_item">
                        <a target="_blank" href="https://meathouse.jwsuperthemes.com/home-2/">
                        <img  src="<?php echo plugin_dir_url(__FILE__).'demo_assets/images/demo2.jpg'; ?>"/>
                        <span>Meat Delivery</span>
                        </a>
                    </div> 
                    <div class="demo_item">
                        <a target="_blank" href="https://meathouse.jwsuperthemes.com/home-3/">
                        <img  src="<?php echo plugin_dir_url(__FILE__).'demo_assets/images/demo3.jpg'; ?>"/>
                        <span>Butcher Store</span>
                        </a>
                    </div>    
                    <div class="demo_item">
                        <a target="_blank" href="https://meathouse.jwsuperthemes.com/home-4/">
                        <img  src="<?php echo plugin_dir_url(__FILE__).'demo_assets/images/demo4.jpg'; ?>"/>
                        <span>Interactive Slider</span>
                        </a>
                    </div>
                    <div class="demo_item">
                        <a target="_blank" href="https://meathouse.jwsuperthemes.com/home-5/">
                        <img  src="<?php echo plugin_dir_url(__FILE__).'demo_assets/images/demo5.jpg'; ?>"/>
                        <span>Split-screen Home</span>
                        </a>
                    </div>
                  
                    
            <?php
            
            die;
            
        }
        
        
        public function jws_show_demo() {
            ?>
                <div class="jws_demo_word">
                    <div class="jws_demo_content">
                        <div class="jws_demo_overlay"></div>
                        <div class="jws_demo_content_inner">
                        <div class="jws_top">
                            <a target="_blank" href="https://themeforest.net/item/zahar-creative-multipurpose-elementor-wordpress-theme/27401236" class="purchase-button">Buy Zahar Now</a>
                            <h4>Meathouse Theme Demos</h4>
                        </div>
                        <div class="jws_demo_items">
                        </div>
                        </div>
                    </div>
                    <div class="jws_demo_nav">
                        <a href="javascript:void(0);" class="jws_show_demo_action">
                        <img class="open_demo" src="<?php echo plugin_dir_url(__FILE__).'demo_assets/images/view.svg'; ?>" />
                        <img class="close_demo" src="<?php echo plugin_dir_url(__FILE__).'demo_assets/images/close.svg'; ?>" />
                        <span><?php echo esc_html__('Demos','zahar'); ?></span>
                        </a>
                        
                        <a target="_blank" href="https://themeforest.net/item/zahar-creative-multipurpose-elementor-wordpress-theme/27401236">
                        <img src="<?php echo plugin_dir_url(__FILE__).'demo_assets/images/buy.svg'; ?>" />
                        <span><?php echo esc_html__('Buy Now','zahar'); ?></span>
                        </a>
                    </div>
                </div>
            <?php
        }  

        
        
        }
   endif;   

new jws_Jws_Demo(); 
