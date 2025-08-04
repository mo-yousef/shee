<?php
/**
 * The template for displaying the footer
 *
 * @package SheCy
 */
?>

	</div><?php // Closing the main content div from header.php, if applicable ?>

	<footer id="colophon" class="shecy-site-footer shecy-bg-gray-800 shecy-text-gray-300">
		<div class="shecy-container shecy-mx-auto shecy-px-4 shecy-py-12">

			<nav class="shecy-footer-navigation shecy-mb-8" aria-label="Footer Menu">
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'footer',
						'menu_class'     => 'shecy-flex shecy-flex-wrap shecy-justify-center shecy-space-x-6',
						'container'      => false,
						'fallback_cb'    => false, // Do not show anything if menu is not set
						'depth'          => 1,
					)
				);
				?>
			</nav>

			<div class="site-info shecy-text-center shecy-text-sm">
				<p>&copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?>. All Rights Reserved.</p>
				<p class="shecy-mt-2">
					<a href="<?php echo esc_url( __( 'https://wordpress.org/', 'shecy' ) ); ?>" class="hover:shecy-text-white">
						<?php printf( esc_html__( 'Powered by %s', 'shecy' ), 'WordPress' ); ?>
					</a>
					<span class="shecy-mx-2">|</span>
					<span>
						<?php printf( esc_html__( 'Theme: %1$s by %2$s.', 'shecy' ), 'She Cy', '<a href="https://jules.ai/" class="hover:shecy-text-white">Jules</a>' ); ?>
					</span>
				</p>
			</div><!-- .site-info -->

		</div>
	</footer><!-- #colophon -->

</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
