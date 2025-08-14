<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package SheCy
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<div id="page" class="site">
	<a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e( 'Skip to content', 'shecy' ); ?></a>

	<header id="masthead" class="site-header" x-data="{ mobileMenuOpen: false, userMenuOpen: false }">
		<div class="header-container">
			<div class="site-branding">
				<?php
				if ( has_custom_logo() ) {
					the_custom_logo();
				} else {
					if ( is_front_page() && is_home() ) :
						?>
						<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
						<?php
					else :
						?>
						<p class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></p>
						<?php
					endif;
				}
				?>
			</div><!-- .site-branding -->

			<nav id="site-navigation" class="main-navigation">
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'menu-1',
						'menu_id'        => 'primary-menu',
						'menu_class'     => 'primary-menu',
						'container'      => false,
					)
				);
				?>
			</nav><!-- #site-navigation -->

			<div class="header-actions">
				<?php if ( is_user_logged_in() ) : ?>
					<div class="user-menu" @click.away="userMenuOpen = false">
						<button @click="userMenuOpen = !userMenuOpen" class="user-menu-toggle btn btn-ghost">
							<?php echo get_avatar( get_current_user_id(), 24 ); ?>
							<span><?php echo wp_get_current_user()->display_name; ?></span>
						</button>
						<div class="user-menu-dropdown" x-show="userMenuOpen" style="display: none;">
							<a href="<?php echo home_url('/dashboard'); ?>">Dashboard</a>
							<a href="<?php echo home_url('/profile'); ?>">Profile</a>
							<a href="<?php echo wp_logout_url( home_url() ); ?>">Logout</a>
						</div>
					</div>
				<?php else : ?>
					<a href="<?php echo home_url('/login'); ?>" class="btn btn-ghost">Log In</a>
					<a href="<?php echo home_url('/register'); ?>" class="btn btn-primary">Register</a>
				<?php endif; ?>

				<button @click="mobileMenuOpen = !mobileMenuOpen" class="menu-toggle">
					<span class="sr-only">Open menu</span>
					&#9776;
				</button>
			</div>
		</div>

		<!-- Off-canvas Mobile Menu -->
		<div class="mobile-menu-overlay" x-show="mobileMenuOpen" @click="mobileMenuOpen = false" style="display: none;"></div>
		<div class="mobile-menu-panel" x-show="mobileMenuOpen" style="display: none;">
			<div class="mobile-menu-header">
				<div class="site-branding">
					<?php
					if ( has_custom_logo() ) {
						the_custom_logo();
					} else {
						?>
						<p class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></p>
						<?php
					}
					?>
				</div>
				<button @click="mobileMenuOpen = false" class="mobile-menu-close">
					<span class="sr-only">Close menu</span>
					&times;
				</button>
			</div>
			<nav id="mobile-navigation" class="mobile-navigation">
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'menu-1',
						'menu_id'        => 'mobile-primary-menu',
						'menu_class'     => 'mobile-primary-menu',
						'container'      => false,
						'items_wrap'     => '<ul id="%1$s" class="%2$s">%3$s</ul>',
					)
				);
				?>
			</nav>
			<div class="mobile-menu-footer">
                <!-- Mobile menu footer content can go here -->
			</div>
		</div>
	</header><!-- #masthead -->
