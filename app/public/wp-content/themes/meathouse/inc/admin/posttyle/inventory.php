<?php // Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

    function jws_register_inventory() {
        global $jws_option;
		$labels = array(
			'name'                => _x( 'inventory', 'Post Type General Name', 'meathouse' ),
			'singular_name'       => _x( 'inventory', 'Post Type Singular Name', 'meathouse' ),
			'menu_name'           => esc_html__( 'Inventory', 'meathouse' ),
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
			'label'               => esc_html__( 'Inventory', 'meathouse' ),
		    'labels'              => $labels,
            'supports'            => array( 'title', 'editor', 'excerpt','page-attributes', 'post-formats','author' , 'custom-fields' , 'revisions' ),
            'taxonomies'          => array( 'cars_cat' ),
            'hierarchical'        => true,
            'public'              => true,
            'show_ui'             => true,
            'show_in_menu'        => true,
            'show_in_nav_menus'   => true,
            'show_in_admin_bar'   => true,
            'menu_position'       => 5,
            'menu_icon'           => ''.JWS_URI_PATH.'/assets/image/posttyle_icon/inventory_icon_type.png',
            'can_export'          => true,
            'has_archive'         => true,
            'exclude_from_search' => false,
            'publicly_queryable'  => true,
            'capability_type'     => 'page',
         
		);


        if(function_exists('custom_reg_post_type')){
        	custom_reg_post_type( 'cars', $args );
        }

		/**
		 * Create a taxonomy category for inventory
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
			'name'					=> _x( 'inventory Categories', 'Taxonomy plural name', 'meathouse' ),
			'singular_name'			=> _x( 'inventoryCategory', 'Taxonomy singular name', 'meathouse' ),
			'search_items'			=> esc_html__( 'Search Categories', 'meathouse' ),
			'popular_items'			=> esc_html__( 'Popular inventory Categories', 'meathouse' ),
			'all_items'				=> esc_html__( 'All inventory Categories', 'meathouse' ),
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
            'rewrite'           => array( 'slug' => 'car_cat' ),
		);
        

        if(function_exists('custom_reg_taxonomy')){
            custom_reg_taxonomy( 'car_cat', array( 'cars' ), $args  );
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
            'rewrite' => array( 'slug' => 'inventory_tag' ),
        );
        
        if(function_exists('custom_reg_taxonomy')){
            custom_reg_taxonomy( 'inventory_tag', array( 'inventory' ), $args  );
        }

	};
add_action( 'init', 'jws_register_inventory', 1 );

function add_featured_image_column_inventory($defaults) {
    $defaults['price'] = esc_html__('Price','meathouse');
    $defaults['featured_image'] = esc_html__('Features Image','meathouse');
    
    $defaults['featured'] = esc_html__('Features','meathouse');
    return $defaults;
}
add_filter('manage_cars_posts_columns', 'add_featured_image_column_inventory');
 
function show_featured_image_column_inventory($column_name, $post_id) {
    
    if ($column_name == 'featured') { 
      $featured =  get_post_meta( $post_id , 'car_asset_type',  true ); 

      ?> <a href="javascript:void(0)" data-id="<?php echo esc_attr($post_id); ?>" class="jws-make-features<?php echo (isset($featured) && $featured == 'featured') ? ' active' : ''; ?>"><i class="jws-icon-star-full"></i></a><?php
        
    }
    if ($column_name == 'featured_image') {
      $car_images =  get_post_meta( $post_id , 'car_images',  true );      
      if(!empty($car_images)) {
        $img_car = jws_getImageBySize(array('attach_id' => $car_images[0], 'thumb_size' => '508x360', 'class' => 'car-images-'.$car_images[0].''));
        if(!empty($img_car['thumbnail'])) echo ''.$img_car['thumbnail'];
        
      }
    }
    
     if ($column_name == 'price') {
      $price =  get_post_meta( $post_id , 'regular_price',  true );      
      if(!empty($price)) {
        jws_car_price_html($class = '', $id = null, $tax_label = false, $echo = true);
       jws_car_price_msrp_html();
        
      }
    }
}
add_action('manage_cars_posts_custom_column', 'show_featured_image_column_inventory', 10, 2); 

// Add new taxonomy, NOT hierarchical (like tags).
		$labels = array(
			'name'                       => esc_html__( 'Year', 'meathouse' ),
			'singular_name'              => esc_html__( 'Year', 'meathouse' ),
			'search_items'               => esc_html__( 'Search Year', 'meathouse' ),
			'popular_items'              => esc_html__( 'Popular Year', 'meathouse' ),
			'all_items'                  => esc_html__( 'All Year', 'meathouse' ),
			'parent_item'                => null,
			'parent_item_colon'          => null,
			'edit_item'                  => esc_html__( 'Edit Year', 'meathouse' ),
			'update_item'                => esc_html__( 'Update Year', 'meathouse' ),
			'add_new_item'               => esc_html__( 'Add New Year', 'meathouse' ),
			'new_item_name'              => esc_html__( 'New Year Name', 'meathouse' ),
			'separate_items_with_commas' => esc_html__( 'Separate year with commas', 'meathouse' ),
			'add_or_remove_items'        => esc_html__( 'Add or remove Year', 'meathouse' ),
			'choose_from_most_used'      => esc_html__( 'Choose from the most used Year', 'meathouse' ),
			'not_found'                  => esc_html__( 'No year found.', 'meathouse' ),
			'menu_name'                  => esc_html__( 'Year', 'meathouse' ),
		);

		$args = array(
			'hierarchical'          => false,
			'labels'                => $labels,
			'show_ui'               => true,
			'update_count_callback' => '_update_post_term_count',
			'query_var'             => true,
			'rewrite'               => array( 'slug' => 'year' ),
		);


        if(function_exists('custom_reg_taxonomy')){
            custom_reg_taxonomy( 'car_year', array( 'cars' ), $args  );
        }

		// Add new taxonomy, NOT hierarchical (like tags).
		$labels = array(
			'name'                       => esc_html__( 'Make', 'meathouse' ),
			'singular_name'              => esc_html__( 'Make', 'meathouse' ),
			'search_items'               => esc_html__( 'Search Make', 'meathouse' ),
			'popular_items'              => esc_html__( 'Popular Make', 'meathouse' ),
			'all_items'                  => esc_html__( 'All Make', 'meathouse' ),
			'parent_item'                => null,
			'parent_item_colon'          => null,
			'edit_item'                  => esc_html__( 'Edit Make', 'meathouse' ),
			'update_item'                => esc_html__( 'Update Make', 'meathouse' ),
			'add_new_item'               => esc_html__( 'Add New Make', 'meathouse' ),
			'new_item_name'              => esc_html__( 'New Make Name', 'meathouse' ),
			'separate_items_with_commas' => esc_html__( 'Separate make with commas', 'meathouse' ),
			'add_or_remove_items'        => esc_html__( 'Add or remove Make', 'meathouse' ),
			'choose_from_most_used'      => esc_html__( 'Choose from the most used Make', 'meathouse' ),
			'not_found'                  => esc_html__( 'No make found.', 'meathouse' ),
			'menu_name'                  => esc_html__( 'Make', 'meathouse' ),
		);

		$args = array(
			'hierarchical'          => false,
			'labels'                => $labels,
			'show_ui'               => true,
			'update_count_callback' => '_update_post_term_count',
			'query_var'             => true,
			'rewrite'               => array( 'slug' => 'make' ),
		);

        if(function_exists('custom_reg_taxonomy')){
            custom_reg_taxonomy( 'car_make', array( 'cars' ), $args  );
        }

		// Add new taxonomy, NOT hierarchical (like tags).
		$labels = array(
			'name'                       => esc_html__( 'Model', 'meathouse' ),
			'singular_name'              => esc_html__( 'Model', 'meathouse' ),
			'search_items'               => esc_html__( 'Search Model', 'meathouse' ),
			'popular_items'              => esc_html__( 'Popular Model', 'meathouse' ),
			'all_items'                  => esc_html__( 'All Model', 'meathouse' ),
			'parent_item'                => null,
			'parent_item_colon'          => null,
			'edit_item'                  => esc_html__( 'Edit Model', 'meathouse' ),
			'update_item'                => esc_html__( 'Update Model', 'meathouse' ),
			'add_new_item'               => esc_html__( 'Add New Model', 'meathouse' ),
			'new_item_name'              => esc_html__( 'New Model Name', 'meathouse' ),
			'separate_items_with_commas' => esc_html__( 'Separate model with commas', 'meathouse' ),
			'add_or_remove_items'        => esc_html__( 'Add or remove Model', 'meathouse' ),
			'choose_from_most_used'      => esc_html__( 'Choose from the most used Model', 'meathouse' ),
			'not_found'                  => esc_html__( 'No model found.', 'meathouse' ),
			'menu_name'                  => esc_html__( 'Model', 'meathouse' ),
		);

		$args = array(
			'hierarchical'          => false,
			'labels'                => $labels,
			'show_ui'               => true,
			'update_count_callback' => '_update_post_term_count',
			'query_var'             => true,
			'rewrite'               => array( 'slug' => 'model' ),
		);

        if(function_exists('custom_reg_taxonomy')){
            custom_reg_taxonomy( 'car_model', array( 'cars' ), $args  );
        }
        
		// Add new taxonomy, NOT hierarchical (like tags).
		$labels = array(
			'name'                       => esc_html__( 'Body Style', 'meathouse' ),
			'singular_name'              => esc_html__( 'Body Style', 'meathouse' ),
			'search_items'               => esc_html__( 'Search Body Style', 'meathouse' ),
			'popular_items'              => esc_html__( 'Popular Body Style', 'meathouse' ),
			'all_items'                  => esc_html__( 'All Body Style', 'meathouse' ),
			'parent_item'                => null,
			'parent_item_colon'          => null,
			'edit_item'                  => esc_html__( 'Edit Body Style', 'meathouse' ),
			'update_item'                => esc_html__( 'Update Body Style', 'meathouse' ),
			'add_new_item'               => esc_html__( 'Add New Body Style', 'meathouse' ),
			'new_item_name'              => esc_html__( 'New Body Style Name', 'meathouse' ),
			'separate_items_with_commas' => esc_html__( 'Separate body style with commas', 'meathouse' ),
			'add_or_remove_items'        => esc_html__( 'Add or remove body style', 'meathouse' ),
			'choose_from_most_used'      => esc_html__( 'Choose from the most used body style', 'meathouse' ),
			'not_found'                  => esc_html__( 'No body style found.', 'meathouse' ),
			'menu_name'                  => esc_html__( 'Body Style', 'meathouse' ),
		);

		$args = array(
			'hierarchical'          => true,
			'labels'                => $labels,
			'show_ui'               => true,
			'update_count_callback' => '_update_post_term_count',
			'query_var'             => true,
			'rewrite'               => array( 'slug' => 'body-style' ),
		);
	
        if(function_exists('custom_reg_taxonomy')){
            custom_reg_taxonomy( 'car_body_style', array( 'cars' ), $args  );
        }

		// Add new taxonomy, NOT hierarchical (like tags).
		$labels = array(
			'name'                       => esc_html__( 'Mileage', 'meathouse' ),
			'singular_name'              => esc_html__( 'Mileage', 'meathouse' ),
			'search_items'               => esc_html__( 'Search Mileage', 'meathouse' ),
			'popular_items'              => esc_html__( 'Popular Mileage', 'meathouse' ),
			'all_items'                  => esc_html__( 'All Mileage', 'meathouse' ),
			'parent_item'                => null,
			'parent_item_colon'          => null,
			'edit_item'                  => esc_html__( 'Edit Mileage', 'meathouse' ),
			'update_item'                => esc_html__( 'Update Mileage', 'meathouse' ),
			'add_new_item'               => esc_html__( 'Add New Mileage', 'meathouse' ),
			'new_item_name'              => esc_html__( 'New Mileage Name', 'meathouse' ),
			'separate_items_with_commas' => esc_html__( 'Separate mileage with commas', 'meathouse' ),
			'add_or_remove_items'        => esc_html__( 'Add or remove Mileage', 'meathouse' ),
			'choose_from_most_used'      => esc_html__( 'Choose from the most used Mileage', 'meathouse' ),
			'not_found'                  => esc_html__( 'No mileage found.', 'meathouse' ),
			'menu_name'                  => esc_html__( 'Mileage', 'meathouse' ),
		);

		$args = array(
			'hierarchical'          => false,
			'labels'                => $labels,
			'show_ui'               => true,
			'update_count_callback' => '_update_post_term_count',
			'query_var'             => true,
			'rewrite'               => array( 'slug' => 'mileage' ),
		);

        if(function_exists('custom_reg_taxonomy')){
            custom_reg_taxonomy( 'car_mileage', array( 'cars' ), $args  );
        }

		// Add new taxonomy, NOT hierarchical (like tags).
		$labels = array(
			'name'                       => esc_html__( 'Transmission', 'meathouse' ),
			'singular_name'              => esc_html__( 'Transmission', 'meathouse' ),
			'search_items'               => esc_html__( 'Search Transmission', 'meathouse' ),
			'popular_items'              => esc_html__( 'Popular Transmission', 'meathouse' ),
			'all_items'                  => esc_html__( 'All Transmission', 'meathouse' ),
			'parent_item'                => null,
			'parent_item_colon'          => null,
			'edit_item'                  => esc_html__( 'Edit Transmission', 'meathouse' ),
			'update_item'                => esc_html__( 'Update Transmission', 'meathouse' ),
			'add_new_item'               => esc_html__( 'Add New Transmission', 'meathouse' ),
			'new_item_name'              => esc_html__( 'New Transmission Name', 'meathouse' ),
			'separate_items_with_commas' => esc_html__( 'Separate transmission with commas', 'meathouse' ),
			'add_or_remove_items'        => esc_html__( 'Add or remove Transmission', 'meathouse' ),
			'choose_from_most_used'      => esc_html__( 'Choose from the most used Transmission', 'meathouse' ),
			'not_found'                  => esc_html__( 'No transmission found.', 'meathouse' ),
			'menu_name'                  => esc_html__( 'Transmission', 'meathouse' ),
		);

		$args = array(
			'hierarchical'          => false,
			'labels'                => $labels,
			'show_ui'               => true,
			'update_count_callback' => '_update_post_term_count',
			'query_var'             => true,
			'rewrite'               => array( 'slug' => 'transmission' ),
		);
	
        if(function_exists('custom_reg_taxonomy')){
            custom_reg_taxonomy( 'car_transmission', array( 'cars' ), $args  );
        }

		// Add new taxonomy, NOT hierarchical (like tags).
		$labels = array(
			'name'                       => esc_html__( 'Condition', 'meathouse' ),
			'singular_name'              => esc_html__( 'Condition', 'meathouse' ),
			'search_items'               => esc_html__( 'Search Condition', 'meathouse' ),
			'popular_items'              => esc_html__( 'Popular Condition', 'meathouse' ),
			'all_items'                  => esc_html__( 'All Condition', 'meathouse' ),
			'parent_item'                => null,
			'parent_item_colon'          => null,
			'edit_item'                  => esc_html__( 'Edit Condition', 'meathouse' ),
			'update_item'                => esc_html__( 'Update Condition', 'meathouse' ),
			'add_new_item'               => esc_html__( 'Add New Condition', 'meathouse' ),
			'new_item_name'              => esc_html__( 'New Condition Name', 'meathouse' ),
			'separate_items_with_commas' => esc_html__( 'Separate condition with commas', 'meathouse' ),
			'add_or_remove_items'        => esc_html__( 'Add or remove Condition', 'meathouse' ),
			'choose_from_most_used'      => esc_html__( 'Choose from the most used Condition', 'meathouse' ),
			'not_found'                  => esc_html__( 'No condition found.', 'meathouse' ),
			'menu_name'                  => esc_html__( 'Condition', 'meathouse' ),
		);

		$args = array(
			'hierarchical'          => false,
			'labels'                => $labels,
			'show_ui'               => true,
			'update_count_callback' => '_update_post_term_count',
			'query_var'             => true,
			'rewrite'               => array( 'slug' => 'condition' ),
		);
	
        if(function_exists('custom_reg_taxonomy')){
            custom_reg_taxonomy( 'car_condition', array( 'cars' ), $args  );
        }

		// Add new taxonomy, NOT hierarchical (like tags).
		$labels = array(
			'name'                       => esc_html__( 'Drivetrain', 'meathouse' ),
			'singular_name'              => esc_html__( 'Drivetrain', 'meathouse' ),
			'search_items'               => esc_html__( 'Search Drivetrain', 'meathouse' ),
			'popular_items'              => esc_html__( 'Popular Drivetrain', 'meathouse' ),
			'all_items'                  => esc_html__( 'All Drivetrain', 'meathouse' ),
			'parent_item'                => null,
			'parent_item_colon'          => null,
			'edit_item'                  => esc_html__( 'Edit Drivetrain', 'meathouse' ),
			'update_item'                => esc_html__( 'Update Drivetrain', 'meathouse' ),
			'add_new_item'               => esc_html__( 'Add New Drivetrain', 'meathouse' ),
			'new_item_name'              => esc_html__( 'New Drivetrain Name', 'meathouse' ),
			'separate_items_with_commas' => esc_html__( 'Separate drivetrain with commas', 'meathouse' ),
			'add_or_remove_items'        => esc_html__( 'Add or remove Drivetrain', 'meathouse' ),
			'choose_from_most_used'      => esc_html__( 'Choose from the most used Drivetrain', 'meathouse' ),
			'not_found'                  => esc_html__( 'No drivetrain found.', 'meathouse' ),
			'menu_name'                  => esc_html__( 'Drivetrain', 'meathouse' ),
		);

		$args = array(
			'hierarchical'          => false,
			'labels'                => $labels,
			'show_ui'               => true,
			'update_count_callback' => '_update_post_term_count',
			'query_var'             => true,
			'rewrite'               => array( 'slug' => 'drivetrain' ),
		);
	
        if(function_exists('custom_reg_taxonomy')){
            custom_reg_taxonomy( 'car_drivetrain', array( 'cars' ), $args  );
        }

		// Add new taxonomy, NOT hierarchical (like tags).
		$labels = array(
			'name'                       => esc_html__( 'Engine', 'meathouse' ),
			'singular_name'              => esc_html__( 'Engine', 'meathouse' ),
			'search_items'               => esc_html__( 'Search Engine', 'meathouse' ),
			'popular_items'              => esc_html__( 'Popular Engine', 'meathouse' ),
			'all_items'                  => esc_html__( 'All Engine', 'meathouse' ),
			'parent_item'                => null,
			'parent_item_colon'          => null,
			'edit_item'                  => esc_html__( 'Edit Engine', 'meathouse' ),
			'update_item'                => esc_html__( 'Update Engine', 'meathouse' ),
			'add_new_item'               => esc_html__( 'Add New Engine', 'meathouse' ),
			'new_item_name'              => esc_html__( 'New Engine Name', 'meathouse' ),
			'separate_items_with_commas' => esc_html__( 'Separate engine with commas', 'meathouse' ),
			'add_or_remove_items'        => esc_html__( 'Add or remove Engine', 'meathouse' ),
			'choose_from_most_used'      => esc_html__( 'Choose from the most used Engine', 'meathouse' ),
			'not_found'                  => esc_html__( 'No engine found.', 'meathouse' ),
			'menu_name'                  => esc_html__( 'Engine', 'meathouse' ),
		);

		$args = array(
			'hierarchical'          => false,
			'labels'                => $labels,
			'show_ui'               => true,
			'update_count_callback' => '_update_post_term_count',
			'query_var'             => true,
			'rewrite'               => array( 'slug' => 'engine' ),
		);

        if(function_exists('custom_reg_taxonomy')){
            custom_reg_taxonomy( 'car_engine', array( 'cars' ), $args  );
        }

		// Add new taxonomy, NOT hierarchical (like tags).
		$labels = array(
			'name'                       => esc_html__( 'Fuel Economy', 'meathouse' ),
			'singular_name'              => esc_html__( 'Fuel Economy', 'meathouse' ),
			'search_items'               => esc_html__( 'Search Fuel Economy', 'meathouse' ),
			'popular_items'              => esc_html__( 'Popular Fuel Economy', 'meathouse' ),
			'all_items'                  => esc_html__( 'All Fuel Economy', 'meathouse' ),
			'parent_item'                => null,
			'parent_item_colon'          => null,
			'edit_item'                  => esc_html__( 'Edit Fuel Economy', 'meathouse' ),
			'update_item'                => esc_html__( 'Update Fuel Economy', 'meathouse' ),
			'add_new_item'               => esc_html__( 'Add New Fuel Economy', 'meathouse' ),
			'new_item_name'              => esc_html__( 'New Fuel Economy Name', 'meathouse' ),
			'separate_items_with_commas' => esc_html__( 'Separate fuel-economy with commas', 'meathouse' ),
			'add_or_remove_items'        => esc_html__( 'Add or remove Fuel Economy', 'meathouse' ),
			'choose_from_most_used'      => esc_html__( 'Choose from the most used Fuel Economy', 'meathouse' ),
			'not_found'                  => esc_html__( 'No fuel-economy found.', 'meathouse' ),
			'menu_name'                  => esc_html__( 'Fuel Economy', 'meathouse' ),
		);

		$args = array(
			'hierarchical'          => false,
			'labels'                => $labels,
			'show_ui'               => true,
			'update_count_callback' => '_update_post_term_count',
			'query_var'             => true,
			'rewrite'               => array( 'slug' => 'fuel-economy' ),
		);
	
        if(function_exists('custom_reg_taxonomy')){
            custom_reg_taxonomy( 'car_fuel_economy', array( 'cars' ), $args  );
        }

		// Add new taxonomy, NOT hierarchical (like tags).
		$labels = array(
			'name'                       => esc_html__( 'Exterior Color', 'meathouse' ),
			'singular_name'              => esc_html__( 'Exterior Color', 'meathouse' ),
			'search_items'               => esc_html__( 'Search Exterior Color', 'meathouse' ),
			'popular_items'              => esc_html__( 'Popular Exterior Color', 'meathouse' ),
			'all_items'                  => esc_html__( 'All Exterior Color', 'meathouse' ),
			'parent_item'                => null,
			'parent_item_colon'          => null,
			'edit_item'                  => esc_html__( 'Edit Exterior Color', 'meathouse' ),
			'update_item'                => esc_html__( 'Update Exterior Color', 'meathouse' ),
			'add_new_item'               => esc_html__( 'Add New Exterior Color', 'meathouse' ),
			'new_item_name'              => esc_html__( 'New Exterior Color Name', 'meathouse' ),
			'separate_items_with_commas' => esc_html__( 'Separate exterior-color with commas', 'meathouse' ),
			'add_or_remove_items'        => esc_html__( 'Add or remove Exterior Color', 'meathouse' ),
			'choose_from_most_used'      => esc_html__( 'Choose from the most used Exterior Color', 'meathouse' ),
			'not_found'                  => esc_html__( 'No exterior-color found.', 'meathouse' ),
			'menu_name'                  => esc_html__( 'Exterior Color', 'meathouse' ),
		);

		$args = array(
			'hierarchical'          => false,
			'labels'                => $labels,
			'show_ui'               => true,
			'update_count_callback' => '_update_post_term_count',
			'query_var'             => true,
			'rewrite'               => array( 'slug' => 'exterior-color' ),
		);
	
        if(function_exists('custom_reg_taxonomy')){
            custom_reg_taxonomy( 'car_exterior_color', array( 'cars' ), $args  );
        }

		// Add new taxonomy, NOT hierarchical (like tags).
		$labels = array(
			'name'                       => esc_html__( 'Interior Color', 'meathouse' ),
			'singular_name'              => esc_html__( 'Interior Color', 'meathouse' ),
			'search_items'               => esc_html__( 'Search Interior Color', 'meathouse' ),
			'popular_items'              => esc_html__( 'Popular Interior Color', 'meathouse' ),
			'all_items'                  => esc_html__( 'All Interior Color', 'meathouse' ),
			'parent_item'                => null,
			'parent_item_colon'          => null,
			'edit_item'                  => esc_html__( 'Edit Interior Color', 'meathouse' ),
			'update_item'                => esc_html__( 'Update Interior Color', 'meathouse' ),
			'add_new_item'               => esc_html__( 'Add New Interior Color', 'meathouse' ),
			'new_item_name'              => esc_html__( 'New Interior Color Name', 'meathouse' ),
			'separate_items_with_commas' => esc_html__( 'Separate interior-color with commas', 'meathouse' ),
			'add_or_remove_items'        => esc_html__( 'Add or remove Interior Color', 'meathouse' ),
			'choose_from_most_used'      => esc_html__( 'Choose from the most used Interior Color', 'meathouse' ),
			'not_found'                  => esc_html__( 'No interior-color found.', 'meathouse' ),
			'menu_name'                  => esc_html__( 'Interior Color', 'meathouse' ),
		);

		$args = array(
			'hierarchical'          => false,
			'labels'                => $labels,
			'show_ui'               => true,
			'update_count_callback' => '_update_post_term_count',
			'query_var'             => true,
			'rewrite'               => array( 'slug' => 'interior-color' ),
		);
		
        if(function_exists('custom_reg_taxonomy')){
            custom_reg_taxonomy( 'car_interior_color', array( 'cars' ), $args  );
        }
        
        
        $labels = array(
			'name'                       => esc_html__( 'Drive Train', 'meathouse' ),
			'singular_name'              => esc_html__( 'Drive Train', 'meathouse' ),
			'search_items'               => esc_html__( 'Search Drive Train', 'meathouse' ),
			'popular_items'              => esc_html__( 'Popular Drive Train', 'meathouse' ),
			'all_items'                  => esc_html__( 'All Drive Train', 'meathouse' ),
			'parent_item'                => null,
			'parent_item_colon'          => null,
			'edit_item'                  => esc_html__( 'Edit Drive Train', 'meathouse' ),
			'update_item'                => esc_html__( 'Update Drive Train', 'meathouse' ),
			'add_new_item'               => esc_html__( 'Add New Drive Train', 'meathouse' ),
			'new_item_name'              => esc_html__( 'New Drive Train Name', 'meathouse' ),
			'separate_items_with_commas' => esc_html__( 'Separate drive-train with commas', 'meathouse' ),
			'add_or_remove_items'        => esc_html__( 'Add or remove Drive Train', 'meathouse' ),
			'choose_from_most_used'      => esc_html__( 'Choose from the most used Drive Train', 'meathouse' ),
			'not_found'                  => esc_html__( 'No drive-train found.', 'meathouse' ),
			'menu_name'                  => esc_html__( 'Drive Train', 'meathouse' ),
		);

		$args = array(
			'hierarchical'          => false,
			'labels'                => $labels,
			'show_ui'               => true,
			'update_count_callback' => '_update_post_term_count',
			'query_var'             => true,
			'rewrite'               => array( 'slug' => 'drive-train' ),
		);
		
        if(function_exists('custom_reg_taxonomy')){
            custom_reg_taxonomy( 'car_drive_train', array( 'cars' ), $args  );
        }
        
        $labels = array(
			'name'                       => esc_html__( 'Registered', 'meathouse' ),
			'singular_name'              => esc_html__( 'Registered', 'meathouse' ),
			'search_items'               => esc_html__( 'Search Registered', 'meathouse' ),
			'popular_items'              => esc_html__( 'Popular Registered', 'meathouse' ),
			'all_items'                  => esc_html__( 'All Registered', 'meathouse' ),
			'parent_item'                => null,
			'parent_item_colon'          => null,
			'edit_item'                  => esc_html__( 'Edit Registered', 'meathouse' ),
			'update_item'                => esc_html__( 'Update Registered', 'meathouse' ),
			'add_new_item'               => esc_html__( 'Add New Registered', 'meathouse' ),
			'new_item_name'              => esc_html__( 'New Registered Name', 'meathouse' ),
			'separate_items_with_commas' => esc_html__( 'Separate registered with commas', 'meathouse' ),
			'add_or_remove_items'        => esc_html__( 'Add or remove Registered', 'meathouse' ),
			'choose_from_most_used'      => esc_html__( 'Choose from the most used Registered', 'meathouse' ),
			'not_found'                  => esc_html__( 'No registered found.', 'meathouse' ),
			'menu_name'                  => esc_html__( 'Registered', 'meathouse' ),
		);

		$args = array(
			'hierarchical'          => false,
			'labels'                => $labels,
			'show_ui'               => true,
			'update_count_callback' => '_update_post_term_count',
			'query_var'             => true,
			'rewrite'               => array( 'slug' => 'registered' ),
		);
	
        if(function_exists('custom_reg_taxonomy')){
            custom_reg_taxonomy( 'car_registered', array( 'cars' ), $args  );
        }
        
        $labels = array(
			'name'                       => esc_html__( 'Door Number', 'meathouse' ),
			'singular_name'              => esc_html__( 'Door Number', 'meathouse' ),
			'search_items'               => esc_html__( 'Search Door Number', 'meathouse' ),
			'popular_items'              => esc_html__( 'Popular Door Number', 'meathouse' ),
			'all_items'                  => esc_html__( 'All Door Number', 'meathouse' ),
			'parent_item'                => null,
			'parent_item_colon'          => null,
			'edit_item'                  => esc_html__( 'Edit Door Number', 'meathouse' ),
			'update_item'                => esc_html__( 'Update Door Number', 'meathouse' ),
			'add_new_item'               => esc_html__( 'Add New Door Number', 'meathouse' ),
			'new_item_name'              => esc_html__( 'New Door Number Name', 'meathouse' ),
			'separate_items_with_commas' => esc_html__( 'Separate Door Number with commas', 'meathouse' ),
			'add_or_remove_items'        => esc_html__( 'Add or remove Door Number', 'meathouse' ),
			'choose_from_most_used'      => esc_html__( 'Choose from the most used Door Number', 'meathouse' ),
			'not_found'                  => esc_html__( 'No Door Number found.', 'meathouse' ),
			'menu_name'                  => esc_html__( 'Door Number', 'meathouse' ),
		);

		$args = array(
			'hierarchical'          => false,
			'labels'                => $labels,
			'show_ui'               => true,
			'update_count_callback' => '_update_post_term_count',
			'query_var'             => true,
			'rewrite'               => array( 'slug' => 'Door Number' ),
		);
	
        if(function_exists('custom_reg_taxonomy')){
            custom_reg_taxonomy( 'car_door_number', array( 'cars' ), $args  );
        }
        
        $labels = array(
			'name'                       => esc_html__( 'Seat Number', 'meathouse' ),
			'singular_name'              => esc_html__( 'Seat Number', 'meathouse' ),
			'search_items'               => esc_html__( 'Search Seat Number', 'meathouse' ),
			'popular_items'              => esc_html__( 'Popular Seat Number', 'meathouse' ),
			'all_items'                  => esc_html__( 'All Seat Number', 'meathouse' ),
			'parent_item'                => null,
			'parent_item_colon'          => null,
			'edit_item'                  => esc_html__( 'Edit Seat Number', 'meathouse' ),
			'update_item'                => esc_html__( 'Update Seat Number', 'meathouse' ),
			'add_new_item'               => esc_html__( 'Add New Seat Number', 'meathouse' ),
			'new_item_name'              => esc_html__( 'New Seat Number Name', 'meathouse' ),
			'separate_items_with_commas' => esc_html__( 'Separate Seat Number with commas', 'meathouse' ),
			'add_or_remove_items'        => esc_html__( 'Add or remove Seat Number', 'meathouse' ),
			'choose_from_most_used'      => esc_html__( 'Choose from the most used Seat Number', 'meathouse' ),
			'not_found'                  => esc_html__( 'No Seat Number found.', 'meathouse' ),
			'menu_name'                  => esc_html__( 'Seat Number', 'meathouse' ),
		);

		$args = array(
			'hierarchical'          => false,
			'labels'                => $labels,
			'show_ui'               => true,
			'update_count_callback' => '_update_post_term_count',
			'query_var'             => true,
			'rewrite'               => array( 'slug' => 'Seat Number' ),
		);
	
        if(function_exists('custom_reg_taxonomy')){
            custom_reg_taxonomy( 'car_seat_number', array( 'cars' ), $args  );
        }
        
		// Add new taxonomy, NOT hierarchical (like tags).
		$labels = array(
			'name'                       => esc_html__( 'Stock Number', 'meathouse' ),
			'singular_name'              => esc_html__( 'Stock Number', 'meathouse' ),
			'search_items'               => esc_html__( 'Search Stock Number', 'meathouse' ),
			'popular_items'              => esc_html__( 'Popular Stock Number', 'meathouse' ),
			'all_items'                  => esc_html__( 'All Stock Number', 'meathouse' ),
			'parent_item'                => null,
			'parent_item_colon'          => null,
			'edit_item'                  => esc_html__( 'Edit Stock Number', 'meathouse' ),
			'update_item'                => esc_html__( 'Update Stock Number', 'meathouse' ),
			'add_new_item'               => esc_html__( 'Add New Stock Number', 'meathouse' ),
			'new_item_name'              => esc_html__( 'New Stock Number Name', 'meathouse' ),
			'separate_items_with_commas' => esc_html__( 'Separate stock-number with commas', 'meathouse' ),
			'add_or_remove_items'        => esc_html__( 'Add or remove Stock Number', 'meathouse' ),
			'choose_from_most_used'      => esc_html__( 'Choose from the most used Stock Number', 'meathouse' ),
			'not_found'                  => esc_html__( 'No stock-number found.', 'meathouse' ),
			'menu_name'                  => esc_html__( 'Stock Number', 'meathouse' ),
		);

		$args = array(
			'hierarchical'          => false,
			'labels'                => $labels,
			'show_ui'               => true,
			'update_count_callback' => '_update_post_term_count',
			'query_var'             => true,
			'rewrite'               => array( 'slug' => 'stock-number' ),
		);
	
        if(function_exists('custom_reg_taxonomy')){
            custom_reg_taxonomy( 'car_stock_number', array( 'cars' ), $args  );
        }

		// Add new taxonomy, NOT hierarchical (like tags).
		$labels = array(
			'name'                       => esc_html__( 'VIN Number', 'meathouse' ),
			'singular_name'              => esc_html__( 'VIN Number', 'meathouse' ),
			'search_items'               => esc_html__( 'Search VIN Number', 'meathouse' ),
			'popular_items'              => esc_html__( 'Popular VIN Number', 'meathouse' ),
			'all_items'                  => esc_html__( 'All VIN Number', 'meathouse' ),
			'parent_item'                => null,
			'parent_item_colon'          => null,
			'edit_item'                  => esc_html__( 'Edit VIN Number', 'meathouse' ),
			'update_item'                => esc_html__( 'Update VIN Number', 'meathouse' ),
			'add_new_item'               => esc_html__( 'Add New VIN Number', 'meathouse' ),
			'new_item_name'              => esc_html__( 'New VIN Number Name', 'meathouse' ),
			'separate_items_with_commas' => esc_html__( 'Separate vin-number with commas', 'meathouse' ),
			'add_or_remove_items'        => esc_html__( 'Add or remove VIN Number', 'meathouse' ),
			'choose_from_most_used'      => esc_html__( 'Choose from the most used VIN Number', 'meathouse' ),
			'not_found'                  => esc_html__( 'No vin-number found.', 'meathouse' ),
			'menu_name'                  => esc_html__( 'VIN Number', 'meathouse' ),
		);

		$args = array(
			'hierarchical'          => false,
			'labels'                => $labels,
			'show_ui'               => true,
			'update_count_callback' => '_update_post_term_count',
			'query_var'             => true,
			'rewrite'               => array( 'slug' => 'vin-number' ),
		);
	
        if(function_exists('custom_reg_taxonomy')){
            custom_reg_taxonomy( 'car_vin_number', array( 'cars' ), $args  );
        }

		$labels = array(
			'name'                       => esc_html__( 'Fuel Type', 'meathouse' ),
			'singular_name'              => esc_html__( 'Fuel Type', 'meathouse' ),
			'search_items'               => esc_html__( 'Search Fuel Type', 'meathouse' ),
			'popular_items'              => esc_html__( 'Popular Fuel Type', 'meathouse' ),
			'all_items'                  => esc_html__( 'All Fuel Type', 'meathouse' ),
			'parent_item'                => null,
			'parent_item_colon'          => null,
			'edit_item'                  => esc_html__( 'Edit Fuel Type', 'meathouse' ),
			'update_item'                => esc_html__( 'Update Fuel Type', 'meathouse' ),
			'add_new_item'               => esc_html__( 'Add New Fuel Type', 'meathouse' ),
			'new_item_name'              => esc_html__( 'New Fuel Type Name', 'meathouse' ),
			'separate_items_with_commas' => esc_html__( 'Separate fuel-type with commas', 'meathouse' ),
			'add_or_remove_items'        => esc_html__( 'Add or remove Fuel Type', 'meathouse' ),
			'choose_from_most_used'      => esc_html__( 'Choose from the most used Fuel Type', 'meathouse' ),
			'not_found'                  => esc_html__( 'No fuel-type found.', 'meathouse' ),
			'menu_name'                  => esc_html__( 'Fuel Type', 'meathouse' ),
		);

		$args = array(
			'hierarchical'          => false,
			'labels'                => $labels,
			'show_ui'               => true,
			'update_count_callback' => '_update_post_term_count',
			'query_var'             => true,
			'rewrite'               => array( 'slug' => 'fuel-type' ),
		);
	
         if(function_exists('custom_reg_taxonomy')){
            custom_reg_taxonomy( 'car_fuel_type', array( 'cars' ), $args  );
        }

		$labels = array(
			'name'                       => esc_html__( 'Trim', 'meathouse' ),
			'singular_name'              => esc_html__( 'Trim', 'meathouse' ),
			'search_items'               => esc_html__( 'Search Trim', 'meathouse' ),
			'popular_items'              => esc_html__( 'Popular Trim', 'meathouse' ),
			'all_items'                  => esc_html__( 'All Trim', 'meathouse' ),
			'parent_item'                => null,
			'parent_item_colon'          => null,
			'edit_item'                  => esc_html__( 'Edit Trim', 'meathouse' ),
			'update_item'                => esc_html__( 'Update Trim', 'meathouse' ),
			'add_new_item'               => esc_html__( 'Add New Trim', 'meathouse' ),
			'new_item_name'              => esc_html__( 'New Trim Name', 'meathouse' ),
			'separate_items_with_commas' => esc_html__( 'Separate trim-type with commas', 'meathouse' ),
			'add_or_remove_items'        => esc_html__( 'Add or remove Trim', 'meathouse' ),
			'choose_from_most_used'      => esc_html__( 'Choose from the most used Trim', 'meathouse' ),
			'not_found'                  => esc_html__( 'No trim found.', 'meathouse' ),
			'menu_name'                  => esc_html__( 'Trim', 'meathouse' ),
		);

		$args = array(
			'hierarchical'          => false,
			'labels'                => $labels,
			'show_ui'               => true,
			'update_count_callback' => '_update_post_term_count',
			'query_var'             => true,
			'rewrite'               => array( 'slug' => 'trim' ),
		);

         if(function_exists('custom_reg_taxonomy')){
            custom_reg_taxonomy( 'car_trim', array( 'cars' ), $args  );
        }

		// Add new taxonomy, NOT hierarchical (like tags).
		$labels = array(
			'name'                       => esc_html__( 'Features & Options', 'meathouse' ),
			'singular_name'              => esc_html__( 'Features & Options', 'meathouse' ),
			'search_items'               => esc_html__( 'Search Features & Options', 'meathouse' ),
			'popular_items'              => esc_html__( 'Popular Features & Options', 'meathouse' ),
			'all_items'                  => esc_html__( 'All Features & Options', 'meathouse' ),
			'parent_item'                => esc_html__( 'Parent Feature', 'meathouse' ),
			'parent_item_colon'          => null,
			'edit_item'                  => esc_html__( 'Edit Features & Options', 'meathouse' ),
			'update_item'                => esc_html__( 'Update Features & Options', 'meathouse' ),
			'add_new_item'               => esc_html__( 'Add New Features & Options', 'meathouse' ),
			'new_item_name'              => esc_html__( 'New Features & Options Name', 'meathouse' ),
			'separate_items_with_commas' => esc_html__( 'Separate features-options with commas', 'meathouse' ),
			'add_or_remove_items'        => esc_html__( 'Add or remove Features & Options', 'meathouse' ),
			'choose_from_most_used'      => esc_html__( 'Choose from the most used Features & Options', 'meathouse' ),
			'not_found'                  => esc_html__( 'No features-options found.', 'meathouse' ),
			'menu_name'                  => esc_html__( 'Features & Options', 'meathouse' ),
		);

		$args = array(
			'hierarchical'          => true,
			'labels'                => $labels,
			'show_ui'               => true,
			'update_count_callback' => '_update_post_term_count',
			'query_var'             => true,
			'rewrite'               => array( 'slug' => 'features-options' ),
		);
	
         if(function_exists('custom_reg_taxonomy')){
            custom_reg_taxonomy( 'car_features_options', array( 'cars' ), $args  );
        }

		// Add new taxonomy, NOT hierarchical (like tags).
		$labels = array(
			'name'                       => esc_html__( 'Vehicle Review Stamps', 'meathouse' ),
			'singular_name'              => esc_html__( 'Vehicle Review Stamps', 'meathouse' ),
			'search_items'               => esc_html__( 'Search Vehicle Review Stamps', 'meathouse' ),
			'popular_items'              => esc_html__( 'Popular Vehicle Review Stamps', 'meathouse' ),
			'all_items'                  => esc_html__( 'All Vehicle Review Stamps', 'meathouse' ),
			'parent_item'                => null,
			'parent_item_colon'          => null,
			'edit_item'                  => esc_html__( 'Edit Vehicle Review Stamps', 'meathouse' ),
			'update_item'                => esc_html__( 'Update Vehicle Review Stamps', 'meathouse' ),
			'add_new_item'               => esc_html__( 'Add New Vehicle Review Stamps', 'meathouse' ),
			'new_item_name'              => esc_html__( 'New Vehicle Review Stamps Name', 'meathouse' ),
			'separate_items_with_commas' => esc_html__( 'Separate Vehicle Review Stamps with commas', 'meathouse' ),
			'add_or_remove_items'        => esc_html__( 'Add or remove Vehicle Review Stamps', 'meathouse' ),
			'choose_from_most_used'      => esc_html__( 'Choose from the most used Vehicle Review Stamps', 'meathouse' ),
			'not_found'                  => esc_html__( 'No Vehicle Review Stamps found.', 'meathouse' ),
			'menu_name'                  => esc_html__( 'Vehicle Review Stamps', 'meathouse' ),
		);

		$args = array(
			'hierarchical'          => true,
			'labels'                => $labels,
			'show_ui'               => true,
			'update_count_callback' => '_update_post_term_count',
			'query_var'             => true,
			'rewrite'               => true,
		);
	
         if(function_exists('custom_reg_taxonomy')){
            custom_reg_taxonomy( 'car_vehicle_review_stamps', array( 'cars' ), $args  );
        }
        
        add_action( 'admin_init', 'jws_remove_metabox' );
if ( ! function_exists( 'jws_remove_metabox' ) ) {
	/**
	 * Remove metabox.
	 */
	function jws_remove_metabox() {
		remove_meta_box( 'tagsdiv-car_year', 'cars', 'side' );
		remove_meta_box( 'tagsdiv-car_make', 'cars', 'side' );
		remove_meta_box( 'tagsdiv-car_model', 'cars', 'side' );
		remove_meta_box( 'tagsdiv-car_body_style', 'cars', 'side' );
		remove_meta_box( 'tagsdiv-car_condition', 'cars', 'side' );
		remove_meta_box( 'tagsdiv-car_mileage', 'cars', 'side' );
		remove_meta_box( 'tagsdiv-car_transmission', 'cars', 'side' );
		remove_meta_box( 'tagsdiv-car_drivetrain', 'cars', 'side' );
		remove_meta_box( 'tagsdiv-car_engine', 'cars', 'side' );
		remove_meta_box( 'tagsdiv-car_fuel_economy', 'cars', 'side' );
		remove_meta_box( 'tagsdiv-car_exterior_color', 'cars', 'side' );
		remove_meta_box( 'tagsdiv-car_interior_color', 'cars', 'side' );
		remove_meta_box( 'tagsdiv-car_stock_number', 'cars', 'side' );
		remove_meta_box( 'tagsdiv-car_vin_number', 'cars', 'side' );
		remove_meta_box( 'tagsdiv-car_fuel_type', 'cars', 'side' );
		remove_meta_box( 'tagsdiv-car_trim', 'cars', 'side' );
		remove_meta_box( 'tagsdiv-car_features_options', 'cars', 'side' );
		remove_meta_box( 'car_features_optionsdiv', 'cars', 'side' );
		remove_meta_box( 'car_vehicle_review_stampsdiv', 'cars', 'side' );
	}
}


