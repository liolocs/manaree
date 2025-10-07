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

    // Coupon Code
    $wp_customize->add_setting(
        'coupon_banner_code',
        array(
            'default' => '',
            'capability' => 'edit_theme_options',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage',
        )
    );
    $wp_customize->add_control(
        'coupon_banner_code',
        array(
            'label' => __('Code du coupon', 'coupon-banner'),
            'section' => 'coupon_banner_section',
            'type' => 'text',
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

    // Add selective refresh for coupon banner
    if (isset($wp_customize->selective_refresh)) {
        $wp_customize->selective_refresh->add_partial(
            'coupon_banner_section',
            array(
                'selector' => '#coupon-banner',
                'container_inclusive' => true,
                'settings' => array(
                    'coupon_banner_enabled',
                    'coupon_banner_text',
                    'coupon_banner_code',
                    'coupon_banner_bg_color',
                    'coupon_banner_height',
                ),
                'render_callback' => function() {
                    $template = COUPON_BANNER_PLUGIN_DIR . 'templates/coupon-banner-template.php';
                    if (file_exists($template)) {
                        include($template);
                    }
                },
            )
        );
    }
}

add_action('customize_register', 'coupon_banner_customizer');
