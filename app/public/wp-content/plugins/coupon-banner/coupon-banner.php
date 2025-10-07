<?php
/**
 * Plugin Name: Coupon Banner
 * Plugin URI:
 * Description: Displays a customizable coupon banner at the top of your website pages
 * Version: 1.0.0
 * Author:
 * Author URI:
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: coupon-banner
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('COUPON_BANNER_VERSION', '1.0.0');
define('COUPON_BANNER_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('COUPON_BANNER_PLUGIN_URL', plugin_dir_url(__FILE__));

/**
 * Enqueue plugin styles
 */
function coupon_banner_enqueue_styles() {
    wp_enqueue_style(
        'coupon-banner-style',
        COUPON_BANNER_PLUGIN_URL . 'assets/css/coupon-banner-min.css',
        array(),
        COUPON_BANNER_VERSION
    );

    // Add dynamic inline CSS for customizer settings
    $bg_color = get_theme_mod('coupon_banner_bg_color', '#0d354f');
    $height = get_theme_mod('coupon_banner_height', 60);

    // Calculate font sizes based on height (scale proportionally)
    $base_height = 60; // default height
    $scale = $height / $base_height;

    $text_size = round(1.1 * $scale, 2);
    $code_size = round(1.2 * $scale, 2);
    $padding_v = round(0.5 * $scale, 2);
    $padding_h = round(1.5 * $scale, 2);
    $gap = round(1.5 * $scale, 2);

    $custom_css = "
        .coupon-banner {
            background-color: {$bg_color} !important;
            min-height: {$height}px;
            padding: " . ($height * 0.15) . "px 2em;
        }
        .coupon-banner-container {
            gap: {$gap}em;
        }
        .coupon-banner-text {
            font-size: {$text_size}rem;
        }
        .coupon-banner-code {
            color: {$bg_color} !important;
            border-color: {$bg_color} !important;
            font-size: {$code_size}rem;
            padding: {$padding_v}em {$padding_h}em;
        }
    ";

    wp_add_inline_style('coupon-banner-style', $custom_css);
}
add_action('wp_enqueue_scripts', 'coupon_banner_enqueue_styles');

/**
 * Include customizer options
 */
require_once COUPON_BANNER_PLUGIN_DIR . 'inc/customizer.php';

/**
 * Inject coupon banner as first element in body
 */
function coupon_banner_inject() {
    $coupon_banner_enabled = get_theme_mod('coupon_banner_enabled', '1');

    if ($coupon_banner_enabled != '1') {
        return;
    }

    include COUPON_BANNER_PLUGIN_DIR . 'templates/coupon-banner-template.php';
}
add_action('wp_body_open', 'coupon_banner_inject', 1);
