<?php // phpcs:ignore WordPress.Files.FileName.InvalidClassFileName
/**
 * Frontend Discount class
 *
 * @author  YITH <plugins@yithemes.com>
 * @package YITH\FrequentlyBoughtTogetherPremium
 * @version 1.3.0
 */

if ( ! defined( 'YITH_WFBT' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( 'YITH_WFBT_Discount' ) ) {
	/**
	 * Frontend class.
	 * The class manage the discount feature.
	 *
	 * @since 1.3.0
	 */
	class YITH_WFBT_Discount {

		/**
		 * The coupon code
		 *
		 * @since 1.4.0
		 * @var string
		 */
		protected $coupon_code = '';

		/**
		 * Discounts data
		 *
		 * @since  1.3.0
		 * @var array
		 */
		protected $data_session = array();

		/**
		 * Constructor
		 *
		 * @access public
		 * @since  1.3.0
		 */
		public function __construct() {

			add_action( 'wp_loaded', array( $this, 'load_session' ), 0 );
			add_action( 'yith_wfbt_group_added_to_cart', array( $this, 'save_data' ), 10, 2 );
			// filter coupon data.
			add_filter( 'woocommerce_get_shop_coupon_data', array( $this, 'filter_coupon_data' ), 10, 2 );
			// add coupon.
			add_action( 'yith_wfbt_group_added_to_cart', array( $this, 'add_coupon' ), 99 );
			// handle calculate totals.
			add_filter( 'woocommerce_after_calculate_totals', array( $this, 'check_cart' ), 99, 1 );
			// filter coupon message.
			add_filter( 'woocommerce_coupon_message', array( $this, 'remove_coupon_messages' ), 10, 3 );
			add_filter( 'woocommerce_coupon_error', array( $this, 'remove_coupon_messages' ), 10, 3 );
			// filter coupon html.
			add_filter( 'woocommerce_cart_totals_coupon_html', array( $this, 'totals_coupon_html' ), 10, 3 );

			// if coupon is removed, delete session data.
			add_action( 'woocommerce_removed_coupon', array( $this, 'delete_session_data' ), 10, 1 );
			add_action( 'woocommerce_cart_emptied', array( $this, 'clear_data_session' ), 10, 1 );
		}

		/**
		 * Load session
		 *
		 * @since  1.3.0
		 */
		public function load_session() {

			if ( is_null( WC()->session ) ) {
				return;
			}

			if ( empty( $this->data_session ) ) {
				$this->data_session = WC()->session->get( 'yith_wfbt_data', array() );
			}
		}

		/**
		 * Get data session
		 *
		 * @since  1.3.0
		 * @return array
		 */
		public function get_data_session() {
			return $this->data_session;
		}

		/**
		 * Set data session
		 *
		 * @since  1.3.0
		 * @param mixed $value The data session to set.
		 */
		public function set_data_session( $value = null ) {
			if ( ! is_null( WC()->session ) ) {
				if ( is_null( $value ) ) {
					$value = $this->data_session;
				}
				WC()->session->set( 'yith_wfbt_data', $value );
			}
		}

		/**
		 * Clear the session data
		 *
		 * @since  1.8.3
		 * @return void
		 */
		public function clear_data_session() {
			WC()->session->set( 'yith_wfbt_data', array() );
		}

		/**
		 * Save single group data on session
		 *
		 * @since  1.3.0
		 * @param array   $added        Added products.
		 * @param integer $main_product The main product ID.
		 * @return void
		 */
		public function save_data( $added, $main_product ) {

			if ( in_array( $main_product, $added, true ) ) {
				$amount = $this->get_discount_amount( $main_product, $added );
				if ( false !== $amount ) {

					$this->data_session[] = array(
						'main'     => $main_product,
						'products' => $added,
						'discount' => $amount,
					);
				}
			}

			$this->set_data_session();
		}

		/**
		 * Check if a discount is valid for a product group
		 *
		 * @since  1.3.0
		 * @param integer|WC_Product $product  Main product instance or ID.
		 * @param array              $added    Product added.
		 * @param array              $data     (Optional) Data array.
		 * @param WC_Cart            $cart     (Optional) WC_Cart instance.
		 * @param float              $subtotal (Optional) A fixed subtotal to use in calculate discount.
		 * @return boolean
		 */
		public function is_discount_valid( $product, $added, $data = array(), $cart = null, $subtotal = 0.00 ) {

			if ( ! ( $product instanceof WC_Product ) ) {
				$product = wc_get_product( intval( $product ) );
			}

			if ( empty( $data ) ) {
				$data = $this->get_product_data( $product );
			}

			// If data is empty or discount is disabled.
			if ( empty( $data ) || 'no' === $data['discount_enabled'] ) {
				return false;
			}

			$discount_perc = isset( $data['discount_percentage'] ) ? intval( $data['discount_percentage'] ) : 0;

			// check for type.
			if ( ! in_array( $data['discount_type'], array( 'percentage', 'fixed' ), true ) ) {
				return false;
			} elseif ( 'fixed' === $data['discount_type'] && ! floatval( $data['discount_fixed'] ) ) {
				return false;
			} elseif ( 'percentage' === $data['discount_type'] && ( ! $discount_perc || $discount_perc > 100 || $discount_perc < 0 ) ) {
				return false;
			} elseif ( 'yes' === $data['discount_conditions'] ) {
				$added_exclude_variable_products = $added;
				$added = array();
				foreach ( $added_exclude_variable_products as $added_item_key => $added_product_id ) {
					$added_product = wc_get_product( $added_product_id );
					if ( $added_product && ! $added_product->is_type( 'variable' ) ) {
						$added[] = $added_product_id;
					}
				}
				if ( intval( $data['discount_min_products'] ) < 2 || intval( $data['discount_min_products'] ) > count( $added ) ) {
					return false;
				} elseif ( $data['discount_min_spend'] ) {
					if ( ! $subtotal ) {
						if ( is_null( $cart ) ) {
							$cart = WC()->cart;
						}
						$cart_contents = $cart->get_cart_contents();

						foreach ( $added as $item_key => $product_id ) {
							/**
							 * APPLY_FILTERS: yith_wfbt_force_not_apply_discount_specific_product
							 *
							 * Filters whether to force not applying the discount for specific products.
							 *
							 * @param bool               $force      Whether to force not applying the discount for specific products or not.
							 * @param int                $product_id Product ID.
							 * @param YITH_WFBT_Discount $discount   Discount object.
							 *
							 * @return bool
							 */
							if ( apply_filters( 'yith_wfbt_force_not_apply_discount_specific_product', false, $product_id, $this ) || ! isset( $cart_contents[ $item_key ] ) ) {
								continue;
							}

							$item_subtotal                             = $cart_contents[ $item_key ]['line_subtotal'];
							wc_prices_include_tax() && $item_subtotal += $cart_contents[ $item_key ]['line_subtotal_tax'];

							$subtotal += $item_subtotal / ( $cart_contents[ $item_key ]['quantity'] );
						}
					}

					return $subtotal >= floatval( $data['discount_min_spend'] );
				}
			}

			return true;
		}

		/**
		 * Get discount amount
		 *
		 * @since  1.3.0
		 * @param integer|WC_Product $product    Main product instance or ID.
		 * @param array              $added      Product added.
		 * @param WC_Cart            $cart       (Optional) WC_Cart instance.
		 * @param mixed              $deprecated A fixed subtotal to use in calculate discount. Deprecated.
		 * @return mixed
		 */
		public function get_discount_amount( $product, $added, $cart = null, $deprecated = '' ) {

			if ( ! ( $product instanceof WC_Product ) ) {
				$product = wc_get_product( intval( $product ) );
			}

			if ( is_null( $cart ) ) {
				$cart = WC()->cart;
			}

			$data     = $this->get_product_data( $product );
			$discount = 0;
			$subtotal = $deprecated || 0;

			if ( $this->is_discount_valid( $product, $added, $data, $cart ) ) {

				$cart_contents = $cart->get_cart_contents();
				foreach ( $added as $item_key => $product_id ) {
					if ( apply_filters( 'yith_wfbt_force_not_apply_discount_specific_product', false, $product_id, $this ) || ! isset( $cart_contents[ $item_key ] ) ) {
						continue;
					}

					$item_subtotal = $cart_contents[ $item_key ]['line_subtotal'];
					if ( wc_prices_include_tax() ) {
						$item_subtotal += $cart_contents[ $item_key ]['line_subtotal_tax'];
					}

					$subtotal += $item_subtotal / ( $cart_contents[ $item_key ]['quantity'] );
				}

				if ( ! empty( $subtotal ) ) {
					if ( 'fixed' === $data['discount_type'] ) {
						$f        = floatval( $data['discount_fixed'] );
						$discount = ( $subtotal > $f ) ? $f : $subtotal;
					} else {
						$discount = ( $subtotal * ( intval( $data['discount_percentage'] ) / 100 ) );
					}
				}
			}

			return $discount ? wc_format_decimal( $discount, wc_get_price_decimals() ) : 0;
		}

		/**
		 * Get discount amount to display based on given product and total.
		 *
		 * @since  1.3.0
		 * @param integer|WC_Product $product  Main product instance or ID.
		 * @param array              $added    Product added.
		 * @param float              $subtotal A fixed subtotal to use in calculate discount.
		 * @return mixed
		 */
		public function get_discount_amount_to_display( $product, $added, $subtotal ) {

			if ( ! ( $product instanceof WC_Product ) ) {
				$product = wc_get_product( intval( $product ) );
			}

			$data     = $this->get_product_data( $product );
			$discount = 0;

			if ( $this->is_discount_valid( $product, $added, $data, null, $subtotal ) ) {
				if ( 'fixed' === $data['discount_type'] ) {
					// Use wc_get_price_to_display to match the WooCommerce tax settings.
					$f        = wc_get_price_to_display( $product, array( 'price' => floatval( $data['discount_fixed'] ) ) );
					$discount = ( $subtotal > $f ) ? $f : $subtotal;
				} else {
					$discount = ( $subtotal * ( intval( $data['discount_percentage'] ) / 100 ) );
				}
			}

			$discount = $discount ? wc_format_decimal( $discount, wc_get_price_decimals() ) : 0;

			/**
			 * APPLY_FILTERS: yith_wfbt_get_discount_amount_to_display
			 *
			 * Filters the discount amount to display in the product page.
			 *
			 * @param float      $discount Discounted amount.
			 * @param WC_Product $product  Product object.
			 * @param array      $added    Array of products.
			 * @param float      $subtotal Subtotal.
			 *
			 * @return float
			 */
			return apply_filters( 'yith_wfbt_get_discount_amount_to_display', $discount, $product, $added, $subtotal );
		}

		/**
		 * Filter coupon data for discount
		 *
		 * @since  1.3.0
		 * @param mixed  $data The coupon data.
		 * @param string $code The coupon code.
		 * @return mixed
		 */
		public function filter_coupon_data( $data, $code ) {

			$my_code = $this->get_coupon_code();

			if ( $code !== $my_code ) {
				return $data;
			}

			$amount = 0;
			foreach ( $this->data_session as $value ) {
				$amount += $value['discount'];
			}

			if ( $amount ) {
				$data = array(
					'code'           => $my_code,
					'amount'         => $amount,
					'discount_type'  => 'fixed_cart',
					/**
					 * APPLY_FILTERS: yith-wfbt-coupon-individual-use
					 *
					 * Filters whether to allow individual uses when using the coupon.
					 *
					 * @param bool $allow_individual_use Whether to allow individual uses when using the coupon or not.
					 *
					 * @return bool
					 */
					'individual_use' => apply_filters( 'yith-wfbt-coupon-individual-use', true ), // phpcs:ignore WordPress.NamingConventions.ValidHookName
					'usage_limit'    => 1,
				);
			}

			return $data;
		}

		/**
		 * Add coupon with discount to cart
		 *
		 * @since  1.3.0
		 */
		public function add_coupon() {
			/**
			 * APPLY_FILTERS: yith_wcfbt_add_coupon
			 *
			 * Filters whether to add the coupon with the discount to the cart.
			 *
			 * @param bool               $add_coupon Whether to add the coupon to the cart or not.
			 * @param YITH_WFBT_Discount $discount   Discount object.
			 *
			 * @return bool
			 */
			if ( apply_filters( 'yith_wcfbt_add_coupon', true, $this ) && ! empty( $this->data_session ) ) {
				$my_code = $this->get_coupon_code();
				( $my_code && ! WC()->cart->has_discount( $my_code ) ) && WC()->cart->add_discount( $my_code );
			}
		}

		/**
		 * Check cart content on cart update/remove item
		 *
		 * @since  1.3.0
		 * @param WC_Cart $cart WC_Cart instance.
		 * @return void
		 */
		public function check_cart( $cart = null ) {

			if ( empty( $this->data_session ) ) {
				return;
			}

			if ( is_null( $cart ) ) {
				$cart = WC()->cart;
			}

			if ( $cart->is_empty() ) {
				$this->set_data_session( array() ); // empty session.
			};

			$items            = $cart->get_cart_contents();
			$products_in_cart = $this->get_products_in_cart( $items );

			foreach ( $this->data_session as $key => $data ) {
				$current = array_intersect( $data['products'], $products_in_cart );

				if ( in_array( $data['main'], $current, true ) ) {
					$amount = $this->get_discount_amount( $data['main'], $current, $cart );
					if ( false !== $amount ) {
						$this->data_session[ $key ]['discount'] = $amount; // set new amount.
						foreach ( $current as $c ) {
							$k = array_search( $c, $products_in_cart, true );
							unset( $products_in_cart[ $k ] );
						}  // remove items
						continue;
					}
				}

				// if you are here, the discount is no more valid. Remove it!
				unset( $this->data_session[ $key ] );
			}

			// Make sure cart has coupon discount and then update data session.
			$this->add_coupon();
			$this->set_data_session();
		}

		/**
		 * Get an array of products in cart
		 *
		 * @since  1.3.0
		 * @param array $items Cart items.
		 * @return array
		 */
		public function get_products_in_cart( $items ) {
			$r = array();
			foreach ( $items as $item_key => $item ) {
				$qty = ! empty( $item['quantity'] ) ? absint( $item['quantity'] ) : 1;
				do {
					$r[] = $item['variation_id'] ? $item['variation_id'] : $item['product_id'];
				} while ( --$qty > 0 );
			}

			return $r;
		}

		/**
		 * Get product data
		 *
		 * @since  1.3.0
		 * @param WC_Product $product Product instance or ID.
		 * @return array
		 */
		public function get_product_data( $product ) {
			if ( $product instanceof WC_Product && $product->is_type( 'variation' ) ) {
				$product = wc_get_product( $product->get_parent_id() );
			}

			$data = yith_wfbt_get_meta( $product );
			// Fix issue with discount DB value different from float.
			if ( ! empty( $data['discount_fixed'] ) && ! is_float( $data['discount_fixed'] ) ) {
				$data['discount_fixed'] = str_replace( wc_get_price_decimal_separator(), '.', $data['discount_fixed'] );
				// Convert multiple dots to just one.
				$data['discount_fixed'] = preg_replace( '/\.(?![^.]+$)|[^0-9.-]/', '', wc_clean( $data['discount_fixed'] ) );
			}

			return $data;
		}

		/**
		 * Remove WC coupon messages
		 *
		 * @since  1.4.0
		 * @param string    $msg      Coupon message.
		 * @param integer   $msg_code Message code.
		 * @param WC_Coupon $coupon   WC_Coupon instance.
		 * @return string
		 */
		public function remove_coupon_messages( $msg, $msg_code, $coupon ) {
			if ( isset( $coupon ) && $coupon->get_code() === $this->get_coupon_code() ) {
				return '';
			}

			return $msg;
		}

		/**
		 * Get discount code
		 *
		 * @since  1.4.0
		 * @return string
		 */
		public function get_coupon_code() {
			if ( empty( $this->coupon_code ) ) {
				$this->coupon_code = yith_wfbt_discount_code_validation( get_option( 'yith-wfbt-discount-name', 'frequently-bought-discount' ) );
				// discount code cannot be empty, if it is, set as default.
				if ( empty( $this->coupon_code ) ) {
					$this->coupon_code = 'frequently-bought-discount';
				}
			}

			/**
			 * APPLY_FILTERS: yith_wcfbt_coupon_code
			 *
			 * Filters the coupon code to apply the discount.
			 *
			 * @param string $coupon_code Coupon code.
			 *
			 * @return string
			 */
			return apply_filters( 'yith_wcfbt_coupon_code', $this->coupon_code );
		}

		/**
		 * Customized template for plugin coupon code
		 *
		 * @since  1.4.0
		 * @param string    $coupon_html          The coupon html.
		 * @param WC_Coupon $coupon               WC_Coupon instance.
		 * @param string    $discount_amount_html Discount amount html.
		 * @return string
		 */
		public function totals_coupon_html( $coupon_html, $coupon, $discount_amount_html ) {
			/**
			 * APPLY_FILTERS: yith_wfbt_disable_remove_coupon
			 *
			 * Filters whether to disable the possibility to remove the coupon from the cart.
			 *
			 * @param bool $disable_remove_coupon Whether to disable the possibility to remove the coupon from the cart or not.
			 *
			 * @return bool
			 */
			if ( $coupon->get_code() === $this->get_coupon_code() && apply_filters( 'yith_wfbt_disable_remove_coupon', true ) ) {
				return $discount_amount_html;
			}

			return $coupon_html;
		}

		/**
		 * Delete session data on remove coupon
		 *
		 * @since  1.8.3
		 * @param string $coupon_code The coupon code.
		 * @return void
		 */
		public function delete_session_data( $coupon_code ) {
			if ( $coupon_code === $this->get_coupon_code() ) {
				$this->clear_data_session();
			}
		}
	}
}
