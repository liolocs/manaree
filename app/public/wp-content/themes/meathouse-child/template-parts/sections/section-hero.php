<?php
/**
 * Hero Section Template
 *
 * @package MeatHouse Child
 */

// Get customizer values
$meathouse_hs_hero = get_theme_mod('meathouse_hs_hero', '1');
$meathouse_hero_title = get_theme_mod('meathouse_hero_title');
$meathouse_hero_description = get_theme_mod('meathouse_hero_description');
$meathouse_hero_btn_text = get_theme_mod('meathouse_hero_btn_text');
$meathouse_hero_btn_link = get_theme_mod('meathouse_hero_btn_link');
$meathouse_hero_image = get_theme_mod('meathouse_hero_image');
$meathouse_hero_video = get_theme_mod('meathouse_hero_video');
$meathouse_hero_video_url = $meathouse_hero_video ? wp_get_attachment_url($meathouse_hero_video) : '';
$meathouse_hero_overlay_opacity = get_theme_mod('meathouse_hero_overlay_opacity', '0.4');

if ($meathouse_hs_hero == '1') : ?>
<section class="meathouse-hero-section elementor-section elementor-section-boxed elementor-section-height-default elementor-section-items-middle elementor-top-section" id="meathouse-hero-section" data-section-type="hero">

    <?php if (!empty($meathouse_hero_video_url)) : ?>
        <!-- Background Video -->
        <div class="elementor-background-video-container elementor-hidden-phone">
            <video autoplay class="elementor-background-video-hosted elementor-html5-video hero-background-video" loop muted playsinline>
                <source src="<?php echo esc_url($meathouse_hero_video_url); ?>" type="video/mp4">
            </video>
        </div>
    <?php elseif (!empty($meathouse_hero_image)) : ?>
        <!-- Background Image -->
        <div class="hero-background-image" style="background-image: url(<?php echo esc_url($meathouse_hero_image); ?>)"></div>
    <?php endif; ?>

    <!-- Overlay -->
    <div class="hero-overlay" style="background: rgba(0, 0, 0, <?php echo esc_attr($meathouse_hero_overlay_opacity); ?>)"></div>

    <!-- Content Container -->
    <div class="elementor-container elementor-column-gap-default jws_section_">

        <!-- Content Column (Hidden on desktop/laptop/mobile/tablet by default, shown on desktop only) -->
        <div class="elementor-column elementor-col-50 elementor-top-column hero-content-column" data-column-type="hero-content">
            <div class="elementor-element-populated elementor-widget-wrap">

                <?php if (!empty($meathouse_hero_title)) : ?>
                    <!-- Title -->
                    <div class="elementor-element animated elementor-widget fadeInUp elementor-element-title elementor-widget-heading" data-animation="fadeInUp">
                        <div class="elementor-widget-container">
                            <h3 class="elementor-heading-title elementor-size-default">
                                <?php echo wp_kses_post($meathouse_hero_title); ?>
                            </h3>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if (!empty($meathouse_hero_description)) : ?>
                    <!-- Description -->
                    <div class="elementor-element animated elementor-widget fadeInUp elementor-element-description" data-animation="fadeInUp">
                        <div class="elementor-widget-container">
                            <p class="hero-description-text">
                                <?php echo wp_kses_post($meathouse_hero_description); ?>
                            </p>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if (!empty($meathouse_hero_btn_text) && !empty($meathouse_hero_btn_link)) : ?>
                    <!-- Button -->
                    <div class="elementor-element animated elementor-widget fadeInUp elementor-align-center elementor-element-button elementor-widget-button" data-animation="fadeInUp">
                        <div class="elementor-widget-container">
                            <div class="elementor-button-wrapper">
                                <a href="<?php echo esc_url($meathouse_hero_btn_link); ?>" class="elementor-button elementor-button-link elementor-size-sm" tabindex="0">
                                    <span class="elementor-button-content-wrapper">
                                        <span class="elementor-button-text"><?php echo esc_html($meathouse_hero_btn_text); ?></span>
                                    </span>
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

            </div>
        </div>

        <!-- Empty Column (Right side) -->
        <div class="elementor-column elementor-col-50 elementor-top-column" data-column-type="empty">
            <div class="elementor-element-populated elementor-widget-wrap"></div>
        </div>

    </div>
</section>
<?php endif; ?>
