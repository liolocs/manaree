<?php


/* Taxonomy Stamps Cars */


add_action( 'product_cat_add_form_fields', 'misha_add_term_fields' );
add_action( 'product_cat_edit_form_fields', 'misha_add_term_fields', 10, 2 ); 
function misha_add_term_fields( $term  ) {

   
       $id = isset($arg['id']) ? $arg['id'] : 'image2';
       $label = isset($arg['label']) ? $arg['label'] : '';
       $desc = isset($arg['desc']) ? $arg['desc'] : '';
                
                if(!isset($term->term_id)) {
                  $id_filed = '';   
                }else {
                  $id_filed = $term->term_id;   
                }
                $default = get_term_meta( $id_filed, 'image2', true );

                ?><tr class="form-field term-image-wrap">
                 <th scope="row" valign="top"><label for="meta-order"><?php _e( 'Category Image 2' ); ?></label></th>
                 <td>
            	<div><ul class="misha_images_mtb">
                <?php
            	/* array with image IDs for hidden field */
            	$hidden = array();
             
                
                  if( $images = get_posts( array(
                		'post_type' => 'attachment',
                		'orderby' => 'post__in', /* we have to save the order */
                		'order' => 'ASC',
                		'post__in' => explode(",",$default), /* $value is the image IDs comma separated */
                		'numberposts' => -1,
                		'post_mime_type' => 'image'
                	) ) ) {
                 
                		foreach( $images as $image ) {
                			$hidden[] = $image->ID;
                            $image_src = wp_get_attachment_image_src( $image->ID, 'full' );
                			echo '<li data-id="' . $image->ID .  '"><span><img src="'.$image_src[0].'" alt="images"></span><a href="#" class="misha_images_remove">X</a></li>';
                		}
                 
                	}  
            	?></ul></div>
                	<input type="hidden" name="<?php echo $id; ?>" value="<?php echo join(',',$hidden); ?>" /><a href="#" class="button misha_upload_images_button"><?php echo esc_html__('Add Images','idealauto'); ?></a>
                </td>
            </tr>
                <?php
 
}


add_action( 'created_product_cat', 'misha_save_term_fields' );
add_action( 'edited_product_cat', 'misha_save_term_fields' );
 
function misha_save_term_fields( $term_id ) {
 
	update_term_meta(
		$term_id,
		'image2',
		sanitize_text_field( $_POST[ 'image2' ] )
	);
 
}