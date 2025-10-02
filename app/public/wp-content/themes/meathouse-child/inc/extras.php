<?php
/**
 * Extra Functions for Content Modification
 *
 * @package MeatHouse Child
 */

/**
 * Global variable to store pre-rendered templates
 */
global $meathouse_child_rendered_templates;
$meathouse_child_rendered_templates = array();

/**
 * Replace element with id="_section-hero" with hero section template
 *
 * @param string $content The page content
 * @return string Modified content
 */
function meathouse_child_inject_hero_section($content)
{
    // Check if element with id="_section-hero" exists in content
    if (strpos($content, 'id="_section-hero"') === false && strpos($content, "id='_section-hero'") === false) {
        return $content;
    }

    // Get hero section template
    ob_start();
    get_template_part('template-parts/sections/section', 'hero');
    $hero_html = ob_get_clean();

    // If template not found in parent, try child theme
    if (empty($hero_html)) {
        ob_start();
        include(get_stylesheet_directory() . '/template-parts/sections/section-hero.php');
        $hero_html = ob_get_clean();
    }

    // Pattern to match the entire element with id="_section-hero"
    // This will match: <div id="_section-hero">...</div> or <section id="_section-hero">...</section>
    $patterns = array(
        // Match with double quotes
        '/<([a-zA-Z][a-zA-Z0-9]*)[^>]*id="_section-hero"[^>]*>.*?<\/\1>/s',
        // Match with single quotes
        '/<([a-zA-Z][a-zA-Z0-9]*)[^>]*id=\'_section-hero\'[^>]*>.*?<\/\1>/s',
    );

    foreach ($patterns as $pattern) {
        if (preg_match($pattern, $content)) {
            $content = preg_replace($pattern, $hero_html, $content);
            break;
        }
    }

    return $content;
}

/**
 * Replace element with id="_banner_rassurance" with banniere rassurance section template
 *
 * @param string $content The page content
 * @return string Modified content
 */
function meathouse_child_inject_banner_section($content)
{
    // Check if element with id="_banner_rassurance" exists in content
    if (strpos($content, 'id="_banner_rassurance"') === false && strpos($content, "id='_banner_rassurance'") === false) {
        return $content;
    }

    // Get banniere rassurance section template
    ob_start();
    include(get_stylesheet_directory() . '/template-parts/sections/section-banner.php');
    $banner_html = ob_get_clean();

    // Pattern to match the entire element with id="_banner_rassurance"
    $patterns = array(
        // Match with double quotes
        '/<([a-zA-Z][a-zA-Z0-9]*)[^>]*id="_banner_rassurance"[^>]*>.*?<\/\1>/s',
        // Match with single quotes
        '/<([a-zA-Z][a-zA-Z0-9]*)[^>]*id=\'_banner_rassurance\'[^>]*>.*?<\/\1>/s',
    );

    foreach ($patterns as $pattern) {
        if (preg_match($pattern, $content)) {
            $content = preg_replace($pattern, $banner_html, $content);
            break;
        }
    }

    return $content;
}

/**
 * Replace element with id="_banniere_apropos" with banniere apropos section template
 *
 * @param string $content The page content
 * @return string Modified content
 */
function meathouse_child_inject_apropos_section($content)
{
    // Check if element with id="_banniere_apropos" exists in content
    if (strpos($content, 'id="_banniere_apropos"') === false && strpos($content, "id='_banniere_apropos'") === false) {
        return $content;
    }

    // Get banniere apropos section template
    ob_start();
    include(get_stylesheet_directory() . '/template-parts/sections/section-apropos.php');
    $apropos_html = ob_get_clean();

    // Pattern to match the entire element with id="_banniere_apropos"
    $patterns = array(
        // Match with double quotes
        '/<([a-zA-Z][a-zA-Z0-9]*)[^>]*id="_banniere_apropos"[^>]*>.*?<\/\1>/s',
        // Match with single quotes
        '/<([a-zA-Z][a-zA-Z0-9]*)[^>]*id=\'_banniere_apropos\'[^>]*>.*?<\/\1>/s',
    );

    foreach ($patterns as $pattern) {
        if (preg_match($pattern, $content)) {
            $content = preg_replace($pattern, $apropos_html, $content);
            break;
        }
    }

    return $content;
}

/**
 * Replace element with id="_banniere_reviews" with banniere reviews section template
 *
 * @param string $content The page content
 * @return string Modified content
 */
function meathouse_child_inject_reviews_section($content)
{
    // Check if element with id="_banniere_reviews" exists in content
    if (strpos($content, 'id="_banniere_reviews"') === false && strpos($content, "id='_banniere_reviews'") === false) {
        return $content;
    }

    // Get banniere reviews section template
    ob_start();
    include(get_stylesheet_directory() . '/template-parts/sections/section-reviews.php');
    $reviews_html = ob_get_clean();

    // Pattern to match the entire element with id="_banniere_reviews"
    $patterns = array(
        // Match with double quotes
        '/<([a-zA-Z][a-zA-Z0-9]*)[^>]*id="_banniere_reviews"[^>]*>.*?<\/\1>/s',
        // Match with single quotes
        '/<([a-zA-Z][a-zA-Z0-9]*)[^>]*id=\'_banniere_reviews\'[^>]*>.*?<\/\1>/s',
    );

    foreach ($patterns as $pattern) {
        if (preg_match($pattern, $content)) {
            $content = preg_replace($pattern, $reviews_html, $content);
            break;
        }
    }

    return $content;
}

/**
 * Replace element with id="_banniere_product_rassurance" with banniere product rassurance section template
 *
 * @param string $content The page content
 * @return string Modified content
 */
function meathouse_child_inject_product_rassurance_section($content)
{
    // Check if element with id="_banniere_product_rassurance" exists in content
    // More flexible check that allows other attributes before the id
    if (strpos($content, '_banniere_product_rassurance') === false) {
        error_log('OUTPUT NOT FOUND');
        return $content;
    }

    // Build the replacement HTML directly without using ob_start()
    // Get the theme mod values
    $meathouse_hs_product_rassurance = get_theme_mod('meathouse_hs_product_rassurance', '1');
    $meathouse_product_rassurance_items = get_theme_mod('meathouse_product_rassurance_items', '');

    $product_rassurance_html = '';

    if ($meathouse_hs_product_rassurance == '1' && !empty($meathouse_product_rassurance_items)) {
        $items = json_decode($meathouse_product_rassurance_items, true);

        if (!empty($items) && is_array($items)) {
            $product_rassurance_html = '<section class="payment-banner" id="payment-banner">';

            foreach ($items as $item) {
                if (!empty($item['image']) || !empty($item['title']) || !empty($item['subtitle'])) {
                    $product_rassurance_html .= '<div class="payment-banner-item">';

                    if (!empty($item['image'])) {
                        $product_rassurance_html .= '<div class="payment-banner-image">';
                        $product_rassurance_html .= '<img src="' . esc_url($item['image']) . '" alt="' . esc_attr($item['title'] ?? '') . '">';
                        $product_rassurance_html .= '</div>';
                    }

                    $product_rassurance_html .= '<div class="payment-banner-content">';

                    if (!empty($item['title'])) {
                        $product_rassurance_html .= '<h3 class="payment-banner-title">' . esc_html($item['title']) . '</h3>';
                    }

                    if (!empty($item['subtitle'])) {
                        $product_rassurance_html .= '<p class="payment-banner-subtitle">' . wp_kses_post($item['subtitle']) . '</p>';
                    }

                    $product_rassurance_html .= '</div>';
                    $product_rassurance_html .= '</div>';
                }
            }

            $product_rassurance_html .= '</section>';
        }
    }

    if (empty($product_rassurance_html)) {
        return $content;
    }

    // Pattern to match the entire element with id="_banniere_product_rassurance"
    // Using a simpler pattern that matches the opening tag and everything until the closing tag
    $pattern = '/<div[^>]*id=["\']_banniere_product_rassurance["\'][^>]*>.*?<\/div>/s';

    if (preg_match($pattern, $content, $matches)) {
        $content = preg_replace($pattern, $product_rassurance_html, $content);
    }

    return $content;
}

/**
 * Main content modification function
 * Add all content modification functions here
 *
 * @param string $content The page content
 * @return string Modified content
 */
function meathouse_child_modify_page_content($content)
{
    // Inject hero section
    $content = meathouse_child_inject_hero_section($content);

    // Inject banniere rassurance section
    $content = meathouse_child_inject_banner_section($content);

    // Inject banniere apropos section
    $content = meathouse_child_inject_apropos_section($content);

    // Inject banniere reviews section
    $content = meathouse_child_inject_reviews_section($content);

    // Inject banniere product rassurance section
    $content = meathouse_child_inject_product_rassurance_section($content);

    // Add more content modifications here as needed

    return $content;
}
