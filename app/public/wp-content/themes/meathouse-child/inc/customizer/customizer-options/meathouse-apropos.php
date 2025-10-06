<?php
/**
 * Banniere Apropos Section Customizer Options
 *
 * @package MeatHouse Child
 */

/**
 * Add Banniere Apropos Section Customizer Options
 */
function meathouse_child_apropos_customizer($wp_customize) {

    // Add Banniere Apropos Section
    $wp_customize->add_section(
        'meathouse_apropos_section',
        array(
            'title' => __('Banniere Apropos', 'meathouse'),
            'panel' => 'meathouse_custom_panel',
            'priority' => 30,
        )
    );

    // Enable/Disable Banniere Apropos Section
    $wp_customize->add_setting(
        'meathouse_hs_apropos',
        array(
            'default' => '1',
            'capability' => 'edit_theme_options',
            'sanitize_callback' => 'meathouse_child_sanitize_checkbox',
            'transport' => 'postMessage',
        )
    );
    $wp_customize->add_control(
        'meathouse_hs_apropos',
        array(
            'label' => __('Activer Banniere Apropos', 'meathouse'),
            'section' => 'meathouse_apropos_section',
            'type' => 'checkbox',
            'priority' => 1,
        )
    );

    // Background Image
    $wp_customize->add_setting(
        'meathouse_apropos_bg_image',
        array(
            'default' => '',
            'capability' => 'edit_theme_options',
            'sanitize_callback' => 'meathouse_child_sanitize_url',
            'transport' => 'postMessage',
        )
    );
    $wp_customize->add_control(
        new WP_Customize_Image_Control(
            $wp_customize,
            'meathouse_apropos_bg_image',
            array(
                'label' => __('Image de fond', 'meathouse'),
                'section' => 'meathouse_apropos_section',
                'priority' => 2,
            )
        )
    );

    // Logo
    // $wp_customize->add_setting(
    //     'meathouse_apropos_logo',
    //     array(
    //         'default' => '',
    //         'capability' => 'edit_theme_options',
    //         'sanitize_callback' => 'meathouse_child_sanitize_url',
    //         'transport' => 'postMessage',
    //     )
    // );
    // $wp_customize->add_control(
    //     new WP_Customize_Image_Control(
    //         $wp_customize,
    //         'meathouse_apropos_logo',
    //         array(
    //             'label' => __('Logo', 'meathouse'),
    //             'section' => 'meathouse_apropos_section',
    //             'priority' => 3,
    //         )
    //     )
    // );

    // Logo Text (circular text around logo)
    // $wp_customize->add_setting(
    //     'meathouse_apropos_logo_text',
    //     array(
    //         'default' => '',
    //         'capability' => 'edit_theme_options',
    //         'sanitize_callback' => 'wp_kses_post',
    //         'transport' => 'postMessage',
    //     )
    // );
    // $wp_customize->add_control(
    //     'meathouse_apropos_logo_text',
    //     array(
    //         'label' => __('Texte circulaire du logo', 'meathouse'),
    //         'description' => __('Texte qui apparaît en cercle autour du logo (séparé par • pour chaque élément)', 'meathouse'),
    //         'section' => 'meathouse_apropos_section',
    //         'type' => 'textarea',
    //         'priority' => 4,
    //     )
    // );

    // Title
    $wp_customize->add_setting(
        'meathouse_apropos_title',
        array(
            'default' => '',
            'capability' => 'edit_theme_options',
            'sanitize_callback' => 'wp_kses_post',
            'transport' => 'postMessage',
        )
    );
    $wp_customize->add_control(
        'meathouse_apropos_title',
        array(
            'label' => __('Titre', 'meathouse'),
            'section' => 'meathouse_apropos_section',
            'type' => 'textarea',
            'priority' => 5,
        )
    );

    // Description
    $wp_customize->add_setting(
        'meathouse_apropos_description',
        array(
            'default' => '',
            'capability' => 'edit_theme_options',
            'sanitize_callback' => 'wp_kses_post',
            'transport' => 'postMessage',
        )
    );
    $wp_customize->add_control(
        'meathouse_apropos_description',
        array(
            'label' => __('Description', 'meathouse'),
            'section' => 'meathouse_apropos_section',
            'type' => 'textarea',
            'priority' => 6,
        )
    );

    // Add selective refresh for apropos section
    if (isset($wp_customize->selective_refresh)) {
        $wp_customize->selective_refresh->add_partial(
            'meathouse_apropos_section',
            array(
                'selector' => '#banniere-apropos',
                'container_inclusive' => true,
                'settings' => array(
                    'meathouse_hs_apropos',
                    'meathouse_apropos_bg_image',
                    'meathouse_apropos_title',
                    'meathouse_apropos_description',
                ),
                'render_callback' => function() {
                    $template = get_stylesheet_directory() . '/template-parts/sections/section-apropos.php';
                    if (file_exists($template)) {
                        include($template);
                    }
                },
            )
        );
    }
}

add_action('customize_register', 'meathouse_child_apropos_customizer');
