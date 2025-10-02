<?php
/**
 * Template part for displaying Banniere Apropos section
 *
 * @package MeatHouse Child
 */

$meathouse_hs_apropos = get_theme_mod('meathouse_hs_apropos', '1');
$meathouse_apropos_bg_image = get_theme_mod('meathouse_apropos_bg_image');
$meathouse_apropos_logo = get_theme_mod('meathouse_apropos_logo');
$meathouse_apropos_logo_text = get_theme_mod('meathouse_apropos_logo_text');
$meathouse_apropos_title = get_theme_mod('meathouse_apropos_title');
$meathouse_apropos_description = get_theme_mod('meathouse_apropos_description');

if ($meathouse_hs_apropos == '1'): ?>
<section class="banniere-apropos" id="banniere-apropos" <?php if (is_customize_preview()) { echo 'data-customize-partial-id="meathouse_apropos_section"'; } ?>>
    <div class="apropos-container">
        <?php if (!empty($meathouse_apropos_bg_image)): ?>
            <div class="apropos-background">
                <img src="<?php echo esc_url($meathouse_apropos_bg_image); ?>" alt="<?php echo esc_attr($meathouse_apropos_title); ?>">
                <?php if (!empty($meathouse_apropos_logo)): ?>
                    <div class="apropos-logo-overlay">
                        <img src="<?php echo esc_url($meathouse_apropos_logo); ?>" alt="<?php echo esc_attr(get_bloginfo('name')); ?>">
                        <?php if (!empty($meathouse_apropos_logo_text)): ?>
                            <div class="apropos-logo-text"><?php echo wp_kses_post($meathouse_apropos_logo_text); ?></div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <div class="apropos-content">
            <?php if (!empty($meathouse_apropos_title)): ?>
                <h2 class="apropos-title"><?php echo wp_kses_post($meathouse_apropos_title); ?></h2>
            <?php endif; ?>

            <?php if (!empty($meathouse_apropos_description)): ?>
                <div class="apropos-description">
                    <?php echo wp_kses_post($meathouse_apropos_description); ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>
<?php endif; ?>
