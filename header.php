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

	<header id="masthead" class="site-header bg-white shadow-md sticky top-0 z-50" x-data="{ mobileMenuOpen: false, userMenuOpen: false }">
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
					<div class="relative hidden md:block" @click.away="userMenuOpen = false">
						<button @click="userMenuOpen = !userMenuOpen" class="flex items-center space-x-2">
							<?php echo get_avatar( get_current_user_id(), 32, '', '', ['class' => 'rounded-full'] ); ?>
							<span><?php echo wp_get_current_user()->display_name; ?></span>
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

		<!-- Off-canvas Mobile Menu -->
		<div x-show="mobileMenuOpen" class="fixed inset-0 flex z-40 md:hidden" role="dialog" aria-modal="true">
			<!-- Off-canvas panel -->
			<div x-show="mobileMenuOpen"
				 x-transition:enter="transition ease-in-out duration-300 transform"
				 x-transition:enter-start="translate-x-full"
				 x-transition:enter-end="translate-x-0"
				 x-transition:leave="transition ease-in-out duration-300 transform"
				 x-transition:leave-start="translate-x-0"
				 x-transition:leave-end="translate-x-full"
				 class="relative flex-1 flex flex-col max-w-xs w-full bg-white"
				 @click.away="mobileMenuOpen = false">

				<div class="absolute top-0 right-0 -mr-12 pt-2">
					<button @click="mobileMenuOpen = false" type="button" class="ml-1 flex items-center justify-center h-10 w-10 rounded-full focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white">
						<span class="sr-only">Close sidebar</span>
						<svg class="h-6 w-6 text-white" x-description="Heroicon name: outline/x" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
					</button>
				</div>

				<div class="flex-1 h-0 pt-5 pb-4 overflow-y-auto">
					<div class="flex-shrink-0 flex items-center px-4">
						<?php
						if ( has_custom_logo() ) {
							the_custom_logo();
						} else {
							?>
							<p class="site-title text-2xl font-bold"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" class="hover:text-violet-500"><?php bloginfo( 'name' ); ?></a></p>
							<?php
						}
						?>
					</div>
					<nav id="mobile-navigation" class="mt-5 px-2 space-y-1">
						<?php
						wp_nav_menu(
							array(
								'theme_location' => 'menu-1',
								'menu_id'        => 'mobile-primary-menu',
								'menu_class'     => '',
								'container'      => false,
								'items_wrap'     => '%3$s',
								'walker'         => new SheCy_Mobile_Nav_Walker(),
							)
						);
						?>
					</nav>
				</div>
				<div class="flex-shrink-0 flex border-t border-gray-200 p-4">
					<div class="flex-shrink-0 w-full group block">
						<div class="space-y-2">
							<a href="<?php echo home_url('/submit-product'); ?>" class="block w-full text-center py-2 px-3 text-base font-medium rounded-md text-white bg-violet-600 hover:bg-violet-700">Submit a Product</a>
							<a href="<?php echo home_url('/submit-business'); ?>" class="block w-full text-center py-2 px-3 text-base font-medium rounded-md text-white bg-gray-700 hover:bg-gray-800">Promote a Business</a>
						</div>
						<div class="border-t border-gray-200 mt-4 pt-4">
							<?php if ( is_user_logged_in() ) : ?>
								<div class="flex items-center mb-4">
									<div>
										<?php echo get_avatar( get_current_user_id(), 40, '', '', ['class' => 'rounded-full'] ); ?>
									</div>
									<div class="ml-3">
										<p class="text-base font-medium text-gray-700"><?php echo wp_get_current_user()->display_name; ?></p>
									</div>
								</div>
								<a href="<?php echo home_url('/dashboard'); ?>" class="block py-2 px-3 text-base font-medium rounded-md text-gray-700 hover:bg-gray-50">Dashboard</a>
								<a href="<?php echo home_url('/profile'); ?>" class="block py-2 px-3 text-base font-medium rounded-md text-gray-700 hover:bg-gray-50">Profile</a>
								<a href="<?php echo wp_logout_url( home_url() ); ?>" class="block py-2 px-3 text-base font-medium rounded-md text-gray-700 hover:bg-gray-50">Logout</a>
							<?php else : ?>
								<a href="<?php echo home_url('/login'); ?>" class="block w-full text-left py-2 px-3 text-base font-medium rounded-md text-gray-700 hover:bg-gray-50">Log In</a>
								<a href="<?php echo home_url('/register'); ?>" class="block w-full text-left mt-1 py-2 px-3 text-base font-medium rounded-md text-gray-700 hover:bg-gray-50">Register</a>
							<?php endif; ?>
						</div>
					</div>
				</div>
			</div>

			<!-- Overlay -->
			<div x-show="mobileMenuOpen"
				 x-transition:enter="ease-in-out duration-300"
				 x-transition:enter-start="opacity-0"
				 x-transition:enter-end="opacity-100"
				 x-transition:leave="ease-in-out duration-300"
				 x-transition:leave-start="opacity-100"
				 x-transition:leave-end="opacity-0"
				 class="fixed inset-0 bg-gray-600 bg-opacity-75"
				 @click="mobileMenuOpen = false">
			</div>

			<div class="flex-shrink-0 w-14">
				<!-- Dummy element to force sidebar to shrink to fit close button -->
			</div>
		</div>
	</header><!-- #masthead -->
