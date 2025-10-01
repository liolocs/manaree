<?php
// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' );
} 
// **********************************************************************// 
// ! Add favicon 
// **********************************************************************// 
if (!function_exists('jws_favicon')) {
    function jws_favicon()
    {

        if (function_exists('has_site_icon') && has_site_icon()) return '';

        // Get the favicon.
        $favicon = '';


        global $jws_option;
        
        if(isset($jws_option['favicon']) && !empty($jws_option['favicon'])) {
            $favicon = $jws_option['favicon']['url'];
        }

        ?>
        <link rel="shortcut icon" href="<?php echo esc_attr($favicon); ?>">
        <link rel="apple-touch-icon-precomposed" sizes="152x152" href="<?php echo esc_attr($favicon); ?>">
        <?php
    }

    add_action('wp_head', 'jws_favicon');
}

if (!function_exists('jws_logo_url')) {
    function jws_logo_url()
    {

        $logo = '';
        global $jws_option;
        
        if(isset($jws_option['logo']) && !empty($jws_option['logo'])) {
            if(!empty($jws_option['logo'])) {
                $logo = $jws_option['logo']['url'];
            }
        }
        
        return $logo;

      
    }
}

//Lets add Open Graph Meta Info
 
function jws_insert_fb_in_head() {
    global $post;
    if ( !is_singular()) //if it is not a post or a page
        return;
        echo '<meta property="og:title" content="' . get_the_title() . '"/>';
        echo '<meta property="og:type" content="article"/>';
        echo '<meta property="og:url" content="' . get_permalink() . '"/>';
        echo '<meta property="og:site_name" content="'.get_bloginfo( 'name' ).'"/>';
    if(has_post_thumbnail( $post->ID )) { //the post does not have featured image, use a default image
        $thumbnail_src = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full' );
        echo '<meta property="og:image" content="' . esc_attr( !empty($thumbnail_src[0]) ? $thumbnail_src[0] : '' ) . '"/>';
        echo '<meta property="og:image:secure_url" content="' . esc_attr( !empty($thumbnail_src[0]) ? $thumbnail_src[0] : ''  ) . '">';
        echo '<meta property="og:image:width" content="500">';
        echo '<meta property="og:image:height" content="400">';
    }

    echo "
";
}
add_action( 'wp_head', 'jws_insert_fb_in_head', 5 );

/**
 * Add extra initialisation for Contact 7 Form in Elementor popups.
 **/
add_action( 'wp_footer', 'jws_back_top_top'); 
function jws_back_top_top() {
    global $jws_option;
 
    $class = 'backToTop fas fa-arrow-up ';
    ?>
        <a href="#" class="<?php echo esc_attr($class); ?>"></a>
    <?php
}

/**
 * Add toolbar for mobile.
 **/
add_action( 'wp_footer', 'jws_toolbar_mobile'); 
function jws_toolbar_mobile() {
     
    $enable = jws_theme_get_option('toolbar_fix');
  
       if(get_post_meta( get_the_ID(), 'tool_bar_checkbox', 1 ) == 'on') {
           $enable = false;
       }  
 
     
    if (class_exists('Woocommerce') && $enable) { 
    $shop = jws_check_layout_shop();
    ?>
        <div class="jws-toolbar-wap">
            <?php  if((is_home() && ((isset($_GET['sidebar']) && $_GET['sidebar'] != 'full') || !isset($_GET['sidebar'])) ) || (is_single() && 'post' == get_post_type() && !isset($_GET['sidebar']))) : ?>
            <div class="jws-toolbar-item">
                <a class="show_filter_shop" href="javascript:void(0)">
                    <i aria-hidden="true" class="jws-icon-dots-three-outline-vertical"></i>
                    <span><?php echo esc_html__('Sidebar','meathouse'); ?></span>
                </a>
            </div>
            <?php endif; ?>
            <?php  if(is_shop() && ($shop['filter_layout'] == 'sideout' || $shop['position'] == 'left' || $shop['position'] == 'right')) : ?>
            <div class="jws-toolbar-item">
                <a class="show_filter_shop" href="javascript:void(0)">
                    <i aria-hidden="true" class="jws-icon-funnel"></i>
                    <span><?php echo esc_html__('Filter','meathouse'); ?></span>
                </a>
            </div>
            <?php endif; ?>
            <?php if(jws_theme_get_option('toolbar_shop')) : ?>
            <div class="jws-toolbar-item">
                <a href="<?php echo get_permalink( wc_get_page_id( 'shop' ) ); ?>">
                    <i aria-hidden="true" class="jws-icon-storefront"></i>
                    <span><?php echo esc_html__('Shop','meathouse'); ?></span>
                </a>
            </div>
            <?php endif; ?>
            <?php if(jws_theme_get_option('toolbar_search')) : ?>
            <div class="jws-toolbar-item">
                <a class="jws_toolbar_search" href="#">
                    <i aria-hidden="true" class="jws-icon-glyph-4"></i>
                    <span><?php echo esc_html__('Search','meathouse'); ?></span>
                </a>
            </div>
            <?php endif; ?>
            <?php if(function_exists('jws_get_wishlist_page_url') && jws_theme_get_option('toolbar_wishlist')) : ?>
            <div class="jws-toolbar-item">
                <a class="jws_toolbar_wishlist" href="<?php echo esc_url( jws_get_wishlist_page_url() ); ?>">
                    <i aria-hidden="true" class="jws-icon-heart-straight-light"></i>
                    <span><?php echo esc_html__('Wishlist','meathouse'); ?></span>
                </a>
            </div>
            <?php endif; ?>
            <?php if(jws_theme_get_option('toolbar_account')) : ?>
            <div class="jws-toolbar-item">
                <a class="jws-open-login<?php if(is_user_logged_in()) echo ' logged'; ?>" href="<?php echo get_permalink( wc_get_page_id( 'myaccount' ) ); ?>">
                    <i aria-hidden="true" class="jws-icon-glyph-3"></i>
                    <span><?php echo esc_html__('My account','meathouse'); ?></span>
                </a>
            </div>
             <?php endif; ?>
             
            <?php 
                $custom_toolbar = jws_theme_get_option('toolbar_custom');
                $i = 0;
                if(!empty($custom_toolbar['redux_repeater_data'])) {
                   foreach($custom_toolbar['redux_repeater_data'] as $value) {
                        if(!empty($custom_toolbar['toolbar_custom_link'][$i])) {
                        ?>
                          <div class="jws-toolbar-item">
                                <a href="<?php echo esc_url($custom_toolbar['toolbar_custom_link'][$i]); ?>">
                                    <i aria-hidden="true" class="<?php echo esc_attr($custom_toolbar['toolbar_custom_icon'][$i]); ?>"></i>
                                    <span><?php echo esc_html($custom_toolbar['toolbar_custom_name'][$i]); ?></span>
                                </a>
                          </div>
                        <?php
                        }
                    $i++;} 
                }
             ?> 
        </div>
    <?php
    }
}


/**
 * Add form login.
 **/
add_action( 'wp_footer', 'jws_form_login_popup'); 
function jws_form_login_popup() {
    global $jws_option;
    ?>
        <div class="jws-form-login-popup">
            <div class="jws-form-overlay"></div>
            <div class="jws-form-content">
                <div class="jws-close"><i aria-hidden="true" class="jws-icon-cross"></i></div>
                <?php jws_get_content_form_login(true,true,'login'); ?>
            </div>
        </div>
    <?php
}


/**
 * Add newseleter popup.
 **/
add_action( 'wp_footer', 'jws_form_newsletter_popup'); 
function jws_form_newsletter_popup() {
    global $jws_option;
    if(jws_theme_get_option('newsletter_enble') && !is_page( 'Landing Page' ) && (did_action( 'elementor/loaded' ) && !\Elementor\Plugin::$instance->preview->is_preview_mode()))    :
    ?>
        <div class="jws-newsletter-popup mfp-hide">
            <div class="jws-form-content">
                <div class="newsletter-content">
                    <?php 
                        if(isset($jws_option['newsletter_content'])){
                             echo do_shortcode('[hf_template id="'.$jws_option['newsletter_content'].'"]');
                        }
                    ?>
               </div>
            </div>
        </div>
    <?php endif; ?>    
    <?php
}


/**
 * Add extra initialisation for Contact 7 Form in Elementor popups.
 **/
function jws_ct_body_classes( $classes ) {
    global $jws_option;
    $layout = (isset($jws_option['button-layout'])) ? $jws_option['button-layout'] : 'default';
    $classes[] = 'button-'.$layout;
    if ( !is_user_logged_in() ) {
            $classes[] = 'user-not-logged-in';
    }

    $layoutcars = (isset($jws_option['cars-detail-layout'])) ? $jws_option['cars-detail-layout'] : 'layout1';
    
    if(isset($_GET['car_layout']) && $_GET['car_layout'] == 'layout2') {
         $layoutcars =  'layout2';
    }
    if($layoutcars == 'layout2') {
       $classes[] = 'hidden-title-bar'; 
    }
    
    if(isset($jws_option['shop_single_layout'])) {
       $classes[] = 'single-product-'.$jws_option['shop_single_layout']; 
    }
    
    if(!did_action( 'elementor/loaded' )) {
       $classes[] = 'not-elementor';  
    }
    /** Footer **/
    if(isset($jws_option['footer-switch-parallax']) && $jws_option['footer-switch-parallax']) {
    $classes[] = 'footer-parallax';
    }
    /** rtl **/
    $classes[] = (isset($jws_option['rtl']) && $jws_option['rtl']) ? 'rtl' : '';
    /** toolbar **/
    if(get_post_meta( get_the_ID(), 'tool_bar_checkbox', 1 ) != 'on') {
        $classes[] = jws_theme_get_option('toolbar_fix') ? 'has_toolbar' : '';
    }  
    
    
    
      
    return $classes;
}
add_filter( 'body_class','jws_ct_body_classes' );

function jws_mini_cart_content2() { ?>
        <div class="jws-mini-cart-wrapper">
            <div class="jws-cart-sidebar">
                <div class="jws_cart_content">
                </div>
            </div>
            <div class="jws-cart-overlay"></div>
        </div>   
<?php }
if (class_exists('Woocommerce')) { 
   add_action( 'wp_footer', 'jws_mini_cart_content2' ); 
}

function jws_filter_backups_demos($demos)
	{
		$demos_array = array(
			'meathouse' => array(
				'title' => esc_html__('Meathouse Demo', 'meathouse'),
				'screenshot' => 'https://gavencreative.com/import_demo/meathouse/screenshot.jpg',
				'preview_link' => 'https://meathouse.jwsuperthemes.com',
			),
		);
        $download_url = 'https://gavencreative.com/import_demo/meathouse/download-script/';
		foreach ($demos_array as $id => $data) {
			$demo = new FW_Ext_Backups_Demo($id, 'piecemeal', array(
				'url' => $download_url,
				'file_id' => $id,
			));
			$demo->set_title($data['title']);
			$demo->set_screenshot($data['screenshot']);
			$demo->set_preview_link($data['preview_link']);
			$demos[$demo->get_id()] = $demo;
			unset($demo);
		}
		return $demos;
}
add_filter('fw:ext:backups-demo:demos', 'jws_filter_backups_demos');
if (!function_exists('jws_deactivate_plugins')){
	function jws_deactivate_plugins() {
		deactivate_plugins(array(
			'brizy/brizy.php'
		));    
		
	}
}
add_action( 'admin_init', 'jws_deactivate_plugins' );


if(class_exists('jws_theme_jwsLove') && !function_exists('post_favorite') ) {
    function post_favorite($return = '',$unit = '',$show_icon = true) {
    	global $post_favorite , $post;
        $love_count = get_post_meta(get_the_ID(), '_jws_love', true);
        if($love_count == '1') {
           $unit = esc_html__(' like','meathouse'); 
        }else{
           $unit = esc_html__(' likes','meathouse');  
        }
    	if($return == 'return') {
    		return $post_favorite->add_love($unit,$show_icon);
    	} else {
    		echo ''.$post_favorite->add_love($unit,$show_icon);
    	}
    }    
}

function jws_store_location_search($atts) {
     $a = shortcode_atts( array(
		'url' => '',
	), $atts );
    ob_start();
    ?>
        <form class="jws-wpsl-search" action="<?php  echo ''.$a['url']; ?>" method="get">
            <input name="search-location" type="text" autocomplete="off" placeholder="<?php echo esc_attr__('Enter Starting Address...','meathouse'); ?>" />
            <button type="submit"><?php echo esc_html__('Find Us','meathouse'); ?></button>
        </form>
    <?php
    $output = ob_get_contents();
    ob_end_clean();
    return ent2ncr($output);
}   

if (defined('meathousecore')) {

add_action( 'admin_menu', 'jws_add_menu_page' );


}

if(!function_exists('jws_add_menu_page')) {
  function jws_add_menu_page() {
    add_menu_page( 'Jws Settings', 'Jws Settings', 'manage_options', 'jws_settings.php', 'jws_settings', '', 3 );
  }  
}


// Hide all posts from users who are not logged-in or are not administrators or members
function jws_exclude_posts($query) {
  global $jws_option;
  if(isset($jws_option['exclude-blog']) && !empty($jws_option['exclude-blog'])) {
     $result = array_map('intval', array_filter($jws_option['exclude-blog'], 'is_numeric'));
     if(!is_admin() && $query->is_main_query() && !is_single()){
        set_query_var('post__not_in', $result);
    }  
  }
  if ( $query->is_post_type_archive( 'questions' ) && $query->is_main_query() && ! is_admin() ) {
    $query->set( 'posts_per_page', jws_theme_get_option('auestions-number') );
    if(isset($_GET['product_questions'])) {
        $meta_query = (array) $query->get( 'meta_query' );
         $meta_query[] = array(
            array(
    				'key'     => 'product_questions',
    				'value'   => sanitize_text_field( $_GET['product_questions'] ),
    				'compare' => 'LIKE',
    		)
       ) ;
	$query->set( 'meta_query', $meta_query );	
    }
     
  }
}
add_action('pre_get_posts', 'jws_exclude_posts');

//add_filter( 'comment_form_default_fields', 'wc_comment_form_change_cookies' , 50 );
function wc_comment_form_change_cookies( $fields ) {
	$commenter = wp_get_current_commenter();
	$consent   = empty( $commenter['comment_author_email'] ) ? '' : ' checked="checked"';
	$fields['cookies'] = '<p class="comment-form-cookies-consent"><input id="wp-comment-cookies-consent" name="wp-comment-cookies-consent" type="checkbox" value="yes"' . $consent . ' />' .
					 '<label for="wp-comment-cookies-consent">'.__('Remember Me!', 'meathouse').'</label></p>';
	return $fields;
}




//move comment field to bottom of form
function wpb_move_comment_field_to_bottom( $fields ) {
    $comment_field = $fields['comment'];
    unset( $fields['comment'] );
    $fields['comment'] = $comment_field;
    return $fields;
}
 
add_filter( 'comment_form_fields', 'wpb_move_comment_field_to_bottom' );



//add comment meta field

function add_custom_comment_field( $comment_id ) {
   if(isset($_POST['title_comment'])) {
    add_comment_meta( $comment_id, 'title_comment', $_POST['title_comment'] );
   } 
}
add_action( 'comment_post', 'add_custom_comment_field' );

function extend_comment_edit_metafields( $comment_id ) {
    if( ! isset( $_POST['extend_comment_update'] ) || ! wp_verify_nonce( $_POST['extend_comment_update'], 'extend_comment_update' ) ) return;
 
	if ( ( isset( $_POST['title_comment'] ) ) && ( $_POST['title_comment'] != '') ) :
	$title_comment = wp_filter_nohtml_kses($_POST['title_comment']);
	update_comment_meta( $comment_id, 'title_comment', $phone );
	else :
	delete_comment_meta( $comment_id, 'title_comment');
	endif;

}
add_action( 'edit_comment', 'extend_comment_edit_metafields' );
