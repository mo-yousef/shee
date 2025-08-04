<?php
/**
 * Template Name: Shop
 *
 * The template for displaying the curated affiliate shop archive.
 *
 * @package SheCy
 */

get_header();
?>

<main id="primary" class="site-main">
	<div class="shecy-container shecy-mx-auto shecy-px-4 shecy-py-12">

		<header class="shecy-text-center shecy-mb-12">
			<h1 class="shecy-text-4xl shecy-font-bold"><?php the_title(); ?></h1>
			<p class="shecy-text-lg shecy-text-gray-600 shecy-mt-2">Shop our curated collection from our favorite brands.</p>
		</header>

		<?php
		// Filters Section - UI only for now
		?>
		<section class="shecy-mb-12 shecy-p-6 shecy-bg-gray-100 shecy-rounded-lg">
			<form role="search" method="get" class="shecy-grid shecy-grid-cols-1 md:shecy-grid-cols-4 shecy-gap-4 shecy-items-center">
				<div class="shecy-col-span-1">
					<label for="filter-category" class="shecy-block shecy-text-sm shecy-font-medium shecy-text-gray-700">Category</label>
					<?php
					wp_dropdown_categories(array(
						'show_option_none' => 'All Categories',
						'taxonomy'         => 'category',
						'name'             => 'product_cat',
						'class'            => 'shecy-mt-1 shecy-block shecy-w-full shecy-py-2 shecy-px-3 shecy-border shecy-border-gray-300 shecy-bg-white shecy-rounded-md shecy-shadow-sm focus:shecy-outline-none focus:shecy-ring-pink-500 focus:shecy-border-pink-500',
					));
					?>
				</div>
				<div class="shecy-col-span-1">
					<label for="filter-brand" class="shecy-block shecy-text-sm shecy-font-medium shecy-text-gray-700">Brand</label>
					<?php
					wp_dropdown_categories(array(
						'show_option_none' => 'All Brands',
						'taxonomy'         => 'product_brand',
						'name'             => 'product_brand',
						'class'            => 'shecy-mt-1 shecy-block shecy-w-full shecy-py-2 shecy-px-3 shecy-border shecy-border-gray-300 shecy-bg-white shecy-rounded-md shecy-shadow-sm focus:shecy-outline-none focus:shecy-ring-pink-500 focus:shecy-border-pink-500',
					));
					?>
				</div>
				<div class="shecy-col-span-1">
					<label for="filter-price" class="shecy-block shecy-text-sm shecy-font-medium shecy-text-gray-700">Price Range</label>
					<select id="filter-price" name="price_range" class="shecy-mt-1 shecy-block shecy-w-full shecy-py-2 shecy-px-3 shecy-border shecy-border-gray-300 shecy-bg-white shecy-rounded-md shecy-shadow-sm focus:shecy-outline-none focus:shecy-ring-pink-500 focus:shecy-border-pink-500">
						<option value="">All Prices</option>
						<option value="0-25">$0 - $25</option>
						<option value="25-50">$25 - $50</option>
						<option value="50-100">$50 - $100</option>
						<option value="100+">$100+</option>
					</select>
				</div>
				<div class="shecy-col-span-1 shecy-text-right">
					<button type="submit" class="shecy-inline-flex shecy-justify-center shecy-py-2 shecy-px-4 shecy-border shecy-border-transparent shecy-shadow-sm shecy-text-sm shecy-font-medium shecy-rounded-md shecy-text-white shecy-bg-pink-500 hover:shecy-bg-pink-600">Filter</button>
				</div>
			</form>
		</section>

		<?php
		// Products Grid
		?>
		<div class="shecy-grid shecy-grid-cols-1 sm:shecy-grid-cols-2 lg:shecy-grid-cols-3 xl:shecy-grid-cols-4 shecy-gap-8">
			<?php
			// We will assume that affiliate products are posts in a category called 'affiliate-shop'
			// The user will need to create this category.
			$args = array(
				'post_type'      => 'post',
				'posts_per_page' => 12,
				'category_name'  => 'affiliate-shop', // IMPORTANT: User must create this category slug
			);
			$shop_query = new WP_Query($args);

			if ($shop_query->have_posts()) :
				while ($shop_query->have_posts()) : $shop_query->the_post();
					// Get custom field data
					$affiliate_url = get_post_meta(get_the_ID(), 'affiliate_url', true);
					$price = get_post_meta(get_the_ID(), 'affiliate_price', true);
					$brands = get_the_terms(get_the_ID(), 'product_brand');

					// Use a placeholder if the URL is not set, but link to it directly if it is.
					$product_link = !empty($affiliate_url) ? esc_url($affiliate_url) : '#';
					?>
					<article class="shecy-bg-white shecy-rounded-lg shecy-shadow-md shecy-overflow-hidden shecy-group shecy-flex shecy-flex-col">
						<a href="<?php echo $product_link; ?>" target="_blank" rel="noopener noreferrer" class="shecy-block">
							<?php if (has_post_thumbnail()) : ?>
								<?php the_post_thumbnail('medium', ['class' => 'shecy-w-full shecy-h-64 shecy-object-cover']); ?>
							<?php else: ?>
								<div class="shecy-w-full shecy-h-64 shecy-bg-gray-200"></div>
							<?php endif; ?>
						</a>
						<div class="shecy-p-4 shecy-flex-grow shecy-flex shecy-flex-col">
							<?php if ($brands && !is_wp_error($brands)) : ?>
								<p class="shecy-text-sm shecy-text-gray-500"><?php echo esc_html($brands[0]->name); ?></p>
							<?php endif; ?>
							<h3 class="shecy-font-semibold shecy-flex-grow shecy-mb-2"><a href="<?php echo $product_link; ?>" target="_blank" rel="noopener noreferrer" class="hover:shecy-text-pink-500"><?php the_title(); ?></a></h3>
							<p class="shecy-text-gray-800 shecy-font-bold">
								<?php echo $price ? '$' . esc_html($price) : ''; ?>
							</p>
						</div>
					</article>
					<?php
				endwhile;
				wp_reset_postdata();
			else :
				echo '<p class="shecy-col-span-full shecy-text-center">No affiliate products found. Make sure to add posts to the "affiliate-shop" category.</p>';
			endif;
			?>
		</div>

		<?php // Optional: Pagination
		// the_posts_pagination(...);
		?>
	</div>
</main>

<?php
get_footer();
?>
