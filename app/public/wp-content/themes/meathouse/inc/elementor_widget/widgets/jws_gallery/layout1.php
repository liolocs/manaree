 <div class="jws_gallery_image">
    <a <?php echo ''.$this->get_render_attribute_string($link_key); ?>>
        <img class="thumbnail-popup" src="<?php echo esc_attr($img['p_img_large'][0]); ?>" alt="<?php echo esc_attr($item['image']['alt']); ?>">
    </a>    
    <?php if($settings['link_action'] == 'lightbox') {
       echo ''.$img['thumbnail'];
    } ?>
</div>