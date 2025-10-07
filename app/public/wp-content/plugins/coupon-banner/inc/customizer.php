<?php
/**
 * Coupon Banner Customizer Options
 *
 * @package Coupon Banner
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Sanitize checkbox
 */
function coupon_banner_sanitize_checkbox($input) {
    return ($input == 1) ? 1 : '';
}

/**
 * Add Coupon Banner Customizer Options
 */
function coupon_banner_customizer($wp_customize) {

    // Add Coupon Panel
    $wp_customize->add_panel(
        'coupon_panel',
        array(
            'title' => __('Coupon', 'coupon-banner'),
            'priority' => 30,
        )
    );

    // Add Coupon Banner Section
    $wp_customize->add_section(
        'coupon_banner_section',
        array(
            'title' => __('Banniere Coupon', 'coupon-banner'),
            'panel' => 'coupon_panel',
            'priority' => 10,
        )
    );

    // Enable/Disable Coupon Banner
    $wp_customize->add_setting(
        'coupon_banner_enabled',
        array(
            'default' => '1',
            'capability' => 'edit_theme_options',
            'sanitize_callback' => 'coupon_banner_sanitize_checkbox',
            'transport' => 'postMessage',
        )
    );
    $wp_customize->add_control(
        'coupon_banner_enabled',
        array(
            'label' => __('Activer Banniere Coupon', 'coupon-banner'),
            'section' => 'coupon_banner_section',
            'type' => 'checkbox',
            'priority' => 1,
        )
    );

    // Banner Text
    $wp_customize->add_setting(
        'coupon_banner_text',
        array(
            'default' => '',
            'capability' => 'edit_theme_options',
            'sanitize_callback' => 'wp_kses_post',
            'transport' => 'postMessage',
        )
    );
    $wp_customize->add_control(
        'coupon_banner_text',
        array(
            'label' => __('Texte de la bannière', 'coupon-banner'),
            'section' => 'coupon_banner_section',
            'type' => 'textarea',
            'priority' => 2,
        )
    );

    // WooCommerce Coupon Selector
    $wp_customize->add_setting(
        'coupon_banner_selected_coupon',
        array(
            'default' => '',
            'capability' => 'edit_theme_options',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage',
        )
    );

    // Get WooCommerce coupons
    $coupon_choices = array('' => __('Sélectionner un coupon', 'coupon-banner'));

    if (class_exists('WooCommerce')) {
        $args = array(
            'posts_per_page' => -1,
            'orderby' => 'title',
            'order' => 'ASC',
            'post_type' => 'shop_coupon',
            'post_status' => 'publish',
        );
        $coupons = get_posts($args);

        foreach ($coupons as $coupon) {
            $coupon_choices[$coupon->post_title] = $coupon->post_title;
        }
    }

    $wp_customize->add_control(
        'coupon_banner_selected_coupon',
        array(
            'label' => __('Sélectionner un coupon WooCommerce', 'coupon-banner'),
            'description' => __('Choisissez un coupon depuis votre boutique WooCommerce', 'coupon-banner'),
            'section' => 'coupon_banner_section',
            'type' => 'select',
            'choices' => $coupon_choices,
            'priority' => 3,
        )
    );

    // Background Color
    $wp_customize->add_setting(
        'coupon_banner_bg_color',
        array(
            'default' => '#0d354f',
            'capability' => 'edit_theme_options',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport' => 'postMessage',
        )
    );
    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'coupon_banner_bg_color',
            array(
                'label' => __('Couleur de fond', 'coupon-banner'),
                'section' => 'coupon_banner_section',
                'priority' => 4,
            )
        )
    );

    // Banner Height
    $wp_customize->add_setting(
        'coupon_banner_height',
        array(
            'default' => '60',
            'capability' => 'edit_theme_options',
            'sanitize_callback' => 'absint',
            'transport' => 'postMessage',
        )
    );
    $wp_customize->add_control(
        'coupon_banner_height',
        array(
            'label' => __('Hauteur de la bannière (px)', 'coupon-banner'),
            'description' => __('Hauteur en pixels (recommandé: 40-100)', 'coupon-banner'),
            'section' => 'coupon_banner_section',
            'type' => 'number',
            'input_attrs' => array(
                'min' => 30,
                'max' => 200,
                'step' => 5,
            ),
            'priority' => 5,
        )
    );

    // Note: Selective refresh is not used because it conflicts with live preview
    // All updates are handled via JavaScript in customizer-preview.js
}

add_action('customize_register', 'coupon_banner_customizer');

/**
 * Enqueue customizer preview script and styles
 */
function coupon_banner_customizer_preview_scripts() {
    wp_enqueue_style(
        'coupon-banner-customizer-preview-style',
        COUPON_BANNER_PLUGIN_URL . 'assets/css/customizer-preview.css',
        array(),
        COUPON_BANNER_VERSION
    );

    wp_enqueue_script(
        'coupon-banner-customizer-preview',
        COUPON_BANNER_PLUGIN_URL . 'assets/js/customizer-preview.js',
        array('jquery', 'customize-preview'),
        COUPON_BANNER_VERSION,
        true
    );
}
add_action('customize_preview_init', 'coupon_banner_customizer_preview_scripts');
