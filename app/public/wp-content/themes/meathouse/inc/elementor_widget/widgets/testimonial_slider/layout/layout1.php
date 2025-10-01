<div class="slider-content">
    <span class="quocte_i">&ldquo;</span>
    <div class="testimonials_description"><?php echo ''.$item['list_description']; ?></div>   
    <?php if(!empty($item['image']['id'])) echo \Elementor\Group_Control_Image_Size::get_attachment_image_html( $item );?>  
    <div class="testimonials-info">
        <div class="testimonials-title"><?php echo ''.$item['list_name']; ?></div>
    </div>  
</div>

