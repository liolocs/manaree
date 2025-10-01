<div class="jws-image_carousel-image">
    <a <?php echo ''.$this->get_render_attribute_string($link_key); ?>>
        <?php 
            if(!empty($item['image']['id'])) echo \Elementor\Group_Control_Image_Size::get_attachment_image_html( $item );
            if(!empty($item['title'])) echo '<div class="name">'.$item['title'].'</div>'
        ?> 
    </a>
</div>