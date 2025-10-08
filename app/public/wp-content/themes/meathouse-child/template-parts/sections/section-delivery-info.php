<?php
/**
 * Template part for displaying Delivery Information section
 *
 * @package MeatHouse Child
 */

$meathouse_hs_delivery_info = get_theme_mod('meathouse_hs_delivery_info', '1');
$meathouse_delivery_info_title = get_theme_mod('meathouse_delivery_info_title');
$meathouse_delivery_info_text = get_theme_mod('meathouse_delivery_info_text');

if ($meathouse_hs_delivery_info == '1' && (!empty($meathouse_delivery_info_title) || !empty($meathouse_delivery_info_text))): ?>
<div class="delivery-info-section" id="delivery-info">
    <?php if (!empty($meathouse_delivery_info_title)): ?>
        <h3 class="delivery-info-title">
            <?php echo esc_html($meathouse_delivery_info_title); ?>
        </h3>
    <?php endif; ?>

    <?php if (!empty($meathouse_delivery_info_text)): ?>
        <div class="delivery-info-text">
            <?php echo wp_kses_post($meathouse_delivery_info_text); ?>
        </div>
    <?php endif; ?>
</div>
<?php endif; ?>
