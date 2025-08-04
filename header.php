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

	<header id="masthead" class="site-header bg-white shadow-md" x-data="{ mobileMenuOpen: false, userMenuOpen: false }">
		<div class="container mx-auto px-4 flex justify-between items-center py-4">
			<div class="site-branding">
				<?php
				if ( has_custom_logo() ) {
					the_custom_logo();
				} else {
					if ( is_front_page() && is_home() ) :
						?>
						<h1 class="site-title text-2xl font-bold"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" class="hover:text-violet-500"><?php bloginfo( 'name' ); ?></a></h1>
						<?php
					else :
						?>
						<p class="site-title text-2xl font-bold"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" class="hover:text-violet-500"><?php bloginfo( 'name' ); ?></a></p>
						<?php
					endif;
				}
				?>
			</div><!-- .site-branding -->

			<nav id="site-navigation" class="main-navigation hidden md:block">
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'menu-1',
						'menu_id'        => 'primary-menu',
						'menu_class'     => 'flex space-x-4',
						'container'      => false,
					)
				);
				?>
			</nav><!-- #site-navigation -->

			<div class="flex items-center space-x-4">
				<?php if ( is_user_logged_in() ) : ?>
					<div class="relative" @click.away="userMenuOpen = false">
						<button @click="userMenuOpen = !userMenuOpen" class="flex items-center space-x-2">
							<?php echo get_avatar( get_current_user_id(), 32, '', '', ['class' => 'rounded-full'] ); ?>
							<span class="hidden md:inline-block"><?php echo wp_get_current_user()->display_name; ?></span>
						</button>
						<div x-show="userMenuOpen" x-transition class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-20">
							<a href="<?php echo home_url('/dashboard'); ?>" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Dashboard</a>
							<a href="<?php echo home_url('/profile'); ?>" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profile</a>
							<a href="<?php echo wp_logout_url( home_url() ); ?>" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Logout</a>
						</div>
					</div>
				<?php else : ?>
					<a href="<?php echo home_url('/login'); ?>" class="hidden md:inline-block text-sm font-medium text-gray-700 hover:text-violet-500">Log In</a>
					<a href="<?php echo home_url('/register'); ?>" class="hidden md:inline-block bg-violet-500 text-white hover:bg-violet-600 py-2 px-4 rounded-md text-sm font-medium">Register</a>
				<?php endif; ?>

				<button @click="mobileMenuOpen = !mobileMenuOpen" class="menu-toggle md:hidden" aria-controls="primary-menu" aria-expanded="false">
					<span class="sr-only">Open menu</span>
					&#9776; <?php // Hamburger icon ?>
				</button>
			</div>
		</div>

		<!-- Mobile Menu -->
		<div x-show="mobileMenuOpen" x-transition class="md:hidden">
			<nav id="mobile-navigation" class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'menu-1',
						'menu_id'        => 'mobile-primary-menu',
						'menu_class'     => 'flex flex-col space-y-2',
						'container'      => false,
					)
				);
				?>
				<?php if ( !is_user_logged_in() ) : ?>
				<div class="border-t border-gray-200 mt-4 pt-4">
					<a href="<?php echo home_url('/login'); ?>" class="block w-full text-left py-2 px-3 text-base font-medium rounded-md text-gray-700 hover:bg-gray-50">Log In</a>
					<a href="<?php echo home_url('/register'); ?>" class="block w-full text-left mt-1 py-2 px-3 text-base font-medium rounded-md text-white bg-violet-500 hover:bg-violet-600">Register</a>
				</div>
				<?php endif; ?>
			</nav>
		</div>
	</header><!-- #masthead -->
