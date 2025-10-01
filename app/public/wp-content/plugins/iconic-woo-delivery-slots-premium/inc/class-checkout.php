<?php
/**
 * WDS checkout class.
 *
 * @package Iconic_WDS
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * WDS checkout class.
 */
class Iconic_WDS_Checkout {
	/**
	 * Run.
	 */
	public static function run() {
		add_filter( 'woocommerce_checkout_fields', array( __CLASS__, 'checkout_fields' ), 10, 1 );
		add_filter( 'woocommerce_checkout_posted_data', array( __CLASS__, 'checkout_posted_data' ) );
		add_action( 'woocommerce_after_checkout_validation', array( __CLASS__, 'catch_shipping' ), 10, 2 );
		add_action( 'woocommerce_checkout_process', array( __CLASS__, 'checkout_process' ), 10 );
		add_action( 'woocommerce_checkout_update_order_meta', array( 'Iconic_WDS_Order', 'update_order_meta' ), 10, 2 );
	}

	/**
	 * Register checkout fields for processing (not display).
	 *
	 * @param array $fields Checkout Fields.
	 *
	 * @return array
	 */
	public static function checkout_fields( $fields ) {
		global $iconic_wds_dates;

		$checkout_fields_data = $iconic_wds_dates->get_checkout_fields_data();

		if ( empty( $checkout_fields_data ) ) {
			return $fields;
		}

		$fields['jckwds'] = array();

		foreach ( $checkout_fields_data as $key => $data ) {
			$fields['jckwds'][ $key ] = array(
				'type'     => 'text',
				'label'    => $data['field_args']['label'],
				'required' => $data['field_args']['required'],
			);
		}

		return $fields;
	}

	/**
	 * Check if shipping is not set when it should be during checkout process.
	 * Prevents orders with empty delivery date.
	 *
	 * @param array    $data   Posts data.
	 * @param WP_Error $errors Errors object.
	 */
	public static function catch_shipping( $data, $errors ) {
		// We don't need to check our virtual setting (Iconic_WDS_Helpers::needs_shipping()),
		// as virtual products don't actually need a shipping method. We only want to check if it
		// *really* needs shipping applied.
		if ( ! WC()->cart->needs_shipping() ) {
			return;
		}

		// If shipping error already exists, do nothing.
		// If shipping method is not empty, do nothing.
		if ( ! empty( $errors->errors['shipping'] ) || ! empty( $data['shipping_method'] ) ) {
			return;
		}

		// Otherwise, throw error and prompt update checkout trigger.
		wc_add_notice(
			__( 'Please select a shipping method.', 'jckwds' ),
			'error',
			array(
				'iconic-wds-update-checkout' => true,
			)
		);
	}

	/**
	 * Remove fields if they are hidden based on shipping method.
	 *
	 * @param array $data Posted Data.
	 *
	 * @return mixed
	 */
	public static function checkout_posted_data( $data ) {
		$fields_hidden = (bool) Iconic_WDS_Helpers::get_filtered_input( 'iconic-wds-fields-hidden' );

		if ( $fields_hidden ) {
			unset( $data['jckwds-delivery-date'], $data['jckwds-delivery-date-ymd'], $data['jckwds-delivery-time'] );

			return $data;
		}

		// Remove 0 value so it is seen as empty.
		$data['jckwds-delivery-time'] = empty( $data['jckwds-delivery-time'] ) ? '' : $data['jckwds-delivery-time'];

		return $data;
	}

	/**
	 * Validate checkout fields.
	 */
	public static function checkout_process() {
		$date_fields_hidden = filter_input( INPUT_POST, 'iconic-wds-fields-hidden', FILTER_SANITIZE_NUMBER_INT );
		$ymd                = filter_input( INPUT_POST, 'jckwds-delivery-date-ymd', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
		$time               = filter_input( INPUT_POST, 'jckwds-delivery-time', FILTER_SANITIZE_FULL_SPECIAL_CHARS );

		if ( empty( $ymd ) || false === $date_fields_hidden || ! empty( $date_fields_hidden ) ) {
			return;
		}

		global $iconic_wds, $woocommerce, $iconic_wds_dates;

		$expires = strtotime( '+10 minutes', time() );

		$max_calulation_method    = $iconic_wds->settings['general_setup_max_order_calculation_method'];
		$orders_remaining_for_day = $iconic_wds_dates->get_orders_remaining_for_day( $ymd );
		$cart_count               = $iconic_wds_dates->cart->get_cart_contents_count();

		if ( $ymd ) {
			// Ensure number of products in the cart are under the day's limit (max order).
			if ( 'products' === $max_calulation_method && Iconic_WDS_Helpers::get_cart_count() > $iconic_wds_dates->get_orders_remaining_for_day( $ymd ) ) {
				wc_add_notice(
					/* Translators: Availble quantity  */
					sprintf( __( 'Sorry, we cannot accept quantity of more than %d for the selected date. Please change the date or reduce the purchase quantity.', 'jckwds' ), $orders_remaining_for_day ),
					'error'
				);

				return;
			}

			// Check if date is booked up.
			if ( 'orders' === $max_calulation_method && ! $orders_remaining_for_day ) {
				wc_add_notice(
					__( 'Sorry, the selected date is no longer available.', 'jckwds' ),
					'error',
					array(
						'iconic-wds-clear-date' => true,
						'iconic-wds-clear-time' => true,
					)
				);
			}
		}

		// Check if selected date is alllowed based on same & next day cutoff time.
		$date_formatted = date_i18n( 'D, jS M', strtotime( $ymd ) );
		$same_day_date  = $iconic_wds_dates->get_same_day_date( 'D, jS M' );
		$next_day_date  = $iconic_wds_dates->get_next_day_date( 'D, jS M' );

		if (
			( $date_formatted === $same_day_date && $iconic_wds_dates->is_same_day_allowed() === $date_formatted )
			||
			( $date_formatted === $next_day_date && $iconic_wds_dates->is_next_day_allowed() === $date_formatted )
		) {
			wc_add_notice(
				__( 'Sorry, the selected date is no longer available.', 'jckwds' ),
				'error',
				array(
					'iconic-wds-clear-time' => true,
				)
			);
		}

		// These conditions only apply if time slots are enabled and selected.
		if ( empty( $time ) || ! $iconic_wds->settings['timesettings_timesettings_setup_enable'] ) {
			// Add 10 minute reservation to prevent double booking.
			$iconic_wds->add_reservation(
				array(
					'datetimeid' => $ymd,
					'date'       => Iconic_WDS_Helpers::convert_date_for_database( $ymd ),
					'processed'  => 0,
					'expires'    => $expires,
				)
			);

			return;
		}

		// Check if date has any slots available.
		$available_slots = $iconic_wds_dates->slots_available_on_date( $ymd );

		if ( empty( $available_slots ) ) {
			wc_add_notice(
				__( 'Sorry, there are no longer any slots available on the selected date.', 'jckwds' ),
				'error',
				array(
					'iconic-wds-clear-date' => true,
					'iconic-wds-clear-time' => true,
				)
			);
		} else {
			// Check if the time slot is still available on the selected date.
			$available_slot_values = wp_list_pluck( $available_slots, 'value' );

			if ( $time && ! in_array( $time, $available_slot_values, true ) ) {
				wc_add_notice(
					__( 'Sorry, that time slot is no longer available.', 'jckwds' ),
					'error',
					array(
						'iconic-wds-clear-time' => true,
					)
				);
			} else {
				$timeslot_id = $iconic_wds->extract_timeslot_id_from_option_value( $time );
				$slot_id     = sprintf( '%s_%s', $ymd, $timeslot_id );
				$timeslot    = self::search_by_slot_id( $slot_id, $available_slots );
				$cart_count  = $iconic_wds_dates->cart->get_cart_contents_count();

				// Ensure number of products in this cart are under the slot's limit (max order).
				if ( 'products' === $max_calulation_method && is_numeric( $timeslot['slots_available_count'] ) && Iconic_WDS_Helpers::get_cart_count() > $timeslot['slots_available_count'] ) {
					wc_add_notice(
						/* Translators: Availble quantity */
						sprintf( __( 'Sorry, we cannot accept quantity of more than %d for the selected time slot. Please change the date/time slot or reduce the purchase quantity.', 'jckwds' ), $timeslot['slots_available_count'] ),
						'error'
					);

					return;
				}

				if ( $timeslot ) {
					// Add 10 minute reservation to prevent double booking.
					$iconic_wds->add_reservation(
						array(
							'datetimeid' => $slot_id,
							'date'       => Iconic_WDS_Helpers::convert_date_for_database( $ymd ),
							'starttime'  => $timeslot['timefrom']['stripped'],
							'endtime'    => $timeslot['timeto']['stripped'],
							'processed'  => 0,
							'expires'    => $expires,
							'asap'       => isset( $timeslot['asap'] ) ? $timeslot['asap'] : false,
						)
					);

					return true;
				}
			}
		}
	}


	/**
	 * Whether to display delivery slots for virtual products which
	 * normally don't require shipping.
	 *
	 * @return bool
	 */
	public static function display_for_virtual_products() {
		global $jckwds;

		return (bool) apply_filters( 'iconic_wds_display_for_virtual', $jckwds->settings['general_setup_display_for_virtual'] );
	}

	/**
	 * Check if date/time fields should be active
	 * for the current shipping method
	 *
	 * @return bool
	 */
	public static function is_delivery_slots_allowed_for_shipping_method( $shipping_method ) {
		$allowed_methods = Iconic_WDS_Settings::get_shipping_methods();

		if ( $allowed_methods && ! empty( $allowed_methods ) ) {
			if ( in_array( 'any', $allowed_methods, true ) ) {
				return true;
			}

			foreach ( $allowed_methods as $allowed_method ) {
				$allowed_method = str_replace( 'wc_shipping_', '', $allowed_method );

				if ( $shipping_method === $allowed_method ) {
					return true;
				}
			}
		}

		return false;
	}

	/**
	 * Search timeslot data by slot ID.
	 *
	 * @param string $slot_id         Timeslot ID, example: 20200903_0.
	 * @param array  $available_slots Available slots.
	 *
	 * @return array|bool
	 */
	public static function search_by_slot_id( $slot_id, $available_slots ) {
		foreach ( $available_slots as $loop_slot ) {
			if ( $slot_id === $loop_slot['slot_id'] ) {
				return $loop_slot;
			}
		}

		return false;
	}
}
