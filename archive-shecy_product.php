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
	<div class="shecy-container shecy-mx-auto shecy-px-4 shecy-py-12">

		<header class="shecy-text-center shecy-mb-12">
			<h1 class="shecy-text-4xl shecy-font-bold"><?php post_type_archive_title(); ?></h1>
			<p class="shecy-text-lg shecy-text-gray-600 shecy-mt-2">Browse and buy pre-loved fashion and beauty products from our community.</p>
		</header>

		<?php
		// Filters Section - UI only for now
		?>
		<section class="shecy-mb-12 shecy-p-6 shecy-bg-gray-100 shecy-rounded-lg">
			<form role="search" method="get" action="<?php echo esc_url( get_post_type_archive_link( 'shecy_product' ) ); ?>" class="shecy-grid shecy-grid-cols-1 md:shecy-grid-cols-4 shecy-gap-4 shecy-items-center">
				<div>
					<label for="filter-category" class="shecy-block shecy-text-sm shecy-font-medium shecy-text-gray-700">Category</label>
					<?php
					wp_dropdown_categories(array(
						'show_option_none' => 'All Categories',
						'taxonomy'         => 'shecy_product_category',
						'name'             => 'shecy_product_category',
						'hierarchical'     => true,
						'class'            => 'shecy-mt-1 shecy-block shecy-w-full shecy-py-2 shecy-px-3 shecy-border shecy-border-gray-300 shecy-bg-white shecy-rounded-md shecy-shadow-sm focus:shecy-outline-none focus:shecy-ring-pink-500 focus:shecy-border-pink-500',
						'value_field'      => 'slug',
						'selected'         => get_query_var( 'shecy_product_category' ),
					));
					?>
				</div>
				<div>
					<label for="filter-condition" class="shecy-block shecy-text-sm shecy-font-medium shecy-text-gray-700">Condition</label>
					<?php
					wp_dropdown_categories(array(
						'show_option_none' => 'All Conditions',
						'taxonomy'         => 'shecy_product_condition',
						'name'             => 'shecy_product_condition',
						'class'            => 'shecy-mt-1 shecy-block shecy-w-full shecy-py-2 shecy-px-3 shecy-border shecy-border-gray-300 shecy-bg-white shecy-rounded-md shecy-shadow-sm focus:shecy-outline-none focus:shecy-ring-pink-500 focus:shecy-border-pink-500',
						'value_field'      => 'slug',
						'selected'         => get_query_var( 'shecy_product_condition' ),
					));
					?>
				</div>
				<div>
					<label for="filter-price" class="shecy-block shecy-text-sm shecy-font-medium shecy-text-gray-700">Price Range</label>
					<select id="filter-price" name="price_range" class="shecy-mt-1 shecy-block shecy-w-full shecy-py-2 shecy-px-3 shecy-border shecy-border-gray-300 shecy-bg-white shecy-rounded-md shecy-shadow-sm focus:shecy-outline-none focus:shecy-ring-pink-500 focus:shecy-border-pink-500">
						<option value="">All Prices</option>
						<option value="0-25">$0 - $25</option>
						<option value="25-50">$25 - $50</option>
						<option value="50-100">$50 - $100</option>
						<option value="100+">$100+</option>
					</select>
				</div>
				<div class="shecy-text-right">
					<button type="submit" class="shecy-inline-flex shecy-justify-center shecy-py-2 shecy-px-4 shecy-border shecy-border-transparent shecy-shadow-sm shecy-text-sm shecy-font-medium shecy-rounded-md shecy-text-white shecy-bg-pink-500 hover:shecy-bg-pink-600">Filter</button>
				</div>
			</form>
		</section>

		<?php if ( have_posts() ) : ?>
			<div class="shecy-grid shecy-grid-cols-1 sm:shecy-grid-cols-2 lg:shecy-grid-cols-3 xl:shecy-grid-cols-4 shecy-gap-8">
				<?php
				while ( have_posts() ) :
					the_post();
					?>
					<article class="shecy-bg-white shecy-rounded-lg shecy-shadow-md shecy-overflow-hidden shecy-group shecy-flex shecy-flex-col">
						<a href="<?php the_permalink(); ?>" class="shecy-block">
							<?php if (has_post_thumbnail()) : ?>
								<?php the_post_thumbnail('medium', ['class' => 'shecy-w-full shecy-h-64 shecy-object-cover']); ?>
							<?php else: ?>
								<div class="shecy-w-full shecy-h-64 shecy-bg-gray-200"></div>
							<?php endif; ?>
						</a>
						<div class="shecy-p-4 shecy-flex-grow shecy-flex shecy-flex-col">
							<h3 class="shecy-font-semibold shecy-flex-grow shecy-mb-2"><a href="<?php the_permalink(); ?>" class="hover:shecy-text-pink-500"><?php the_title(); ?></a></h3>
							<p class="shecy-text-gray-800 shecy-font-bold">
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
					'prev_text' => '<span class="shecy-sr-only">Previous</span><svg class="shecy-h-5 shecy-w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>',
					'next_text' => '<span class="shecy-sr-only">Next</span><svg class="shecy-h-5 shecy-w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" /></svg>',
					'screen_reader_text' => __( 'Products navigation' ),
				)
			);
			?>

		<?php else : ?>
			<div class="shecy-text-center shecy-py-16">
				<h2 class="shecy-text-2xl shecy-font-bold shecy-mb-4">Nothing Found</h2>
				<p class="shecy-text-gray-600">No products were found matching your criteria. Try adjusting your filters.</p>
			</div>
		<?php endif; ?>

	</div>
</main>

<?php get_footer(); ?>
