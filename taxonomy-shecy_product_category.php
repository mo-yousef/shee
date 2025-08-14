<?php
/**
 * The template for displaying shecy_product category archives
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package SheCy
 */

get_header(); ?>

<main id="primary" class="site-main bg-gray-50">
	<div class="container mx-auto px-4 py-12">

		<header class="mb-12">
			<h1 class="text-4xl font-bold text-center"><?php single_term_title(); ?></h1>
			<div class="text-lg text-gray-600 mt-2 text-center max-w-2xl mx-auto"><?php echo term_description(); ?></div>
		</header>

		<div class="grid grid-cols-1 lg:grid-cols-4 gap-8">

			<?php // Left Column: Filters ?>
			<aside class="lg:col-span-1 bg-white p-6 rounded-lg shadow-sm h-fit">
				<h2 class="text-xl font-bold mb-4">Filters</h2>
				<form role="search" method="get" action="<?php echo esc_url( get_term_link( get_queried_object() ) ); ?>" class="space-y-6">
					<div>
						<label for="filter-condition" class="block text-sm font-medium text-gray-700">Condition</label>
						<?php
						wp_dropdown_categories(array(
							'show_option_none' => 'All Conditions',
							'taxonomy'         => 'shecy_product_condition',
							'name'             => 'shecy_product_condition',
							'class'            => 'mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-violet-500 focus:border-violet-500',
							'value_field'      => 'slug',
							'selected'         => get_query_var( 'shecy_product_condition' ),
						));
						?>
					</div>
					<div>
						<label for="filter-price" class="block text-sm font-medium text-gray-700">Price Range</label>
						<select id="filter-price" name="price_range" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-violet-500 focus:border-violet-500">
							<option value="">All Prices</option>
							<option value="0-25">$0 - $25</option>
							<option value="25-50">$25 - $50</option>
							<option value="50-100">$50 - $100</option>
							<option value="100+">$100+</option>
						</select>
					</div>
					<div>
						<button type="submit" class="w-full inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-violet-500 hover:bg-violet-600">Apply Filters</button>
					</div>
				</form>
			</aside>

			<?php // Right Column: Products Grid ?>
			<div class="lg:col-span-3">
				<?php if ( have_posts() ) : ?>
					<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-8">
						<?php
						while ( have_posts() ) :
							the_post();
							?>
							<article class="group relative rounded-lg overflow-hidden shadow-sm hover:shadow-lg transition-shadow duration-300 bg-white">
								<a href="<?php the_permalink(); ?>">
									<?php if (has_post_thumbnail()) : ?>
										<?php the_post_thumbnail('medium_large', ['class' => 'w-full h-80 object-cover']); ?>
									<?php else : ?>
										<div class="w-full h-80 bg-gray-200"></div>
									<?php endif; ?>
									<div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
									<div class="absolute bottom-0 left-0 p-4">
										<h3 class="font-semibold text-lg text-white mb-1"><?php the_title(); ?></h3>
										<p class="text-white/90 text-md font-bold">
											<?php
											$price = get_post_meta(get_the_ID(), 'product_price', true);
											echo $price ? '$' . esc_html($price) : 'Price not set';
											?>
										</p>
									</div>
								</a>
							</article>
							<?php
						endwhile;
						?>
					</div>

					<?php
					the_posts_pagination(
						array(
							'prev_text' => '<span class="sr-only">Previous</span><svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>',
							'next_text' => '<span class="sr-only">Next</span><svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" /></svg>',
							'screen_reader_text' => __( 'Products navigation' ),
							'before_page_number' => '<span class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md">',
							'after_page_number'  => '</span>',
						)
					);
					?>

				<?php else : ?>
					<div class="text-center py-16 bg-white rounded-lg shadow-sm">
						<h2 class="text-2xl font-bold mb-4">Nothing Found</h2>
						<p class="text-gray-600">No products were found in this category. Try adjusting your filters or checking back later.</p>
					</div>
				<?php endif; ?>
			</div>

		</div>
	</div>
</main>

<?php get_footer(); ?>
