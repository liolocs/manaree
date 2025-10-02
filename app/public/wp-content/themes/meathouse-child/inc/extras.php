<?php
/**
 * Extra Functions for Content Modification
 *
 * @package MeatHouse Child
 */

/**
 * Replace element with id="_section-hero" with hero section template
 *
 * @param string $content The page content
 * @return string Modified content
 */
function meathouse_child_inject_hero_section($content) {
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
 * Replace element with id="_banner_rassurance" with banner section template
 *
 * @param string $content The page content
 * @return string Modified content
 */
function meathouse_child_inject_banner_section($content) {
    // Check if element with id="_banner_rassurance" exists in content
    if (strpos($content, 'id="_banner_rassurance"') === false && strpos($content, "id='_banner_rassurance'") === false) {
        return $content;
    }

    // Get banner section template
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
 * Main content modification function
 * Add all content modification functions here
 *
 * @param string $content The page content
 * @return string Modified content
 */
function meathouse_child_modify_page_content($content) {
    // Inject hero section
    $content = meathouse_child_inject_hero_section($content);

    // Inject banner reassurance section
    $content = meathouse_child_inject_banner_section($content);

    // Add more content modifications here as needed

    return $content;
}
