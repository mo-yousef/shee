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

	<?php // Hero Section ?>
	<section class="relative bg-gradient-to-r from-violet-500 to-fuchsia-500 text-white h-[70vh] flex items-center justify-center">
		<div class="absolute inset-0 opacity-20">
			<img src="<?php echo get_template_directory_uri(); ?>/assets/images/hero-placeholder.jpg" alt="Fashion and makeup highlights" class="w-full h-full object-cover">
		</div>
		<div class="relative z-10 text-center p-8">
			<h1 class="text-4xl md:text-6xl font-extrabold mb-4 drop-shadow-lg">Elevate Your Style</h1>
			<p class="text-lg md:text-xl mb-8 max-w-2xl mx-auto">Discover the latest in fashion, beauty, and sustainable style from a vibrant community.</p>
			<a href="/shop" class="bg-white text-violet-600 hover:bg-gray-100 py-3 px-8 rounded-full font-bold text-lg transition-all transform hover:scale-105">Explore Now</a>
		</div>
	</section>

	<?php // Featured Categories Section ?>
	<section class="py-20 bg-white">
		<div class="container mx-auto px-4">
			<h2 class="text-3xl font-bold text-center mb-12">Featured Categories</h2>
			<div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-8">
				<?php
				$product_categories = get_terms( array( 'taxonomy' => 'shecy_product_category', 'number' => 5 ) );
				if ( ! empty( $product_categories ) && ! is_wp_error( $product_categories ) ) {
					foreach ( $product_categories as $category ) {
						?>
						<a href="<?php echo esc_url( get_term_link( $category ) ); ?>" class="text-center group">
							<div class="p-6 bg-gray-100 rounded-full w-32 h-32 mx-auto flex items-center justify-center transition-all duration-300 group-hover:bg-violet-100 group-hover:shadow-lg">
								<span class="text-4xl">🛍️</span>
							</div>
							<h3 class="mt-4 font-semibold text-lg group-hover:text-violet-600"><?php echo esc_html( $category->name ); ?></h3>
						</a>
						<?php
					}
				}
				?>
			</div>
		</div>
	</section>

	<?php // Trending Products Section ?>
	<section class="py-20 bg-gray-50">
		<div class="container mx-auto px-4">
			<h2 class="text-3xl font-bold text-center mb-12">Trending in the Marketplace</h2>
			<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
				<?php
				$trending_products_query = new WP_Query(array(
					'post_type' => 'shecy_product',
					'posts_per_page' => 4,
					'orderby' => 'meta_value_num',
					'meta_key' => 'shecy_post_views',
					'order' => 'DESC',
				));

				if ($trending_products_query->have_posts()) :
					while ($trending_products_query->have_posts()) : $trending_products_query->the_post();
						?>
						<article class="bg-white rounded-lg shadow-lg overflow-hidden group transform hover:-translate-y-2 transition-transform duration-300">
							<div class="relative">
								<?php if (has_post_thumbnail()) : ?>
									<a href="<?php the_permalink(); ?>">
										<?php the_post_thumbnail('medium_large', ['class' => 'w-full h-64 object-cover']); ?>
									</a>
								<?php endif; ?>
								<div class="absolute top-4 right-4 bg-violet-500 text-white text-xs font-bold px-2 py-1 rounded-full"><?php echo get_the_terms(get_the_ID(), 'shecy_product_category')[0]->name; ?></div>
							</div>
							<div class="p-6">
								<h3 class="font-bold text-xl mb-2 truncate"><a href="<?php the_permalink(); ?>" class="hover:text-violet-600"><?php the_title(); ?></a></h3>
								<p class="text-gray-800 font-bold text-lg mb-4">
									<?php
									$price = get_post_meta(get_the_ID(), 'product_price', true);
									echo $price ? '$' . esc_html($price) : 'Price not set';
									?>
								</p>
								<div class="flex justify-between items-center text-sm text-gray-600">
									<span>👁️ <?php echo (int)get_post_meta(get_the_ID(), 'shecy_post_views', true); ?> Views</span>
									<a href="<?php the_permalink(); ?>" class="bg-violet-500 text-white hover:bg-violet-600 py-2 px-4 rounded-full font-bold text-sm transition-colors">View Details</a>
								</div>
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
		</div>
	</section>

	<?php // Testimonials Section ?>
	<section class="py-20 bg-white">
		<div class="container mx-auto px-4">
			<h2 class="text-3xl font-bold text-center mb-12">What Our Community Says</h2>
			<div class="grid grid-cols-1 md:grid-cols-3 gap-8">
				<div class="bg-gray-50 p-8 rounded-lg shadow-lg">
					<p class="text-gray-600 mb-6">"This is the best platform to buy and sell pre-loved items. I found so many unique pieces!"</p>
					<div class="flex items-center">
						<img src="https://i.pravatar.cc/100?u=a" alt="User Avatar" class="w-12 h-12 rounded-full mr-4">
						<div>
							<p class="font-bold">Jane Doe</p>
							<p class="text-sm text-gray-500">Fashion Enthusiast</p>
						</div>
					</div>
				</div>
				<div class="bg-gray-50 p-8 rounded-lg shadow-lg">
					<p class="text-gray-600 mb-6">"I love how easy it is to list my products. The community is so supportive and friendly."</p>
					<div class="flex items-center">
						<img src="https://i.pravatar.cc/100?u=b" alt="User Avatar" class="w-12 h-12 rounded-full mr-4">
						<div>
							<p class="font-bold">John Smith</p>
							<p class="text-sm text-gray-500">Small Business Owner</p>
						</div>
					</div>
				</div>
				<div class="bg-gray-50 p-8 rounded-lg shadow-lg">
					<p class="text-gray-600 mb-6">"A fantastic way to discover local businesses and support the community. Highly recommended!"</p>
					<div class="flex items-center">
						<img src="https://i.pravatar.cc/100?u=c" alt="User Avatar" class="w-12 h-12 rounded-full mr-4">
						<div>
							<p class="font-bold">Sarah Johnson</p>
							<p class="text-sm text-gray-500">Local Shopper</p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>

	<?php // Call to Actions Section ?>
	<section class="bg-violet-600 text-white py-20">
		<div class="container mx-auto px-4 text-center">
			<h2 class="text-3xl font-bold mb-4">Join Our Vibrant Community</h2>
			<p class="text-violet-200 mb-8 max-w-2xl mx-auto">Whether you're looking to declutter your closet, find unique treasures, or promote your business, She Cy is the place for you.</p>
			<div class="flex justify-center gap-4">
				<a href="/submit-product" class="bg-white text-violet-600 hover:bg-gray-100 py-3 px-8 rounded-full font-bold text-lg transition-all transform hover:scale-105">Submit a Product</a>
				<a href="/submit-business" class="bg-violet-500 text-white hover:bg-violet-400 py-3 px-8 rounded-full font-bold text-lg transition-all transform hover:scale-105">Promote a Business</a>
			</div>
		</div>
	</section>

</main><!-- #main -->

<?php
get_footer();
?>
