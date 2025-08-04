<?php
/**
 * The template for displaying the marketplace archive for shecy_product CPT
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package SheCy
 */

get_header(); ?>

<main id="primary" class="site-main">
	<div class="container mx-auto px-4 py-12">

		<header class="text-center mb-12">
			<h1 class="text-4xl font-bold"><?php post_type_archive_title(); ?></h1>
			<p class="text-lg text-gray-600 mt-2">Browse and buy pre-loved fashion and beauty products from our community.</p>
		</header>

		<?php
		// Filters Section - UI only for now
		?>
		<section class="mb-12 p-6 bg-gray-100 rounded-lg">
			<form role="search" method="get" action="<?php echo esc_url( get_post_type_archive_link( 'shecy_product' ) ); ?>" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-center">
				<div>
					<label for="filter-category" class="block text-sm font-medium text-gray-700">Category</label>
					<?php
					wp_dropdown_categories(array(
						'show_option_none' => 'All Categories',
						'taxonomy'         => 'shecy_product_category',
						'name'             => 'shecy_product_category',
						'hierarchical'     => true,
						'class'            => 'mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-pink-500 focus:border-pink-500',
						'value_field'      => 'slug',
						'selected'         => get_query_var( 'shecy_product_category' ),
					));
					?>
				</div>
				<div>
					<label for="filter-condition" class="block text-sm font-medium text-gray-700">Condition</label>
					<?php
					wp_dropdown_categories(array(
						'show_option_none' => 'All Conditions',
						'taxonomy'         => 'shecy_product_condition',
						'name'             => 'shecy_product_condition',
						'class'            => 'mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-pink-500 focus:border-pink-500',
						'value_field'      => 'slug',
						'selected'         => get_query_var( 'shecy_product_condition' ),
					));
					?>
				</div>
				<div>
					<label for="filter-price" class="block text-sm font-medium text-gray-700">Price Range</label>
					<select id="filter-price" name="price_range" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-pink-500 focus:border-pink-500">
						<option value="">All Prices</option>
						<option value="0-25">$0 - $25</option>
						<option value="25-50">$25 - $50</option>
						<option value="50-100">$50 - $100</option>
						<option value="100+">$100+</option>
					</select>
				</div>
				<div class="text-right">
					<button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-pink-500 hover:bg-pink-600">Filter</button>
				</div>
			</form>
		</section>

		<?php if ( have_posts() ) : ?>
			<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
				<?php
				while ( have_posts() ) :
					the_post();
					?>
					<article class="bg-white rounded-lg shadow-md overflow-hidden group flex flex-col">
						<a href="<?php the_permalink(); ?>" class="block">
							<?php if (has_post_thumbnail()) : ?>
								<?php the_post_thumbnail('medium', ['class' => 'w-full h-64 object-cover']); ?>
							<?php else: ?>
								<div class="w-full h-64 bg-gray-200"></div>
							<?php endif; ?>
						</a>
						<div class="p-4 flex-grow flex flex-col">
							<h3 class="font-semibold flex-grow mb-2"><a href="<?php the_permalink(); ?>" class="hover:text-pink-500"><?php the_title(); ?></a></h3>
							<p class="text-gray-800 font-bold">
								<?php
								$price = get_post_meta(get_the_ID(), 'product_price', true);
								echo $price ? '$' . esc_html($price) : 'Price not set';
								?>
							</p>
						</div>
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
				)
			);
			?>

		<?php else : ?>
			<div class="text-center py-16">
				<h2 class="text-2xl font-bold mb-4">Nothing Found</h2>
				<p class="text-gray-600">No products were found matching your criteria. Try adjusting your filters.</p>
			</div>
		<?php endif; ?>

	</div>
</main>

<?php get_footer(); ?>
