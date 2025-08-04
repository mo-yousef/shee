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
	<div class="container mx-auto px-4 py-12">

		<header class="text-center mb-12">
			<h1 class="text-4xl font-bold"><?php the_title(); ?></h1>
			<p class="text-lg text-gray-600 mt-2">Shop our curated collection from our favorite brands.</p>
		</header>

		<?php
		// Filters Section - UI only for now
		?>
		<section class="mb-12 p-6 bg-gray-100 rounded-lg">
			<form role="search" method="get" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-center">
				<div class="col-span-1">
					<label for="filter-category" class="block text-sm font-medium text-gray-700">Category</label>
					<?php
					wp_dropdown_categories(array(
						'show_option_none' => 'All Categories',
						'taxonomy'         => 'category',
						'name'             => 'product_cat',
						'class'            => 'mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-pink-500 focus:border-pink-500',
					));
					?>
				</div>
				<div class="col-span-1">
					<label for="filter-brand" class="block text-sm font-medium text-gray-700">Brand</label>
					<?php
					wp_dropdown_categories(array(
						'show_option_none' => 'All Brands',
						'taxonomy'         => 'product_brand',
						'name'             => 'product_brand',
						'class'            => 'mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-pink-500 focus:border-pink-500',
					));
					?>
				</div>
				<div class="col-span-1">
					<label for="filter-price" class="block text-sm font-medium text-gray-700">Price Range</label>
					<select id="filter-price" name="price_range" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-pink-500 focus:border-pink-500">
						<option value="">All Prices</option>
						<option value="0-25">$0 - $25</option>
						<option value="25-50">$25 - $50</option>
						<option value="50-100">$50 - $100</option>
						<option value="100+">$100+</option>
					</select>
				</div>
				<div class="col-span-1 text-right">
					<button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-pink-500 hover:bg-pink-600">Filter</button>
				</div>
			</form>
		</section>

		<?php
		// Products Grid
		?>
		<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
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
					<article class="bg-white rounded-lg shadow-md overflow-hidden group flex flex-col">
						<a href="<?php echo $product_link; ?>" target="_blank" rel="noopener noreferrer" class="block">
							<?php if (has_post_thumbnail()) : ?>
								<?php the_post_thumbnail('medium', ['class' => 'w-full h-64 object-cover']); ?>
							<?php else: ?>
								<div class="w-full h-64 bg-gray-200"></div>
							<?php endif; ?>
						</a>
						<div class="p-4 flex-grow flex flex-col">
							<?php if ($brands && !is_wp_error($brands)) : ?>
								<p class="text-sm text-gray-500"><?php echo esc_html($brands[0]->name); ?></p>
							<?php endif; ?>
							<h3 class="font-semibold flex-grow mb-2"><a href="<?php echo $product_link; ?>" target="_blank" rel="noopener noreferrer" class="hover:text-pink-500"><?php the_title(); ?></a></h3>
							<p class="text-gray-800 font-bold">
								<?php echo $price ? '$' . esc_html($price) : ''; ?>
							</p>
						</div>
					</article>
					<?php
				endwhile;
				wp_reset_postdata();
			else :
				echo '<p class="col-span-full text-center">No affiliate products found. Make sure to add posts to the "affiliate-shop" category.</p>';
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
