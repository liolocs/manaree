<?php $img_atts = isset($item['image']['id']) ? \Elementor\Group_Control_Image_Size::get_attachment_image_src( $item['image']['id'], 'image', $item ) : '';?>
<div class="jws-banner-inner">
    <a <?php echo ''.$this->get_render_attribute_string($link_key); ?>>
        <div style="background-image: url(<?php echo ''.$img_atts; ?>);" class="banner-image"></div>
        <div class="jws-banner-content">
            <div class="inner">
                <h4 class="text-1">
                <?php echo esc_html($item['text1']); ?>
            </h4>
            <div class="text-2"><?php echo esc_html($item['text2']); ?></div>
            <span class="button"><?php echo esc_html($item['text3']); ?></span>
            </div>
        </div>
    </a>
</div>