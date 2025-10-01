<?php 
function jws_cmb2_metaboxes() { 
    
    // For Page
    $cmb = new_cmb2_box( array(
		'id'            => 'page_metabox',
		'title'         => esc_html__( 'Page Setting', 'meathouse' ),
		'object_types'  => array('page'), // Post type
		'context' => 'normal',
		'priority' => 'high',
		'show_names' => true, // Show field names on the left
	) );
    $args = array('post_type' => 'hf_template', 'posts_per_page' => -1);
    $loop = new WP_Query($args);
    if($loop->have_posts()) {  
        while($loop->have_posts()) : $loop->the_post();
            //
            $varID = get_the_id();
            $varName = get_the_title();
            $pageArray[$varID]=$varName;
        endwhile;
        wp_reset_postdata();   
    }else {
       $pageArray['null']=''; 
    }
    $cmb->add_field( array(
    	'name'             => 'Select Header For Page',
    	'id'               => 'page_select_header',
    	'type'             => 'select',
    	'show_option_none' => true,
    	'options' => $pageArray
    ) );
    $cmb->add_field( array(
    	'name'             => 'Select Footer For Page',
    	'id'               => 'page_select_footer',
    	'type'             => 'select',
    	'show_option_none' => true,
    	'options' => $pageArray
    ) );
    $cmb->add_field( array(
    	'name' => 'Turn Off Header',
    	'id'   => 'turn_off_header',
    	'type' => 'checkbox',
    ) ); 
    $cmb->add_field( array(
    	'name' => 'Turn Off Footer',
    	'id'   => 'turn_off_footer',
    	'type' => 'checkbox',
    ) ); 
    $cmb->add_field( array(
    	'name' => 'Turn Off Title Bar',
    	'id'   => 'title_bar_checkbox',
    	'type' => 'checkbox',
    ) );  
    $cmb->add_field( array(
    	'name' => 'Turn Off Tool Bar Mobile',
    	'id'   => 'tool_bar_checkbox',
    	'type' => 'checkbox',
    ) ); 
    $cmb->add_field( array(
    	'name'             => 'Select Custom Title Bar For Page',
    	'id'               => 'page_select_titlebar',
    	'type'             => 'select',
    	'show_option_none' => true,
    	'options' => $pageArray,
        'attributes' => array(
			'data-conditional-id'    => 'title_bar_checkbox',
			'data-conditional-value' => 'off',
		),
    ) );
 
    $cmb->add_field( array(
    	'name'             => 'Header Absolute',
    	'id'               => 'page_header_absolute',
    	'type'             => 'select',
    	'show_option_none' => true,
        'options'          => array(
            'off' => __( 'No', 'meathouse' ),
            'on'   => __( 'Yes', 'meathouse' ),
        ),
    ) );      
    
    // For Product
	$cmb = new_cmb2_box( array(
		'id'            => 'product_metabox',
		'title'         => esc_html__( 'Product Setting', 'meathouse' ),
		'object_types'  => array('product'), // Post type
		'context' => 'normal',
		'priority' => 'high',
		'show_names' => true, // Show field names on the left
		) );
        
     
    
    $cmb->add_field( array(
        'name'             => 'Layout',
        'id'               => 'shop_single_layout',
        'type'             => 'select',
        'show_option_none' => true,
        'options'          => array(
            'default' => __( 'Default', 'meathouse' ),
        ),
    ) );  
    $cmb->add_field( array(
        'name'             => 'Thumbnail Position',
        'id'               => 'shop_single_thumbnail_position',
        'type'             => 'select',
        'show_option_none' => true,
        'options' => array(
                'left' => 'Left',
                'right' => 'Right',
                'bottom' => 'Bottom',
                'bottom2' => 'Bottom 4 Item'
            ),
    ) );
    $cmb->add_field( array(
        'name'             => 'Video Type',
        'id'               => 'shop_single_video_type',
        'type'             => 'select',
        'default'          => 'popup',
        'options' => array(
                'popup' => 'Popup',
                'inner' => 'Inner',
            ),
    ) ); 
    $cmb->add_field( array(
    	'name' => 'Product Video',
    	'id'   => 'product_video',
    	'type' => 'text',
        'desc' => 'Add link video for product(youtube, vimeo, mp4 ,...)',
    ) );

      // For questions
	$cmb = new_cmb2_box( array(
		'id'            => 'questions_metabox',
		'title'         => esc_html__( 'Questions Setting', 'meathouse' ),
		'object_types'  => array('questions'), // Post type
		'context' => 'normal',
		'priority' => 'high',
		'show_names' => true, // Show field names on the left
		) );
        
    $cmb->add_field( array(
    	'name' => 'Name',
    	'id'   => 'product_name',
    	'type' => 'text',
    ) );
    $cmb->add_field( array(
    	'name' => 'Email',
    	'id'   => 'product_email',
    	'type' => 'text',
    ) );
    $cmb->add_field( array(
    	'name' => 'Answer Content',
    	'id'   => 'answer_content',
    	'type' => 'textarea',
    ) );

   // For Blog quote
	$cmb = new_cmb2_box( array(
		'id'            => 'blog_quote_metabox',
		'title'         => esc_html__( 'Blog quote Setting', 'meathouse' ),
		'object_types'  => array('post'), // Post type
		'context' => 'normal',
		'priority' => 'high',
		'show_names' => true, // Show field names on the left
        'show_on'      => array( 'key' => 'post_format', 'value' => 'quote' ),
		) );
	$cmb->add_field( array(
    	'name' => 'Add Name Quote',
    	'id'   => 'blog_name_quote',
    	'type' => 'text',
    ) );
    // For Blog Audio
	$cmb = new_cmb2_box( array(
		'id'            => 'blog_audio_metabox',
		'title'         => esc_html__( 'Blog Audio Setting', 'meathouse' ),
		'object_types'  => array('post'), // Post type
		'context' => 'normal',
		'priority' => 'high',
		'show_names' => true, // Show field names on the left
        'show_on'      => array( 'key' => 'post_format', 'value' => 'audio' ),
		) );
	$cmb->add_field( array(
    	'name' => 'Add Link Audio',
    	'id'   => 'blog_audio_url',
    	'type' => 'text',
    ) );
    // For Blog Link
	$cmb = new_cmb2_box( array(
		'id'            => 'blog_link_metabox',
		'title'         => esc_html__( 'Blog Link Setting', 'meathouse' ),
		'object_types'  => array('post'), // Post type
		'context' => 'normal',
		'priority' => 'high',
		'show_names' => true, // Show field names on the left
         'show_on'      => array( 'key' => 'post_format', 'value' => 'link' ),
		) );
	$cmb->add_field( array(
    	'name' => 'Add Name Link',
    	'id'   => 'blog_name_link',
    	'type' => 'text',
    ) );
    $cmb->add_field( array(
    	'name' => 'Add Url Link',
    	'id'   => 'blog_url_link',
    	'type' => 'text',
    ) );
    // For Blog Video
	$cmb = new_cmb2_box( array(
		'id'            => 'blog_video_metabox',
		'title'         => esc_html__( 'Blog Video Setting', 'meathouse' ),
		'object_types'  => array('post'), // Post type
		'context' => 'normal',
		'priority' => 'high',
		'show_names' => true, // Show field names on the left
         'show_on'      => array( 'key' => 'post_format', 'value' => 'video' ),
		) );
	$cmb->add_field( array(
    	'name' => 'Add Url For Video',
    	'desc' => 'show video when click',
    	'id'   => 'blog_video',
    	'type' => 'text',
    ) );
     // For Blog Galley
	$cmb = new_cmb2_box( array(
		'id'            => 'blog_gallery_metabox',
		'title'         => esc_html__( 'Blog Gallery Setting', 'meathouse' ),
		'object_types'  => array('post'), // Post type
		'context' => 'normal',
		'priority' => 'high',
		'show_names' => true, // Show field names on the left
         'show_on'      => array( 'key' => 'post_format', 'value' => 'gallery' ),
		) );
    $cmb->add_field( array(
	'name' => 'Image List',
	'desc' => '',
	'id'   => 'image_gallery_list',
	'type' => 'file_list',
	// 'preview_size' => array( 100, 100 ), // Default: array( 50, 50 )
	// 'query_args' => array( 'type' => 'image' ), // Only images attachment
	// Optional, override default text strings
	'text' => array(
		'add_upload_files_text' => esc_html__( 'Add Image', 'meathouse' ), // default: "Add or Upload Files"
		'remove_image_text' => esc_html__( 'Remove Image', 'meathouse' ), // default: "Remove Image"

	),
    ) );
 
}
add_action( 'cmb2_admin_init', 'jws_cmb2_metaboxes' );




function jws_cmb_show_on_post_format( $display, $post_format ) {

    if ( ! isset( $post_format['show_on']['key'] ) ) {
        return $display;
    }

    $post_id = 0;

    // If we're showing it based on ID, get the current ID
    if ( isset( $_GET['post'] ) ) {
        $post_id = $_GET['post'];
    } elseif ( isset( $_POST['post_ID'] ) ) {
        $post_id = $_POST['post_ID'];
    }

    if ( ! $post_id ) {
        return $display;
    }

    $value  = get_post_format($post_id);
 
    if ( empty( $post_format['show_on']['key'] ) ) {
        return (bool) $value;
    }

    return $value == $post_format['show_on']['value'];
}
add_filter( 'cmb2_show_on', 'jws_cmb_show_on_post_format', 10, 2 );
