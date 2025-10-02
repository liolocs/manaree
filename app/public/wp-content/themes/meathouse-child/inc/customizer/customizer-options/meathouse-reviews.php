<?php
/**
 * Banniere Reviews Section Customizer Options
 *
 * @package MeatHouse Child
 */

/**
 * Add Banniere Reviews Section Customizer Options
 */
function meathouse_child_reviews_customizer($wp_customize) {

    // Add Banniere Reviews Section
    $wp_customize->add_section(
        'meathouse_reviews_section',
        array(
            'title' => __('Banniere Avis', 'meathouse'),
            'panel' => 'meathouse_custom_panel',
            'priority' => 40,
        )
    );

    // Enable/Disable Banniere Reviews Section
    $wp_customize->add_setting(
        'meathouse_hs_reviews',
        array(
            'default' => '1',
            'capability' => 'edit_theme_options',
            'sanitize_callback' => 'meathouse_child_sanitize_checkbox',
            'transport' => 'postMessage',
        )
    );
    $wp_customize->add_control(
        'meathouse_hs_reviews',
        array(
            'label' => __('Activer Banniere Avis', 'meathouse'),
            'section' => 'meathouse_reviews_section',
            'type' => 'checkbox',
            'priority' => 1,
        )
    );

    // Image
    $wp_customize->add_setting(
        'meathouse_reviews_image',
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
            'meathouse_reviews_image',
            array(
                'label' => __('Image', 'meathouse'),
                'section' => 'meathouse_reviews_section',
                'priority' => 2,
            )
        )
    );

    // Title
    $wp_customize->add_setting(
        'meathouse_reviews_title',
        array(
            'default' => '',
            'capability' => 'edit_theme_options',
            'sanitize_callback' => 'wp_kses_post',
            'transport' => 'postMessage',
        )
    );
    $wp_customize->add_control(
        'meathouse_reviews_title',
        array(
            'label' => __('Titre', 'meathouse'),
            'section' => 'meathouse_reviews_section',
            'type' => 'textarea',
            'priority' => 3,
        )
    );

    // Rating
    $wp_customize->add_setting(
        'meathouse_reviews_rating',
        array(
            'default' => '5',
            'capability' => 'edit_theme_options',
            'sanitize_callback' => 'absint',
            'transport' => 'postMessage',
        )
    );
    $wp_customize->add_control(
        'meathouse_reviews_rating',
        array(
            'label' => __('Note (1-5)', 'meathouse'),
            'section' => 'meathouse_reviews_section',
            'type' => 'number',
            'input_attrs' => array(
                'min' => 1,
                'max' => 5,
                'step' => 1,
            ),
            'priority' => 4,
        )
    );

    // Quotes JSON storage
    $wp_customize->add_setting(
        'meathouse_reviews_quotes',
        array(
            'default' => '',
            'capability' => 'edit_theme_options',
            'sanitize_callback' => 'wp_kses_post',
        )
    );

    // Helper fields for up to 3 quotes
    for ($i = 1; $i <= 3; $i++) {
        // Quote Text
        $wp_customize->add_setting(
            "meathouse_reviews_quote_{$i}_text",
            array(
                'default' => '',
                'capability' => 'edit_theme_options',
                'sanitize_callback' => 'wp_kses_post',
                'transport' => 'postMessage',
            )
        );
        $wp_customize->add_control(
            "meathouse_reviews_quote_{$i}_text",
            array(
                'label' => sprintf(__('Citation %d - Texte', 'meathouse'), $i),
                'section' => 'meathouse_reviews_section',
                'type' => 'textarea',
                'priority' => (10 + ($i * 2)),
            )
        );

        // Quote Name
        $wp_customize->add_setting(
            "meathouse_reviews_quote_{$i}_name",
            array(
                'default' => '',
                'capability' => 'edit_theme_options',
                'sanitize_callback' => 'sanitize_text_field',
                'transport' => 'postMessage',
            )
        );
        $wp_customize->add_control(
            "meathouse_reviews_quote_{$i}_name",
            array(
                'label' => sprintf(__('Citation %d - Nom', 'meathouse'), $i),
                'section' => 'meathouse_reviews_section',
                'type' => 'text',
                'priority' => (11 + ($i * 2)),
            )
        );
    }

    // Add selective refresh for reviews section
    if (isset($wp_customize->selective_refresh)) {
        $wp_customize->selective_refresh->add_partial(
            'meathouse_reviews_section',
            array(
                'selector' => '#banniere-reviews',
                'container_inclusive' => true,
                'settings' => array(
                    'meathouse_hs_reviews',
                    'meathouse_reviews_image',
                    'meathouse_reviews_title',
                    'meathouse_reviews_rating',
                    'meathouse_reviews_quote_1_text',
                    'meathouse_reviews_quote_1_name',
                    'meathouse_reviews_quote_2_text',
                    'meathouse_reviews_quote_2_name',
                    'meathouse_reviews_quote_3_text',
                    'meathouse_reviews_quote_3_name',
                ),
                'render_callback' => function() {
                    $template = get_stylesheet_directory() . '/template-parts/sections/section-reviews.php';
                    if (file_exists($template)) {
                        include($template);
                    }
                },
            )
        );
    }
}

add_action('customize_register', 'meathouse_child_reviews_customizer');

/**
 * Build JSON from individual quote fields on customizer save
 */
function meathouse_child_build_reviews_json($wp_customize) {
    $quotes = array();

    for ($i = 1; $i <= 3; $i++) {
        $text = get_theme_mod("meathouse_reviews_quote_{$i}_text");
        $name = get_theme_mod("meathouse_reviews_quote_{$i}_name");

        if (!empty($text) || !empty($name)) {
            $quotes[] = array(
                'quote_text' => $text,
                'quote_name' => $name,
            );
        }
    }

    if (!empty($quotes)) {
        set_theme_mod('meathouse_reviews_quotes', json_encode($quotes));
    }
}
add_action('customize_save_after', 'meathouse_child_build_reviews_json');
