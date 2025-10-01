<?php
global $jws_option;
$result = '';
if(isset($jws_option['exclude-blog']) && !empty($jws_option['exclude-blog'])) {
 array_push($jws_option['exclude-blog'], $post->ID);   
 $result = array_map('intval', array_filter($jws_option['exclude-blog'], 'is_numeric'));
}else {
  $result = array($post->ID); 
}

$related = get_posts( array( 'category__in' => wp_get_post_categories($post->ID), 'numberposts' => 100,'post_type' => 'post', 'post__not_in' => $result ) );
if( isset($related[0]) ) :
 ?>
<h3><?php esc_html_e('Related Post','meathouse'); ?></h3>
<div class="post_related_slider jws_blog_layout1" data-slick='{"slidesToShow":2 ,"slidesToScroll": 1, "infinite" : true, "responsive":[{"breakpoint": 1024,"settings":{"slidesToShow": 2}},{"breakpoint": 767,"settings":{"slidesToShow": 2}},{"breakpoint": 480,"settings":{"slidesToShow": 1}}]}'>          
    <?php  foreach( $related as $post ) {
       setup_postdata($post); 
       $format = has_post_format() ? get_post_format() : 'no_format'; ?> 
       <div class="jws_blog_item col-xl-6 col-lg-6 col-12 slick-slide">
            <?php 
                get_template_part( 'template-parts/content/blog/layout/related' );
            ?>
        </div>       
    <?php } ?>
</div>
<?php wp_reset_postdata(); endif; ?>