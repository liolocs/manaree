<?php
/**
 * Banniere Rassurance Section Customizer Options
 *
 * @package MeatHouse Child
 */

/**
 * Add Banniere Rassurance Section Customizer Options
 */
function meathouse_child_banner_customizer($wp_customize) {

    // Add Banniere Rassurance Section
    $wp_customize->add_section(
        'meathouse_banner_section',
        array(
            'title' => __('Banniere Rassurance', 'meathouse'),
            'panel' => 'meathouse_custom_panel',
            'priority' => 20,
        )
    );

    // Enable/Disable Banniere Rassurance Section
    $wp_customize->add_setting(
        'meathouse_hs_banner',
        array(
            'default' => '1',
            'capability' => 'edit_theme_options',
            'sanitize_callback' => 'meathouse_child_sanitize_checkbox',
            'transport' => 'postMessage',
        )
    );
    $wp_customize->add_control(
        'meathouse_hs_banner',
        array(
            'label' => __('Activer Banniere Rassurance', 'meathouse'),
            'section' => 'meathouse_banner_section',
            'type' => 'checkbox',
            'priority' => 1,
        )
    );

    // Banner Items (stored as JSON - hidden setting, built from individual fields)
    $wp_customize->add_setting(
        'meathouse_banner_items',
        array(
            'default' => '',
            'capability' => 'edit_theme_options',
            'sanitize_callback' => 'wp_kses_post',
        )
    );

    // Individual fields for easier item management
    for ($i = 1; $i <= 4; $i++) {
        // Item Image
        $wp_customize->add_setting(
            "meathouse_banner_item_{$i}_image",
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
                "meathouse_banner_item_{$i}_image",
                array(
                    'label' => sprintf(__('Élément %d - Image', 'meathouse'), $i),
                    'section' => 'meathouse_banner_section',
                    'priority' => (10 + ($i * 3)),
                )
            )
        );

        // Item Title
        $wp_customize->add_setting(
            "meathouse_banner_item_{$i}_title",
            array(
                'default' => '',
                'capability' => 'edit_theme_options',
                'sanitize_callback' => 'sanitize_text_field',
                'transport' => 'postMessage',
            )
        );
        $wp_customize->add_control(
            "meathouse_banner_item_{$i}_title",
            array(
                'label' => sprintf(__('Élément %d - Titre', 'meathouse'), $i),
                'section' => 'meathouse_banner_section',
                'type' => 'text',
                'priority' => (11 + ($i * 3)),
            )
        );

        // Item Description
        $wp_customize->add_setting(
            "meathouse_banner_item_{$i}_description",
            array(
                'default' => '',
                'capability' => 'edit_theme_options',
                'sanitize_callback' => 'wp_kses_post',
                'transport' => 'postMessage',
            )
        );
        $wp_customize->add_control(
            "meathouse_banner_item_{$i}_description",
            array(
                'label' => sprintf(__('Élément %d - Description', 'meathouse'), $i),
                'section' => 'meathouse_banner_section',
                'type' => 'textarea',
                'priority' => (12 + ($i * 3)),
            )
        );
    }

    // Add selective refresh for banner section
    if (isset($wp_customize->selective_refresh)) {
        $wp_customize->selective_refresh->add_partial(
            'meathouse_hs_banner',
            array(
                'selector' => '#banniere-reassurance',
                'container_inclusive' => true,
                'render_callback' => function() {
                    $template = get_stylesheet_directory() . '/template-parts/sections/section-banner.php';
                    if (file_exists($template)) {
                        include($template);
                    }
                },
            )
        );

        // Add selective refresh for each banner item
        for ($i = 1; $i <= 4; $i++) {
            $wp_customize->selective_refresh->add_partial(
                "meathouse_banner_item_{$i}",
                array(
                    'selector' => "[data-customize-partial-id='meathouse_banner_item_{$i}']",
                    'container_inclusive' => true,
                    'settings' => array(
                        "meathouse_banner_item_{$i}_image",
                        "meathouse_banner_item_{$i}_title",
                        "meathouse_banner_item_{$i}_description",
                    ),
                    'render_callback' => function() use ($i) {
                        $items_json = get_theme_mod('meathouse_banner_items', '');
                        $items = json_decode($items_json, true);

                        if (!empty($items) && is_array($items) && isset($items[$i - 1])) {
                            $item = $items[$i - 1];
                            if (!empty($item['image']) || !empty($item['title']) || !empty($item['description'])) : ?>
                                <div class="banniere-item" <?php if (is_customize_preview()) { echo 'data-customize-partial-id="meathouse_banner_item_' . $i . '"'; } ?>>
                                    <?php if (!empty($item['image'])) : ?>
                                        <div class="banniere-item-image">
                                            <img src="<?php echo esc_url($item['image']); ?>" alt="<?php echo esc_attr($item['title'] ?? ''); ?>">
                                        </div>
                                    <?php endif; ?>

                                    <?php if (!empty($item['title'])) : ?>
                                        <div class="banniere-item-title">
                                            <h3><?php echo esc_html($item['title']); ?></h3>
                                        </div>
                                    <?php endif; ?>

                                    <?php if (!empty($item['description'])) : ?>
                                        <div class="banniere-item-description">
                                            <p><?php echo wp_kses_post($item['description']); ?></p>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endif;
                        }
                    },
                )
            );
        }
    }
}

add_action('customize_register', 'meathouse_child_banner_customizer');

/**
 * Build JSON from individual fields on customizer save
 */
function meathouse_child_build_banner_json($wp_customize) {
    $items = array();

    for ($i = 1; $i <= 4; $i++) {
        $image = get_theme_mod("meathouse_banner_item_{$i}_image");
        $title = get_theme_mod("meathouse_banner_item_{$i}_title");
        $description = get_theme_mod("meathouse_banner_item_{$i}_description");

        if (!empty($image) || !empty($title) || !empty($description)) {
            $items[] = array(
                'image' => $image,
                'title' => $title,
                'description' => $description,
            );
        }
    }

    if (!empty($items)) {
        set_theme_mod('meathouse_banner_items', json_encode($items));
    }
}
add_action('customize_save_after', 'meathouse_child_build_banner_json');
