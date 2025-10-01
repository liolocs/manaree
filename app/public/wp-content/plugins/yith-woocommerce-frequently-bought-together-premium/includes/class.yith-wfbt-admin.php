<?php // phpcs:ignore WordPress.Files.FileName.InvalidClassFileName
/**
 * Admin class
 *
 * @author YITH <plugins@yithemes.com>
 * @package YITH\FrequentlyBoughtTogetherPremium
 * @version 1.0.0
 */

if ( ! defined( 'YITH_WFBT' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( 'YITH_WFBT_Admin' ) ) {
	/**
	 * Admin class.
	 * The class manage all the admin behaviors.
	 *
	 * @since 1.0.0
	 */
	class YITH_WFBT_Admin {

		/**
		 * Single instance of the class
		 *
		 * @since 1.0.0
		 * @var \YITH_WFBT_Admin
		 */
		protected static $instance;

		/**
		 * Plugin product data options
		 *
		 * @since 1.3.0
		 * @var array
		 */
		public $product_options = array();

		/**
		 * Plugin version
		 *
		 * @since 1.0.0
		 * @var string
		 */
		public $version = YITH_WFBT_VERSION;

		/**
		 * Panel Object
		 *
		 * @var $panel
		 */
		protected $panel;

		/**
		 * Waiting List panel page
		 *
		 * @var string $panel_page
		 */
		protected $panel_page = 'yith_wfbt_panel';

		/**
		 * Returns single instance of the class
		 *
		 * @since 1.0.0
		 * @return YITH_WFBT_Admin
		 */
		public static function get_instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * Constructor
		 *
		 * @access public
		 * @since  1.0.0
		 */
		public function __construct() {

			add_action( 'init', array( $this, 'init_vars' ), 1 );
			add_action( 'admin_menu', array( $this, 'register_panel' ), 5 );

			// Add action links.
			add_filter( 'plugin_action_links_' . plugin_basename( YITH_WFBT_DIR . '/' . basename( YITH_WFBT_FILE ) ), array( $this, 'action_links' ) );
			add_filter( 'yith_show_plugin_row_meta', array( $this, 'plugin_row_meta' ), 10, 5 );

			// enqueue style and scripts.
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ), 15 );

			// custom tab.
			add_action( 'yith_wfbt_data_table', array( $this, 'data_table' ) );

			// add section in product edit page.
			add_filter( 'woocommerce_product_data_tabs', array( $this, 'add_bought_together_tab' ), 20, 1 );
			add_action( 'woocommerce_product_data_panels', array( $this, 'add_bought_together_panel' ) );
			// delete linked product.
			add_action( 'wp_ajax_yith_wfbt_delete_linked', array( $this, 'delete_linked_product' ) );
			// ajax update list of variation for variable product.
			add_action( 'wp_ajax_yith_update_variation_list', array( $this, 'yith_ajax_update_variation_list' ) );
			// search product.
			add_action( 'wp_ajax_yith_ajax_search_product', array( $this, 'yith_ajax_search_product' ) );

			// table action.
			add_action( 'admin_init', array( $this, 'table_actions' ), 10 );

			add_filter( 'woocommerce_admin_settings_sanitize_option_yith-wfbt-discount-name', array( $this, 'sanitize_discount' ), 10, 3 );

			// save tabs options.
			/**
			 * APPLY_FILTERS: yith_wfbt_product_types_meta_save
			 *
			 * Filters the product types on which save the plugin options in the product panel.
			 *
			 * @param array $product_types Product types.
			 *
			 * @return array
			 */
			$product_types = apply_filters(
				'yith_wfbt_product_types_meta_save',
				array(
					'simple',
					'variable',
					'grouped',
					'external',
					'rentable',
				)
			);
			foreach ( $product_types as $product_type ) {
				add_action( 'woocommerce_process_product_meta_' . $product_type, array( $this, 'save_bought_together_tab' ), 10, 1 );
			}

			// add custom image size type.
			add_action( 'woocommerce_admin_field_yith_image_size', array( $this, 'custom_image_size' ), 10, 1 );

			add_action( 'yith_wfbt_product_panel_before_field_discount_type', array( $this, 'maybe_add_coupon_alert' ), 10 );

			// Support on variations.
			add_filter( 'yith_wfbt_product_types_to_skip', array( $this, 'enable_variable_products' ) );
		}

		/**
		 * Init plugin admin vars
		 *
		 * @since  2.0.0
		 */
		public function init_vars() {
			$this->product_options = include YITH_WFBT_DIR . '/plugin-options/product-data-options.php';
		}

		/**
		 * Action Links
		 *
		 * Add the action links to plugin admin page
		 *
		 * @since    1.0
		 * @param array $links | links plugin array.
		 *
		 * @return   mixed Array
		 * @use      plugin_action_links_{$plugin_file_name}
		 */
		public function action_links( $links ) {
			$links = yith_add_action_links( $links, $this->panel_page, true, YITH_WFBT_SLUG );
			return $links;
		}

		/**
		 * Add a panel under YITH Plugins tab
		 *
		 * @since    1.0
		 * @use      /Yit_Plugin_Panel class
		 * @return   void
		 * @see      plugin-fw/lib/yit-plugin-panel.php
		 */
		public function register_panel() {

			if ( ! empty( $this->panel ) ) {
				return;
			}

			$admin_tabs = array(
				'general'     => __( 'Settings', 'yith-woocommerce-frequently-bought-together' ),
				'label-style' => __( 'Label & Style', 'yith-woocommerce-frequently-bought-together' ),
				'data'        => __( 'Linked Products', 'yith-woocommerce-frequently-bought-together' ),
			);

			if ( defined( 'YITH_WCWL' ) && YITH_WCWL ) {
				$admin_tabs['slider'] = __( 'Slider in Wishlist', 'yith-woocommerce-frequently-bought-together' );
			}

			$args = array(
				/**
				 * APPLY_FILTERS: yith-wfbt-register-panel-create-menu-page
				 *
				 * Filters whether to create a menu page for the plugin panel.
				 *
				 * @param bool $create_menu_page whether to create a menu page for the plugin panel or not.
				 *
				 * @return bool
				 */
				'create_menu_page' => apply_filters( 'yith-wfbt-register-panel-create-menu-page', true ), // phpcs:ignore WordPress.NamingConventions.ValidHookName
				'parent_slug'      => '',
				'page_title'       => 'YITH WooCommerce Frequently Bought Together',
				'menu_title'       => 'Frequently Bought Together',
				/**
				 * APPLY_FILTERS: yith-wfbt-register-panel-capabilities
				 *
				 * Filters the capability used to access the plugin panel.
				 *
				 * @param string $capability Capability.
				 *
				 * @return string
				 */
				'capability'       => apply_filters( 'yith-wfbt-register-panel-capabilities', 'manage_options' ), // phpcs:ignore WordPress.NamingConventions.ValidHookName
				'parent'           => '',
				/**
				 * APPLY_FILTERS: yith-wfbt-register-panel-parent-page
				 *
				 * Filters the parent page of the plugin panel.
				 *
				 * @param string $parent_page Parent page.
				 *
				 * @return string
				 */
				'parent_page'      => apply_filters( 'yith-wfbt-register-panel-parent-page', 'yith_plugin_panel' ), // phpcs:ignore WordPress.NamingConventions.ValidHookName
				'page'             => $this->panel_page,
				/**
				 * APPLY_FILTERS: yith-wfbt-admin-tabs
				 *
				 * Filters the available tabs in the plugin panel.
				 *
				 * @param array $tabs Admin tabs.
				 *
				 * @return array
				 */
				'admin-tabs'       => apply_filters( 'yith-wfbt-admin-tabs', $admin_tabs ), // phpcs:ignore WordPress.NamingConventions.ValidHookName
				'options-path'     => YITH_WFBT_DIR . '/plugin-options',
				'class'            => yith_set_wrapper_class(),
                'plugin_slug'      => YITH_WFBT_SLUG,
                'is_premium'       => defined( YITH_WFBT_PREMIUM ),
			);

			/* === Fixed: not updated theme  === */
			if ( ! class_exists( 'YIT_Plugin_Panel_WooCommerce' ) ) {
				require_once YITH_WFBT_DIR . '/plugin-fw/lib/yit-plugin-panel-wc.php';
			}

			$this->panel = new YIT_Plugin_Panel_WooCommerce( $args );
		}

		/**
		 * Plugin_row_meta
		 *
		 * Add the action links to plugin admin page
		 *
		 * @since    1.0
		 * @use      plugin_row_meta
		 * @param array    $new_row_meta_args An array of plugin row meta.
		 * @param string[] $plugin_meta       An array of the plugin's metadata,
		 *                                    including the version, author,
		 *                                    author URI, and plugin URI.
		 * @param string   $plugin_file       Path to the plugin file relative to the plugins directory.
		 * @param array    $plugin_data       An array of plugin data.
		 * @param string   $status            Status of the plugin. Defaults are 'All', 'Active',
		 *                                    'Inactive', 'Recently Activated', 'Upgrade', 'Must-Use',
		 *                                    'Drop-ins', 'Search', 'Paused'.
		 *
		 * @return   Array
		 */
		public function plugin_row_meta( $new_row_meta_args, $plugin_meta, $plugin_file, $plugin_data, $status ) {
			if ( defined( 'YITH_WFBT_INIT' ) && YITH_WFBT_INIT === $plugin_file ) {
				$new_row_meta_args['slug'] = YITH_WFBT_SLUG;

				if ( defined( 'YITH_WFBT_PREMIUM' ) ) {
					$new_row_meta_args['is_premium'] = true;
				}
			}
			return $new_row_meta_args;
		}

		/**
		 * Add custom image size to standard WC types
		 *
		 * @since  1.0.0
		 * @access public
		 * @param array $value //TODO: short description.
		 */
		public function custom_image_size( $value ) {

			$option_values = get_option( 'yith-wfbt-image-size' );
			$width         = isset( $option_values['width'] ) ? $option_values['width'] : $value['default']['width'];
			$height        = isset( $option_values['height'] ) ? $option_values['height'] : $value['default']['height'];
			$crop          = false !== $option_values['crop'] ? $option_values['crop'] : $value['default']['crop'];

			?>
			<tr valign="top">
			<th scope="row" class="titledesc"><?php echo esc_html( $value['title'] ); ?></th>
			<td class="forminp yith_image_size_settings">
				<div class="yith_image_size_wrap">
					<input name="<?php echo esc_attr( $value['id'] ); ?>[width]"
						id="<?php echo esc_attr( $value['id'] ); ?>-width" type="text" size="3"
						value="<?php echo esc_attr( $width ); ?>"/><span>&times;</span>
					<input name="<?php echo esc_attr( $value['id'] ); ?>[height]"
						id="<?php echo esc_attr( $value['id'] ); ?>-height" type="text" size="3"
						value="<?php echo esc_attr( $height ); ?>"/><span>px</span>

					<label><input name="<?php echo esc_attr( $value['id'] ); ?>[crop]"
							id="<?php echo esc_attr( $value['id'] ); ?>-crop" type="checkbox"
							value="1" <?php checked( 1, $crop ); ?> /> <?php esc_html_e( 'Hard Crop?', 'yith-woocommerce-frequently-bought-together' ); ?></label>
				</div>
				<span class="description"><?php echo wp_kses_post( $value['desc'] ); ?></span>
			</td>
			</tr>
			<?php

		}

		/**
		 * Enqueue scripts
		 *
		 * @since  1.0.0
		 */
		public function enqueue_scripts() {

			global $post;

			if ( ( ! empty( $post ) && get_post_type( $post->ID ) === 'product' )
				|| isset( $_GET['page'] ) && $_GET['page'] === $this->panel_page ) { // phpcs:ignore WordPress.Security.NonceVerification

				$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

				wp_enqueue_style( 'yith-wfbt-style', YITH_WFBT_ASSETS_URL . '/css/yith-wfbt-admin.css', array(), YITH_WFBT_VERSION );

				if ( ! empty( $post ) ) {
					// require field style by plugin FW.
					wp_enqueue_style( 'yith-plugin-fw-fields' );
					wp_enqueue_script( 'yith-plugin-fw-fields' );

					wp_enqueue_script( 'yith-wfbt-script', YITH_WFBT_ASSETS_URL . '/js/yith-wfbt-admin' . $suffix . '.js', array( 'jquery' ), YITH_WFBT_VERSION, true );
					wp_localize_script(
						'yith-wfbt-script',
						'yith_wfbt',
						array(
							'ajaxurl' => admin_url( 'admin-ajax.php' ),
							'postID'  => $post->ID,
						)
					);
				} elseif ( isset( $_GET['tab'] ) && 'data' === $_GET['tab'] ) { // phpcs:ignore WordPress.Security.NonceVerification

					wp_enqueue_script( 'yith-wfbt-script', YITH_WFBT_ASSETS_URL . '/js/yith-wfbt-table' . $suffix . '.js', array( 'jquery' ), YITH_WFBT_VERSION, true );
					wp_localize_script(
						'yith-wfbt-script',
						'yith_wfbt',
						array(
							'ajaxurl'     => admin_url( 'admin-ajax.php' ),
							'action'      => 'yith_wfbt_delete_linked',
							'security'    => wp_create_nonce( 'yith_wfbt_delete_linked' ),
							'deleteLabel' => _x( 'Delete', 'Delete single linked product', 'yith-woocommerce-frequently-bought-together' ),
						)
					);
				}
			}
		}

		/**
		 * Print data table
		 *
		 * @access public
		 * @since  1.0.0
		 */
		public function data_table() {

			include_once 'admin-tables/class.yith-wfbt-products-table.php';
			$table = new YITH_WFBT_Products_Table();
			$table->prepare_items();

			// then template.
			include_once YITH_WFBT_DIR . '/templates/admin/data-table.php';
		}

		/**
		 * Add bought together tab in edit product page
		 *
		 * @since  1.0.0
		 *
		 * @param mixed $tabs Product edit page tabs.
		 * @return mixed
		 */
		public function add_bought_together_tab( $tabs ) {

			$tabs['yith-wfbt'] = array(
				'label'  => _x( 'Frequently Bought Together', 'tab in product data box', 'yith-woocommerce-frequently-bought-together' ),
				'target' => 'yith_wfbt_data_option',
				'class'  => array( 'hide_if_grouped', 'hide_if_external', 'hide_if_bundle', 'hide_if_yith_bundle' ),
			);

			return $tabs;
		}

		/**
		 * Add bought together panel in edit product page
		 *
		 * @since  1.0.0
		 */
		public function add_bought_together_panel() {

			global $post, $product_object;

			$product_id = $post->ID;
			if ( is_null( $product_object ) ) {
				$product_object = wc_get_product( $product_id );
			}
			$to_exclude = array( $product_id );

			$metas   = yith_wfbt_get_meta( $product_object );
			$options = $this->product_options;

			if ( file_exists( YITH_WFBT_DIR . '/templates/admin/product-panel.php' ) ) {
				include_once YITH_WFBT_DIR . '/templates/admin/product-panel.php';
			}
		}

		/**
		 * Get variations id for variable post
		 *
		 * @access public
		 * @since  1.0.0
		 * @param string $post_id Post ID.
		 * @param bool   $only_id Get only id.
		 * @return mixed
		 */
		public function get_variations( $post_id, $only_id = false ) {

			// Get variations.
			$args = array(
				'post_type'   => 'product_variation',
				'post_status' => array( 'private', 'publish' ),
				'numberposts' => -1,
				'orderby'     => 'menu_order',
				'order'       => 'asc',
				'post_parent' => $post_id,
			);

			$posts  = get_posts( $args );
			$return = array();

			foreach ( $posts as $post ) {
				$product_id = $post->ID;
				$product    = wc_get_product( $product_id );

				if ( ! $product ) {
					continue;
				}

				if ( $only_id ) {
					$variation = $product_id;
				} else {
					$variation['id']   = $product_id;
					$variation['name'] = '#' . $product_id;

					$attrs = $product->get_variation_attributes();
					foreach ( $attrs as $attr ) {
						$variation['name'] .= ' - ' . $attr;
					}
				}

				$return[] = $variation;
			}

			return $return;

		}

		/**
		 * Ajax action search product
		 *
		 * @since  1.0.0
		 */
		public function yith_ajax_search_product() {

			global $post;

			ob_start();

			check_ajax_referer( 'search-products', 'security' );

			$term       = isset( $_GET['term'] ) ? (string) wc_clean( stripslashes( $_GET['term'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification, WordPress.Security.ValidatedSanitizedInput
			$post_types = array( 'product', 'product_variation' );

			$to_exclude = isset( $_GET['exclude'] ) ? explode( ',', wc_clean( wp_unslash( $_GET['exclude'] ) ) ) : false; // phpcs:ignore WordPress.Security.NonceVerification, WordPress.Security.ValidatedSanitizedInput

			if ( empty( $term ) ) {
				die();
			}

			$args = array(
				'post_type'      => $post_types,
				'post_status'    => 'publish',
				'posts_per_page' => -1,
				's'              => $term,
				'fields'         => 'ids',
			);

			if ( $to_exclude ) {
				$args['post__not_in'] = $to_exclude;
			}

			if ( is_numeric( $term ) ) {

				$args2 = array(
					'post_type'      => $post_types,
					'post_status'    => 'publish',
					'posts_per_page' => -1,
					'post__in'       => array( 0, $term ),
					'fields'         => 'ids',
				);

				$args3 = array(
					'post_type'      => $post_types,
					'post_status'    => 'publish',
					'posts_per_page' => -1,
					'post_parent'    => $term,
					'fields'         => 'ids',
				);

				$args4 = array(
					'post_type'      => $post_types,
					'post_status'    => 'publish',
					'posts_per_page' => -1,
					'meta_query'     => array( // phpcs:ignore WordPress.DB.SlowDBQuery
						array(
							'key'     => '_sku',
							'value'   => $term,
							'compare' => 'LIKE',
						),
					),
					'fields'         => 'ids',
				);

				$posts = array_unique( array_merge( get_posts( $args ), get_posts( $args2 ), get_posts( $args3 ), get_posts( $args4 ) ) );

			} else {

				$args2 = array(
					'post_type'      => $post_types,
					'post_status'    => 'publish',
					'posts_per_page' => -1,
					'meta_query'     => array( // phpcs:ignore WordPress.DB.SlowDBQuery
						array(
							'key'     => '_sku',
							'value'   => $term,
							'compare' => 'LIKE',
						),
					),
					'fields'         => 'ids',
				);

				$posts = array_unique( array_merge( get_posts( $args ), get_posts( $args2 ) ) );

			}

			$found_products = array();

			if ( $posts ) {
				foreach ( $posts as $post ) { // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
					$current_id = $post;
					$product    = wc_get_product( $post );

					/**
					 * APPLY_FILTERS: yith_wfbt_product_types_to_skip
					 *
					 * Filters the product types to skip when searching for products.
					 *
					 * @param array $product_types Product types to skip.
					 *
					 * @return array
					 */
					$types_to_skip = apply_filters( 'yith_wfbt_product_types_to_skip', array( 'variable', 'external' ) );

					// exclude variable product.
					if ( ! $product || $product->is_type( $types_to_skip ) ) {
						continue;
					} elseif ( $product->is_type( 'variation' ) ) {
						$current_id = wp_get_post_parent_id( $post );
						if ( ! wc_get_product( $current_id ) ) {
							continue;
						}
					}

					// last check for vendor.
					if ( get_option( 'yith-wfbt-vendor-products', 'no' ) === 'no' && function_exists( 'YITH_WFBT_Multivendor' ) && ! YITH_WFBT_Multivendor()->is_vendor_product( $current_id ) ) {
						continue;
					}

					$formatted_name          = wp_strip_all_tags( $product->get_formatted_name() );
					$found_products[ $post ] = rawurldecode( $formatted_name );
				}
			}

			/**
			 * APPLY_FILTERS: yith_wfbt_ajax_search_product_result
			 *
			 * Filters the results for the found products.
			 *
			 * @param array $found_products Found products.
			 *
			 * @return array
			 */
			wp_send_json( apply_filters( 'yith_wfbt_ajax_search_product_result', $found_products ) );
		}

		/**
		 * Ajax action that hanlde delete linked product from admin table
		 *
		 * @since 2.0.0
		 * @return void
		 */
		public function delete_linked_product() {
			check_ajax_referer( 'yith_wfbt_delete_linked', 'security' );

			$product_id = ! empty( $_POST['product_id'] ) ? intval( $_POST['product_id'] ) : 0;
			$linked_id  = ! empty( $_POST['linked_id'] ) ? intval( $_POST['linked_id'] ) : 0;

			if ( empty( $product_id ) || empty( $linked_id ) ) {
				wp_send_json_error();
			}

			$product = wc_get_product( $product_id );

			if ( ! $product ) {
				wp_send_json_error();
			}

			// get meta.
			$products = yith_wfbt_get_meta( $product, 'products' );
			// remove.
			$diff = array_diff( $products, array( $linked_id ) );
			yith_wfbt_set_meta( $product, array( 'products' => $diff ) );

			wp_send_json_success();
		}

		/**
		 * Save options
		 *
		 * @since  1.0.0
		 * @param mixed $post_id The post ID.
		 */
		public function save_bought_together_tab( $post_id ) {

			$product  = wc_get_product( $post_id );
			$new_meta = array();

			foreach ( $this->product_options as $fields ) {
				foreach ( $fields as $key => $field ) {

					if ( ! is_array( $field ) ) {
						continue;
					}

					$val = '';
					switch ( $field['type'] ) {

						case 'checkbox':
							$val = isset( $_POST[ $field['name'] ] ) ? 'yes' : 'no'; // phpcs:ignore WordPress.Security.NonceVerification
							break;

						case 'product_select':
							// save products group.
							$val = array();
							if ( isset( $_POST[ $field['name'] ] ) ) { // phpcs:ignore WordPress.Security.NonceVerification
								$val = ! is_array( $_POST[ $field['name'] ] ) ? explode( ',', wc_clean( wp_unslash( $_POST[ $field['name'] ] ) ) ) : wc_clean( wp_unslash( $_POST[ $field['name'] ] ) ); // phpcs:ignore WordPress.Security.NonceVerification, WordPress.Security.ValidatedSanitizedInput
								$val = array_filter( array_map( 'intval', $val ) );
							}
							break;

						case 'variation_select':
							$selected_variation = isset( $_POST[ $field['name'] ] ) ? $_POST[ $field['name'] ] : ''; // phpcs:ignore WordPress.Security.NonceVerification, WordPress.Security.ValidatedSanitizedInput
							$variations         = $this->get_variations( $post_id, true );
							// save selected if is valid.
							if ( ! empty( $variations ) && in_array( $selected_variation, $variations, false ) ) { // phpcs:ignore WordPress.PHP.StrictInArray
								$val = $selected_variation;
							} elseif ( ! empty( $variations ) ) {
								// else save first.
								$val = array_shift( $variations );
							}
							break;

						default:
							if ( isset( $_POST[ $field['name'] ] ) ) { // phpcs:ignore WordPress.Security.NonceVerification
								$val = 'number' === $field['type'] ? intval( $_POST[ $field['name'] ] ) : ( 'textarea' === $field['type'] ? sanitize_textarea_field( wp_unslash( $_POST[ $field['name'] ] ) ) : wc_clean( wp_unslash( $_POST[ $field['name'] ] ) ) ); // phpcs:ignore WordPress.Security.NonceVerification, WordPress.Security.ValidatedSanitizedInput

								if ( 'number' === $field['type'] && ! empty( $field['attr'] ) ) {
									// check min.
									if ( isset( $field['attr']['min'] ) && $val < $field['attr']['min'] ) {
										$val = $field['attr']['min'];
									}
									if ( isset( $field['attr']['max'] ) && $val > $field['attr']['max'] ) {
										$val = $field['attr']['max'];
									}
								}

								if ( isset( $field['class'] ) && in_array( 'wc_input_price', $field['class'], true ) ) {
									$val = wc_format_decimal( sanitize_text_field( wp_unslash( $val ) ) );
								}
							}
							break;
					}

					$new_meta[ $key ] = $val;
				}
			}

			// then save.
			yith_wfbt_set_meta( $product, $new_meta );
		}

		/**
		 * Update variation list after a var
		 */
		public function yith_ajax_update_variation_list() {

			if ( ! isset( $_POST['productID'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification
				die();
			}

			$id      = intval( $_POST['productID'] ); // phpcs:ignore WordPress.Security.NonceVerification
			$product = wc_get_product( $id );

			ob_start();

			$variations = $this->get_variations( $id );
			$selected   = yith_wfbt_get_meta( $product, 'default_variation' );
			foreach ( $variations as $variation ) :
				?>
				<option value="<?php echo esc_html( $variation['id'] ); ?>" <?php selected( $variation['id'], $selected ); ?>><?php echo esc_html( $variation['name'] ); ?></option>
				<?php
			endforeach;

			echo ob_get_clean(); // phpcs:ignore WordPress.Security.EscapeOutput
			die();
		}

		/**
		 * Get panel page name
		 *
		 * @access public
		 * @since  1.1.4
		 */
		public function get_panel_page_name() {
			return $this->panel_page;
		}

		/**
		 * Handle table action
		 *
		 * @since  1.3.0
		 */
		public function table_actions() {

			$page   = isset( $_GET['page'] ) ? sanitize_text_field( wp_unslash( $_GET['page'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification
			$tab    = isset( $_GET['tab'] ) ? sanitize_text_field( wp_unslash( $_GET['tab'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification
			$action = isset( $_GET['action'] ) ? sanitize_text_field( wp_unslash( $_GET['action'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification

			if ( $page !== $this->panel_page || 'data' !== $tab || '' === $action ) {
				return;
			}

			// remove linked.
			if ( 'delete' === $action ) {

				$ids = isset( $_GET['id'] ) ? wc_clean( wp_unslash( $_GET['id'] ) ) : array(); // phpcs:ignore WordPress.Security.NonceVerification
				if ( ! is_array( $ids ) ) {
					$ids = explode( ',', $ids );
				}
				// delete post meta.
				foreach ( $ids as $id ) {
					$product = wc_get_product( $id );
					if ( ! $product ) {
						continue;
					}
					yith_wfbt_delete_meta( $product );
				}
				// add message.
				if ( empty( $ids ) ) {
					$mess = 1;
				} else {
					$mess = 2;
				}
			}

			$list_query_args = array(
				'page' => $page,
				'tab'  => $tab,
			);
			// Set users table.
			if ( isset( $_GET['view'] ) && isset( $_GET['post_id'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification
				$list_query_args['view']    = sanitize_text_field( wp_unslash( $_GET['view'] ) ); // phpcs:ignore WordPress.Security.NonceVerification
				$list_query_args['post_id'] = sanitize_text_field( wp_unslash( $_GET['post_id'] ) ); // phpcs:ignore WordPress.Security.NonceVerification
			}
			// Add message.
			if ( isset( $mess ) && '' !== $mess ) {
				$list_query_args['wfbt_mess'] = $mess;
			}

			$list_url = add_query_arg( $list_query_args, admin_url( 'admin.php' ) );

			wp_safe_redirect( $list_url );
			exit;

		}

		/**
		 * Sanitize discount name option
		 *
		 * @since  1.3.4
		 * @param string $value String to sanitize.
		 * @param array  $option Array option.
		 * @param mixed  $raw_value Raw value.
		 * @return string
		 */
		public function sanitize_discount( $value, $option, $raw_value ) {
			return yith_wfbt_discount_code_validation( $value );
		}

		/**
		 * Maybe add an alert if WC coupons are disabled
		 *
		 * @since  1.4.0
		 * @return void
		 */
		public function maybe_add_coupon_alert() {
			if ( ! wc_coupons_enabled() ) {
				?>
				<p class="coupon-disabled-error">
					<?php
					// translators: %s stand for WooCommerce settings page.
					echo wp_kses_post( sprintf( __( 'You must enable coupons in order to use the plugin discount feature. Please enable it <a href="%s">here.</a>', 'yith-woocommerce-frequently-bought-together' ), esc_html( admin_url( 'admin.php?page=wc-settings' ) ) ) );
					?>
				</p>
				<?php
			}
		}


		/**
		 * Enable to set a variable product at backend
		 *
		 * @param mixed $product_type The product type.
		 * @return string
		 */
		public function enable_variable_products( $product_type ) {
			return 'external';
		}
	}
}
/**
 * Unique access to instance of YITH_WFBT_Admin class
 *
 * @since 1.0.0
 * @return YITH_WFBT_Admin
 */
function YITH_WFBT_Admin() { // phpcs:ignore WordPress.NamingConventions.ValidFunctionName
	return YITH_WFBT_Admin::get_instance();
}
