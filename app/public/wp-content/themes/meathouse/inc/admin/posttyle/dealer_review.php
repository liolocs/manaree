<?php // Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

    function jws_register_dealer_review() {
        global $jws_option;
		$labels = array(
			'name'                => _x( 'Dealer Review', 'Post Type General Name', 'meathouse' ),
			'singular_name'       => _x( 'Dealer Review', 'Post Type Singular Name', 'meathouse' ),
			'menu_name'           => esc_html__( 'Dealer Review', 'meathouse' ),
			'parent_item_colon'   => esc_html__( 'Parent Item:', 'meathouse' ),
			'all_items'           => esc_html__( 'Dealer Review', 'meathouse' ),
			'view_item'           => esc_html__( 'View Item', 'meathouse' ),
			'add_new_item'        => esc_html__( 'Add New Item', 'meathouse' ),
			'add_new'             => esc_html__( 'Add New', 'meathouse' ),
			'edit_item'           => esc_html__( 'Edit Item', 'meathouse' ),
			'update_item'         => esc_html__( 'Update Item', 'meathouse' ),
			'search_items'        => esc_html__( 'Search Item', 'meathouse' ),
			'not_found'           => esc_html__( 'Not found', 'meathouse' ),
			'not_found_in_trash'  => esc_html__( 'Not found in Trash', 'meathouse' ),
		);

		$args = array(
			'label'               => esc_html__( 'Dealer Review', 'meathouse' ),
		    'labels'              => $labels,
            'supports'            => array( 'title', 'editor', 'excerpt', 'thumbnail','page-attributes', 'post-formats' ),
            'hierarchical'        => true,
            'public'              => true,
            'show_ui'             => true,
            'show_in_menu'		    =>	'jws_settings.php',
            'menu_icon'           => ''.JWS_URI_PATH.'/assets/image/posttyle_icon/dealer_review_icon_type.png',
            'can_export'          => true,
            'exclude_from_search' => false,
            'publicly_queryable'  => true,
            'capability_type'     => 'post',
         
		);


        if(function_exists('custom_reg_post_type')){
        	custom_reg_post_type( 'dealer_review', $args );
        }
	};
add_action( 'init', 'jws_register_dealer_review', 1 );