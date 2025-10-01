 <div class="jws_gallery_image">
    <a <?php echo ''.$this->get_render_attribute_string($link_key); ?>>
        <img class="thumbnail-popup" src="<?php echo esc_attr($img['p_img_large'][0]); ?>">
    </a>    
    <?php if($settings['link_action'] == 'lightbox') {
       echo ''.$img['thumbnail'];
    } ?>
    <div class="content">
            <?php 
                if(!empty($settings['image']['url'])) echo '<img src="'.$settings['image']['url'].'" alt="'.$settings['image']['alt'].'">';
                $image_title = get_the_title($attach_id);
                $image_caption = wp_get_attachment_caption($attach_id);
                echo '<span class="title-top">'.$image_caption.'</span>';
                echo '<h5 class="title">'.$image_title.'</h5>';
            ?>
    </div>
</div>