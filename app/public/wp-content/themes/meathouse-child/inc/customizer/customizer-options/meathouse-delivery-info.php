<?php
/**
 * Delivery Information Section Customizer Options
 *
 * @package MeatHouse Child
 */

/**
 * Add Delivery Information Section Customizer Options
 */
function meathouse_child_delivery_info_customizer($wp_customize) {

    // Add Delivery Information Section
    $wp_customize->add_section(
        'meathouse_delivery_info_section',
        array(
            'title' => __('Delivery Information (Cart)', 'meathouse'),
            'panel' => 'meathouse_custom_panel',
            'priority' => 50,
        )
    );

    // Enable/Disable Delivery Information Section
    $wp_customize->add_setting(
        'meathouse_hs_delivery_info',
        array(
            'default' => '1',
            'capability' => 'edit_theme_options',
            'sanitize_callback' => 'meathouse_child_sanitize_checkbox',
            'transport' => 'postMessage',
        )
    );
    $wp_customize->add_control(
        'meathouse_hs_delivery_info',
        array(
            'label' => __('Activer Delivery Information', 'meathouse'),
            'section' => 'meathouse_delivery_info_section',
            'type' => 'checkbox',
            'priority' => 1,
        )
    );

    // Background Color
    $wp_customize->add_setting(
        'meathouse_delivery_info_bg_color',
        array(
            'default' => '#FFF5E6',
            'capability' => 'edit_theme_options',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport' => 'postMessage',
        )
    );
    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'meathouse_delivery_info_bg_color',
            array(
                'label' => __('Couleur de fond', 'meathouse'),
                'section' => 'meathouse_delivery_info_section',
                'priority' => 2,
            )
        )
    );

    // Text Color
    $wp_customize->add_setting(
        'meathouse_delivery_info_text_color',
        array(
            'default' => '#333333',
            'capability' => 'edit_theme_options',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport' => 'postMessage',
        )
    );
    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'meathouse_delivery_info_text_color',
            array(
                'label' => __('Couleur du texte', 'meathouse'),
                'section' => 'meathouse_delivery_info_section',
                'priority' => 3,
            )
        )
    );

    // Create 6 delivery info items
    $selective_refresh_settings = array(
        'meathouse_hs_delivery_info',
        'meathouse_delivery_info_bg_color',
        'meathouse_delivery_info_text_color',
    );

    for ($i = 1; $i <= 6; $i++) {
        // Item Text
        $wp_customize->add_setting(
            "meathouse_delivery_info_item_{$i}_text",
            array(
                'default' => '',
                'capability' => 'edit_theme_options',
                'sanitize_callback' => 'wp_kses_post',
                'transport' => 'postMessage',
            )
        );
        $wp_customize->add_control(
            "meathouse_delivery_info_item_{$i}_text",
            array(
                'label' => sprintf(__('Item %d - Texte', 'meathouse'), $i),
                'description' => __('Supporte: **gras**, [color=#hex]texte coloré[/color], emojis', 'meathouse'),
                'section' => 'meathouse_delivery_info_section',
                'type' => 'textarea',
                'priority' => 10 + ($i * 10),
            )
        );

        // Item Custom Color (Optional)
        $wp_customize->add_setting(
            "meathouse_delivery_info_item_{$i}_color",
            array(
                'default' => '',
                'capability' => 'edit_theme_options',
                'sanitize_callback' => 'sanitize_hex_color',
                'transport' => 'postMessage',
            )
        );
        $wp_customize->add_control(
            new WP_Customize_Color_Control(
                $wp_customize,
                "meathouse_delivery_info_item_{$i}_color",
                array(
                    'label' => sprintf(__('Item %d - Couleur personnalisée (optionnel)', 'meathouse'), $i),
                    'section' => 'meathouse_delivery_info_section',
                    'priority' => 11 + ($i * 10),
                )
            )
        );

        // Item Bold Toggle
        $wp_customize->add_setting(
            "meathouse_delivery_info_item_{$i}_bold",
            array(
                'default' => '',
                'capability' => 'edit_theme_options',
                'sanitize_callback' => 'meathouse_child_sanitize_checkbox',
                'transport' => 'postMessage',
            )
        );
        $wp_customize->add_control(
            "meathouse_delivery_info_item_{$i}_bold",
            array(
                'label' => sprintf(__('Item %d - Texte en gras', 'meathouse'), $i),
                'section' => 'meathouse_delivery_info_section',
                'type' => 'checkbox',
                'priority' => 12 + ($i * 10),
            )
        );

        // Add to selective refresh settings array
        $selective_refresh_settings[] = "meathouse_delivery_info_item_{$i}_text";
        $selective_refresh_settings[] = "meathouse_delivery_info_item_{$i}_color";
        $selective_refresh_settings[] = "meathouse_delivery_info_item_{$i}_bold";
    }

    // Add selective refresh for delivery info section
    if (isset($wp_customize->selective_refresh)) {
        $wp_customize->selective_refresh->add_partial(
            'meathouse_delivery_info_section',
            array(
                'selector' => '#delivery-info',
                'container_inclusive' => true,
                'settings' => $selective_refresh_settings,
                'render_callback' => function() {
                    $template = get_stylesheet_directory() . '/template-parts/sections/section-delivery-info.php';
                    if (file_exists($template)) {
                        include($template);
                    }
                },
            )
        );
    }
}

add_action('customize_register', 'meathouse_child_delivery_info_customizer');
