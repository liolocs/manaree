<?php
/**
 * Coupon Banner Section Customizer Options
 *
 * @package MeatHouse Child
 */

/**
 * Add Coupon Banner Section Customizer Options
 */
function meathouse_child_coupon_banner_customizer($wp_customize) {

    // Add Coupon Banner Section
    $wp_customize->add_section(
        'meathouse_coupon_banner_section',
        array(
            'title' => __('Banniere Coupon', 'meathouse'),
            'panel' => 'meathouse_custom_panel',
            'priority' => 5, // High priority to show at top
        )
    );

    // Enable/Disable Coupon Banner Section
    $wp_customize->add_setting(
        'meathouse_hs_coupon_banner',
        array(
            'default' => '1',
            'capability' => 'edit_theme_options',
            'sanitize_callback' => 'meathouse_child_sanitize_checkbox',
            'transport' => 'postMessage',
        )
    );
    $wp_customize->add_control(
        'meathouse_hs_coupon_banner',
        array(
            'label' => __('Activer Banniere Coupon', 'meathouse'),
            'section' => 'meathouse_coupon_banner_section',
            'type' => 'checkbox',
            'priority' => 1,
        )
    );

    // Banner Text
    $wp_customize->add_setting(
        'meathouse_coupon_banner_text',
        array(
            'default' => '',
            'capability' => 'edit_theme_options',
            'sanitize_callback' => 'wp_kses_post',
            'transport' => 'postMessage',
        )
    );
    $wp_customize->add_control(
        'meathouse_coupon_banner_text',
        array(
            'label' => __('Texte de la bannière', 'meathouse'),
            'section' => 'meathouse_coupon_banner_section',
            'type' => 'textarea',
            'priority' => 2,
        )
    );

    // Coupon Code
    $wp_customize->add_setting(
        'meathouse_coupon_banner_code',
        array(
            'default' => '',
            'capability' => 'edit_theme_options',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage',
        )
    );
    $wp_customize->add_control(
        'meathouse_coupon_banner_code',
        array(
            'label' => __('Code du coupon', 'meathouse'),
            'section' => 'meathouse_coupon_banner_section',
            'type' => 'text',
            'priority' => 3,
        )
    );

    // Add selective refresh for coupon banner section
    if (isset($wp_customize->selective_refresh)) {
        $wp_customize->selective_refresh->add_partial(
            'meathouse_coupon_banner_section',
            array(
                'selector' => '#coupon-banner',
                'container_inclusive' => true,
                'settings' => array(
                    'meathouse_hs_coupon_banner',
                    'meathouse_coupon_banner_text',
                    'meathouse_coupon_banner_code',
                ),
                'render_callback' => function() {
                    $template = get_stylesheet_directory() . '/template-parts/sections/section-coupon-banner.php';
                    if (file_exists($template)) {
                        include($template);
                    }
                },
            )
        );
    }
}

add_action('customize_register', 'meathouse_child_coupon_banner_customizer');
