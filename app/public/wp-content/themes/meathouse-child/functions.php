<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

// BEGIN ENQUEUE PARENT ACTION
// AUTO GENERATED - Do not modify or remove comment markers above or below:

if ( !function_exists( 'chld_thm_cfg_locale_css' ) ):
    function chld_thm_cfg_locale_css( $uri ){
        if ( empty( $uri ) && is_rtl() && file_exists( get_template_directory() . '/rtl.css' ) )
            $uri = get_template_directory_uri() . '/rtl.css';
        return $uri;
    }
endif;
add_filter( 'locale_stylesheet_uri', 'chld_thm_cfg_locale_css' );

if ( !function_exists( 'chld_thm_cfg_parent_css' ) ):
    function chld_thm_cfg_parent_css() {
        wp_enqueue_style( 'chld_thm_cfg_parent', trailingslashit( get_template_directory_uri() ) . 'style.css', array( 'circula','jws-jwsicon','jws-default','magnificPopup','slick','awesome','jws-style','jws-style-reset' ) );
    }
endif;
add_action( 'wp_enqueue_scripts', 'chld_thm_cfg_parent_css', 10 );

// END ENQUEUE PARENT ACTION

/**
 * ======================================
 * MeatHouse Child Theme Custom Functions
 * ======================================
 */

/**
 * Enqueue child theme styles and scripts
 */
if ( !function_exists( 'meathouse_child_enqueue_styles' ) ):
    function meathouse_child_enqueue_styles() {
        // Dynamically get version number of the parent stylesheet
        $theme   = wp_get_theme( 'MeatHouse' );
        $version = $theme->get( 'Version' );

        // Load the child theme stylesheet
        wp_enqueue_style( 'child-style', get_stylesheet_directory_uri() . '/style.css', array(), $version );

        // Load header CSS
        wp_enqueue_style( 'header-style', get_stylesheet_directory_uri() . '/assets/css/header.css', array(), $version );

        // Load hero section CSS
        wp_enqueue_style( 'hero-section-style', get_stylesheet_directory_uri() . '/assets/css/hero-section.css', array(), $version );

        // Load banner reassurance CSS
        wp_enqueue_style( 'banner-reassurance-style', get_stylesheet_directory_uri() . '/assets/css/banner-reassurance.css', array(), $version );

        // Load banniere apropos CSS
        wp_enqueue_style( 'banniere-apropos-style', get_stylesheet_directory_uri() . '/assets/css/banniere-apropos.css', array(), $version );

        // Load banniere reviews CSS
        wp_enqueue_style( 'banniere-reviews-style', get_stylesheet_directory_uri() . '/assets/css/banniere-reviews.css', array(), $version );

        // Load footer CSS
        wp_enqueue_style( 'footer-style', get_stylesheet_directory_uri() . '/assets/css/footer.css', array(), $version );

        // Add more modular CSS files here as needed
    }
endif;
add_action( 'wp_enqueue_scripts', 'meathouse_child_enqueue_styles' );

/**
 * Include customizer options
 */
require get_stylesheet_directory() . '/inc/customizer/customizer-options/meathouse-hero.php';
require get_stylesheet_directory() . '/inc/customizer/customizer-options/meathouse-banner.php';
require get_stylesheet_directory() . '/inc/customizer/customizer-options/meathouse-apropos.php';
require get_stylesheet_directory() . '/inc/customizer/customizer-options/meathouse-reviews.php';

/**
 * Include content modification functions
 */
require get_stylesheet_directory() . '/inc/extras.php';

/**
 * Hook into the output buffer to modify content before it's sent to browser
 */
add_action( 'init', function() {
    ob_start( 'meathouse_child_modify_page_content' );
} );

/**
 * Also hook into the_content filter as an additional layer
 */
add_filter( 'the_content', 'meathouse_child_modify_page_content' );
