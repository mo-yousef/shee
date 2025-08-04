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
	<section class="shecy-relative shecy-bg-gray-800 shecy-text-white shecy-h-[60vh] shecy-flex shecy-items-center shecy-justify-center">
		<div class="shecy-absolute shecy-inset-0 shecy-opacity-50">
			<img src="<?php echo get_template_directory_uri(); ?>/assets/images/hero-placeholder.jpg" alt="Fashion and makeup highlights" class="shecy-w-full shecy-h-full shecy-object-cover">
		</div>
		<div class="shecy-relative shecy-z-10 shecy-text-center shecy-p-8">
			<h1 class="shecy-text-4xl md:shecy-text-6xl shecy-font-bold shecy-mb-4">Elevate Your Style</h1>
			<p class="shecy-text-lg md:shecy-text-xl shecy-mb-8">Discover the latest in fashion, beauty, and sustainable style.</p>
			<a href="/shop" class="shecy-bg-pink-500 shecy-text-white hover:shecy-bg-pink-600 shecy-py-3 shecy-px-8 shecy-rounded-full shecy-font-bold shecy-text-lg shecy-transition-colors">Explore Now</a>
		</div>
	</section>

	<?php
	// Latest Blog Posts Section
	?>
	<section class="shecy-py-16 shecy-bg-gray-50">
		<div class="shecy-container shecy-mx-auto shecy-px-4">
			<h2 class="shecy-text-3xl shecy-font-bold shecy-text-center shecy-mb-8">From Our Blog</h2>
			<div class="shecy-grid shecy-grid-cols-1 md:shecy-grid-cols-2 lg:shecy-grid-cols-3 shecy-gap-8">
				<?php
				$latest_posts_query = new WP_Query(array(
					'post_type' => 'post',
					'posts_per_page' => 3,
					'ignore_sticky_posts' => 1,
				));

				if ($latest_posts_query->have_posts()) :
					while ($latest_posts_query->have_posts()) : $latest_posts_query->the_post();
						?>
						<article class="shecy-bg-white shecy-rounded-lg shecy-shadow-md shecy-overflow-hidden">
							<?php if (has_post_thumbnail()) : ?>
								<a href="<?php the_permalink(); ?>">
									<?php the_post_thumbnail('medium_large', ['class' => 'shecy-w-full shecy-h-48 shecy-object-cover']); ?>
								</a>
							<?php endif; ?>
							<div class="shecy-p-6">
								<h3 class="shecy-font-bold shecy-text-xl shecy-mb-2"><a href="<?php the_permalink(); ?>" class="hover:shecy-text-pink-500"><?php the_title(); ?></a></h3>
								<div class="shecy-text-gray-600">
									<?php the_excerpt(); ?>
								</div>
								<a href="<?php the_permalink(); ?>" class="shecy-text-pink-500 hover:shecy-text-pink-600 shecy-font-semibold shecy-mt-4 shecy-inline-block">Read More &rarr;</a>
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
	<section class="shecy-py-16">
		<div class="shecy-container shecy-mx-auto shecy-px-4">
			<h2 class="shecy-text-3xl shecy-font-bold shecy-text-center shecy-mb-8">Trending in the Marketplace</h2>
			<div class="shecy-grid shecy-grid-cols-1 sm:shecy-grid-cols-2 md:shecy-grid-cols-4 shecy-gap-8">
				<?php
				$trending_products_query = new WP_Query(array(
					'post_type' => 'shecy_product',
					'posts_per_page' => 4,
				));

				if ($trending_products_query->have_posts()) :
					while ($trending_products_query->have_posts()) : $trending_products_query->the_post();
						?>
						<article class="shecy-bg-white shecy-rounded-lg shecy-shadow-md shecy-overflow-hidden shecy-group">
							<div class="shecy-relative">
								<?php if (has_post_thumbnail()) : ?>
									<a href="<?php the_permalink(); ?>">
										<?php the_post_thumbnail('medium', ['class' => 'shecy-w-full shecy-h-56 shecy-object-cover']); ?>
									</a>
								<?php endif; ?>
								<div class="shecy-absolute shecy-inset-0 shecy-bg-black shecy-opacity-0 group-hover:shecy-opacity-20 shecy-transition-opacity"></div>
							</div>
							<div class="shecy-p-4">
								<h3 class="shecy-font-semibold shecy-truncate"><a href="<?php the_permalink(); ?>" class="hover:shecy-text-pink-500"><?php the_title(); ?></a></h3>
								<p class="shecy-text-gray-700 shecy-font-bold">
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
			<div class="shecy-text-center shecy-mt-8">
				<a href="<?php echo get_post_type_archive_link('shecy_product'); ?>" class="shecy-bg-gray-800 shecy-text-white hover:shecy-bg-gray-900 shecy-py-3 shecy-px-8 shecy-rounded-full shecy-font-bold shecy-text-lg shecy-transition-colors">Visit Marketplace</a>
			</div>
		</div>
	</section>

	<?php
	// Call to Actions Section
	?>
	<section class="shecy-bg-pink-500 shecy-text-white shecy-py-16">
		<div class="shecy-container shecy-mx-auto shecy-px-4 shecy-text-center">
			<div class="shecy-grid shecy-grid-cols-1 md:shecy-grid-cols-2 shecy-gap-8">
				<div class="shecy-p-8 shecy-bg-pink-400 shecy-rounded-lg">
					<h2 class="shecy-text-2xl shecy-font-bold shecy-mb-4">Have something to sell?</h2>
					<p class="shecy-mb-6">Join our community marketplace and give your pre-loved items a new life.</p>
					<a href="/submit-product" class="shecy-bg-white shecy-text-pink-500 hover:shecy-bg-gray-100 shecy-py-3 shecy-px-6 shecy-rounded-full shecy-font-bold shecy-transition-colors">Submit a Product</a>
				</div>
				<div class="shecy-p-8 shecy-bg-pink-400 shecy-rounded-lg">
					<h2 class="shecy-text-2xl shecy-font-bold shecy-mb-4">Are you a business?</h2>
					<p class="shecy-mb-6">Get featured in our directory and connect with a passionate audience.</p>
					<a href="/submit-business" class="shecy-bg-white shecy-text-pink-500 hover:shecy-bg-gray-100 shecy-py-3 shecy-px-6 shecy-rounded-full shecy-font-bold shecy-transition-colors">Promote Your Business</a>
				</div>
			</div>
		</div>
	</section>

</main><!-- #main -->

<?php
get_footer();
?>
