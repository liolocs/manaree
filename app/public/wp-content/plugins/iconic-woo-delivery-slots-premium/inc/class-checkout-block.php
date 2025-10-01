<?php
/**
 * Checkout block integration.
 *
 * @package Iconic_WDS
 */

use Automattic\WooCommerce\StoreApi\Schemas\V1\CheckoutSchema;

/**
 * Class Iconic_WDS_Checkout_Block.
 */
class Iconic_WDS_Checkout_Block {
	/**
	 * Init.
	 */
	public static function run() {
		add_action( 'woocommerce_blocks_loaded', array( __CLASS__, 'on_blocks_loaded' ) );
	}

	/**
	 * On blocks loaded.
	 */
	public static function on_blocks_loaded() {
		self::extend_store_api();
		self::extension_cart_update_callback();
		add_action( 'woocommerce_blocks_checkout_block_registration', array( __CLASS__, 'register_integration' ) );
		add_action( 'woocommerce_store_api_checkout_update_order_from_request', array( __CLASS__, 'save_delivery_slot_information_on_checkout' ), 10, 2 );
	}

	/**
	 * Handle the server side of the cart update (extensionCartUpdate).
	 * This is called when the cart is updated via the REST API.
	 *
	 * @return void
	 */
	public static function extension_cart_update_callback() {
		woocommerce_store_api_register_update_callback(
			array(
				'namespace' => 'iconic-wds',
				'callback'  => function( $data ) {
					global $iconic_wds;

					$data = array(
						'jckwds-delivery-date'     => isset( $data['date'] ) ? $data['date'] : '',
						'jckwds-delivery-date-ymd' => isset( $data['date_ymd'] ) ? $data['date_ymd'] : '',
						'jckwds-delivery-time'     => isset( $data['timeslot'] ) ? $data['timeslot'] : '',
					);

					$iconic_wds->check_fee( $data );
				},
			)
		);
	}

	/**
	 * Register integration.
	 *
	 * @param IntegrationRegistry $integration_registry Integration registry.
	 *
	 * @return void
	 */
	public static function register_integration( $integration_registry ) {
		$integration_registry->register( new Iconic_WDS_Checkout_Block_Integration() );
	}

	/**
	 * Extends the cart schema to include the shipping-workshop value.
	 */
	public static function extend_store_api() {
		$extend = Automattic\WooCommerce\StoreApi\StoreApi::container()->get( Automattic\WooCommerce\StoreApi\Schemas\ExtendSchema::class );
		if ( is_callable( array( $extend, 'register_endpoint_data' ) ) ) {
			$extend->register_endpoint_data(
				array(
					'endpoint'        => CheckoutSchema::IDENTIFIER,
					'namespace'       => 'iconic-wds',
					'schema_callback' => array( __CLASS__, 'extend_checkout_schema' ),
					'schema_type'     => ARRAY_A,
				)
			);
		}
	}

	/**
	 * Extends the checkout schema.
	 *
	 * @return array
	 */
	public static function extend_checkout_schema() {
		return array(
			'date'     => array(
				'description' => __( 'Delivery date', 'jckwds' ),
				'type'        => array( 'string', 'null' ),
				'context'     => array( 'view', 'edit' ),
				'readonly'    => true,
				'optional'    => true,
			),
			'date_ymd' => array(
				'description' => __( 'Delivery date YMD', 'jckwds' ),
				'type'        => array( 'string', 'null' ),
				'context'     => array( 'view', 'edit' ),
				'readonly'    => true,
				'optional'    => true,
			),
			'timeslot' => array(
				'description' => __( 'Timeslot', 'jckwds' ),
				'type'        => array( 'string', 'null' ),
				'context'     => array( 'view', 'edit' ),
				'readonly'    => true,
				'optional'    => true,
			),
		);
	}

	/**
	 * Save delivery slot information on checkout.
	 *
	 * @param \WC_Order        $order   Order.
	 * @param \WP_REST_Request $request Request.
	 *
	 * @return void
	 */
	public static function save_delivery_slot_information_on_checkout( \WC_Order $order, \WP_REST_Request $request ) {
		if ( empty( $request['extensions']['iconic-wds'] ) ) {
			return;
		}

		$request_data = $request['extensions']['iconic-wds'];
		$data         = array(
			'jckwds-delivery-date'     => isset( $request_data['date'] ) ? $request_data['date'] : '',
			'jckwds-delivery-date-ymd' => isset( $request_data['date_ymd'] ) ? $request_data['date_ymd'] : '',
			'jckwds-delivery-time'     => isset( $request_data['timeslot'] ) ? $request_data['timeslot'] : '',
		);

		Iconic_WDS_Order::update_order_meta( $order->get_id(), $data );
	}
}
