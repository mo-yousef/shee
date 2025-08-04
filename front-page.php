<?php
/**
 * The template for displaying the homepage
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package SheCy
 */

get_header();
?>

<main id="primary" class="site-main">

	<?php
	// Hero Section
	?>
	<section class="relative bg-gray-800 text-white h-[60vh] flex items-center justify-center">
		<div class="absolute inset-0 opacity-50">
			<img src="<?php echo get_template_directory_uri(); ?>/assets/images/hero-placeholder.jpg" alt="Fashion and makeup highlights" class="w-full h-full object-cover">
		</div>
		<div class="relative z-10 text-center p-8">
			<h1 class="text-4xl md:text-6xl font-bold mb-4">Elevate Your Style</h1>
			<p class="text-lg md:text-xl mb-8">Discover the latest in fashion, beauty, and sustainable style.</p>
			<a href="/shop" class="bg-violet-500 text-white hover:bg-violet-600 py-3 px-8 rounded-full font-bold text-lg transition-colors">Explore Now</a>
		</div>
	</section>

	<?php
	// Latest Blog Posts Section
	?>
	<section class="py-16 bg-gray-50">
		<div class="container mx-auto px-4">
			<h2 class="text-3xl font-bold text-center mb-8">From Our Blog</h2>
			<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
				<?php
				$latest_posts_query = new WP_Query(array(
					'post_type' => 'post',
					'posts_per_page' => 3,
					'ignore_sticky_posts' => 1,
				));

				if ($latest_posts_query->have_posts()) :
					while ($latest_posts_query->have_posts()) : $latest_posts_query->the_post();
						?>
						<article class="bg-white rounded-lg shadow-md overflow-hidden">
							<?php if (has_post_thumbnail()) : ?>
								<a href="<?php the_permalink(); ?>">
									<?php the_post_thumbnail('medium_large', ['class' => 'w-full h-48 object-cover']); ?>
								</a>
							<?php endif; ?>
							<div class="p-6">
								<h3 class="font-bold text-xl mb-2"><a href="<?php the_permalink(); ?>" class="hover:text-violet-500"><?php the_title(); ?></a></h3>
								<div class="text-gray-600">
									<?php the_excerpt(); ?>
								</div>
								<a href="<?php the_permalink(); ?>" class="text-violet-500 hover:text-violet-600 font-semibold mt-4 inline-block">Read More &rarr;</a>
							</div>
						</article>
						<?php
					endwhile;
					wp_reset_postdata();
				else :
					echo '<p>No recent posts found.</p>';
				endif;
				?>
			</div>
		</div>
	</section>

	<?php
	// Trending Products Section
	?>
	<section class="py-16">
		<div class="container mx-auto px-4">
			<h2 class="text-3xl font-bold text-center mb-8">Trending in the Marketplace</h2>
			<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-8">
				<?php
				$trending_products_query = new WP_Query(array(
					'post_type' => 'shecy_product',
					'posts_per_page' => 4,
				));

				if ($trending_products_query->have_posts()) :
					while ($trending_products_query->have_posts()) : $trending_products_query->the_post();
						?>
						<article class="bg-white rounded-lg shadow-md overflow-hidden group">
							<div class="relative">
								<?php if (has_post_thumbnail()) : ?>
									<a href="<?php the_permalink(); ?>">
										<?php the_post_thumbnail('medium', ['class' => 'w-full h-56 object-cover']); ?>
									</a>
								<?php endif; ?>
								<div class="absolute inset-0 bg-black opacity-0 group-hover:opacity-20 transition-opacity"></div>
							</div>
							<div class="p-4">
								<h3 class="font-semibold truncate"><a href="<?php the_permalink(); ?>" class="hover:text-violet-500"><?php the_title(); ?></a></h3>
								<p class="text-gray-700 font-bold">
									<?php
									// Placeholder for price. This will be a custom field.
									$price = get_post_meta(get_the_ID(), 'price', true);
									echo $price ? '$' . esc_html($price) : 'Price not set';
									?>
								</p>
							</div>
						</article>
						<?php
					endwhile;
					wp_reset_postdata();
				else :
					echo '<p>No products found in the marketplace yet.</p>';
				endif;
				?>
			</div>
			<div class="text-center mt-8">
				<a href="<?php echo get_post_type_archive_link('shecy_product'); ?>" class="bg-gray-800 text-white hover:bg-gray-900 py-3 px-8 rounded-full font-bold text-lg transition-colors">Visit Marketplace</a>
			</div>
		</div>
	</section>

	<?php
	// Call to Actions Section
	?>
	<section class="bg-violet-500 text-white py-16">
		<div class="container mx-auto px-4 text-center">
			<div class="grid grid-cols-1 md:grid-cols-2 gap-8">
				<div class="p-8 bg-violet-400 rounded-lg">
					<h2 class="text-2xl font-bold mb-4">Have something to sell?</h2>
					<p class="mb-6">Join our community marketplace and give your pre-loved items a new life.</p>
					<a href="/submit-product" class="bg-white text-violet-500 hover:bg-gray-100 py-3 px-6 rounded-full font-bold transition-colors">Submit a Product</a>
				</div>
				<div class="p-8 bg-violet-400 rounded-lg">
					<h2 class="text-2xl font-bold mb-4">Are you a business?</h2>
					<p class="mb-6">Get featured in our directory and connect with a passionate audience.</p>
					<a href="/submit-business" class="bg-white text-violet-500 hover:bg-gray-100 py-3 px-6 rounded-full font-bold transition-colors">Promote Your Business</a>
				</div>
			</div>
		</div>
	</section>

</main><!-- #main -->

<?php
get_footer();
?>
