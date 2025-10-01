<?php 
    $archive_year  = get_the_time('Y'); 
	$archive_month = get_the_time('m'); 
	$archive_day   = get_the_time('d');
    
    global $jws_option;
    $format = get_post_format();
    $image_size = isset($jws_option['single_related_imagesize']) ? $jws_option['single_related_imagesize'] : 'full';
  
  
if($format == 'quote'){ ?>
     <div class="jws_post_ql quote">
         <i class="ql_icon">&ldquo;</i>
         <div class="ql_overlay">
            <div class="ql_holder"></div>
            <div class="ql_bg">
                <?php echo get_the_post_thumbnail(get_the_ID(), 'full'); ?>
            </div>
         </div>
         <div class="jws_ql_content">
               <h4 class="jws_ql_excerpt">
                        <?php  echo get_the_title();?>
               </h4>
               <?php $quote_name = get_post_meta(get_the_ID(), 'blog_name_quote', true); if(isset($quote_name)) echo '<div class="ql_name">'.$quote_name.'</div>';  ?>
         </div>
     </div>
<?php }elseif($format == 'link'){ ?>
    <div class="jws_post_ql link">
         <i class="ql_icon jws-icon-glyph-9"></i>
          <div class="ql_overlay">
            <div class="ql_holder"></div>
            <div class="ql_bg">
                <?php echo get_the_post_thumbnail(get_the_ID(), 'full'); ?>
            </div>
         </div>
         <div class="jws_ql_content">
               <h4 class="jws_ql_excerpt">
                        <?php  echo get_the_title();?>
               </h4>
               <?php $link_name = get_post_meta(get_the_ID(), 'blog_name_link', true); if(isset($link_name)) echo '<div class="ql_name"><a target="_blank"  href="'.get_post_meta(get_the_ID(), 'blog_url_link', true).'">'.$link_name.'</a></div>';  ?>
         </div>
     </div>
<?php }else {?>
   <div class="jws_post_wap">
        <div class="jws_post_image">
          <?php 
          if($format == 'gallery'){  
                include( 'format/gallery.php' );
          }else {
            if (function_exists('jws_getImageBySize')) {
                     $attach_id = get_post_thumbnail_id();
                     $img = jws_getImageBySize(array('attach_id' => $attach_id, 'thumb_size' => $image_size, 'class' => 'attachment-large wp-post-image'));
                     echo (!empty($img['thumbnail'])) ? ''.$img['thumbnail'] : '';
            
                     }else {
                     echo ''.$img = get_the_post_thumbnail(get_the_ID(), $image_size);
              }
              if($format == 'video') {
                $link_video = get_post_meta(get_the_ID(), 'blog_video', true);
                ?>
                 <div class="video_format">
                     <a class="url" href="<?php echo esc_url($link_video); ?>">
                        <span class="video_icon">
                            <i class="jws-icon-glyph-20"></i>
                        </span>
                     </a>
                 </div>
                 <?php
              }
              
              if($format == 'audio') {
                $link_audio = get_post_meta(get_the_ID(), 'blog_audio_url', true);
                if(!empty($link_audio)) :
                wp_enqueue_style('mediaelementplayer');
                wp_enqueue_script( 'media-element' );
                ?>
                   <audio class="post_audio">
                        <source src="<?php echo esc_attr($link_audio); ?>">
                    </audio>
 
                <?php
                endif;
              }   
          }
         ?>
    
        </div>
        <div class="jws_post_content">
               <div class="jws_post_meta">
                    <span class="entry-date"><a href="<?php echo esc_url(get_day_link($archive_year, $archive_month, $archive_day)); ?>"><?php echo get_the_date(); ?></a></span> 
                    <span class="post_cat"><?php echo get_the_term_list(get_the_ID(), 'category', '', ', '); ?></span> 
               </div>
               <h4 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4> 
       
               <a href="<?php the_permalink(); ?>" class="jws_post_readmore">
                    <span> <?php echo esc_html__('Read more','meathouse'); ?> </span>
               </a>
               
        </div>
    </div>   
<?php }


  
  
