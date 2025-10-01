<?php
/**
 * WDS Compat class for WooCommerce PayPal Payments.
 *
 * @package Iconic_WDS
 */

defined( 'ABSPATH' ) || exit;

/**
 * Compatiblity with WooCommerce PayPal Payments.
 * https://wordpress.org/plugins/woocommerce-paypal-payments/
 *
 * @class    Iconic_WDS_Compat_Woo_Paypal_Payments
 */
class Iconic_WDS_Compat_Woo_Paypal_Payments {
	/**
	 * Run.
	 */
	public static function run() {
		add_action( 'plugins_loaded', array( __CLASS__, 'hooks' ) );
	}

	/**
	 * Hooks.
	 */
	public static function hooks() {
		if ( ! Iconic_WDS_Core_Helpers::is_plugin_active( 'woocommerce-paypal-payments/woocommerce-paypal-payments.php' ) ) {
			return;
		}

		remove_action( 'woocommerce_after_checkout_validation', array( 'Iconic_WDS_Checkout', 'catch_shipping' ), 10 );
	}
}
