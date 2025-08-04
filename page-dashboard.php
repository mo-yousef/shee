<?php
/**
 * Template Name: User Dashboard
 *
 * @package SheCy
 */

// Redirect non-logged-in users to the login page.
if ( ! is_user_logged_in() ) {
	wp_redirect( home_url( '/login' ) ); // Assumes a login page exists at /login
	exit;
}

get_header();

$current_user = wp_get_current_user();
$active_tab = isset( $_GET['tab'] ) ? sanitize_key( $_GET['tab'] ) : 'products';
?>

<main id="primary" class="site-main shecy-bg-gray-50">
	<div class="shecy-container shecy-mx-auto shecy-px-4 shecy-py-12">

		<header class="shecy-mb-8">
			<h1 class="shecy-text-3xl shecy-font-bold">Welcome, <?php echo esc_html( $current_user->display_name ); ?>!</h1>
			<p class="shecy-text-gray-600">This is your dashboard. Manage your submissions and profile here.</p>
		</header>

		<div class="shecy-flex shecy-flex-col md:shecy-flex-row shecy-gap-8">

			<?php // Left Sidebar: Tab Navigation ?>
			<aside class="shecy-w-full md:shecy-w-1/4">
				<ul class="shecy-space-y-2">
					<li><a href="?tab=products" class="<?php echo $active_tab === 'products' ? 'shecy-bg-pink-500 shecy-text-white' : 'shecy-bg-white hover:shecy-bg-gray-100'; ?> shecy-block shecy-py-2 shecy-px-4 shecy-rounded-md shecy-font-semibold">My Products</a></li>
					<li><a href="?tab=businesses" class="<?php echo $active_tab === 'businesses' ? 'shecy-bg-pink-500 shecy-text-white' : 'shecy-bg-white hover:shecy-bg-gray-100'; ?> shecy-block shecy-py-2 shecy-px-4 shecy-rounded-md shecy-font-semibold">My Businesses</a></li>
					<li><a href="?tab=profile" class="<?php echo $active_tab === 'profile' ? 'shecy-bg-pink-500 shecy-text-white' : 'shecy-bg-white hover:shecy-bg-gray-100'; ?> shecy-block shecy-py-2 shecy-px-4 shecy-rounded-md shecy-font-semibold">My Profile</a></li>
					<li><a href="?tab=submit" class="<?php echo $active_tab === 'submit' ? 'shecy-bg-pink-500 shecy-text-white' : 'shecy-bg-white hover:shecy-bg-gray-100'; ?> shecy-block shecy-py-2 shecy-px-4 shecy-rounded-md shecy-font-semibold">Submit New</a></li>
				</ul>
			</aside>

			<?php // Right Content Area ?>
			<div class="shecy-w-full md:shecy-w-3/4 shecy-bg-white shecy-p-8 shecy-rounded-lg shecy-shadow-md">

				<?php // My Products Tab Content
				if ( $active_tab === 'products' ) : ?>
					<h2 class="shecy-text-2xl shecy-font-bold shecy-mb-4">My Products</h2>
					<?php
					$products_query = new WP_Query( array(
						'post_type' => 'shecy_product',
						'author' => $current_user->ID,
						'posts_per_page' => -1,
					) );
					if ( $products_query->have_posts() ) : ?>
						<div class="shecy-space-y-4">
						<?php while ( $products_query->have_posts() ) : $products_query->the_post(); ?>
							<div class="shecy-p-4 shecy-border shecy-rounded-md shecy-flex shecy-justify-between shecy-items-center">
								<div>
									<a href="<?php the_permalink(); ?>" class="shecy-font-semibold hover:shecy-text-pink-500"><?php the_title(); ?></a>
									<span class="shecy-ml-2 shecy-text-sm shecy-text-white shecy-bg-gray-400 shecy-px-2 shecy-py-1 shecy-rounded-full"><?php echo get_post_status(); ?></span>
								</div>
								<a href="/edit-product?product_id=<?php the_ID(); ?>" class="shecy-text-pink-500 hover:shecy-underline">Edit</a>
							</div>
						<?php endwhile; wp_reset_postdata(); ?>
						</div>
					<?php else : ?>
						<p>You have not submitted any products yet.</p>
					<?php endif; ?>

				<?php // My Businesses Tab Content
				elseif ( $active_tab === 'businesses' ) : ?>
					<h2 class="shecy-text-2xl shecy-font-bold shecy-mb-4">My Businesses</h2>
					<?php
					$businesses_query = new WP_Query( array(
						'post_type' => 'shecy_business',
						'author' => $current_user->ID,
						'posts_per_page' => -1,
					) );
					if ( $businesses_query->have_posts() ) : ?>
						<div class="shecy-space-y-4">
						<?php while ( $businesses_query->have_posts() ) : $businesses_query->the_post(); ?>
							<div class="shecy-p-4 shecy-border shecy-rounded-md shecy-flex shecy-justify-between shecy-items-center">
								<div>
									<a href="<?php the_permalink(); ?>" class="shecy-font-semibold hover:shecy-text-pink-500"><?php the_title(); ?></a>
									<span class="shecy-ml-2 shecy-text-sm shecy-text-white shecy-bg-gray-400 shecy-px-2 shecy-py-1 shecy-rounded-full"><?php echo get_post_status(); ?></span>
								</div>
								<a href="/edit-business?business_id=<?php the_ID(); ?>" class="shecy-text-pink-500 hover:shecy-underline">Edit</a>
							</div>
						<?php endwhile; wp_reset_postdata(); ?>
						</div>
					<?php else : ?>
						<p>You have not submitted any businesses yet.</p>
					<?php endif; ?>

				<?php // My Profile Tab Content
				elseif ( $active_tab === 'profile' ) : ?>
					<h2 class="shecy-text-2xl shecy-font-bold shecy-mb-4">My Profile</h2>
					<div class="shecy-space-y-2">
						<p><strong>Username:</strong> <?php echo esc_html( $current_user->user_login ); ?></p>
						<p><strong>Email:</strong> <?php echo esc_html( $current_user->user_email ); ?></p>
						<p><strong>Display Name:</strong> <?php echo esc_html( $current_user->display_name ); ?></p>
					</div>
					<a href="/profile-settings" class="shecy-mt-6 shecy-inline-block shecy-bg-pink-500 shecy-text-white hover:shecy-bg-pink-600 shecy-py-2 shecy-px-4 shecy-rounded-md shecy-font-semibold">Edit Profile</a>

				<?php // Submit New Tab Content
				elseif ( $active_tab === 'submit' ) : ?>
					<h2 class="shecy-text-2xl shecy-font-bold shecy-mb-4">Submit New</h2>
					<p class="shecy-mb-6">Choose what you would like to add to the She Cy platform.</p>
					<div class="shecy-flex shecy-gap-4">
						<a href="/submit-product" class="shecy-flex-1 shecy-text-center shecy-bg-gray-800 shecy-text-white hover:shecy-bg-gray-900 shecy-py-4 shecy-px-6 shecy-rounded-lg shecy-font-bold shecy-text-lg shecy-transition-colors">Submit a Product</a>
						<a href="/submit-business" class="shecy-flex-1 shecy-text-center shecy-bg-gray-800 shecy-text-white hover:shecy-bg-gray-900 shecy-py-4 shecy-px-6 shecy-rounded-lg shecy-font-bold shecy-text-lg shecy-transition-colors">Promote a Business</a>
					</div>
				<?php endif; ?>

			</div>
		</div>
	</div>
</main>

<?php
get_footer();
?>
