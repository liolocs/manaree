<?php
/**
 * Banner Reassurance Section Template
 *
 * @package MeatHouse Child
 */

// Get customizer values
$meathouse_hs_banner = get_theme_mod('meathouse_hs_banner', '1');
$meathouse_banner_items = get_theme_mod('meathouse_banner_items', '');

if ($meathouse_hs_banner == '1' && !empty($meathouse_banner_items)) :
    $items = json_decode($meathouse_banner_items, true);

    if (!empty($items) && is_array($items)) : ?>
        <section class="banner-reassurance" id="banner-reassurance">
            <div class="banner-container">
                <div class="banner-items">
                    <?php foreach ($items as $item) :
                        if (!empty($item['image']) || !empty($item['title']) || !empty($item['description'])) : ?>
                            <div class="banner-item">
                                <?php if (!empty($item['image'])) : ?>
                                    <div class="banner-item-image">
                                        <img src="<?php echo esc_url($item['image']); ?>" alt="<?php echo esc_attr($item['title'] ?? ''); ?>">
                                    </div>
                                <?php endif; ?>

                                <?php if (!empty($item['title'])) : ?>
                                    <div class="banner-item-title">
                                        <h3><?php echo esc_html($item['title']); ?></h3>
                                    </div>
                                <?php endif; ?>

                                <?php if (!empty($item['description'])) : ?>
                                    <div class="banner-item-description">
                                        <p><?php echo wp_kses_post($item['description']); ?></p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endif;
                    endforeach; ?>
                </div>
            </div>
        </section>
    <?php endif;
endif; ?>
