<?php
/**
 * Template part for displaying Delivery Information section
 *
 * @package MeatHouse Child
 */

$meathouse_hs_delivery_info = get_theme_mod('meathouse_hs_delivery_info', '1');
$meathouse_delivery_info_bg_color = get_theme_mod('meathouse_delivery_info_bg_color', '#FFF5E6');
$meathouse_delivery_info_text_color = get_theme_mod('meathouse_delivery_info_text_color', '#333333');

// Check if section is enabled
if ($meathouse_hs_delivery_info != '1') {
    return;
}

// Collect all items
$has_content = false;
$items = array();

for ($i = 1; $i <= 6; $i++) {
    $text = get_theme_mod("meathouse_delivery_info_item_{$i}_text");
    if (!empty($text)) {
        $items[] = array(
            'text' => $text,
            'color' => get_theme_mod("meathouse_delivery_info_item_{$i}_color"),
        );
        $has_content = true;
    }
}

// Only render if there's at least one item
if (!$has_content) {
    return;
}
?>

<div class="delivery-info-section" id="delivery-info" style="background-color: <?php echo esc_attr($meathouse_delivery_info_bg_color); ?>; color: <?php echo esc_attr($meathouse_delivery_info_text_color); ?>;">
    <?php foreach ($items as $item): ?>
        <div class="delivery-info-item"<?php if (!empty($item['color'])): ?> style="color: <?php echo esc_attr($item['color']); ?>;"<?php endif; ?>>
            <?php echo meathouse_parse_delivery_text($item['text']); ?>
        </div>
    <?php endforeach; ?>
</div>
