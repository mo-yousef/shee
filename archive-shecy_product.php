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
	<div class="marketplace-container">

		<header class="marketplace-header">
			<h1 class="marketplace-title"><?php post_type_archive_title(); ?></h1>
			<p class="marketplace-description">Browse and buy pre-loved fashion and beauty products from our community.</p>
		</header>

		<div class="marketplace-layout">
			<aside class="marketplace-filters">
				<form role="search" method="get" action="<?php echo esc_url( get_post_type_archive_link( 'shecy_product' ) ); ?>" class="filters-form">
					<h3 class="filters-title">Filters</h3>
					<div class="filter-group">
						<label for="filter-category" class="filter-label">Category</label>
						<?php
						wp_dropdown_categories(array(
							'show_option_none' => 'All Categories',
							'taxonomy'         => 'shecy_product_category',
							'name'             => 'shecy_product_category',
							'hierarchical'     => true,
							'class'            => 'filter-select',
							'value_field'      => 'slug',
							'selected'         => get_query_var( 'shecy_product_category' ),
						));
						?>
					</div>
					<div class="filter-group">
						<label for="filter-condition" class="filter-label">Condition</label>
						<?php
						wp_dropdown_categories(array(
							'show_option_none' => 'All Conditions',
							'taxonomy'         => 'shecy_product_condition',
							'name'             => 'shecy_product_condition',
							'class'            => 'filter-select',
							'value_field'      => 'slug',
							'selected'         => get_query_var( 'shecy_product_condition' ),
						));
						?>
					</div>
					<div class="filter-group">
						<label for="filter-price" class="filter-label">Price Range</label>
						<select id="filter-price" name="price_range" class="filter-select">
							<option value="">All Prices</option>
							<option value="0-25">$0 - $25</option>
							<option value="25-50">$25 - $50</option>
							<option value="50-100">$50 - $100</option>
							<option value="100+">$100+</option>
						</select>
					</div>
				</form>
			</aside>

			<div class="product-grid-container">
				<?php if ( have_posts() ) : ?>
					<div class="product-grid">
						<?php
						while ( have_posts() ) :
							the_post();
							get_template_part( 'template-parts/content', 'product-card' );
						endwhile;
						?>
					</div>

					<?php
					the_posts_pagination(
						array(
							'prev_text' => '&larr; Previous',
							'next_text' => 'Next &rarr;',
							'screen_reader_text' => __( 'Products navigation' ),
						)
					);
					?>

				<?php else : ?>
					<div class="no-products-found">
						<h2 class="no-products-title">Nothing Found</h2>
						<p>No products were found matching your criteria. Try adjusting your filters.</p>
					</div>
				<?php endif; ?>
			</div>
		</div>

	</div>
</main>

<?php get_footer(); ?>
