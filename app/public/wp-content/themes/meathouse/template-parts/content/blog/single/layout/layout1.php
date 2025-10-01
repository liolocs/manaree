<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package WordPress
 * @subpackage meathouse
 * @since 1.0.0
 */
global $jws_option; 

    $comments_number = get_comments_number();
    $archive_year  = get_the_time('Y'); 
	$archive_month = get_the_time('m'); 
	$archive_day   = get_the_time('d');
    $gallery = get_post_meta( get_the_ID(), 'blog_gallery', true );
    $image_size = (isset($jws_option['single_blog_imagesize']) && !empty($jws_option['single_blog_imagesize'])) ? $jws_option['single_blog_imagesize'] : 'full';
    $format = get_post_format();
    
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

            <header>
                  <div class="jws_post_image<?php if(!has_post_thumbnail()) echo esc_attr(' post-no-thumbnail'); ?>">
                     
                        <?php 
                           if($format == 'gallery'){  
                                include( dirname(__FILE__) . '/../format/gallery.php' );
                           } elseif($format == 'quote'){  
                               ?>
                               
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
                               
                               <?php
                           } elseif($format == 'link'){  
                               ?>
                               
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
                               
                               <?php
                           } else {
                               if (function_exists('jws_getImageBySize')) {
                                         $attach_id = get_post_thumbnail_id();
                                         $img = jws_getImageBySize(array('attach_id' => $attach_id, 'thumb_size' => $image_size, 'class' => 'attachment-large wp-post-image'));
                                         echo ''.(!empty($img['thumbnail'])) ? ''.$img['thumbnail'] : '';
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

                <div class="jws-post-info">
                    <div class="jws_post_meta">
                        <span class="entry-date"><a href="<?php echo esc_url(get_day_link($archive_year, $archive_month, $archive_day)); ?>"><?php echo get_the_date(); ?></a></span> 
                        <span class="post_cat"><?php echo get_the_term_list(get_the_ID(), 'category', '', ', '); ?></span> 
                        <span class="post_author"><?php echo '<a href="'.get_author_posts_url( get_the_author_meta( 'ID' ) ).'"><span>'.esc_html__('By ','meathouse').'</span>'.get_the_author(); ?></a></span> 
                        <a href="<?php echo get_the_permalink().'#comments'; ?>" class="entry-comment"><span class="jws-comment jws-icon-glyph-33"></span><?php echo sprintf( _n( '%s Comment', '%s Comments', $comments_number, 'meathouse' ), $comments_number );; ?></span></a>
                    </div>
                    <?php if($format != 'quote' && $format != 'link' ) : ?>
                        <h3 class="entry_title">
                            <?php echo get_the_title(); ?>
                        </h3>
                    <?php endif; ?>
                    
                </div>
                
           </header>
           <div class="entry_content">
                <?php the_content(); ?> 
           </div>
           <div class="clear-both"></div>
           <footer>
                <div class="row">
                    <div class="col-xl-7 col-lg-6 col-12">
                        <?php echo jws_get_tags(); ?>
                    </div>
                    <div class="col-xl-5 col-lg-6 col-12">
                        <?php if(function_exists('jws_share_buttons')) echo jws_share_buttons(); ?>
                    </div>
                </div>
                <?php 
                    get_template_part( 'template-parts/content/blog/single/template/author_box/author_box1' );
                    get_template_part( 'template-parts/content/blog/single/template/nav/nav2' ); 
                    if (did_action( 'elementor/loaded' ) ) { 
                     ?>
                    <div class="post-related jws-blog-element">
                       
                            <?php get_template_part( 'template-parts/content/blog/single/template/related' ); ?>
                        
                    </div>
                    
           
                    <?php
                    }
                     // If comments are open or we have at least one comment, load up the comment template.
    				if ( comments_open() || get_comments_number() ) {
    					comments_template();
    				}
                 ?>
            </footer>   
		<?php
		wp_link_pages(
			array(
				'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'meathouse' ),
				'after'  => '</div>',
			)
		);
		?>

</article><!-- #post-<?php the_ID(); ?> -->
