<a href="<?php echo esc_url($url); ?>" <?php echo esc_attr($target.$nofollow); ?>>
    <?php if(!empty($settings['image']['id'])) echo '<div class="box-image">'. \Elementor\Group_Control_Image_Size::get_attachment_image_html( $settings ).'</div>'; ?>
    <div class="box-icon">
        <?php \Elementor\Icons_Manager::render_icon( $settings['icon'], [ 'aria-hidden' => 'true' ] );  ?>  
    </div>
    <h5 class="box-title">
        <?php echo esc_html($settings['info_title']); ?>
    </h5>
    <div class="box-content">
        <?php echo ''.$settings['info_content']; ?>
    </div>
    <div class="box-more">
       <?php echo esc_html($settings['info_readmore']); ?><i class="jws-icon-caret-right-thin"></i>
    </div>
</a>