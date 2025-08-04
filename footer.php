<?php
/**
 * The template for displaying the footer
 *
 * @package SheCy
 */
?>

	</div><?php // Closing the main content div from header.php, if applicable ?>

	<footer id="colophon" class="site-footer bg-gray-800 text-gray-300">
		<div class="container mx-auto px-4 py-12">

			<nav class="footer-navigation mb-8" aria-label="Footer Menu">
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'footer',
						'menu_class'     => 'flex flex-wrap justify-center space-x-6',
						'container'      => false,
						'fallback_cb'    => false, // Do not show anything if menu is not set
						'depth'          => 1,
					)
				);
				?>
			</nav>

			<div class="site-info text-center text-sm">
				<p>&copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?>. All Rights Reserved.</p>
				<p class="mt-2">
					<a href="<?php echo esc_url( __( 'https://wordpress.org/', 'shecy' ) ); ?>" class="hover:text-white">
						<?php printf( esc_html__( 'Powered by %s', 'shecy' ), 'WordPress' ); ?>
					</a>
					<span class="mx-2">|</span>
					<span>
						<?php printf( esc_html__( 'Theme: %1$s by %2$s.', 'shecy' ), 'She Cy', '<a href="https://jules.ai/" class="hover:text-white">Jules</a>' ); ?>
					</span>
				</p>
			</div><!-- .site-info -->

		</div>
	</footer><!-- #colophon -->

</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
