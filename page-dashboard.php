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

<main id="primary" class="site-main bg-gray-50">
	<div class="container mx-auto px-4 py-12">

		<header class="mb-8">
			<h1 class="text-3xl font-bold">Welcome, <?php echo esc_html( $current_user->display_name ); ?>!</h1>
			<p class="text-gray-600">This is your dashboard. Manage your submissions and profile here.</p>
		</header>

		<div class="flex flex-col md:flex-row gap-8">

			<?php // Left Sidebar: Tab Navigation ?>
			<aside class="w-full md:w-1/4">
				<ul class="space-y-2">
					<li><a href="?tab=products" class="<?php echo $active_tab === 'products' ? 'bg-violet-500 text-white' : 'bg-white hover:bg-gray-100'; ?> block py-2 px-4 rounded-md font-semibold">My Products</a></li>
					<li><a href="?tab=businesses" class="<?php echo $active_tab === 'businesses' ? 'bg-violet-500 text-white' : 'bg-white hover:bg-gray-100'; ?> block py-2 px-4 rounded-md font-semibold">My Businesses</a></li>
					<li><a href="?tab=profile" class="<?php echo $active_tab === 'profile' ? 'bg-violet-500 text-white' : 'bg-white hover:bg-gray-100'; ?> block py-2 px-4 rounded-md font-semibold">My Profile</a></li>
					<li><a href="?tab=submit" class="<?php echo $active_tab === 'submit' ? 'bg-violet-500 text-white' : 'bg-white hover:bg-gray-100'; ?> block py-2 px-4 rounded-md font-semibold">Submit New</a></li>
				</ul>
			</aside>

			<?php // Right Content Area ?>
			<div class="w-full md:w-3/4 bg-white p-8 rounded-lg shadow-md">

				<?php // My Products Tab Content
				if ( $active_tab === 'products' ) : ?>
					<h2 class="text-2xl font-bold mb-4">My Products</h2>
					<?php
					$products_query = new WP_Query( array(
						'post_type' => 'shecy_product',
						'author' => $current_user->ID,
						'posts_per_page' => -1,
						'post_status' => array('publish', 'pending', 'draft'),
					) );
					if ( $products_query->have_posts() ) : ?>
						<div class="space-y-4">
						<?php while ( $products_query->have_posts() ) : $products_query->the_post(); ?>
							<div class="p-4 border rounded-md flex justify-between items-center">
								<div>
									<a href="<?php the_permalink(); ?>" class="font-semibold hover:text-violet-500"><?php the_title(); ?></a>
									<span class="ml-2 text-sm text-white bg-gray-400 px-2 py-1 rounded-full"><?php echo get_post_status(); ?></span>
								</div>
								<a href="/edit-product?product_id=<?php the_ID(); ?>" class="text-violet-500 hover:underline">Edit</a>
							</div>
						<?php endwhile; wp_reset_postdata(); ?>
						</div>
					<?php else : ?>
						<p>You have not submitted any products yet.</p>
					<?php endif; ?>

				<?php // My Businesses Tab Content
				elseif ( $active_tab === 'businesses' ) : ?>
					<h2 class="text-2xl font-bold mb-4">My Businesses</h2>
					<?php
					$businesses_query = new WP_Query( array(
						'post_type' => 'shecy_business',
						'author' => $current_user->ID,
						'posts_per_page' => -1,
						'post_status' => array('publish', 'pending', 'draft'),
					) );
					if ( $businesses_query->have_posts() ) : ?>
						<div class="space-y-4">
						<?php while ( $businesses_query->have_posts() ) : $businesses_query->the_post(); ?>
							<div class="p-4 border rounded-md flex justify-between items-center">
								<div>
									<a href="<?php the_permalink(); ?>" class="font-semibold hover:text-violet-500"><?php the_title(); ?></a>
									<span class="ml-2 text-sm text-white bg-gray-400 px-2 py-1 rounded-full"><?php echo get_post_status(); ?></span>
								</div>
								<a href="/edit-business?business_id=<?php the_ID(); ?>" class="text-violet-500 hover:underline">Edit</a>
							</div>
						<?php endwhile; wp_reset_postdata(); ?>
						</div>
					<?php else : ?>
						<p>You have not submitted any businesses yet.</p>
					<?php endif; ?>

				<?php // My Profile Tab Content
				elseif ( $active_tab === 'profile' ) : ?>
					<h2 class="text-2xl font-bold mb-4">My Profile</h2>
					<div class="space-y-2">
						<p><strong>Username:</strong> <?php echo esc_html( $current_user->user_login ); ?></p>
						<p><strong>Email:</strong> <?php echo esc_html( $current_user->user_email ); ?></p>
						<p><strong>Display Name:</strong> <?php echo esc_html( $current_user->display_name ); ?></p>
					</div>
					<a href="/profile-settings" class="mt-6 inline-block bg-violet-500 text-white hover:bg-violet-600 py-2 px-4 rounded-md font-semibold">Edit Profile</a>

				<?php // Submit New Tab Content
				elseif ( $active_tab === 'submit' ) : ?>
					<h2 class="text-2xl font-bold mb-4">Submit New</h2>
					<p class="mb-6">Choose what you would like to add to the She Cy platform.</p>
					<div class="flex gap-4">
						<a href="/submit-product" class="flex-1 text-center bg-gray-800 text-white hover:bg-gray-900 py-4 px-6 rounded-lg font-bold text-lg transition-colors">Submit a Product</a>
						<a href="/submit-business" class="flex-1 text-center bg-gray-800 text-white hover:bg-gray-900 py-4 px-6 rounded-lg font-bold text-lg transition-colors">Promote a Business</a>
					</div>
				<?php endif; ?>

			</div>
		</div>
	</div>
</main>

<?php
get_footer();
?>
