<?php
/**
 * Template part for displaying Banniere Product Rassurance section
 *
 * @package MeatHouse Child
 */

$meathouse_hs_product_rassurance = get_theme_mod('meathouse_hs_product_rassurance', '1');
$meathouse_product_rassurance_items = get_theme_mod('meathouse_product_rassurance_items', '');

if ($meathouse_hs_product_rassurance == '1') :
    $items = !empty($meathouse_product_rassurance_items) ? json_decode($meathouse_product_rassurance_items, true) : array();
    ?>
    <section class="payment-banner" id="payment-banner">
        <?php if (!empty($items) && is_array($items)) :
            foreach ($items as $item) :
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
            endforeach;
        endif; ?>
    </section>
<?php endif; ?>
