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

if ($meathouse_hs_hero == '1'): ?>
    <section class="hero-section" id="hero-section">
        <div class="hero-container">
            <?php if (!empty($meathouse_hero_video_url)): ?>
                <video autoplay class="hero-background-video" loop muted playsinline>
                    <source src="<?php echo esc_url($meathouse_hero_video_url); ?>" type="video/mp4">
                </video>
            <?php elseif (!empty($meathouse_hero_image)): ?>
                <div class="hero-background-image" style="background-image: url(<?php echo esc_url($meathouse_hero_image); ?>)">
                </div>
            <?php endif; ?>

            <div class="hero-overlay"
                style="background: rgba(0, 0, 0, <?php echo esc_attr($meathouse_hero_overlay_opacity); ?>)"></div>

            <div class="hero-content">
                <div class="main-content text-center">
                    <?php if (!empty($meathouse_hero_title)): ?>
                        <h1><?php echo wp_kses_post($meathouse_hero_title); ?></h1>
                    <?php endif; ?>

                    <?php if (!empty($meathouse_hero_description)): ?>
                        <div class="hero-description">
                            <span><?php echo wp_kses_post($meathouse_hero_description); ?></span>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($meathouse_hero_btn_text) && !empty($meathouse_hero_btn_link)): ?>
                        <div class="hero-button-wrapper">
                            <a class="btn btn-primary" data-text="<?php echo esc_attr($meathouse_hero_btn_text); ?>"
                                href="<?php echo esc_url($meathouse_hero_btn_link); ?>">
                                <span><?php echo esc_html($meathouse_hero_btn_text); ?></span>
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>
<?php endif; ?>