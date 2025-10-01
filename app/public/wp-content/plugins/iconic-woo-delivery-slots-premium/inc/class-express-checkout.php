<?php
/**
 * Iconic_WDS_Express_Checkout.
 *
 * Compatibility with Google Pay/Apple Pay express payments.
 *
 * @package Iconic_WDS
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( class_exists( 'Iconic_WDS_Express_Checkout' ) ) {
	return;
}

/**
 * Iconic_WDS_Express_Checkout.
 *
 * @class    Iconic_WDS_Express_Checkout.
 */
class Iconic_WDS_Express_Checkout {
	/**
	 * Run.
	 */
	public static function run() {
		add_action( 'wp', array( __CLASS__, 'disable_validation_for_stripe_express_create_order' ) );
	}

	/**
	 * Disable our validation for Stripe express checkout.
	 *
	 * @return void
	 */
	public static function disable_validation_for_stripe_express_create_order() {
		$wc_ajax = filter_input( INPUT_GET, 'wc-ajax', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
		if ( 'wc_stripe_create_order' !== $wc_ajax ) {
			return;
		}

		remove_action( 'woocommerce_after_checkout_validation', array( 'Iconic_WDS_Checkout', 'catch_shipping' ), 10 );
		remove_action( 'woocommerce_checkout_process', array( 'Iconic_WDS_Checkout', 'checkout_process' ), 10 );
		remove_action( 'woocommerce_checkout_fields', array( 'Iconic_WDS_Checkout', 'checkout_fields' ), 10 );
	}
}
