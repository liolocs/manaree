<?php
/**
 * Template for displaying Coupon Banner
 *
 * @package Coupon Banner
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

$coupon_banner_enabled = get_theme_mod('coupon_banner_enabled', '1');
$coupon_banner_text = get_theme_mod('coupon_banner_text');
$coupon_banner_selected_coupon = get_theme_mod('coupon_banner_selected_coupon');

if ($coupon_banner_enabled == '1' && (!empty($coupon_banner_text) || !empty($coupon_banner_selected_coupon))): ?>
<section class="coupon-banner" id="coupon-banner" data-customize-section="coupon_banner_section">
    <div class="coupon-banner-container">
        <?php if (!empty($coupon_banner_text)): ?>
            <div class="coupon-banner-text">
                <?php echo wp_kses_post($coupon_banner_text); ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($coupon_banner_selected_coupon)): ?>
            <div class="coupon-banner-code">
                <?php echo esc_html($coupon_banner_selected_coupon); ?>
            </div>
        <?php endif; ?>
    </div>
</section>
<?php endif; ?>
