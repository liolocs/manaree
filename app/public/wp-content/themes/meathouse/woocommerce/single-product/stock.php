<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
<p class="stock <?php echo esc_attr( $class ); ?>"><?php echo wp_kses_post( $availability ); ?></p>