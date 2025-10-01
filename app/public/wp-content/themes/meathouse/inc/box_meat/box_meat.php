<?php
    
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
 
 if ( !class_exists( 'Jws_Meat_House' ) ) :
    
    
    
	class Jws_Meat_House {

		function __construct() {
	       
          if(function_exists('insert_shortcode')) {
            insert_shortcode('jws_box_meat',array($this,'shortcode_frontend'));
          } 
           
          add_action( 'wp_ajax_nopriv_add_item', array($this,'add_item') );
          add_action( 'wp_ajax_add_item', array($this,'add_item') );
           
           
          add_action( 'wp_ajax_nopriv_add_section_save', array($this,'add_section_save') );
          add_action( 'wp_ajax_add_section_save', array($this,'add_section_save') );
           
          add_action( 'wp_ajax_nopriv_save_frequency', array($this,'save_frequency') );
          add_action( 'wp_ajax_save_frequency', array($this,'save_frequency') ); 
          
          add_action( 'wp_ajax_nopriv_remove_cart_old', array($this,'remove_cart_old') );
          add_action( 'wp_ajax_remove_cart_old', array($this,'remove_cart_old') ); 
          

          add_filter( 'woocommerce_add_cart_item_data', array($this,'jws_add_item_box_data'), 10, 4 ); 
          add_filter( 'woocommerce_widget_cart_item_custom', array($this,'jws_add_item_box_data_name'), 10, 4 ); 
          
          add_filter( 'woocommerce_checkout_cart_item_quantity', array($this,'jws_add_item_box_data_name'), 1, 3 ); 
          add_filter( 'woocommerce_cart_item_price', array($this,'jws_add_item_box_data_name'), 1, 3 ); 
          
          add_action( 'woocommerce_checkout_create_order_line_item', array($this,'jws_add_customize_items_data_to_order'), 10, 4 );  

          $this->step_progrss();
          
          
          $this->edit_box_form_checkout();  

		}
        
        public function enqueue_script() {  
            global $jws_option;
            wp_enqueue_script( 'jws-box-meat', JWS_URI_PATH. '/assets/js/box_meat.js', [], '1.0' , true );
            wp_enqueue_script( 'stick-content', JWS_URI_PATH. '/assets/js/sticky_content.js', array(), '', true );
            wp_enqueue_style('owl-carousel');
            wp_enqueue_script('owl-carousel');
            wp_enqueue_script( 'jws-woocommerce');
            wp_localize_script(
			'jws-box-meat',
			'jws_script_box',
    			array(
                    'notification_limit_title'  => isset($jws_option['notification_limit_title']) && !empty($jws_option['notification_limit_title']) ? $jws_option['notification_limit_title'] : 'Max quanlity in box!',
                    'notification_limit_text'  => isset($jws_option['notification_limit_text']) && !empty($jws_option['notification_limit_text']) ? $jws_option['notification_limit_text'] : 'To add more products to your box, you must remove a product',
    
    			    'notification_success_title'  => isset($jws_option['notification_success_title']) && !empty($jws_option['notification_success_title']) ? $jws_option['notification_success_title'] : 'Success',
                    'notification_success_text'  => isset($jws_option['notification_success_text']) && !empty($jws_option['notification_success_text']) ? $jws_option['notification_success_text'] : 'The box is full.',
                    
                    'notification_select_options_title'  => isset($jws_option['notification_select_options_title']) && !empty($jws_option['notification_select_options_title']) ? $jws_option['notification_select_options_title'] : 'Hi!',
                    'notification_select_options_text'  => isset($jws_option['notification_select_options_text']) && !empty($jws_option['notification_select_options_text']) ? $jws_option['notification_select_options_text'] : 'Please Select options',
                    
                    
                    'remaining_text_left'  => isset($jws_option['remaining_text_left']) && !empty($jws_option['remaining_text_left']) ? $jws_option['remaining_text_left'] : 'Fill your box to continue -',
                    'remaining_text_right'  => isset($jws_option['remaining_text_right']) && !empty($jws_option['remaining_text_right']) ? $jws_option['remaining_text_right'] : 'points remaining',
                    'pts' => jws_theme_get_option('pts_text','pts')
                )
	       );  
        } 
        
        
        
        
        
        public function edit_box_form_checkout() { 
        
        }
        
        
        protected function get_all_product_data() { 
              $wc_attr = $this->box_query(true);  
              $product_query = new WP_Query($wc_attr);  
              return $product_query->posts;
        }
        
        protected function box_item_varitions() { 
            $return = jws_theme_get_option('choose-attr-display-meat-name');
            return $return;
        }
        
        protected function box_id() { 
            if(isset($_COOKIE['box_id'])) {
                return $_COOKIE['box_id'];
            }  
        }
        
        protected function step_value() { 
            if(isset($_COOKIE['nav_tep'])) {
                return $_COOKIE['nav_tep'];
            }  
        }
        
        protected function total_count() { 
            $id = $this->box_id();
            $points_total = get_post_meta( $id, '_box_points_total', true );
            return $points_total;   
        }
        
        
         public function step_progrss() { 
            $total_points = $this->total_count(); 
            if($total_points) {
               $tep = 'builder';
            }   

        }
        
        
        function jws_add_customize_items_data_to_order( $item, $cart_item_key, $values, $order ) {
             foreach( $item as $cart_item_key=>$values ) {
                
                
                       
             if(isset($values['meat_box_error']) && !empty($values['meat_box_error'])) {
                $product_name  = $values['meat_box_error'];
             }   
                
             if( isset( $values['meat_box'] ) ) {
                    $product_name .= '<div class="box_added">';
                    $product_name .= '<div class="box_added_content">';    
                    foreach($values['meat_box'] as $id => $count) {
                        if(isset($values['meat_box_variation'][$id])) {
                            $variation_id = $values['meat_box_variation'][$id];
                            $points = get_post_meta( $variation_id, '_box_points', true );
                            $points_html = !empty($points) ? esc_html($points) : '0';
                            $attribute_pa = get_post_meta( $variation_id, 'attribute_pa_lbs', true ); 
                            $product_name .= '<div class="item-box">'.get_the_title( $id ).' - '.$attribute_pa.' x '.$count.'<span class="points"> ('.$points_html.jws_theme_get_option('pts_text','pts').')</span></div>'; 
                        }else {
                            $points = get_post_meta( $id, '_box_points', true );
                            $points_html = !empty($points) ? esc_html($points) : '0';
                            $product_name .= '<div class="item-box">'.get_the_title( $id ).' x '.$count.'<span class="points"> ('.$points_html.jws_theme_get_option('pts_text','pts').')</span></div>';   
                        }
                       
                
                    }
                    $product_name .= '</div></div>'; 
               
             }
             
             if( isset( $values['frequency-box'] ) ) { 
               
                global $jws_option; 
                $frequency_tab = $jws_option['frequency_box'];
                    
                   
                $product_name .= '<h5 class="frequency_time"><span>'.esc_html__(' Subscription','meathouse').'</span>'.$frequency_tab['frequency_time'][$values['frequency-box']].'</h5>';
               
                
             }
    
                 if( isset( $product_name ) && !empty($product_name) ) {
                    $item->add_meta_data( __( 'Product In Box', 'meathouse' ), $product_name , true );
                 }
             }
      }
        
                    
            
        public function jws_add_item_box_data($cart_item_data, $product_id, $variation_id, $quantity) { 
            
             $total_points = $this->total_count();    
             $id = $this->box_id();   
             if($product_id != $id) {
                return $cart_item_data;
             }  
             session_start();
             if(isset( $_SESSION['jws-meat-box'] ) && !empty($_SESSION['jws-meat-box']) && !empty($_SESSION['added_points']) ) {
                 if($_SESSION['added_points'] < $total_points )  {
                    $cart_item_data['meat_box_error'] = '<p>'.esc_html__('You have not added enough products to this box','meathouse').'</p>';
                    return $cart_item_data;
                 }
                 
                 $cart_item_data['item_points_added'] = $_SESSION['item_points_added'];
                 $cart_item_data['meat_box'] = $_SESSION['jws-meat-box'];
                 $cart_item_data['meat_box_variation'] = $_SESSION['box-variation'];

             }
             if(isset( $_SESSION['frequency-box'] ) && !empty($_SESSION['frequency-box']) ) { 
                $cart_item_data['frequency-box'] = $_SESSION['frequency-box'];
             }
      
          
             session_destroy();
                 
          
       
        
                
              
          
            
             return $cart_item_data;
        }
        
            public function jws_add_item_box_data_name($product_name, $values, $cart_item_key ) { 
                  
             if(isset($values['meat_box_error']) && !empty($values['meat_box_error'])) {
                $product_name  .= $values['meat_box_error'];
                return $product_name;
             }   
                
             if( isset( $values['meat_box'] ) ) {
                    $product_name .= '<div class="box_added">';
                    $product_name .= '<div class="show_box_added">'.esc_html__('Show product added','meathouse').'</div>'; 
                    $product_name .= '<div class="box_added_content">';    
                    foreach($values['meat_box'] as $id => $count) {
                        if(isset($values['meat_box_variation'][$id])) {
                            $variation_id = $values['meat_box_variation'][$id];
                            $points = get_post_meta( $variation_id, '_box_points', true );
                            $points_html = !empty($points) ? esc_html($points) : '0';
                            $attribute_pa = get_post_meta( $variation_id, 'attribute_pa_lbs', true ); 
                            $product_name .= '<div class="item-box">'.get_the_title( $id ).' - '.$attribute_pa.' x '.$count.'<span class="points"> ('.$points_html.jws_theme_get_option('pts_text','pts').')</span></div>'; 
                        }else {
                            $points = get_post_meta( $id, '_box_points', true );
                            $points_html = !empty($points) ? esc_html($points) : '0';
                            $product_name .= '<div class="item-box">'.get_the_title( $id ).' x '.$count.'<span class="points"> ('.$points_html.jws_theme_get_option('pts_text','pts').')</span></div>';   
                        }
                       
                
                    }
                    $product_name .= '</div></div>'; 
               
             }
             
             if( isset( $values['frequency-box'] ) ) { 
               
                global $jws_option; 
                $frequency_tab = $jws_option['frequency_box'];
                    
                   
                $product_name .= '<h5 class="frequency_time"><span>'.esc_html__(' Subscription','meathouse').'</span>'.$frequency_tab['frequency_time'][$values['frequency-box']].'</h5>';
               
                
             }
             
            
             
             
             return $product_name;
            
        }
        
        public function shortcode_frontend() {
        $edit_data_choose = '';    
        if(isset($_GET['cart_key'])) {
            $edit_data =  WC()->cart->get_cart();
            $edit_data = isset($edit_data[$_GET['cart_key']]) ? $edit_data[$_GET['cart_key']] : '';
            if(isset($edit_data['meat_box']) && !empty($edit_data)) {
                $edit_data = $edit_data;
                $edit_data_choose = $edit_data;
                ?>
                    <script>
                        var edit_cart_data = <?php echo json_encode($edit_data); ?>
                    </script>
                <?php
            }  
        }
          
            
     
        $this->enqueue_script();
        $total_points = $this->total_count();  
        $id = $this->box_id();
        global $jws_option; 
        ob_start();
        $tep = $this->step_progrss();
        $number = array('wel' => '0','pri' => '1','bui' => '2','pay' => '3',);
        if($jws_option['turn_tab_welcome']) {
            $number = array(
            'wel' => '1',
            'pri' => '2',
            'bui' => '3',
            'pay' => '4',
            
            );
        }
        ?>
        <script>
             var box_meat_total = <?php echo ''.$total_points ? esc_attr($total_points) : '0'; ?>;
             var box_id = <?php echo ''.$id ? esc_attr($id) : '0'; ?>;
        </script>
        <div class="jws_box_bulder">
            
            <div class="nav_step">  
                <ul>
                    <?php if($jws_option['turn_tab_welcome']) : ?>
                        <li><a id="step_welcome" data-id="section_welcome" class="<?php if($total_points): echo 'edited'; else: echo 'current'; endif; ?>" href="javascript:void(0);"><span></span><?php echo esc_html($number['wel'].'. ').esc_html__('Welcome','meathouse'); ?></a></li>
                    <?php endif; ?>
                    <li><a id="step_pricing" data-id="section_pricing" class="<?php if($total_points) : echo 'edited'; elseif(!$jws_option['turn_tab_welcome']) : echo 'current'; endif; ?>" href="javascript:void(0);"><span></span><?php echo esc_html($number['pri'].'. ').esc_html__('Pricing','meathouse'); ?></a></li>
                    <li><a id="step_builder" data-id="section_builder" class="<?php if($total_points) echo 'current'; ?>" href="javascript:void(0);"><span></span><?php echo esc_html($number['bui'].'. ').esc_html__('Build Your Box','meathouse'); ?></a></li>
                    <li><a id="step_payment" data-id="section_builder"  href="javascript:void(0);"><span></span><?php echo esc_html($number['pay'].'. ').esc_html__('Payment','meathouse'); ?></a></li>
                </ul> 
            </div>
             <?php if($jws_option['turn_tab_welcome']) : ?>
                <div id="section_welcome" class="content_section <?php if(!$total_points) echo 'active'; ?>">
                    <?php
                    
                        $welcome = jws_theme_get_option('include_content_tab_welcome');
                        if($welcome) {
                            echo do_shortcode('[hf_template id="' . $welcome . '"]'); 
                        }
                    
                    ?>
                </div>
            <?php endif; ?>
             <div id="section_pricing" class="content_section <?php if(!$jws_option['turn_tab_welcome']) echo 'active'; ?>">
                    <?php 
                        $pricing = jws_theme_get_option('include_content_tab_box');
                        if($pricing) {
                            echo do_shortcode('[hf_template id="' . $pricing . '"]'); 
                        }
                    ?>
                </div>
           
            <div id="section_builder" class="content_section <?php if($total_points) echo 'active'; ?>">
                <?php 
                    if(empty($total_points)) {
                        return '<h5>'.esc_html__('Box Not Found , Please Choose Box Again.','meathouse').'</h5>';
                    } else {
                       ?>
                        
                         <div class="row">
                            <div class="col-bulder-left">
                                <?php  
                                    $this->box_nav($total_points);
                                    $this->box_content();
                                ?>
                            </div>
                            <div class="col-bulder-right">
                                <div class="jws_sticky_move">
                                    <div class="col-bulder-inner">
                                        <div class="overlay"></div>
                                        <div class="remove"><i class="jws-icon-cross"></i></div>
                                        <?php $this->box_choosed($edit_data_choose); ?>
                                    </div>
                                </div>    
                            </div>
                        </div>
                       
                       <?php 
                    }
                ?>
               
            </div> 
            <div id="section_payment" class="content_section"></div>
        </div>
        <?php
        return ob_get_clean();
        
        }   
        
        protected function box_nav($total_points) { 
           global $jws_option; 
           if(isset($jws_option['exclude-category-in-box'])) {
               $cat_exclude = array_map('intval', array_filter($jws_option['exclude-category-in-box'], 'is_numeric'));
            } 
            global $wp;
            if ( '' === get_option( 'permalink_structure' ) ) {
            	$form_action =  remove_query_arg( array( 'page', 'paged' ), add_query_arg( $wp->query_string, '', home_url( $wp->request ) ) );
                $link =  remove_query_arg( array( 'page', 'paged' ), add_query_arg( $wp->query_string, '', home_url( $wp->request ) ) );
            } else {
            	$form_action =  preg_replace( '%\/page/[0-9]+%', '', home_url( trailingslashit( $wp->request ) ) );
                $link =  preg_replace( '%\/page/[0-9]+%', '', home_url( trailingslashit( $wp->request ) ) );
            }
            
            if ( isset( $_GET['product_cat'] ) ) {
			$link = add_query_arg( 'product_cat', wc_clean( $_GET['product_cat'] ), $link );
    		}
    
    		if ( isset( $_GET['product_points'] ) ) {
    			$link = add_query_arg( 'product_points', wc_clean( $_GET['product_points'] ), $link );
    		}
            
            if ( isset( $_GET['search'] ) ) {
			$link = add_query_arg( 'search', wc_clean( $_GET['search'] ), $link );
    		}
            
            $link = add_parameter_after_custom_link($link);
           ?>
           
             <span class="show_box_mobile">
                <i class="jws-icon-glyph-24"></i>
            </span> 
            <span class="show_box_filter_mobile">
                <i class="jws-icon-funnel"></i>
            </span> 
          
           <div class="box_nav">
                <div class="overlay"></div>
                <div class="row nav_inner">
                    <div class="col-xl-5 col-lg-5 col-12">
                        <h3 class="nav_title"><?php echo esc_html__('Build Your Box','meathouse'); ?></h3>
                    </div>
                    <div class="col-xl-7 col-lg-7 col-12 box_filter_right">
                        <div class="row cla">
                             <div class="filter_box_item">
                               <form role="search" action="<?php echo esc_url($form_action); ?>" method="get" class="search-meatbox">
                                    <input type="text" class="s" placeholder="<?php echo esc_html__('Search product...','meathouse'); ?>" value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>" name="search">
                                    <button type="submit" class="searchsubmit"> <i aria-hidden="true" class="jws-icon-glyph-4"></i> </button>
                                    <?php wc_query_string_form_fields( null, array(  'search' ) ); ?>
                                    <span class="form-loader"></span>
                                </form>
                             </div>   
                             <div class="filter_box_item category_box_filter">
                                <span class="current"><?php echo isset($_GET['product_cat']) ? esc_html($_GET['product_cat']) :  esc_html__('Type','meathouse'); ?></span>
                                <ul>
            	        			<?php 
                                        $query = new \WP_Term_Query(array(
                                            'hide_empty' => true,
                                            'taxonomy'   => 'product_cat',
                                            'exclude' => $cat_exclude
                                        ));
                                        echo '<li data-slug=""><a href="'.remove_query_arg( 'product_cat' ).'">'.esc_html__('All','meathouse').'</a></li>';
                                        if (! empty($query->terms)) {
                                            foreach ($query->terms as $cat) {
                                                $link_cat = add_query_arg( 'product_cat', $cat->slug , $link );
                                                echo '<li data-slug="'.$cat->slug .'"><a href="'.$link_cat.'">'.$cat->name.'</a></li>';
                                            }
                                        }
                                    ?>
                                </ul>
                            </div>
                            
                            <div class="filter_box_item points_box_filter">
                                <span class="current"><?php echo isset($_GET['product_points']) ? esc_html($_GET['product_points']) :  esc_html__('Points','meathouse'); ?></span>
                                <ul>
            	        			<?php 
                                        echo '<li data-slug=""><a href="'.remove_query_arg( 'product_points' ).'">'.esc_html__('All','meathouse').'</a></li>';
                                        for($i = 1 ; $i <= $total_points ; $i++) {
                                            $link_points = add_query_arg( 'product_points', $i , $link );
                                            echo '<li><a href="'.$link_points.'">'.$i.'</a></li>'; 
                                        }
                                        
                                    ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
           </div>
       
           
           <?php
            
        }
       
        
        protected function box_choosed($edit_data_choose) { 
           $total_points = $this->total_count();
           $id = $this->box_id(); 
           $added_points = 0;
           ?>
           <div class="box_choosed">
              <div class="choosed_status">
                <div class="left">
                 <div class="point_info">
                    <h5> <i class="jws-icon-glyph-24"></i> <?php echo esc_html__('Your Box','meathouse'); ?> </h5>  
                    <p><?php echo esc_html__('Box size:','meathouse'); ?><span class="size"><?php echo get_the_title($id); ?></span></p> 
                 </div>   
                 <span class="point_pro">
                        
                       <svg
                           class="progress-ring"
                           width="25"
                           height="25">
                          <circle 
                            class="progress-ring__circle"
                            stroke="white"
                            stroke-width="4"
                            fill="transparent"
                            r="10.5"
                            cx="12.5"
                            cy="12.5"/>
                        </svg>
                 
                 </span>
                </div>
                <div class="right">
                    <span class="point_number">
                        <span class="points_added"><?php echo esc_html($added_points); ?></span><span class="points_total"> / <?php echo esc_html($total_points); ?></span>
                    </span>
                </div> 
              </div>
              <div class="choosed_item_list">
              
                <?php
                
                if(!empty($edit_data_choose)) {
                    
                   foreach($edit_data_choose['meat_box'] as $id => $value) {
                    
                            
                          $count = $value;  
                            
                    
                          if(isset($edit_data_choose['meat_box_variation'][$id]))  {
                            $variation_id = $edit_data_choose['meat_box_variation'][$id];
                            $id = wp_get_post_parent_id($variation_id);
                          }
                          
                          $products = wc_get_product($id);
                          $variation_attributes = '';
                          $vari_sug = $this->box_item_varitions();
                            if(!empty($variation_id) && $products->is_type( 'variable' ) ) {
                                $remove_id =  $id.'_'.$variation_id;
                                $variation = wc_get_product($variation_id);
                                $variation_attributes = $variation->get_variation_attributes();
                                $name_variation = ucfirst($variation_attributes['attribute_'.$vari_sug.'']);
                                $points_html = get_post_meta( $variation_id, '_box_points', true ); 
                            }else {
                                $remove_id = $id;
                                $name_variation = '';
                                $points_html  = get_post_meta( $id, '_box_points', true );  
                            }
             
                          
                          
                     ?>
                         <div class="item_box" data-product-id="<?php echo esc_attr($remove_id); ?>">
                                <div class="item-image">
                                    <?php
                                         
                                         echo '<span class="points">'.$points_html.jws_theme_get_option('pts_text','pts').'</span>'; 
                                         echo get_the_post_thumbnail( $id, 'thumbnail', array() );
                                    ?>
                                </div>
                                <div class="item-content">
                                    <?php 
                                        echo '<h6 class="woocommerce-loop-product__title"><a href="' . esc_url( get_permalink( $id ) ) . '">' . get_the_title($id) . '</a></h6>';
                                        echo '<div class="item-attr">'.$name_variation.' x <span class="count">'.$count.'</span></div>'
                                    ?>
                                </div>
                                <span class="remove-item jws-icon-cross"></span>
                       </div>
                     <?php
                   }
                }                
                
                 ?>
              
              </div>
              <div class="choosed_addtocart">
                <div class="box-continue"></div>
                  <div class="frequency-checkout">
                    <?php 
                        global $jws_option; 
                        $frequency_tab = $jws_option['frequency_box'];   
                        
                        if(!empty($frequency_tab['redux_repeater_data'])) {
                        $i = 0;    
                             foreach($frequency_tab['redux_repeater_data'] as $value) {
                                if(!empty($edit_data_choose['frequency-box']) && $edit_data_choose['frequency-box'] == $i) {
                                    $checked = 'checked';
                                }else{
                                    $checked = '';
                                }
                                ?>
                                    <div class="frequency_item" data-frequency="<?php echo esc_attr($i); ?>">
                                        
                                      <input id="frehoice<?php echo esc_attr($i); ?>" type="radio" name="frequency_choose" value="<?php echo esc_attr($i); ?>" <?php echo esc_attr($checked); ?>>
                                      <label for="frehoice<?php echo esc_attr($i); ?>">   <?php echo esc_html($frequency_tab['frequency_time'][$i]); ?> </label>
                                      
                                    </div>
                                <?php
                                  
                             $i++; }
                        }
                    ?>
                </div>
                <a class="add_to_checkout diable" <?php if(isset($_GET['cart_key'])) echo 'data-cart_key="'.$_GET['cart_key'].'"'; ?> href="javascript:void(0);"><span><?php echo esc_html__('Add to cart','meathouse'); ?></span></a>
              </div>
           </div>
           
           
           <?php
            
        }
        
        

        protected function box_query($get_id = false) { 
            global $jws_option;     
            
            $paged = (get_query_var('paged')) ? get_query_var('paged') : 1; 

            $post_type = 'product';
 
            $wc_attr = array(
                'post_type'         => $post_type,
                'posts_per_page'    => '-1',
                'paged'             => $paged,
                'orderby'           => $jws_option['box_orderby'],
                'order'             => $jws_option['box_order'],
                'meta_query' => array(
                     array(
                     	'key'       => '_stock_status',
                        'value'     => 'outofstock',
                        'compare'   => 'NOT IN'
                    )
                ),
             ); 
  
             
            if($get_id) {
              $wc_attr['fields']  = 'ids';
            } 
            
            if($jws_option['query_box_type'] == 'custom_query') {
                if(isset($jws_option['include-category-in-box'])) {
                    $wc_attr['product_cat'] = implode(',', $jws_option['include-category-in-box']);
                }
                if(isset($jws_option['exclude-category-in-box'])) {
                    $wc_attr['tax_query'] = array( array(
                        'taxonomy' => 'product_cat',
                        'field'    => 'term_id',
                        'terms'    =>  $jws_option['exclude-category-in-box'], // Term ids to be excluded
                        'operator' => 'NOT IN' // Excluding terms
                    ) );
                }  
                if(isset($jws_option['exclude-product-in-box'])) {
                   $result = array_map('intval', array_filter($jws_option['exclude-product-in-box'], 'is_numeric'));
                   $wc_attr['post__not_in']  = $result;
                }
            }
            
            /* Filter box */
            if(isset($_GET['product_points'])) {   
                 $wc_attr['meta_query'] = array(
                        'relation' => 'AND',
                        array(
                             'relation' => 'OR',   
                             array(
                                'key' => '_box_points',
                                'value' => serialize(strval($_GET['product_points'])),  // works for int-array
                                'compare' => 'LIKE'
                            ),
                            array(
                                'key' => '_box_points',
                                'value' => $_GET['product_points'],  // works for string-array
                                'compare' => '='
                            ),
                        ),
                        array(
                             array(
                             	'key'       => '_stock_status',
                                'value'     => 'outofstock',
                                'compare'   => 'NOT IN'
                            )
                        )
                    ); 
            }  

            if(isset($_GET['product_cat'])) {
                  $wc_attr['product_cat'] = $_GET['product_cat'];
            }
            
            
            if(isset($_GET['search'])) {
                  $wc_attr['s'] = sanitize_text_field( $_GET['search']);
            }

            return $wc_attr;
            
            
        }
        
        protected function box_content() { 
          global $jws_option;     

          $wc_attr = $this->box_query();  
          $product_query = new WP_Query($wc_attr);  
          $columns = $jws_option['select-box-item-columns'];
  
          if($columns == '6') {
            $col = '2';
          }elseif($columns == '5') {
            $col = '20';
          }elseif($columns == '4') {
            $col = '3';
          }else {
            $col = '4';
          }
          $grid_class = 'product-item product col-xl-'.$col.' col-lg-4 col-6'; 
          $class  = 'builder row'; 
          

          ?>
            
            <div class="products-wrap products-tab <?php echo esc_attr($class) ?>">
    
            <?php
                                                                                                                                                                                                
                $i = 0;
                if($product_query->have_posts()) {
                    while ($product_query->have_posts()) : $product_query->the_post();
                   $points = get_post_meta( get_the_ID(), '_box_points', true );

                    ?>
                     <div class="<?php echo esc_attr($grid_class); ?>">
                            <?php 
                                include( JWS_ABS_PATH_WC.'/archive-layout/content-builder.php' )
                            ?>
                     </div>
                     <?php 
                     $i++;
                  
                endwhile;
                wp_reset_postdata();
         
                }else {
                    echo '<div class="col-12 no_product"><p><i aria-hidden="true" class="jws-icon-warning-light"></i>'.esc_html__('No products were found matching your selection.','meathouse').'</p></div>';
                }

            ?>

        </div>
        <?php
        
            
        }
        

        public function add_section_save() { 
            
            if(isset($_POST['count']) &&  (isset($_POST['old_cart']) || in_array($_POST['id'],$this->get_all_product_data()))  ) {

                $box_variation = isset($_POST['varition_edded']) ? $_POST['varition_edded'] : '';
                $added_points = isset($_POST['added_points']) ? $_POST['added_points'] : 0;
                $item_points_added = isset($_POST['item_points_added']) ? $_POST['item_points_added'] : 0;
                session_start();
                $_SESSION['jws-meat-box'] = $_POST['count'];
                $_SESSION['box-variation'] = $box_variation;
                $_SESSION['added_points'] = $added_points;
                $_SESSION['item_points_added'] = $item_points_added;
            }   
        }
        
        
        
        public function save_frequency() { 
            $status = 'error';
            if(isset($_POST['id'])) {

                session_start();
                
                $_SESSION['frequency-box'] = $_POST['id'];
                
                $status = $_SESSION['frequency-box'];
                
            }  

          wp_send_json_success( $status );  
        }
        
        
        public function remove_cart_old() { 
            
            if(isset($_GET['cart_key'])) {

                 $cart = WC()->instance()->cart;
                 $cart->set_quantity($_GET['cart_key'],0);
            }  
            $result = array(
                'item' => $cart,
                'check' => $_GET['cart_key'],

            );
            wp_send_json_success( $result );
        }
        
           
               
        
        
        
        
        public function add_item() {  
            ob_start();
            $points_html = 0;
            if(isset($_POST['id']) && in_array($_POST['id'],$this->get_all_product_data()) ) {
            $status = 'ok';    
            $data = $_POST;    
            $id = $_POST['id']; 
            $variation_id = isset($_POST['variation_id']) ? $_POST['variation_id'] : '';   
              
            $count = isset($_POST['count'][$id]) ? $_POST['count'][$id] : '1';  
            $products = wc_get_product($id);
            $variation_attributes = '';
        
            $total_points = $this->total_count();
            if( $products->is_type( 'variable' ) ){ 
              $points = get_post_meta( $variation_id, '_box_points', true ); 
            }else {
              $points = get_post_meta( $id, '_box_points', true );  
            }
            
            $points_html = !empty($points) ? esc_html($points) : '0';
            
            if($points_html == '0') {
              $status = 'error';  
            }else{
              $this->add_item_content($id,$points_html,$count,$variation_id,$products);  
            }
 
          
            }else {
               $status = 'error';
            }
            $content = ob_get_clean();
            
           
 
            $result = array(
               'content' => $content,
               'points' => $points_html,
               'status' => $status,
            
            );
            wp_send_json_success( $result );
        }
        
        public function add_item_content($id,$points_html,$count,$variation_id,$products) { 
            $vari_sug = $this->box_item_varitions();
            if(!empty($variation_id) && $products->is_type( 'variable' ) ) {
                $remove_id =  $id.'_'.$variation_id;
                $variation = wc_get_product($variation_id);
                $variation_attributes = $variation->get_variation_attributes();
                $name_variation = ucfirst($variation_attributes['attribute_'.$vari_sug.'']);
            }else {
                $remove_id = $id;
                $name_variation = '';
            }
            ?>
            
                <div class="item_box" data-product-id="<?php echo esc_attr($remove_id); ?>">
                    <div class="item-image">
                        <?php
                             
                             echo '<span class="points">'.$points_html.jws_theme_get_option('pts_text','pts').'</span>'; 
                             echo get_the_post_thumbnail( $id, 'thumbnail', array() );
                        ?>
                    </div>
                    <div class="item-content">
                        <?php 
                            echo '<h6 class="woocommerce-loop-product__title"><a href="' . esc_url( get_permalink( $id ) ) . '">' . $products->get_title() . '</a></h6>';
                            echo '<div class="item-attr">'.$name_variation.' x <span class="count">'.$count.'</span></div>'
                        ?>
                    </div>
                    <span class="remove-item jws-icon-cross"></span>
                </div>
            
            <?php
            
        }
        
        
    }
    new Jws_Meat_House();
endif;    