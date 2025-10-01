<?php // Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

    function jws_register_project() {
        global $jws_option;
		$labels = array(
			'name'                => _x( 'Projects', 'Post Type General Name', 'meathouse' ),
			'singular_name'       => _x( 'Projects', 'Post Type Singular Name', 'meathouse' ),
			'menu_name'           => esc_html__( 'Project', 'meathouse' ),
			'parent_item_colon'   => esc_html__( 'Parent Item:', 'meathouse' ),
			'all_items'           => esc_html__( 'All Items', 'meathouse' ),
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
			'label'               => esc_html__( 'Projects', 'meathouse' ),
		    'labels'              => $labels,
            'supports'            => array( 'title', 'editor', 'excerpt', 'thumbnail','page-attributes', 'post-formats', ),
            'taxonomies'          => array( 'projects_cat' ),
            'hierarchical'        => true,
            'public'              => true,
            'show_ui'             => true,
            'show_in_menu'        => true,
            'show_in_nav_menus'   => true,
            'show_in_admin_bar'   => true,
            'menu_position'       => 5,
            'menu_icon'           => ''.JWS_URI_PATH.'/assets/image/posttyle_icon/project_icon_type.png',
            'can_export'          => true,
            'has_archive'         => true,
            'exclude_from_search' => false,
            'publicly_queryable'  => true,
            'capability_type'     => 'page',
		);


        if(function_exists('custom_reg_post_type')){
        	custom_reg_post_type( 'projects', $args );
        }

		/**
		 * Create a taxonomy category for project
		 *
		 * @uses  Inserts new taxonomy object into the list
		 * @uses  Adds query vars
		 *
		 * @param string  Name of taxonomy object
		 * @param array|string  Name of the object type for the taxonomy object.
		 * @param array|string  Taxonomy arguments
		 * @return null|WP_Error WP_Error if errors, otherwise null.
		 */
		
		$labels = array(
			'name'					=> _x( 'projects Categories', 'Taxonomy plural name', 'meathouse' ),
			'singular_name'			=> _x( 'projects Category', 'Taxonomy singular name', 'meathouse' ),
			'search_items'			=> esc_html__( 'Search Categories', 'meathouse' ),
			'popular_items'			=> esc_html__( 'Popular project Categories', 'meathouse' ),
			'all_items'				=> esc_html__( 'All project Categories', 'meathouse' ),
			'parent_item'			=> esc_html__( 'Parent Category', 'meathouse' ),
			'parent_item_colon'		=> esc_html__( 'Parent Category', 'meathouse' ),
			'edit_item'				=> esc_html__( 'Edit Category', 'meathouse' ),
			'update_item'			=> esc_html__( 'Update Category', 'meathouse' ),
			'add_new_item'			=> esc_html__( 'Add New Category', 'meathouse' ),
			'new_item_name'			=> esc_html__( 'New Category', 'meathouse' ),
			'add_or_remove_items'	=> esc_html__( 'Add or remove Categories', 'meathouse' ),
			'choose_from_most_used'	=> esc_html__( 'Choose from most used text-domain', 'meathouse' ),
			'menu_name'				=> esc_html__( 'Category', 'meathouse' ),
		);
	
		$args = array(
			'hierarchical'      => true,
            'labels'            => $labels,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => array( 'slug' => 'projects_cat' ),
		);
        

        if(function_exists('custom_reg_taxonomy')){
            custom_reg_taxonomy( 'projects_cat', array( 'projects' ), $args  );
        }
        
        $labels = array(
            'name' => esc_html__( 'Tags', 'meathouse' ),
            'singular_name' => esc_html__( 'Tag',  'meathouse'  ),
            'search_items' =>  esc_html__( 'Search Tags' , 'meathouse' ),
            'popular_items' => esc_html__( 'Popular Tags' , 'meathouse' ),
            'all_items' => esc_html__( 'All Tags' , 'meathouse' ),
            'parent_item' => null,
            'parent_item_colon' => null,
            'edit_item' => esc_html__( 'Edit Tag' , 'meathouse' ), 
            'update_item' => esc_html__( 'Update Tag' , 'meathouse' ),
            'add_new_item' => esc_html__( 'Add New Tag' , 'meathouse' ),
            'new_item_name' => esc_html__( 'New Tag Name' , 'meathouse' ),
            'separate_items_with_commas' => esc_html__( 'Separate tags with commas' , 'meathouse' ),
            'add_or_remove_items' => esc_html__( 'Add or remove tags' , 'meathouse' ),
            'choose_from_most_used' => esc_html__( 'Choose from the most used tags' , 'meathouse' ),
            'menu_name' => esc_html__( 'Tags','meathouse'),
        ); 
    
        $args = array(
            'hierarchical' => false,
            'labels' => $labels,
            'show_ui' => true,
            'update_count_callback' => '_update_post_term_count',
            'query_var' => true,
            'rewrite' => array( 'slug' => 'projects_tag' ),
        );
        
        if(function_exists('custom_reg_taxonomy')){
            custom_reg_taxonomy( 'projects_tag', array( 'projects' ), $args  );
        }

	};
add_action( 'init', 'jws_register_project', 1 );

function add_featured_image_column_project($defaults) {
    $defaults['featured_image'] = 'Featured Image';
    return $defaults;
}
add_filter('manage_projects_posts_columns', 'add_featured_image_column_project');
 
function show_featured_image_column_project($column_name, $post_id) {
    if ($column_name == 'featured_image') {
        echo get_the_post_thumbnail($post_id, 'thumbnail'); 
    }
}
add_action('manage_projects_posts_custom_column', 'show_featured_image_column_project', 10, 2); 
