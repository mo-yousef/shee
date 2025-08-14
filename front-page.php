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

<main id="primary" class="site-main bg-white">

	<div class="container mx-auto px-4 py-12">

		<div class="text-center py-12">
			<h1 class="text-4xl font-bold text-gray-800 mb-4">She Cy Marketplace</h1>
			<p class="text-lg text-gray-600 mb-8">Discover unique items from our community</p>
			<form role="search" method="get" class="search-form relative max-w-lg mx-auto" action="<?php echo esc_url( home_url( '/' ) ); ?>">
				<label class="w-full">
					<span class="screen-reader-text">Search for:</span>
					<input type="search" class="search-field w-full py-4 pl-6 pr-16 rounded-full border-2 border-gray-200 focus:outline-none focus:border-violet-500 transition-colors" placeholder="Search products..." value="<?php echo get_search_query(); ?>" name="s" />
				</label>
				<button type="submit" class="search-submit absolute top-0 right-0 h-full px-6 text-gray-600 hover:text-violet-600">
					<svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
					</svg>
				</button>
				<input type="hidden" name="post_type" value="shecy_product" />
			</form>
		</div>

		<div class="category-filters py-6 border-b border-gray-200 mb-12">
			<div class="flex space-x-4 overflow-x-auto pb-4">
				<a href="<?php echo get_post_type_archive_link('shecy_product'); ?>" class="py-2 px-4 rounded-full font-semibold text-sm whitespace-nowrap bg-gray-200 text-gray-800 hover:bg-violet-500 hover:text-white transition-colors">All</a>
				<?php
				$categories = get_terms( array( 'taxonomy' => 'shecy_product_category', 'hide_empty' => false ) );
				if ( ! empty( $categories ) && ! is_wp_error( $categories ) ) {
					foreach ( $categories as $category ) {
						echo '<a href="' . esc_url( get_term_link( $category ) ) . '" class="py-2 px-4 rounded-full font-semibold text-sm whitespace-nowrap bg-gray-100 text-gray-600 hover:bg-violet-500 hover:text-white transition-colors">' . esc_html( $category->name ) . '</a>';
					}
				}
				?>
			</div>
		</div>

		<?php // Featured Products Section ?>
		<section>
			<div class="container mx-auto px-4">
				<h2 class="text-2xl font-bold text-gray-900 sm:text-3xl mb-8">Featured Products</h2>
				<div class="grid grid-cols-2 gap-4 mt-8 sm:mt-12 lg:grid-cols-4 sm:gap-6">
					<?php
					$latest_products_query = new WP_Query(array(
						'post_type' => 'shecy_product',
						'posts_per_page' => 4,
						'orderby' => 'date',
						'order' => 'DESC',
					));

					if ($latest_products_query->have_posts()) :
						while ($latest_products_query->have_posts()) : $latest_products_query->the_post();
							get_template_part( 'template-parts/content', 'product-card' );
						endwhile;
						wp_reset_postdata();
					else :
						echo '<p>No products found in the marketplace yet.</p>';
					endif;
					?>
				</div>
				<div class="text-center mt-12">
					<a href="<?php echo get_post_type_archive_link('shecy_product'); ?>" class="inline-block bg-violet-500 text-white hover:bg-violet-600 py-3 px-8 rounded-full font-bold text-lg transition-all transform hover:scale-105">View More Products</a>
				</div>
			</div>
		</section>

		<?php // Trending Products Section ?>
		<section class="py-12 sm:py-16 lg:py-20">
			<div class="container mx-auto px-4">
				<div class="flex justify-between items-center mb-8">
					<h2 class="text-2xl font-bold text-gray-900 sm:text-3xl">Trending Products</h2>
					<div class="flex space-x-2">
						<button class="trending-swiper-prev p-2 rounded-full bg-gray-100 hover:bg-gray-200 transition-colors">
							<svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
						</button>
						<button class="trending-swiper-next p-2 rounded-full bg-gray-100 hover:bg-gray-200 transition-colors">
							<svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
						</button>
					</div>
				</div>
				<div class="swiper-container trending-products-swiper">
					<div class="swiper-wrapper">
						<?php
						$trending_products_query = new WP_Query(array(
							'post_type' => 'shecy_product',
							'posts_per_page' => 10,
							'orderby' => 'meta_value_num',
							'meta_key' => 'shecy_post_views',
							'order' => 'DESC',
						));

						if ($trending_products_query->have_posts()) :
							while ($trending_products_query->have_posts()) : $trending_products_query->the_post();
								?>
								<div class="swiper-slide">
									<?php get_template_part( 'template-parts/content', 'product-card' ); ?>
								</div>
								<?php
							endwhile;
							wp_reset_postdata();
						endif;
						?>
					</div>
				</div>
			</div>
		</section>
	</div>

</main><!-- #main -->

<?php
get_footer();
?>
