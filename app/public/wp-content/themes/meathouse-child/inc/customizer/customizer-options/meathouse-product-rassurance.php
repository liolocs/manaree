<?php
/**
 * Banniere Product Rassurance Section Customizer Options
 *
 * @package MeatHouse Child
 */

/**
 * Add Banniere Product Rassurance Section Customizer Options
 */
function meathouse_child_product_rassurance_customizer($wp_customize) {

    // Add Banniere Product Rassurance Section
    $wp_customize->add_section(
        'meathouse_product_rassurance_section',
        array(
            'title' => __('Banniere Product Rassurance', 'meathouse'),
            'panel' => 'meathouse_custom_panel',
            'priority' => 50,
        )
    );

    // Enable/Disable Banniere Product Rassurance Section
    $wp_customize->add_setting(
        'meathouse_hs_product_rassurance',
        array(
            'default' => '1',
            'capability' => 'edit_theme_options',
            'sanitize_callback' => 'meathouse_child_sanitize_checkbox',
            'transport' => 'postMessage',
        )
    );
    $wp_customize->add_control(
        'meathouse_hs_product_rassurance',
        array(
            'label' => __('Activer Banniere Product Rassurance', 'meathouse'),
            'section' => 'meathouse_product_rassurance_section',
            'type' => 'checkbox',
            'priority' => 1,
        )
    );

    // Items JSON storage
    $wp_customize->add_setting(
        'meathouse_product_rassurance_items',
        array(
            'default' => '',
            'capability' => 'edit_theme_options',
            'sanitize_callback' => 'wp_kses_post',
        )
    );

    // Helper fields for 2 items
    for ($i = 1; $i <= 2; $i++) {
        // Item Image
        $wp_customize->add_setting(
            "meathouse_product_rassurance_item_{$i}_image",
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
                "meathouse_product_rassurance_item_{$i}_image",
                array(
                    'label' => sprintf(__('Élément %d - Image', 'meathouse'), $i),
                    'section' => 'meathouse_product_rassurance_section',
                    'priority' => (10 + ($i * 3)),
                )
            )
        );

        // Item Title
        $wp_customize->add_setting(
            "meathouse_product_rassurance_item_{$i}_title",
            array(
                'default' => '',
                'capability' => 'edit_theme_options',
                'sanitize_callback' => 'sanitize_text_field',
                'transport' => 'postMessage',
            )
        );
        $wp_customize->add_control(
            "meathouse_product_rassurance_item_{$i}_title",
            array(
                'label' => sprintf(__('Élément %d - Titre', 'meathouse'), $i),
                'section' => 'meathouse_product_rassurance_section',
                'type' => 'text',
                'priority' => (11 + ($i * 3)),
            )
        );

        // Item Subtitle
        $wp_customize->add_setting(
            "meathouse_product_rassurance_item_{$i}_subtitle",
            array(
                'default' => '',
                'capability' => 'edit_theme_options',
                'sanitize_callback' => 'wp_kses_post',
                'transport' => 'postMessage',
            )
        );
        $wp_customize->add_control(
            "meathouse_product_rassurance_item_{$i}_subtitle",
            array(
                'label' => sprintf(__('Élément %d - Sous-titre', 'meathouse'), $i),
                'section' => 'meathouse_product_rassurance_section',
                'type' => 'textarea',
                'priority' => (12 + ($i * 3)),
            )
        );
    }

    // Add selective refresh for product rassurance section
    if (isset($wp_customize->selective_refresh)) {
        $wp_customize->selective_refresh->add_partial(
            'meathouse_hs_product_rassurance',
            array(
                'selector' => '#payment-banner',
                'container_inclusive' => true,
                'render_callback' => function() {
                    $template = get_stylesheet_directory() . '/template-parts/sections/section-product-rassurance.php';
                    if (file_exists($template)) {
                        include($template);
                    }
                },
            )
        );

        // Add selective refresh for each product rassurance item
        for ($i = 1; $i <= 2; $i++) {
            $wp_customize->selective_refresh->add_partial(
                "meathouse_product_rassurance_item_{$i}",
                array(
                    'selector' => "#payment-banner .payment-banner-item:nth-child({$i})",
                    'container_inclusive' => true,
                    'settings' => array(
                        "meathouse_product_rassurance_item_{$i}_image",
                        "meathouse_product_rassurance_item_{$i}_title",
                        "meathouse_product_rassurance_item_{$i}_subtitle",
                    ),
                    'render_callback' => function() use ($i) {
                        $items_json = get_theme_mod('meathouse_product_rassurance_items', '');
                        $items = json_decode($items_json, true);

                        if (!empty($items) && is_array($items) && isset($items[$i - 1])) {
                            $item = $items[$i - 1];
                            if (!empty($item['image']) || !empty($item['title']) || !empty($item['subtitle'])) : ?>
                                <div class="payment-banner-item">
                                    <?php if (!empty($item['image'])) : ?>
                                        <div class="payment-banner-image">
                                            <img src="<?php echo esc_url($item['image']); ?>" alt="<?php echo esc_attr($item['title'] ?? ''); ?>">
                                        </div>
                                    <?php endif; ?>

                                    <div class="payment-banner-content">
                                        <?php if (!empty($item['title'])) : ?>
                                            <h3 class="payment-banner-title"><?php echo esc_html($item['title']); ?></h3>
                                        <?php endif; ?>

                                        <?php if (!empty($item['subtitle'])) : ?>
                                            <p class="payment-banner-subtitle"><?php echo wp_kses_post($item['subtitle']); ?></p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endif;
                        }
                    },
                )
            );
        }
    }
}

add_action('customize_register', 'meathouse_child_product_rassurance_customizer');

/**
 * Build JSON from individual fields on customizer save
 */
function meathouse_child_build_product_rassurance_json($wp_customize) {
    $items = array();

    for ($i = 1; $i <= 2; $i++) {
        $image = get_theme_mod("meathouse_product_rassurance_item_{$i}_image");
        $title = get_theme_mod("meathouse_product_rassurance_item_{$i}_title");
        $subtitle = get_theme_mod("meathouse_product_rassurance_item_{$i}_subtitle");

        if (!empty($image) || !empty($title) || !empty($subtitle)) {
            $items[] = array(
                'image' => $image,
                'title' => $title,
                'subtitle' => $subtitle,
            );
        }
    }

    if (!empty($items)) {
        set_theme_mod('meathouse_product_rassurance_items', json_encode($items));
    }
}
add_action('customize_save_after', 'meathouse_child_build_product_rassurance_json');
