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

        // Load banniere product rassurance CSS
        wp_enqueue_style( 'banniere-product-rassurance-style', get_stylesheet_directory_uri() . '/assets/css/banniere-product-rassurance.css', array(), $version );

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
require get_stylesheet_directory() . '/inc/customizer/customizer-options/meathouse-product-rassurance.php';

/**
 * Include content modification functions
 */
require get_stylesheet_directory() . '/inc/extras.php';

/**
 * Hook into the_content filter to modify page content
 */
add_filter( 'the_content', 'meathouse_child_modify_page_content' );

/**
 * Enqueue JavaScript for product rassurance banner injection
 * This approach avoids output buffering conflicts with Elementor
 */
add_action( 'wp_enqueue_scripts', 'meathouse_child_enqueue_product_rassurance_script' );

function meathouse_child_enqueue_product_rassurance_script() {
    // Get the theme mod values
    $meathouse_hs_product_rassurance = get_theme_mod('meathouse_hs_product_rassurance', '1');
    $meathouse_product_rassurance_items = get_theme_mod('meathouse_product_rassurance_items', '');

    if ($meathouse_hs_product_rassurance != '1' || empty($meathouse_product_rassurance_items)) {
        return;
    }

    $items = json_decode($meathouse_product_rassurance_items, true);

    if (empty($items) || !is_array($items)) {
        return;
    }

    // Build the HTML
    $html = '<section class="payment-banner" id="payment-banner">';

    foreach ($items as $item) {
        if (!empty($item['image']) || !empty($item['title']) || !empty($item['subtitle'])) {
            $html .= '<div class="payment-banner-item">';

            if (!empty($item['image'])) {
                $html .= '<div class="payment-banner-image">';
                $html .= '<img src="' . esc_url($item['image']) . '" alt="' . esc_attr($item['title'] ?? '') . '">';
                $html .= '</div>';
            }

            $html .= '<div class="payment-banner-content">';

            if (!empty($item['title'])) {
                $html .= '<h3 class="payment-banner-title">' . esc_html($item['title']) . '</h3>';
            }

            if (!empty($item['subtitle'])) {
                $html .= '<p class="payment-banner-subtitle">' . wp_kses_post($item['subtitle']) . '</p>';
            }

            $html .= '</div>';
            $html .= '</div>';
        }
    }

    $html .= '</section>';

    // Enqueue inline script to inject the banner
    wp_add_inline_script( 'jquery', "
        jQuery(document).ready(function($) {
            // Look for element with id='_banniere_product_rassurance' and replace it
            var targetElement = $('#_banniere_product_rassurance');
            if (targetElement.length) {
                targetElement.replaceWith(" . json_encode($html) . ");
            }
        });
    " );
}
