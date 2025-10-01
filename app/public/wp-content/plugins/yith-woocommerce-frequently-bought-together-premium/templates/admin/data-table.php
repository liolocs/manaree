<?php
/**
 * Admin View: Data Table Settings
 *
 * @author YITH <plugins@yithemes.com>
 * @package YITH\FrequentlyBoughtTogetherPremium
 */

if ( ! defined( 'YITH_WFBT' ) ) {
	exit; // Exit if accessed directly.
}

$mess = isset( $_GET['wfbt_mess'] ) ? wc_clean( wp_unslash( $_GET['wfbt_mess'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification, WordPress.Security.ValidatedSanitizedInput

switch ( $mess ) {
	case 1:
		$notice = __( 'Select at least one product that has to be removed.', 'yith-woocommerce-frequently-bought-together' );
		break;
	case 2:
		$message = __( 'Selected products have been removed.', 'yith-woocommerce-frequently-bought-together' );
		break;
	default:
		break;
}

$list_query_args = array(
	'page' => isset( $_GET['page'] ) ? sanitize_text_field( wp_unslash( $_GET['page'] ) ) : '', // phpcs:ignore WordPress.Security.NonceVerification
	'tab'  => isset( $_GET['tab'] ) ? sanitize_text_field( wp_unslash( $_GET['tab'] ) ) : '', // phpcs:ignore WordPress.Security.NonceVerification
);

$list_url = add_query_arg( $list_query_args, admin_url( 'admin.php' ) );

?>
<div class="wrap yith-wfbt">

	<?php if ( ! empty( $notice ) ) : ?>
		<div id="notice" class="error below-h2"><p><?php echo esc_html( $notice ); ?></p></div>
		<?php
	endif;

	if ( ! empty( $message ) ) :
		?>
		<div id="message" class="updated below-h2"><p><?php echo esc_html( $message ); ?></p></div>
		<?php
	endif;

	?>

	<form id="yith-wfbt-data-table" class="yith-wfbt-table" method="GET" action="<?php echo esc_url( $list_url ); ?>">
		<input type="hidden" name="page" value="<?php echo esc_attr( sanitize_text_field( wp_unslash( $_GET['page'] ) ) ); // phpcs:ignore WordPress.Security.NonceVerification ?>"/>
		<input type="hidden" name="tab" value="<?php echo esc_attr( sanitize_text_field( wp_unslash( $_GET['tab'] ) ) ); // phpcs:ignore WordPress.Security.NonceVerification ?>"/>
		<?php $table->add_search_box( esc_html__( 'Search Product', 'yith-woocommerce-frequently-bought-together' ), 's' ); ?>
		<?php $table->display(); ?>
	</form>

</div>
