<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package WordPress
 * @subpackage meathouse
 * @since 1.0.0
 */
// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' );
} 
global $jws_option;
get_header('elementor');


?>

	<section id="primary" class="content-area">
		<main id="main" class="site-main">
            <?php  the_content(); ?>
		</main><!-- #main -->
	</section><!-- #primary -->

<?php
get_footer('elementor');
