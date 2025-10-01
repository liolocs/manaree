<?php // phpcs:ignore WordPress.Files.FileName.InvalidClassFileName
/**
 * Frontend class
 *
 * @author YITH <plugins@yithemes.com>
 * @package YITH\FrequentlyBoughtTogetherPremium
 * @version 1.0.0
 */

if ( ! defined( 'YITH_WFBT' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( 'YITH_WFBT_Frontend' ) ) {
	/**
	 * Frontend class.
	 * The class manage all the frontend behaviors.
	 *
	 * @since 1.0.0
	 */
	class YITH_WFBT_Frontend {

		/**
		 * Single instance of the class
		 *
		 * @since 1.0.0
		 * @var YITH_WFBT_Frontend
		 */
		protected static $instance;

		/**
		 * Plugin version
		 *
		 * @since 1.0.0
		 * @var string
		 */
		public $version = YITH_WFBT_VERSION;

		/**
		 * Discount class
		 *
		 * @since 1.0.0
		 * @var string
		 */
		public $discount = null;

		/**
		 * Refresh form action
		 *
		 * @since 1.3.0
		 * @var string
		 */
		public $action_refresh = 'yith_wfbt_refresh_form';

		/**
		 * Load select variations dialog content
		 *
		 * @since 1.5.0
		 * @var string
		 */
		public $action_variations_dialog_content = 'yith_wfbt_load_variations_dialog_content';

        /**
         * Set the block position
         *
         * @since 1.32.0
         * @var string
         */
        public $block_position = 'before';

		/**
		 * Returns single instance of the class
		 *
		 * @since 1.0.0
		 * @return YITH_WFBT_Frontend
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

			$this->discount = new YITH_WFBT_Discount();

			// enqueue scripts.
			add_action( 'wp_enqueue_scripts', array( $this, 'register_scripts' ) );
			add_action( 'template_redirect', array( $this, 'before_add_form' ) );
			// register shortcode.
			add_shortcode( 'ywfbt_form', array( $this, 'wfbt_shortcode' ) );
			add_shortcode( 'yith_wfbt', array( $this, 'bought_together_shortcode' ) );

			// ajax update price.
			add_action( 'wc_ajax_' . $this->action_refresh, array( $this, 'refresh_form' ) );
			add_action( 'wp_ajax_nopriv_' . $this->action_refresh, array( $this, 'refresh_form' ) );

			// ajax load variations dialog.
			add_action( 'wc_ajax_' . $this->action_variations_dialog_content, array( $this, 'load_variations_dialog' ) );
			add_action( 'wp_ajax_nopriv_' . $this->action_variations_dialog_content, array( $this, 'load_variations_dialog' ) );

			// prevent issue with YITH WooCommerce Surveys.
			add_filter( 'yith_wc_survey_prevent_display_form', array( $this, 'yith_wc_survey_prevent_display_form' ), 10, 1 );
		}

		/**
		 * Register scripts and styles for plugin
		 *
		 * @since  1.0.4
		 */
		public function register_scripts() {

			$suffix      = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
			$assets_path = str_replace( array( 'http:', 'https:' ), '', WC()->plugin_url() ) . '/assets/';

			wp_register_script( 'jquery-blockui', $assets_path . 'js/jquery-blockui/jquery.blockUI' . $suffix . '.js', array( 'jquery' ), '2.60', false );

			/**
			 * APPLY_FILTERS: yith_wfbt_stylesheet_paths
			 *
			 * Filters the path of the plugin CSS files.
			 *
			 * @param array $paths Paths for the CSS files.
			 *
			 * @return array
			 */
			$paths      = apply_filters( 'yith_wfbt_stylesheet_paths', array( WC()->template_path() . 'yith-wfbt-frontend.css', 'yith-wfbt-frontend.css' ) );
			$located    = locate_template( $paths, false, false );
			$search     = array( get_stylesheet_directory(), get_template_directory() );
			$replace    = array( get_stylesheet_directory_uri(), get_template_directory_uri() );
			$stylesheet = ! empty( $located ) ? str_replace( $search, $replace, $located ) : YITH_WFBT_ASSETS_URL . '/css/yith-wfbt.css';

			wp_register_style( 'yith-wfbt-style', $stylesheet, array(), $this->version );
			wp_register_script( 'yith-wfbt', YITH_WFBT_ASSETS_URL . '/js/yith-wfbt' . $suffix . '.js', array( 'jquery', 'jquery-blockui' ), $this->version, true );

			if ( ! defined( 'YITH_PROTEO_VERSION' ) ) {
				/**
				 * APPLY_FILTERS: yith_wcfbt_jquery_modal_style
				 *
				 * Filters the CDN URL to load the modal styles.
				 *
				 * @param $cdn_url CDN URL to load the modal styles.
				 *
				 * @return string
				 */
				$style = apply_filters( 'yith_wcfbt_jquery_modal_style', 'https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.css' );

				/**
				 * APPLY_FILTERS: yith_wcfbt_jquery_modal_script
				 *
				 * Filters the CDN URL to load the modal scripts.
				 *
				 * @param $cdn_url CDN URL to load the modal scripts.
				 *
				 * @return string
				 */
				$script = apply_filters( 'yith_wcfbt_jquery_modal_script', 'https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.js' );
				wp_register_style( 'yith-wfbt-query-dialog-style', $style, array(), YITH_WFBT_VERSION );
				wp_register_script( 'yith-wfbt-query-dialog', $script, array( 'jquery' ), YITH_WFBT_VERSION, true );
			}

			// register script for carousel.
			wp_register_style( 'yith-wfbt-carousel-style', YITH_WFBT_ASSETS_URL . '/css/owl.carousel.css', array(), YITH_WFBT_VERSION );
			wp_register_script( 'yith-wfbt-carousel-js', YITH_WFBT_ASSETS_URL . '/js/owl.carousel.min.js', array( 'jquery' ), YITH_WFBT_VERSION, true );
		}

		/**
		 * Enqueue scripts
		 *
		 * @since  1.0.0
		 */
		public function enqueue_scripts() {

			wp_enqueue_script( 'jquery-blockui' );
			wp_enqueue_script( 'yith-wfbt' );
			wp_enqueue_script( 'yith-wfbt-query-dialog' );

			wp_localize_script(
				'yith-wfbt',
				'yith_wfbt',
				array(
					'ajaxurl'              => WC_AJAX::get_endpoint( '%%endpoint%%' ),
					'refreshForm'          => $this->action_refresh,
					'loadVariationsDialog' => $this->action_variations_dialog_content,
					'loader'               => get_option( 'yith-wfbt-loader', YITH_WFBT_ASSETS_URL . '/images/loader.gif' ),
					'visible_elem'         => get_option( 'yith-wfbt-slider-elems', 4 ),
					/**
					 * APPLY_FILTERS: yith_wfbt_default_variation_selector
					 *
					 * Filters the variation selector to use in the plugin script.
					 *
					 * @param string $selector Selector.
					 *
					 * @return string
					 */
					'variation_selector'   => apply_filters( 'yith_wfbt_default_variation_selector', '.variations_form' ),
				)
			);

			wp_enqueue_style( 'yith-wfbt-query-dialog-style' );

			wp_enqueue_style( 'yith-wfbt-style' );

			$form_background  = get_option( 'yith-wfbt-form-background-color', '#ffffff' );
			$background       = yith_wfbt_get_proteo_default( 'yith-wfbt-button-color', '#222222' );
			$background_hover = yith_wfbt_get_proteo_default( 'yith-wfbt-button-color-hover', '#222222' );
			$text_color       = yith_wfbt_get_proteo_default( 'yith-wfbt-button-text-color', '#ffffff' );
			$text_color_hover = yith_wfbt_get_proteo_default( 'yith-wfbt-button-text-color-hover', '#ffffff' );

			$inline_css = "
                .yith-wfbt-submit-block .yith-wfbt-submit-button{background: {$background};color: {$text_color};border-color: {$background};}
                .yith-wfbt-submit-block .yith-wfbt-submit-button:hover{background: {$background_hover};color: {$text_color_hover};border-color: {$background_hover};}
                .yith-wfbt-form{background: {$form_background};}";

			wp_add_inline_style( 'yith-wfbt-style', $inline_css );

			/* Enqueue variation add to cart for select variation dialog */
			wp_enqueue_script(
				'wc-add-to-cart-variation',
				WC()->plugin_url() . 'assets/js/frontend/add-to-cart-variation.min.js',
				array(
					'jquery',
					'wp-util',
					'jquery-blockui',
				),
				WC()->version,
				false
			);

			$params = array(
				'wc_ajax_url'                      => WC_AJAX::get_endpoint( '%%endpoint%%' ),
				'i18n_no_matching_variations_text' => esc_attr__( 'Sorry, no products matched your selection. Please choose a different combination.', 'woocommerce' ),
				'i18n_make_a_selection_text'       => esc_attr__( 'Please select some product options before adding this product to your cart.', 'woocommerce' ),
				'i18n_unavailable_text'            => esc_attr__( 'Sorry, this product is unavailable. Please choose a different combination.', 'woocommerce' ),
			);

			wp_localize_script( 'wc-add-to-cart-variation', 'wc_add_to_cart_variation_params', $params );
		}

		/**
		 * Handle action before print form
		 *
		 * @since  1.0.4
		 */
		public function before_add_form() {

			global $post;

			if ( is_null( $post ) ) {
				return;
			}

			$position = get_option( 'yith-wfbt-form-position', 2 );

			if ( $post instanceof WP_Post && 'product' !== $post->post_type || 4 === $position ) {
				return;
			}

			// include style and scripts.
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ), 15 );

			// print form.

            if ( yith_plugin_fw_wc_is_using_block_template_in_single_product() ) {

                switch ( $position ) {
                    case 1:
                        add_action( 'render_block_woocommerce/product-meta', array( $this, 'wc_block_display_bought_together_form' ), 99,3 );
                        break;
                    case 2:
                        add_filter( 'render_block_woocommerce/product-details', array( $this, 'wc_block_display_bought_together_form' ), 10, 3 );
                        break;
                    case 3:
                        $this->block_position = 'after';
                        add_filter( 'render_block_woocommerce/product-details', array( $this, 'wc_block_display_bought_together_form' ), 10, 3 );
                        break;
                    case 5:
                        $this->block_position = 'after';
                        add_action( 'render_block_woocommerce/product-meta', array( $this, 'wc_block_display_bought_together_form' ), 10, 3 );
                        break;
                }

            } else {
                switch ( $position ) {
                    case 1:
                        add_action( 'woocommerce_single_product_summary', array( $this, 'add_bought_together_form' ), 99 );
                        break;
                    case 2:
                        add_action( 'woocommerce_after_single_product_summary', array( $this, 'add_bought_together_form' ), 5 );
                        break;
                    case 3:
                        add_action( 'woocommerce_after_single_product_summary', array( $this, 'add_bought_together_form' ), 99 );
                        break;
                    case 5:
                        add_action( 'woocommerce_single_product_summary', array( $this, 'add_bought_together_form' ), 35 );
                        break;
                }
            }


		}

		/**
		 * Form Template
		 *
		 * @since  1.0.0
		 * @param string|boolean $product_id The product id or false to get global product.
		 */
		public function add_bought_together_form( $product_id = false, $return = false ) {

			global $sitepress;

			$n_variable_products = 0;

			if ( ! $product_id || is_bool( $product_id ) ) {
				global $product, $post;
				if ( ! isset( $product ) && isset( $post ) ) {
					$product = wc_get_product( $post->ID );
				}
			} else {
				// make sure to get always translated products.
				$product_id = function_exists( 'wpml_object_id_filter' ) ? wpml_object_id_filter( $product_id, 'product', true ) : $product_id;
				$product    = wc_get_product( $product_id );
			}

			if ( ! is_object( $product ) || $product->is_type( array( 'grouped', 'external' ) ) || ! $this->can_be_added( $product ) ) {
				return;
			}

			$product_id = yit_get_base_product_id( $product );
			$variation  = null;

			// get meta for current product.
			$metas = yith_wfbt_get_meta( $product );

			switch ( $metas['products_type'] ) {
				case 'related':
					$group = wc_get_related_products( $product_id, -1 );
					break;
				case 'cross-sells':
					$group = $product->get_cross_sell_ids();
					break;
				case 'up-sells':
					$group = $product->get_upsell_ids();
					break;
				default:
					$group = isset( $metas['products'] ) ? $metas['products'] : array();
					break;
			}

			// if group is empty
			// first get group of original product.
			$original_product    = false;
			$original_product_id = false;
			if ( empty( $group ) && function_exists( 'wpml_object_id_filter' ) && 'yes' === get_option( 'yith-wcfbt-wpml-association', 'yes' ) && method_exists( $sitepress, 'get_default_language' ) ) {
				$original_product_id = wpml_object_id_filter( $product_id, 'product', true, $sitepress->get_default_language() );
				$original_product    = wc_get_product( $original_product_id );
				$metas               = yith_wfbt_get_meta( $original_product );
				$group               = isset( $metas['products'] ) ? $metas['products'] : array();
			}

			/**
			 * APPLY_FILTERS: yith_wfbt_add_bought_together_form_empty_group_handle
			 *
			 * Filters if the group is empty or not.
			 *
			 * @param bool  $group_is_empty True or false depending in if the group has content or not.
			 * @param array $group          Group of frequently bought products associated to the product.
			 * @param int   $product_id     The main product ID.
			 *
			 * @return bool
			 */
			if ( apply_filters( 'yith_wfbt_add_bought_together_form_empty_group_handle', empty( $group ), $group, $product_id ) ) {
				return;
			}

			// sort random array key products.
			/**
			 * APPLY_FILTERS: yith_wcfbt_shuffle_group
			 *
			 * Filters whether to apply a random sort to the products in the group.
			 *
			 * @param bool $shuffle_group Whether to apply a random sort to the products or not.
			 *
			 * @return bool
			 */
			if ( apply_filters( 'yith_wcfbt_shuffle_group', true ) ) {
				shuffle( $group );
			}

			// check for variation.
			if ( $product->is_type( 'variable' ) ) {

				$variation_id = isset( $metas['default_variation'] ) ? intval( $metas['default_variation'] ) : '';
				if ( $original_product && $variation_id ) {
					if ( function_exists( 'wpml_object_id_filter' ) ) {
						$variation_id = wpml_object_id_filter( $variation_id, 'product', false );
					}
				}

				if ( ! $variation_id || is_null( $variation_id ) ) {
					return;
				}

				$variation = wc_get_product( $variation_id );
				if ( ! $this->can_be_added( $variation ) ) {
					$variation = $this->get_first_available_variation( $product, $variation_id );
				}
				if ( ! $variation ) {
					return;
				}

				$product_id = $variation->get_id();
			}

			// if $num is empty set it to 2.
			$num = ( ! empty( $metas['num_visible'] && ! empty( $metas['visibility_type'] ) && 'randomly' === $metas['visibility_type'] ) )
				? intval( $metas['num_visible'] )
				: count( $group );

			$products[] = empty( $variation ) ? $product : $variation;
			if ( $product instanceof WC_Product_Variable ) {
				$n_variable_products++;
			}

			/**
			 * APPLY_FILTERS: yith_wfbt_price_to_display
			 *
			 * Filters the price to display in the Frequently Bought Together form.
			 *
			 * @param float                $price_to_display Price to display.
			 * @param WC_Product           $product          Product object.
			 * @param WC_Product_Variation $variation        Product variation object.
			 *
			 * @return float
			 */
			$total          = apply_filters( 'yith_wfbt_price_to_display', wc_get_price_to_display( empty( $variation ) ? $product : $variation ), $product, $variation );
			$total_discount = null;
			$num_products   = $this->can_be_added( $product ) ? 1 : 0;

			do {

				if ( empty( $group ) ) { // exit if group is empty!
					break;
				}

				$the_id = array_shift( $group );
				$the_id = ( function_exists( 'wpml_object_id_filter' ) && defined( 'ICL_SITEPRESS_VERSION' ) ) ? wpml_object_id_filter( $the_id, 'product', false ) : $the_id;
				if ( empty( $the_id ) ) {
					continue;
				}

				$current              = wc_get_product( $the_id );
				$current_variation_id = false;

				if ( ! $this->can_be_added( $current ) ) {
					continue;
				}

				if ( $current->is_type( 'variable' ) ) {
					// try to get variation default if available.
					$default_attributes = array();
					foreach ( $current->get_default_attributes() as $attribute_key => $attribute_value ) {
						$default_attributes[ 'attribute_' . $attribute_key ] = $attribute_value;
					}

					// try to find the variation id based on default attributes.
					if ( ! empty( $default_attributes ) ) {
						$data_store           = WC_Data_Store::load( 'product' );
						$current_variation_id = $data_store->find_matching_product_variation( $current, $default_attributes );
					}

					// if variation exists and can be added use it else get the variable.
					if ( ! empty( $current_variation_id ) && $this->can_be_added( $current_variation_id ) ) {
						$current = wc_get_product( $current_variation_id );
					}
				}

				// increase total.
				if ( ! $current->is_type( 'variable' ) ) {
					$total += apply_filters( 'yith_wfbt_price_to_display', wc_get_price_to_display( $current ), $current, null );
					$num_products++;
				}

				// add to main array.
				$products[] = $current;

			} while ( count( $products ) < $num + 1 );

			/**
			 * APPLY_FILTERS: yith_wfbt_filter_group_products_front
			 *
			 * Filters the products to be displayed in the Frequently Bought Together form.
			 *
			 * @param array $products Array of products.
			 *
			 * @return array
			 */
			$products = apply_filters( 'yith_wfbt_filter_group_products_front', $products );
			if ( empty( $products ) ) {
				return;
			}

			// calculate discount if main product is selected.
			$discount = floatval( $this->discount->get_discount_amount_to_display( ! empty( $original_product ) ? $original_product : $product, $products, $total ) );
			if ( $discount ) {
				$total_discount = ( $total - $discount );
			}

			/**
			 * APPLY_FILTERS: yith_wfbt_total_discount
			 *
			 * Filters the total discount to be applied when purchasing the products together.
			 *
			 * @param float $total_discount Total discount.
			 * @param float $discount       Discount.
			 *
			 * @return float
			 */
			$total_discount = apply_filters( 'yith_wfbt_total_discount', $total_discount, $discount );

			// set labels.
			$label       = $this->get_label_option( 'button', $num_products );
			$label_total = $this->get_label_option( 'total', $num_products );

			// add modal.
			if ( ! has_action( 'wp_footer', array( $this, 'add_variation_modal' ) ) ) {
				add_action( 'wp_footer', array( $this, 'add_variation_modal' ) );
			}

			wc_get_template(
				'yith-wfbt-form.php',
				array(
					'main_product_id'    => $product_id,
					/**
					 * APPLY_FILTERS: yith_wfbt_filter_group_products_front_custom
					 *
					 * Filters the products to be displayed in the Frequently Bought Together form.
					 *
					 * @param array $products Array of products.
					 *
					 * @return array
					 */
					'products'           => apply_filters( 'yith_wfbt_filter_group_products_front_custom', $products ),
					'unchecked'          => array(),
					'additional_text'    => yith_wfbt_get_meta( $product, 'additional_text' ),
					'label'              => $label,
					'label_total'        => $label_total,
					'title'              => get_option( 'yith-wfbt-form-title', __( 'Frequently Bought Together', 'yith-woocommerce-frequently-bought-together' ) ),
					/**
					 * APPLY_FILTERS: yith_wfbt_total_html
					 *
					 * Filters the HTML content for the total price in the Frequently Bought Together form.
					 *
					 * @param string $total_html     Total price in HTML.
					 * @param float  $total          Initial total price.
					 * @param float  $total_discount Total price after applying discount.
					 * @param int    $product_id     Product ID.
					 * @param array  $products       Array of products.
					 *
					 * @return string
					 */
					'total'              => apply_filters( 'yith_wfbt_total_html', ! is_null( $total_discount ) ? wc_format_sale_price( $total, $total_discount ) : wc_price( $total ), $total, $total_discount, $product_id, $products ),
					/**
					 * APPLY_FILTERS: yith_wfbt_discount_html
					 *
					 * Filters the HTML content for the discount amount in the Frequently Bought Together form.
					 *
					 * @param float $discount       Discounted amount when purchasin the products together.
					 * @param int   $product_id     Product ID.
					 * @param array $products       Array of products.
					 *
					 * @return float
					 */
					'discount'           => apply_filters( 'yith_wfbt_discount_html', $discount, $product_id, $products ),
					'total_discount'     => $total_discount,
					'is_empty'           => 'yes' === $metas['show_unchecked'],
					'show_unchecked'     => 'yes' === $metas['show_unchecked'],
					'popup_button_label' => get_option( 'yith-wfbt-open-popup-button-label', __( 'View options >', 'yith-woocommerce-frequently-bought-together' ) ),
				),
				'',
				YITH_WFBT_DIR . 'templates/'
			);

			wp_reset_postdata();
		}

		/**
		 * Get the first available variation for passed product
		 *
		 * @since  1.3.0
		 * @param WC_Product $product Product instance.
		 * @param integer    $variation_id Variation id to exclude.
		 * @return WC_Product_Variation|false
		 */
		public function get_first_available_variation( $product, $variation_id = 0 ) {

			$variations = $product->get_children();

			if ( is_array( $variations ) ) {
				foreach ( $variations as $variation ) {
					if ( $variation === $variation_id ) {
						continue;
					}
					$product_variation = wc_get_product( $variation );
					if ( $this->can_be_added( $product_variation ) ) {
						return $product_variation;
					}
				}
			}

			return false;
		}

		/**
		 * Check if product can be added to frequently form
		 *
		 * @access public
		 * @since  1.0.5
		 * @param object|int $product Product to check if can be added to form.
		 * @return boolean
		 */
		public function can_be_added( $product ) {

			if ( ! is_object( $product ) ) {
				$product = wc_get_product( intval( $product ) );
			}

			$can = $product && ( ! $product->managing_stock() || $product->is_in_stock() || $product->backorders_allowed() ) && $product->is_purchasable();

			/**
			 * APPLY_FILTERS: yith_wfbt_product_can_be_added
			 *
			 * Filters whether the product can be added to the Frequently Bought Together form.
			 *
			 * @param bool       $can     Whether the product can be added to the form or not.
			 * @param WC_Product $product Product object.
			 *
			 * @return bool
			 */
			return apply_filters( 'yith_wfbt_product_can_be_added', $can, $product );
		}

		/**
		 * Frequently Bought Together Shortcode
		 *
		 * @since  1.0.5
		 * @param array $atts Shortcode attributes.
		 * @return string
		 */
		public function wfbt_shortcode( $atts ) {

			$atts = shortcode_atts(
				array(
					'product_id' => 0,
				),
				$atts
			);

			extract( $atts ); // phpcs:ignore WordPress.PHP.DontExtract

			// include style and scripts.
			$this->enqueue_scripts();

			ob_start();
			$this->add_bought_together_form( intval( $product_id ) );
			return ob_get_clean();
		}


		/**
		 * Register Frequently Bought Together shortcode
		 *
		 * @since  1.0.0
		 * @param mixed $atts Shortcode attributes.
		 * @param null  $content (Optional) Shortcode contents.
		 */
		public function bought_together_shortcode( $atts, $content = null ) {

			extract( // phpcs:ignore WordPress.PHP.DontExtract
				shortcode_atts(
					array(
						'products' => '',
					),
					$atts
				)
			);

			$products = explode( ',', $products );
			$elems    = array();

			// take products to show.
			foreach ( $products as $product_id ) {
				$product     = wc_get_product( $product_id );
				$product_ids = yith_wfbt_get_meta( $product, 'products' );

				if ( ! $product_ids ) {
					continue;
				}

				foreach ( $product_ids as $id ) {
					// add elem only if is not present in array products.
					if ( ! in_array( $id, $products, true ) ) {
						$elems[] = $id;
					}
				}
			}
			// remove duplicate.
			$elems = array_unique( $elems );

			if ( empty( $elems ) ) {
				return;
			}

			$this->enqueue_scripts();

			wc_get_template( 'yith-wfbt-shortcode.php', array( 'products' => $elems ), '', YITH_WFBT_DIR . 'templates/' );
		}

		/**
		 * Refresh form in ajax
		 *
		 * @since  1.3.0
		 * @return void
		 */
		public function refresh_form() {
            //phpcs:disable WordPress.Security.NonceVerification
			if ( ! isset( $_REQUEST['action'] ) || $_REQUEST['action'] !== $this->action_refresh || ! isset( $_REQUEST['product_id'] ) ) {
				die();
			}

			$main_id      = absint( $_REQUEST['product_id'] );
			$variation_id = isset( $_REQUEST['variation_id'] ) ? absint( $_REQUEST['variation_id'] ) : false;
			$group        = ( isset( $_REQUEST['group'] ) && is_array( $_REQUEST['group'] ) ) ? wc_clean( wp_unslash( $_REQUEST['group'] ) ) : array(); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput
			$unchecked    = ( isset( $_REQUEST['unchecked'] ) && is_array( $_REQUEST['unchecked'] ) ) ? wc_clean( wp_unslash( $_REQUEST['unchecked'] ) ) : array(); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput

			$total          = 0;
			$discount       = 0;
			$total_discount = null;
			$checked        = array();
            //phpcs:enable WordPress.Security.NonceVerification

			// get main product.
			$product = wc_get_product( $main_id );
			if ( $product->is_type( 'variation' ) ) {
				$product = wc_get_product( $product->get_parent_id() );
			}

			foreach ( $group as $key => $product_id ) {

				$variation = null;
				if ( (int) $product_id === (int) $main_id && $variation_id ) {
					$variation = wc_get_product( $variation_id );
					if ( $this->can_be_added( $variation ) ) {
						// set new main and new product.
						$main_id    = $variation_id;
						$products[] = $variation;
						$product_id = $variation_id;
						$checked[]  = $variation_id;
						$total     += wc_get_price_to_display( $variation );
						continue;
					}
				}
				$p          = wc_get_product( $product_id );
				$products[] = $p;
				if ( ! in_array( $product_id, $unchecked, true ) ) {
					$checked[] = $product_id;
					$total    += apply_filters( 'yith_wfbt_price_to_display', wc_get_price_to_display( empty( $variation ) ? $p : $variation ), $p, $variation );
				}
			}

			// Calculate discount if main product is selected.
			if ( ! in_array( $main_id, $unchecked, true ) ) {
				$discount = floatval( $this->discount->get_discount_amount_to_display( $main_id, $checked, $total ) );
				if ( $discount ) {
					$total_discount = ( $total - $discount );
				}
			}

			$total_discount = apply_filters( 'yith_wfbt_total_discount', $total_discount, $discount );

			$num_products = count( $group ) - count( $unchecked );

			// set labels.
			$label       = $this->get_label_option( 'button', $num_products );
			$label_total = $this->get_label_option( 'total', $num_products );
			ob_start();

			wc_get_template(
				'yith-wfbt-form.php',
				array(
					'product'            => $product,
					'main_product_id'    => $main_id,
					'products'           => apply_filters( 'yith_wfbt_filter_group_products_front_custom', $products ),
					'unchecked'          => $unchecked,
					'additional_text'    => yith_wfbt_get_meta( $product, 'additional_text' ),
					'label'              => $label,
					'label_total'        => $label_total,
					'title'              => get_option( 'yith-wfbt-form-title', __( 'Frequently Bought Together', 'yith-woocommerce-frequently-bought-together' ) ),
					'total'              => apply_filters( 'yith_wfbt_total_html', ! is_null( $total_discount ) ? wc_format_sale_price( $total, $total_discount ) : wc_price( $total ), $total, $total_discount, $main_id, $products ),
					'discount'           => apply_filters( 'yith_wfbt_discount_html', $discount, $main_id, $products ),
					'total_discount'     => $total_discount,
					'is_empty'           => ! $num_products,
					'show_unchecked'     => false,
					'popup_button_label' => get_option( 'yith-wfbt-open-popup-button-label', __( 'View options >', 'yith-woocommerce-frequently-bought-together' ) ),
				),
				'',
				YITH_WFBT_DIR . 'templates/'
			);

			echo ob_get_clean(); // phpcs:ignore WordPress.Security.EscapeOutput
		}

		/**
		 * Get option based on number of products and type
		 *
		 * @since  1.3.0
		 * @param string  $type The label type.
		 * @param integer $number The label reference number.
		 * @return string
		 */
		public function get_label_option( $type, $number ) {
			if ( 2 > $number ) {
				$option  = 'button' === $type ? 'yith-wfbt-button-single-label' : 'yith-wfbt-total-single-label';
				$default = 'button' === $type ? __( 'Add to Cart', 'yith-woocommerce-frequently-bought-together' ) : __( 'Price', 'yith-woocommerce-frequently-bought-together' );
			} elseif ( 2 === $number ) {
				$option  = 'button' === $type ? 'yith-wfbt-button-double-label' : 'yith-wfbt-total-double-label';
				$default = 'button' === $type ? __( 'Add both to Cart', 'yith-woocommerce-frequently-bought-together' ) : __( 'Price for both', 'yith-woocommerce-frequently-bought-together' );
			} elseif ( 3 === $number ) {
				$option  = 'button' === $type ? 'yith-wfbt-button-three-label' : 'yith-wfbt-total-three-label';
				$default = 'button' === $type ? __( 'Add all three to Cart', 'yith-woocommerce-frequently-bought-together' ) : __( 'Price for all three', 'yith-woocommerce-frequently-bought-together' );
			} else {
				$option  = 'button' === $type ? 'yith-wfbt-button-multi-label' : 'yith-wfbt-total-multi-label';
				$default = 'button' === $type ? __( 'Add all to Cart', 'yith-woocommerce-frequently-bought-together' ) : __( 'Price for all', 'yith-woocommerce-frequently-bought-together' );
			}

			$label = call_user_func( '__', get_option( $option, $default ), 'yith-woocommerce-frequently-bought-together' );
			return $label;
		}


		/**
		 * Load content for variation gallery
		 *
		 * @since  1.4.0
		 */
		public function load_variations_dialog() {
            //phpcs:disable WordPress.Security.NonceVerification
			if ( isset( $_POST['product_id'] ) && 0 !== $_POST['product_id'] ) {
				$product = wc_get_product( wc_clean( wp_unslash( $_POST['product_id'] ) ) );// phpcs:ignore WordPress.Security.ValidatedSanitizedInput
				wc_get_template(
					'yith-wfbt-dialog-variations.php',
					array(
						'single_product' => $product,
						'product_id'     => wc_clean( wp_unslash( $_POST['product_id'] ) ), // phpcs:ignore WordPress.Security.ValidatedSanitizedInput
					),
					'',
					YITH_WFBT_DIR . 'templates/'
				);
			}

			die();
            //phpcs:enable WordPress.Security.NonceVerification
		}

		/**
		 * Prevent conflict issue with YITH WooCommerce Surveys
		 *
		 * @param  mixed $prevent_form YITH WooCommerce Surveys filter default value.
		 * @return bool
		 */
		public function yith_wc_survey_prevent_display_form( $prevent_form ) {
			if ( isset( $_REQUEST['action'] ) && 'yith_wfbt_load_variations_dialog_content' === sanitize_text_field( wp_unslash( $_REQUEST['action'] ) ) ) { // phpcs:ignore WordPress.Security.NonceVerification
				$prevent_form = true;
			}
			return $prevent_form;
		}

		/**
		 * Add variation modal on footer if needed
		 *
		 * @since 2.0.0
		 */
		public function add_variation_modal() {
			?>
			<div id="yith-wfbt-modal" class="modal">
				<a href="#" rel="modal:close"><?php esc_html_e( 'Close', 'yith-woocommerce-frequently-bought-together' ); ?></a>
			</div>
			<?php
		}

        /**
         * Add frequently bought together form in case Woo Blocks are used.
         *
         * @param string     $html Block content.
         * @param array      $pars_block The full block, including name and attributes.
         * @param WP_Block   $block The block instance.
         *
         * @return string
         */
        public function wc_block_display_bought_together_form( $html, $pars_block, $block ) {

            global $post;

            $product_id = $block->context['postId'] ?? $post->ID;

            ob_start();
             '<div class="yith-wcfbt-content alignwide">';
             $this->add_bought_together_form( $product_id, true );
             '</div>';
             $form = ob_get_clean();

             $html = $this->block_position === 'before' ? $form . $html : $html . $form;

            return $html;
        }
	}
}
/**
 * Unique access to instance of YITH_WFBT_Frontend class
 *
 * @since 1.0.0
 * @return YITH_WFBT_Frontend
 */
function YITH_WFBT_Frontend() { // phpcs:ignore WordPress.NamingConventions.ValidFunctionName
	return YITH_WFBT_Frontend::get_instance();
}
