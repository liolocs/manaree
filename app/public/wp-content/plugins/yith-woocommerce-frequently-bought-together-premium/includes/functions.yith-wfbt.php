<?php // phpcs:ignore WordPress.Files.FileName.InvalidClassFileName
/**
 * Common functions
 *
 * @author YITH <plugins@yithemes.com>
 * @package YITH\FrequentlyBoughtTogetherPremium
 * @version 1.0.0
 */

if ( ! defined( 'YITH_WFBT' ) ) {
	exit;
} // Exit if accessed directly

if ( ! function_exists( 'yith_wfbt_get_meta' ) ) {
	/**
	 * Get plugin product meta
	 *
	 * @since  1.3.0
	 * @param mixed  $product The product.
	 * @param string $key The key to retrieve.
	 * @return mixed
	 */
	function yith_wfbt_get_meta( $product, $key = '' ) {
		// get product if id is passed.
		if ( ! ( $product instanceof WC_Product ) ) {
			$product = wc_get_product( intval( $product ) );
		}

		if ( ! $product ) {
			return '';
		}

		$metas = yit_get_prop( $product, YITH_WFBT_META, true );
		if ( ! is_array( $metas ) || empty( $metas ) ) {
			$metas = array();
			// search for single meta.
			foreach ( YITH_WFBT()->old_metas as $old_key => $new_key ) {
				$metas[ $new_key ] = yit_get_prop( $product, $old_key, true );
				delete_post_meta( $product->get_id(), $old_key );
			}

			$metas = array_filter( $metas );
			! empty( $metas ) && update_post_meta( $product->get_id(), YITH_WFBT_META, $metas );
		}

		// backward compatibility discount options.
		if ( ! empty( $metas['discount_percentage'] ) || ! empty( $metas['discount_fixed'] ) ) {
			if ( empty( $metas['discount_enabled'] ) ) {
				$metas['discount_enabled'] = 'yes';
			}
			if ( empty( $metas['discount_conditions'] ) ) {
				$metas['discount_conditions'] = 'yes';
			}
		}
		// if a num of visible items is set, set default visibility type to randomly to keep old settings valid.
		if ( ! empty( $metas['num_visible'] ) && empty( $metas['visibility_type'] ) ) {
			$metas['visibility_type'] = 'randomly';
		}

		// merge with default.
		$metas = array_merge(
			array(
				'default_variation'     => '',
				/**
				 * APPLY_FILTERS: yith_wfbt_default_product_type
				 *
				 * Filters the default option for the products type to use in the Frequently Bought Together form.
				 *
				 * @param string $default_product_type Default value for the product type option.
				 *
				 * @return string
				 */
				'products_type'         => apply_filters( 'yith_wfbt_default_product_type', 'custom' ),
				'products'              => array(),
				'visibility_type'       => 'all',
				'num_visible'           => '2',
				'show_unchecked'        => 'no',
				'additional_text'       => '',
				'discount_enabled'      => 'no',
				'discount_type'         => 'percentage',
				'discount_fixed'        => '10',
				'discount_percentage'   => '10',
				'discount_conditions'   => 'no',
				'discount_min_spend'    => '',
				'discount_min_products' => '2',
			),
			$metas
		);

		// retro compatibility with old option prev 1.6.0.
		if ( isset( $metas['use_related'] ) && 'yes' === $metas['use_related'] ) {
			$metas['products_type'] = 'related';
		}

		if ( ! $key ) {
			return $metas;
		}

		return isset( $metas[ $key ] ) ? $metas[ $key ] : '';
	}
}

if ( ! function_exists( 'yith_wfbt_set_meta' ) ) {
	/**
	 * Get plugin product meta
	 *
	 * @since  1.3.0
	 * @param mixed $product The product.
	 * @param array $value The value to set.
	 */
	function yith_wfbt_set_meta( $product, $value = array() ) {
		// get product if id is passed.
		if ( ! ( $product instanceof WC_Product ) ) {
			$product = wc_get_product( intval( $product ) );
		}

		if ( $product && is_array( $value ) ) {
			yit_save_prop( $product, YITH_WFBT_META, $value );
		}
	}
}

if ( ! function_exists( 'yith_wfbt_delete_meta' ) ) {
	/**
	 * Get plugin product meta
	 *
	 * @since  1.3.0
	 * @param mixed $product The product.
	 */
	function yith_wfbt_delete_meta( $product ) {
		// get product if id is passed.
		if ( ! ( $product instanceof WC_Product ) ) {
			$product = wc_get_product( intval( $product ) );
		}

		if ( $product ) {
			yit_delete_prop( $product, YITH_WFBT_META );
		}
	}
}

if ( ! function_exists( 'yith_wfbt_discount_message' ) ) {
	/**
	 * Build message based on discount passed
	 *
	 * @since  1.3.0
	 * @param array $discount An array of discount data.
	 * @return string
	 */
	function yith_wfbt_discount_message( $discount ) {

		if ( $discount['min_spend'] && $discount['min_products'] ) {
			// translators: %s: //TODO: short description.
			$message = sprintf( __( 'Spend at least %1$s for %2$s or more products of this group and get a %3$s off.', 'yith-woocommerce-frequently-bought-together' ), $discount['min_spend'], $discount['min_products'], $discount['amount'] );
		} else {
			// translators: %s: //TODO: short description.
			$message = sprintf( __( 'Purchase %1$s or more products and get a %2$s off.', 'yith-woocommerce-frequently-bought-together' ), array( $discount['min_products'], $discount['amount'] ) );
		}

		/**
		 * APPLY_FILTERS: yith_wfbt_discount_message
		 *
		 * Filters the discount message.
		 *
		 * @param string $message  Discount message.
		 * @param array  $discount An array of discount data.
		 *
		 * @return string
		 */
		return apply_filters( 'yith_wfbt_discount_message', $message, $discount );
	}
}

if ( ! function_exists( 'yith_wfbt_discount_code_validation' ) ) {
	/**
	 * Validate a discount code
	 *
	 * @since  1.3.4
	 * @param string $code The discount code.
	 * @return string
	 */
	function yith_wfbt_discount_code_validation( $code ) {

		$code = strtolower( trim( $code ) );
		$code = str_replace( ' ', '-', $code );
		$code = preg_replace( '/[^0-9a-z-]/', '', $code );

		return $code;
	}
}

if ( ! function_exists( 'yith_wfbt_get_proteo_default' ) ) {
	/**
	 * Filter option default value if Proteo theme is active
	 *
	 * @since  1.5.1
	 * @param string  $key The option key.
	 * @param mixed   $default (Optional) The option default value.
	 * @param boolean $force_default (Optional) Force default value or not.
	 * @return string
	 */
	function yith_wfbt_get_proteo_default( $key, $default = '', $force_default = false ) {

		// get value from DB if requested and return if not empty.
		if ( ! $force_default ) {
			$value = get_option( $key, $default );
		}

		if ( ! empty( $value ) ) {
			return $value;
		}

		if ( ! defined( 'YITH_PROTEO_VERSION' ) ) {
			return $default;
		}

		switch ( $key ) {
			case 'yith-wfbt-button-color':
				$default = get_theme_mod( 'yith_proteo_button_style_1_bg_color', '#448a85' );
				break;
			case 'yith-wfbt-button-color-hover':
				$default = get_theme_mod( 'yith_proteo_button_style_1_bg_color_hover', yith_proteo_adjust_brightness( get_theme_mod( 'yith_proteo_main_color_shade', '#448a85' ), 0.2 ) );
				break;
			case 'yith-wfbt-button-text-color':
				$default = get_theme_mod( 'yith_proteo_button_style_1_text_color', '#ffffff' );
				break;
			case 'yith-wfbt-button-text-color-hover':
				$default = get_theme_mod( 'yith_proteo_button_style_1_text_color_hover', '#ffffff' );
				break;

		}

		return $default;
	}
}
