<?php
/**
 * Admin View: Product Settings
 *
 * @author YITH <plugins@yithemes.com>
 * @package YITH\FrequentlyBoughtTogetherPremium
 */

if ( ! defined( 'YITH_WFBT' ) ) {
	exit;
} // Exit if accessed directly

?>

<div id="yith_wfbt_data_option" class="panel woocommerce_options_panel">

	<?php foreach ( $options as $group => $fields ) : ?>
		<div class="options_group <?php echo ! empty( $fields['class'] ) ? esc_attr( $fields['class'] ) : ''; ?>">
			<?php
			foreach ( $fields as $field_key => $field ) :
				/**
				 * DO_ACTION: yith_wfbt_product_panel_before_field_$field_key
				 *
				 * Allows to render some content before the field in the product panel options.
				 * <code>$field_key</code> will be replaced with the key for each field.
				 *
				 * @param array $field Field to show.
				 */
				do_action( "yith_wfbt_product_panel_before_field_{$field_key}", $field );

				if ( ! is_array( $field ) ) {
					continue;
				}
				// build data if any.
				$data = '';
				if ( isset( $field['data'] ) ) {
					foreach ( $field['data'] as $key => $value ) {
						$data .= ' data-' . $key . '="' . $value . '"';
					}
				}
				// build attr if any.
				$attr = '';
				if ( isset( $field['attr'] ) ) {
					foreach ( $field['attr'] as $key => $value ) {
						$attr .= ' ' . $key . '="' . $value . '"';
					}
				}

				$desc  = ! empty( $field['desc'] ) ? esc_attr( $field['desc'] ) : '';
				$class = ! empty( $field['class'] ) ? esc_attr( implode( ' ', $field['class'] ) ) : '';

				$value = $metas[ $field_key ];
				if ( ! empty( $field['class'] ) && in_array( 'wc_input_price', $field['class'], true ) ) {
					$value = wc_format_localized_price( $metas[ $field_key ] );
				}
				?>
				<p class="form-field" <?php echo $data; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
					<label
						for="<?php echo esc_attr( $field['name'] ); ?>"><?php echo esc_html( $field['label'] ); ?></label>
					<?php
					switch ( $field['type'] ) :

						case 'variation_select':
							?>
							<select id="<?php echo esc_attr( $field['name'] ); ?>"
								name="<?php echo esc_attr( $field['name'] ); ?>" <?php echo esc_attr( $attr ); ?>>
								<?php
								$variations = YITH_WFBT_Admin()->get_variations( $product_id );
								foreach ( $variations as $variation ) :
									// store var id.
									$to_exclude[] = $variation['id'];
									?>
									<option value="<?php echo esc_attr( $variation['id'] ); ?>" <?php selected( $variation['id'], $value ); ?>><?php echo esc_html( $variation['name'] ); ?></option>
									<?php
								endforeach;
								?>
							</select>
							<?php
							break;

						case 'product_select':
							$product_ids = array_filter( array_map( 'absint', (array) $value ) );
							$json_ids    = array();

							foreach ( $product_ids as $product_id ) {
								$product = wc_get_product( $product_id );
								if ( is_object( $product ) ) {
									$json_ids[ $product_id ] = wp_strip_all_tags( $product->get_formatted_name() );
								}
							}

							yit_add_select2_fields(
								array(
									'class'             => 'wc-product-search',
									'style'             => 'width: 50%;',
									'id'                => 'yith_wfbt_ids',
									'name'              => 'yith_wfbt_ids',
									'data-placeholder'  => __( 'Search for a product&hellip;', 'yith-woocommerce-frequently-bought-together' ),
									'data-multiple'     => true,
									'data-action'       => 'yith_ajax_search_product',
									'data-selected'     => $json_ids,
									'value'             => implode( ',', array_keys( $json_ids ) ),
									'custom-attributes' => array(
										'data-exclude' => implode( ',', $to_exclude ),
									),
								)
							);
							break;

						case 'select':
							?>
							<select id="<?php echo esc_attr( $field['name'] ); ?>"
								name="<?php echo esc_attr( $field['name'] ); ?>" <?php echo esc_attr( $attr ); ?>>
								<?php foreach ( $field['options'] as $option_key => $option_name ) : ?>
									<option
										value="<?php echo esc_attr( $option_key ); ?>" <?php selected( $option_key, $value ); ?>><?php echo esc_html( $option_name ); ?></option>
								<?php endforeach; ?>
							</select>
							<?php
							break;

						case 'radio':
							?>
							<?php foreach ( $field['options'] as $option_key => $option_name ) : ?>
								<label for="<?php echo esc_attr( $field['name'] . '-' . $option_key ); ?>" class="radio-label">
									<input type="radio"
										id="<?php echo esc_attr( $field['name'] . '-' . $option_key ); ?>"
										name="<?php echo esc_attr( $field['name'] ); ?>"
										value="<?php echo esc_attr( $option_key ); ?>" <?php checked( $option_key, $value ); ?>>
									<span><?php echo esc_html( $option_name ); ?></span></label><br>
								<?php
							endforeach;
							break;

						case 'textarea':
							?>
							<textarea id="<?php echo esc_attr( $field['name'] ); ?>"
								name="<?php echo esc_attr( $field['name'] ); ?>" <?php echo esc_attr( $attr ); ?>><?php echo esc_html( $value ); ?></textarea>
							<?php
							break;

						case 'checkbox': // this is a workaround to use plugin fw onoff keeping the WooCommerce html.
							?>
							<span class="yith-plugin-ui">
								<span class="yith-plugin-fw-onoff-container">
									<input type="checkbox" id="<?php echo esc_attr( $field['name'] ); ?>"
											name="<?php echo esc_attr( $field['name'] ); ?>"
											value="yes" <?php checked( 'yes', $value ); ?> <?php echo esc_attr( $attr ); ?> class="on_off">
									<span class="yith-plugin-fw-onoff">
                                        <span class="yith-plugin-fw-onoff__handle">
                                            <svg class="yith-plugin-fw-onoff__icon yith-plugin-fw-onoff__icon--on" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" role="img">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                            </svg>
                                            <svg class="yith-plugin-fw-onoff__icon yith-plugin-fw-onoff__icon--off" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" role="img">
                                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                            </svg>
                                        </span>
                                        <span class="yith-plugin-fw-onoff__zero-width-space notranslate">&#8203;</span>
                                    </span>
								</span>
							</span>
							<?php
							break;
						default:
							?>
							<input type="<?php echo esc_attr( $field['type'] ); ?>" class="<?php echo esc_attr( $class ); ?>"
								id="<?php echo esc_attr( $field['name'] ); ?>"
								name="<?php echo esc_attr( $field['name'] ); ?>"
								value="<?php echo esc_attr( $value ); ?>" <?php echo esc_attr( $attr ); ?>/>
							<?php
							break;
					endswitch;
					?>

					<?php if ( ! empty( $desc ) && 'checkbox' !== $field['type'] ) : ?>
						<span class="desc"><?php echo esc_html( $desc ); ?></span>
					<?php endif; ?>
				</p>
			<?php endforeach; ?>
		</div>
	<?php endforeach; ?>
</div>
