<?php
/**
 * Form template
 *
 * @author YITH <plugins@yithemes.com>
 * @package YITH\FrequentlyBoughtTogetherPremium
 * @version 1.3.0
 */

if ( ! defined( 'YITH_WFBT' ) ) {
	exit;
} // Exit if accessed directly

if ( empty( $product ) ) {
	global $product;
}

/**
 * APPLY_FILTERS: yith_wfbt_hide_form
 *
 * Filters whether to hide the Frequently Bought Together form in the product page.
 *
 * @param bool       $hide_form Whether to hide the Frequently Bought Together form or not.
 * @param WC_Product $product   Product object.
 * @param array      $products  Products in the form.
 *
 * @return bool
 */
if ( ! isset( $products ) || count( $products ) < 2 || apply_filters( 'yith_wfbt_hide_form', false, $product, $products ) ) {
	return;
}

/**
 * Current product object
 *
 * @var $product WC_Product
 */
// set query.
$url = ! is_null( $product ) ? $product->get_permalink() : '';
$url = add_query_arg( 'action', 'yith_bought_together', $url );
$url = wp_nonce_url( $url, 'yith_bought_together' );

$metas         = yith_wfbt_get_meta( $product );
$meta_products = isset( $metas['products'] ) ? $metas['products'] : array();

?>

<div class="yith-wfbt-section woocommerce">
	<?php
	if ( $title ) {
		echo '<h2>' . esc_html( $title ) . '</h2>';
	}

	if ( ! empty( $additional_text ) ) {
		echo '<p class="additional-text">' . wp_kses_post( nl2br( $additional_text ) ) . '</p>';
	}
	?>

	<form class="yith-wfbt-form" method="post" action="<?php echo esc_url( $url ); ?>">
		<?php if ( ! $show_unchecked ) : ?>
			<table class="yith-wfbt-images">
				<tbody>
				<tr>
					<?php
					$i = 0;
					foreach ( $products as $product ) :
						if ( in_array( absint( $product->get_id() ), array_map( 'absint', $unchecked ), true ) || apply_filters('yith_wfbt_hide_image_on_product_variables', $product instanceof WC_Product_Variable, $product ) ) {
							continue;
						}
						?>

						<?php if ( $i > 0 ) : ?>
						<td class="image_plus image_plus_<?php echo esc_attr( $i ); ?>" data-rel="offeringID_<?php echo esc_attr( $i ); ?>">+</td>
					<?php endif; ?>
						<td class="image-td" data-rel="offeringID_<?php echo esc_attr( $i ); ?>">
							<a href="<?php echo esc_url( $product->get_permalink() ); ?>">
								<?php echo wp_kses_post( $product->get_image( 'yith_wfbt_image_size' ) ); ?>
							</a>
						</td>
						<?php
						$i ++;
					endforeach;
					?>
				</tr>
				</tbody>
			</table>
		<?php endif; ?>

		<?php if ( ! $is_empty ) : ?>
			<div class="yith-wfbt-submit-block">
				<div class="price_text">
					<span class="total_price_label">
						<?php echo esc_html( $label_total ); ?>:
					</span>
					<span class="total_price">
						<?php
						/**
						 * APPLY_FILTERS: yith_wfbt_total_price_text
						 *
						 * Filters the text in the form to state the total price when purchasing the products together.
						 *
						 * @param string $total     Text.
						 *
						 * @return string
						 */
						echo wp_kses_post( apply_filters( 'yith_wfbt_total_price_text', $total ) );
						?>
					</span>
					<?php if ( ! empty( $discount ) && 'yes' === get_option( 'yith-wfbt-show-discount-save', 'no' ) ) : ?>
						<span class="save-amount">
							<?php
							/**
							 * APPLY_FILTERS: yith_wfbt_save_discount_text
							 *
							 * Filters the text in the form to state the amount saved when purchasing the products together.
							 *
							 * @param string $text     Text.
							 * @param float  $discount Amount saved when purchasing products together.
							 *
							 * @return string
							 */
							// translators: %s stand for the amount saved with the discount.
							echo wp_kses_post( apply_filters( 'yith_wfbt_save_discount_text', sprintf( __( 'Save %s', 'yith-woocommerce-frequently-bought-together' ), wc_price( $discount ) ), $discount ) );
							?>
						</span>
					<?php endif; ?>
				</div>

				<button type="submit" class="yith-wfbt-submit-button button">
					<?php echo esc_html( $label ); ?>
				</button>
			</div>
		<?php endif; ?>

		<ul class="yith-wfbt-items">
			<?php
			$j = 0;
			foreach ( $products as $product ) :
				$product_id          = $product->get_id();
				$is_variable         = $product->is_type( 'variable' );
				$is_variation        = $product->is_type( 'variation' );
				$variable_product_id = $is_variable ? $product_id : $product->get_parent_id();

				$variations_modal = false;
				if ( $is_variable || ( $is_variation && in_array( absint( $product->get_parent_id() ), array_map( 'absint', $meta_products ), true ) ) ) {
					$variations_modal = true;
				}
				?>
				<li class="yith-wfbt-item <?php echo $is_variable ? 'choise-variation' : ''; ?>">
					<label for="offeringID_<?php echo esc_attr( $j ); ?>">
						<input type="checkbox" name="offeringID[]" id="offeringID_<?php echo esc_attr( $j ); ?>" class="active"
								value="<?php echo esc_attr( $product_id ); ?>"
							<?php echo ( ! in_array( absint( $product_id ), array_map( 'absint', $unchecked ), true ) && ! $show_unchecked && ! $is_variable ) ? 'checked="checked"' : ''; ?>
							<?php echo $is_variable ? 'disabled' : ''; ?>
								data-variable_product_id= <?php echo esc_attr( $variable_product_id ); ?>
						>
						<?php if ( $product_id !== $main_product_id ) : ?>
						<a href="<?php echo esc_url( $product->get_permalink() ); ?>">
							<?php endif ?>

							<span class="product-name">
							<?php
							/**
							 * APPLY_FILTERS: yith_wfbt_this_product_label
							 *
							 * Filters the label to mark the current product in the Frequently Bought Together form in the product page.
							 *
							 * @param string $label Label.
							 *
							 * @return string
							 */
							$this_product_label = apply_filters( 'yith_wfbt_this_product_label', __( 'This Product', 'yith-woocommerce-frequently-bought-together' ) . ': ' );
							echo ( ( $product_id === $main_product_id ) ? esc_html( $this_product_label ) : '' ) . esc_html( sprintf( '%1$s %2$s', $product->get_title(), wc_get_formatted_variation( $product, true ) ) );
							?>
						</span>

							<?php if ( $product_id !== $main_product_id ) : ?>
						</a>
					<?php endif; ?>

						-
						<span class="price">
							<?php echo wp_kses_post( $product->get_price_html() ); ?>
						</span>

						<?php if ( $variations_modal ) : ?>
							<a href="#" class="yith-wfbt-open-modal" data-product_id="<?php echo esc_attr( $variable_product_id ); ?>"><?php echo esc_html( $popup_button_label ); ?></a>
						<?php endif; ?>

					</label>
					<?php
					/**
					 * DO_ACTION: yith_wfbt_end_item
					 *
					 * Allows to render some content after each product in the Frequently Bought Together section.
					 *
					 * @param WC_Product $product Product object.
					 */
					do_action( 'yith_wfbt_end_item', $product );
					?>
				</li>
				<?php
				$j ++;
			endforeach;
			?>
		</ul>

		<input type="hidden" name="yith-wfbt-main-product" value="<?php echo esc_attr( $main_product_id ); ?>">
	</form>
</div>
