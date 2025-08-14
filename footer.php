<?php
/**
 * The template for displaying the footer
 *
 * @package SheCy
 */
?>

	</div><?php // This closes a div from header.php. It might need to be removed if the new structure doesn't need it. I will keep it for now. ?>

	<footer id="colophon" class="site-footer">
		<div class="footer-container">
			<nav class="footer-navigation" aria-label="Footer Menu">
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'footer',
						'menu_class'     => 'footer-menu',
						'container'      => false,
						'fallback_cb'    => false,
						'depth'          => 1,
					)
				);
				?>
			</nav>

			<div class="site-info">
				<p>&copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?>. All Rights Reserved.</p>
				<p class="site-info__credits">
					<a href="<?php echo esc_url( __( 'https://wordpress.org/', 'shecy' ) ); ?>">
						<?php printf( esc_html__( 'Powered by %s', 'shecy' ), 'WordPress' ); ?>
					</a>
					<span class="separator">|</span>
					<span>
						<?php printf( esc_html__( 'Theme: %1$s by %2$s.', 'shecy' ), 'She Cy', '<a href="https://jules.ai/">Jules</a>' ); ?>
					</span>
				</p>
			</div>
		</div>
	</footer>

</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
