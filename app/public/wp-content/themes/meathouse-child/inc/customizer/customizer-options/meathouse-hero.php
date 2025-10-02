<?php
/**
 * Hero Section Customizer Options
 *
 * @package MeatHouse Child
 */

// Sanitization functions
if (!function_exists('meathouse_child_sanitize_checkbox')) {
    function meathouse_child_sanitize_checkbox($input) {
        return ($input == true) ? '1' : '0';
    }
}

if (!function_exists('meathouse_child_sanitize_text')) {
    function meathouse_child_sanitize_text($input) {
        return wp_kses_post($input);
    }
}

if (!function_exists('meathouse_child_sanitize_url')) {
    function meathouse_child_sanitize_url($input) {
        return esc_url_raw($input);
    }
}

if (!function_exists('meathouse_child_sanitize_number')) {
    function meathouse_child_sanitize_number($input) {
        return floatval($input);
    }
}

/**
 * Add Hero Section Customizer Options
 */
function meathouse_child_hero_customizer($wp_customize) {

    // Add Custom Panel
    $wp_customize->add_panel(
        'meathouse_custom_panel',
        array(
            'title' => __('Custom', 'meathouse'),
            'priority' => 10,
        )
    );

    // Add Sections Section
    $wp_customize->add_section(
        'meathouse_sections_section',
        array(
            'title' => __('Sections', 'meathouse'),
            'panel' => 'meathouse_custom_panel',
            'priority' => 10,
        )
    );

    // Add Hero Section
    $wp_customize->add_section(
        'meathouse_hero_section',
        array(
            'title' => __('Hero', 'meathouse'),
            'panel' => 'meathouse_custom_panel',
            'priority' => 10,
        )
    );

    // Enable/Disable Hero Section
    $wp_customize->add_setting(
        'meathouse_hs_hero',
        array(
            'default' => '1',
            'capability' => 'edit_theme_options',
            'sanitize_callback' => 'meathouse_child_sanitize_checkbox',
            'transport' => 'postMessage',
        )
    );
    $wp_customize->add_control(
        'meathouse_hs_hero',
        array(
            'label' => __('Enable Hero Section', 'meathouse'),
            'section' => 'meathouse_hero_section',
            'type' => 'checkbox',
            'priority' => 1,
        )
    );

    // Add selective refresh for hero section
    if (isset($wp_customize->selective_refresh)) {
        $wp_customize->selective_refresh->add_partial(
            'meathouse_hs_hero',
            array(
                'selector' => '#hero-section',
                'container_inclusive' => true,
                'render_callback' => function() {
                    $template = get_stylesheet_directory() . '/template-parts/sections/section-hero.php';
                    if (file_exists($template)) {
                        include($template);
                    }
                },
            )
        );
    }

    // Hero Background Video
    $wp_customize->add_setting(
        'meathouse_hero_video',
        array(
            'default' => '',
            'capability' => 'edit_theme_options',
            'sanitize_callback' => 'absint',
        )
    );
    $wp_customize->add_control(
        new WP_Customize_Media_Control(
            $wp_customize,
            'meathouse_hero_video',
            array(
                'label' => __('Background Video (MP4)', 'meathouse'),
                'description' => __('Upload a background video. This will take priority over background image.', 'meathouse'),
                'section' => 'meathouse_hero_section',
                'mime_type' => 'video',
                'priority' => 2,
            )
        )
    );

    // Hero Background Image
    $wp_customize->add_setting(
        'meathouse_hero_image',
        array(
            'default' => '',
            'capability' => 'edit_theme_options',
            'sanitize_callback' => 'meathouse_child_sanitize_url',
        )
    );
    $wp_customize->add_control(
        new WP_Customize_Image_Control(
            $wp_customize,
            'meathouse_hero_image',
            array(
                'label' => __('Background Image', 'meathouse'),
                'description' => __('Fallback if video is not set or not supported.', 'meathouse'),
                'section' => 'meathouse_hero_section',
                'priority' => 3,
            )
        )
    );

    // Hero Overlay Opacity
    $wp_customize->add_setting(
        'meathouse_hero_overlay_opacity',
        array(
            'default' => '0.4',
            'capability' => 'edit_theme_options',
            'sanitize_callback' => 'meathouse_child_sanitize_number',
        )
    );
    $wp_customize->add_control(
        'meathouse_hero_overlay_opacity',
        array(
            'label' => __('Overlay Opacity', 'meathouse'),
            'description' => __('Dark overlay opacity (0.0 to 1.0). Default: 0.4', 'meathouse'),
            'section' => 'meathouse_hero_section',
            'type' => 'number',
            'input_attrs' => array(
                'min' => 0,
                'max' => 1,
                'step' => 0.1,
            ),
            'priority' => 4,
        )
    );

    // Hero Logo
    $wp_customize->add_setting(
        'meathouse_hero_logo',
        array(
            'default' => '',
            'capability' => 'edit_theme_options',
            'sanitize_callback' => 'meathouse_child_sanitize_url',
        )
    );
    $wp_customize->add_control(
        new WP_Customize_Image_Control(
            $wp_customize,
            'meathouse_hero_logo',
            array(
                'label' => __('Hero Logo', 'meathouse'),
                'description' => __('Upload a logo image to display in the hero section.', 'meathouse'),
                'section' => 'meathouse_hero_section',
                'priority' => 4.5,
            )
        )
    );

    // Hero Title
    $wp_customize->add_setting(
        'meathouse_hero_title',
        array(
            'default' => '',
            'capability' => 'edit_theme_options',
            'sanitize_callback' => 'meathouse_child_sanitize_text',
        )
    );
    $wp_customize->add_control(
        'meathouse_hero_title',
        array(
            'label' => __('Hero Title', 'meathouse'),
            'description' => __('Main heading text. Supports HTML tags like <br>.', 'meathouse'),
            'section' => 'meathouse_hero_section',
            'type' => 'textarea',
            'priority' => 5,
        )
    );

    // Hero Description
    $wp_customize->add_setting(
        'meathouse_hero_description',
        array(
            'default' => '',
            'capability' => 'edit_theme_options',
            'sanitize_callback' => 'meathouse_child_sanitize_text',
        )
    );
    $wp_customize->add_control(
        'meathouse_hero_description',
        array(
            'label' => __('Hero Description', 'meathouse'),
            'description' => __('Description text below the title.', 'meathouse'),
            'section' => 'meathouse_hero_section',
            'type' => 'textarea',
            'priority' => 6,
        )
    );

    // Hero Button Text
    $wp_customize->add_setting(
        'meathouse_hero_btn_text',
        array(
            'default' => '',
            'capability' => 'edit_theme_options',
            'sanitize_callback' => 'meathouse_child_sanitize_text',
        )
    );
    $wp_customize->add_control(
        'meathouse_hero_btn_text',
        array(
            'label' => __('Button Text', 'meathouse'),
            'section' => 'meathouse_hero_section',
            'type' => 'text',
            'priority' => 7,
        )
    );

    // Hero Button Link
    $wp_customize->add_setting(
        'meathouse_hero_btn_link',
        array(
            'default' => '',
            'capability' => 'edit_theme_options',
            'sanitize_callback' => 'meathouse_child_sanitize_url',
        )
    );
    $wp_customize->add_control(
        'meathouse_hero_btn_link',
        array(
            'label' => __('Button Link', 'meathouse'),
            'description' => __('Full URL for the button link.', 'meathouse'),
            'section' => 'meathouse_hero_section',
            'type' => 'url',
            'priority' => 8,
        )
    );
}

add_action('customize_register', 'meathouse_child_hero_customizer');
