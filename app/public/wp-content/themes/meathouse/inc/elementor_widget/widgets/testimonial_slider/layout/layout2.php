<div class="slider-content">
       <div class="content-left">
           <?php if(!empty($item['image']['id'])) echo \Elementor\Group_Control_Image_Size::get_attachment_image_html( $item );?> 
           <span class="average-rating">
                   <span class="jws-star-rating"><span class="jws-star-rated" style="width:100%"></span></span>
           </span>   
       </div> 
       <div class="content-right">
           <div class="testimonials-description"><?php echo ''.$item['list_description']; ?></div>
           <div class="testimonials-info">
               <?php 
                 if(!empty($item['list_name'])) {
                    echo '<div class="testimonials-title">'.$item['list_name'].'</div>';
                 }
                 if(!empty($item['list_job'])) {
                    echo '<div class="testimonials-job">'.$item['list_job'].'</div>';
                 }
               ?>  
           </div> 
      </div>          
</div>

