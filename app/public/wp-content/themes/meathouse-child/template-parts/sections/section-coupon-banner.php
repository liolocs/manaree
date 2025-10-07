<?php
/**
 * Template part for displaying Coupon Banner section
 *
 * @package MeatHouse Child
 */

$meathouse_hs_coupon_banner = get_theme_mod('meathouse_hs_coupon_banner', '1');
$meathouse_coupon_banner_text = get_theme_mod('meathouse_coupon_banner_text');
$meathouse_coupon_banner_code = get_theme_mod('meathouse_coupon_banner_code');

if ($meathouse_hs_coupon_banner == '1' && (!empty($meathouse_coupon_banner_text) || !empty($meathouse_coupon_banner_code))): ?>
<section class="coupon-banner" id="coupon-banner">
    <div class="coupon-banner-container">
        <?php if (!empty($meathouse_coupon_banner_text)): ?>
            <div class="coupon-banner-text">
                <?php echo wp_kses_post($meathouse_coupon_banner_text); ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($meathouse_coupon_banner_code)): ?>
            <div class="coupon-banner-code">
                <?php echo esc_html($meathouse_coupon_banner_code); ?>
            </div>
        <?php endif; ?>
    </div>
</section>
<?php endif; ?>
