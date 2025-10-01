<?php 
class jws_CHECKBOX_FILTER_class extends WP_Widget {

	function __construct() {
		$args = array(
			'name'        => esc_html__( 'Jws Filter Product By Taxonomy', 'meathouse' ),
			'description' => esc_html__( 'It displays Filter', 'meathouse' ),
			'classname'   => 'widget-filter-checkbox'
		);
		parent::__construct( '', '', $args );

	}

	/**
	 * method to display in the admin
	 *
	 * @param $instance
	 */
	function form( $instance ) {
		$instance = wp_parse_args(
			(array) $instance,
			array(
				'title'       => esc_html__( 'Type', 'meathouse' ), // Legacy.
                'type' => 'car_type', // Legacy.

			)
		);

		extract( $instance );

		?>
		<p>
			<label
				for="<?php echo esc_attr( esc_attr( $this->get_field_id( 'title' ) ) ); ?>"> <?php esc_html_e( 'Title:',
					'meathouse' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( esc_attr( $this->get_field_id( 'title' ) ) ); ?>"
			       name="<?php echo esc_attr( esc_attr( $this->get_field_name( 'title' ) ) ); ?>" type="text"
			       value="<?php if ( isset( $title ) ) {
				       echo esc_attr( $title );
			       } ?>">
		</p>

		      <label for="<?php echo esc_attr($this->get_field_id( 'type' )); ?> "><?php esc_html_e('Type Filter:','meathouse'); ?></label>
            <select id="<?php echo esc_attr($this->get_field_id( 'type' )) ?>" name="<?php echo esc_attr($this->get_field_name( 'type' )); ?>">
                 <option <?php selected( $type, 'category' ); ?> value="category" ><?php echo esc_attr('category','meathouse'); ?></option>
                 <option <?php selected( $type, 'brand' ); ?> value="brand" ><?php echo esc_attr('brand','meathouse'); ?></option>
                 <option <?php selected( $type, 'features' ); ?> value="features" ><?php echo esc_attr('features','meathouse'); ?></option>
            </select>
		</p>
		<?php
	}

	/**
	 * frontend for the site
	 *
	 * @param $args
	 * @param $instance
	 */
	function widget( $args, $instance ) {
		//default values
		$instance = wp_parse_args(
			(array) $instance,
			array(
				'title'       => esc_html__( 'PRICE', 'meathouse' ), // Legacy.
                'type' => 'category', // Legacy.
			)
		);


		extract( $args );
		extract( $instance );
        $widget_id = $args;
   
		// Create a filter to the other plug-ins can change them
		$title         = sanitize_text_field( apply_filters( 'widget_title', $title ) );
		$before_widget = str_ireplace( 'class="widget"', 'class="widget widget-tag-cloud"', $before_widget );
		echo ''.$before_widget;
		echo ''.$before_title . esc_attr( $title ) . ''.$after_title;
		?>

		<div class="type checkbox">

			<?php

            if($type == 'brand') {
                $tax = 'product_brand';
                $all = esc_attr__('All brand','meathouse');
            }elseif($type == 'category') {
                $tax = 'product_cat';
                $all = esc_attr__('All Category','meathouse');
            }elseif($type == 'features') {
                $tax = 'product_features';
                $all = esc_attr__('All Features','meathouse');
            }

            $args = array(
    			'orderby'    => 'name',
    			'order'      => 'ASC',
    			'hide_empty' => 1,
    			'pad_counts' => true,
    			'child_of'   => '0',
                'parent'     => '0',
    		);
   
            global $jws_option;

 
            if(isset($jws_option['exclude-category-in-shop']) && !empty($jws_option['exclude-category-in-shop'])) {
                $args['exclude'] = $jws_option['exclude-category-in-shop']; // Don't display products in the clothing category on the shop page. 
            }
    
        
            $filter_name    = $type;
            $cats  =  get_terms($tax,$args );
                 
            global $wp;
            
    		 if(!is_shop() && is_tax()) {
                $form_action = get_permalink( wc_get_page_id( 'shop' ) );
            }else{
        		if ( '' === get_option( 'permalink_structure' ) ) {
        			$form_action = remove_query_arg( array( 'page', 'paged' ), add_query_arg( $wp->query_string, '', home_url( $wp->request ) ) );
        		} else {
        			$form_action = preg_replace( '%\/page/[0-9]+%', '', home_url( trailingslashit( $wp->request ) ) );
        		}
            }
            $cat_current = get_queried_object_id();
         
			?>
            <form method="get" action="<?php echo esc_url( $form_action ); ?>">
                    <input name="<?php echo ''.$tax; ?>" type="hidden" class="file_checkbox_value">
                    <?php echo wc_query_string_form_fields( null, array($tax), '', true ); ?>
            </form>
			<ul>

				<?php
                $args = array();
                if(isset($_GET[$tax])) {
                  $args   =   explode( ',',  $_GET[$tax] ); ;  
                }
      
				foreach ( $cats as $cat ) {
					if ( ! isset( $cat->name ) ) {
						continue;
					}
                    $id = $cat->term_id; 

                            $current_values = isset( $cat ) ? $cat : array();
                            
                			$current_filter = isset( $_GET[$filter_name] ) ? explode( ',', wc_clean( $_GET[$filter_name] ) ) : array();
                			$current_filter = array_map( 'sanitize_title', $current_filter );
                            
                        	// skip the term for the current archive
                			if ( get_current_term_id() === $cat->term_id ) {
                			//	continue;
                			}
                            
                            $option_is_set  = in_array( $cat->term_id,$current_filter);
                            if ( ! in_array( $cat->term_id , $current_filter ) ) {
                				$current_filter[] = $cat->term_id;
                              
                			}
        
                              if(isset($current_filter[1])) {
                                $link =  jws_shop_page_link(true);
                              }else{
                                $link =  jws_shop_page_link(false);
                              }
                              
         
                            // Add current filters to URL.
                			foreach ( $current_filter as $key => $value ) {
                		
                				// Exclude query arg for current term archive term
        
                				if ( $value == get_current_term_id() ) {
                					unset( $current_filter[$key] );
                                    
                				}
                
                				// Exclude self so filter can be unset on click.
                				if ( $option_is_set && $value == $cat->term_id ) {
                				 
                					unset( $current_filter[$key] );
                                 
                      
                              
                				}
                			}
                            if ( ! empty( $current_filter ) ) {
                            
                				$link = add_query_arg( $filter_name, implode( ',', $current_filter ), jws_shop_page_link(true) );
                			}
      
					?>
             
                    <li>
                  
                         <a class="sort-product-checkbox catlog-layout<?php if (in_array($cat->slug, $args) || ($cat_current == $id )) echo esc_attr(' active'); ?>" data-name="<?php echo esc_attr( $tax ); ?>" data-value="<?php echo esc_attr( $cat->slug ); ?>"><span class="check"></span><span class="text"><?php echo esc_html( $cat->name ); ?></span></a>
                    </li>
                   
				
				<?php } ?>
			</ul> 
          
           
		</div>

		<?php
		echo  ''.$after_widget;
	}

	function update( $new_instance, $old_instance ) {
			$instance             = $old_instance;
    		$new_instance         = wp_parse_args( (array) $new_instance, array(
    			'title'    => '',
    			'type' => 'category',
    		) );
    		$instance['title']    = sanitize_text_field( $new_instance['title'] );
    		$instance['type'] = $new_instance['type'] ? $new_instance['type'] : 'category';
    		return $instance;
	}
}

function get_current_term_id() {
		return absint( is_tax() ? get_queried_object()->term_id : 0 );
}

if(function_exists('insert_widgets')) {
    insert_widgets( 'jws_CHECKBOX_FILTER_class' );
}