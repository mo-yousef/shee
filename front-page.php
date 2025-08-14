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

		<div class="relative bg-gradient-to-r from-sky-500 to-indigo-500">
    <div class="absolute inset-0">
        <img class="object-cover w-full h-full" src="<?php echo get_template_directory_uri(); ?>/assets/images/hero-placeholder.jpg" alt="Hero background">
        <div class="absolute inset-0 bg-gray-900 bg-opacity-50"></div>
    </div>
    <div class="relative px-4 py-24 mx-auto sm:px-6 lg:px-8 max-w-7xl">
        <div class="max-w-lg mx-auto text-center">
            <h1 class="text-3xl font-bold text-white sm:text-4xl lg:text-5xl">
                Find your next favorite thing
            </h1>
            <p class="mt-4 text-base font-normal text-gray-200 sm:text-lg">
                A community-driven marketplace for unique and handcrafted items.
            </p>
            <form role="search" method="get" class="search-form mt-8 sm:flex sm:justify-center" action="<?php echo esc_url( home_url( '/' ) ); ?>">
                <label class="w-full sm:w-auto">
                    <span class="screen-reader-text">Search for:</span>
                    <input type="search" class="search-field w-full px-5 py-4 text-base text-gray-900 placeholder-gray-500 bg-white border border-transparent rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-900 focus:ring-white" placeholder="Search products..." value="<?php echo get_search_query(); ?>" name="s">
                </label>
                <button type="submit" class="search-submit inline-flex items-center justify-center w-full sm:w-auto px-5 py-4 mt-4 sm:mt-0 sm:ml-4 text-base font-semibold text-white bg-indigo-600 border border-transparent rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-900 focus:ring-indigo-500">
                    Search
                </button>
								<input type="hidden" name="post_type" value="shecy_product" />
            </form>
        </div>
    </div>
</div>

<section class="py-12 sm:py-16 lg:py-20 bg-gray-50">
      <div class="px-4 mx-auto sm:px-6 lg:px-8 max-w-7xl">
        <div class="flex items-center justify-center text-center md:justify-between sm:text-left">
          <div>
            <h2 class="text-2xl font-bold text-gray-900 sm:text-3xl">
              Popular Categories
            </h2>
            <p class="text-base text-gray-600 font-normal mt-2.5">
              Choose from wide variety of items
            </p>
          </div>

          <div class="hidden md:block">
            <a href="#" title="" class="inline-flex items-center p-1 -m-1 text-xs font-bold tracking-wide text-gray-400 uppercase transition-all duration-200 rounded hover:text-gray-900 focus:ring-2 focus:text-gray-900 focus:ring-gray-900 focus:ring-offset-2 focus:outline-none" role="button">
              All Categories
              <svg class="w-4 h-4 ml-1.5 -mt-0.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
              </svg>
            </a>
          </div>
        </div>

        <div class="grid grid-cols-2 gap-5 mt-8 text-center sm:mt-12 sm:grid-cols-3 xl:grid-cols-6 sm:gap-6">
          <div class="relative transition-all duration-300 bg-gray-100 rounded-xl hover:shadow-xl hover:bg-white">
            <div class="px-4 py-5 sm:p-6">
              <img class="object-cover w-24 h-24 mx-auto border border-gray-200 rounded-full" src="https://cdn.rareblocks.xyz/collection/clarity-ecommerce/images/categories/2/smart-watches.png" alt="">

              <p class="mt-5 text-base font-bold text-gray-900">
                <a href="#" title="">
                  Smart <br class="block sm:hidden xl:block">Watches
                  <span class="absolute inset-0" aria-hidden="true"></span>
                </a>
              </p>
            </div>
          </div>

          <div class="relative transition-all duration-300 bg-gray-100 rounded-xl hover:shadow-xl hover:bg-white">
            <div class="px-4 py-5 sm:p-6">
              <img class="object-cover w-24 h-24 mx-auto border border-gray-200 rounded-full" src="https://cdn.rareblocks.xyz/collection/clarity-ecommerce/images/categories/2/wireless-earphone.png" alt="">
              <p class="mt-5 text-base font-bold text-gray-900">
                <a href="#" title="">
                  True Wireless Earphone
                  <span class="absolute inset-0" aria-hidden="true"></span>
                </a>
              </p>
            </div>
          </div>

          <div class="relative transition-all duration-300 bg-gray-100 rounded-xl hover:shadow-xl hover:bg-white">
            <div class="px-4 py-5 sm:p-6">
              <img class="object-cover w-24 h-24 mx-auto border border-gray-200 rounded-full" src="https://cdn.rareblocks.xyz/collection/clarity-ecommerce/images/categories/2/wireless-headphone.png" alt="">
              <p class="mt-5 text-base font-bold text-gray-900">
                <a href="#" title="">
                  Wireless Headphone
                  <span class="absolute inset-0" aria-hidden="true"></span>
                </a>
              </p>
            </div>
          </div>

          <div class="relative transition-all duration-300 bg-gray-100 rounded-xl hover:shadow-xl hover:bg-white">
            <div class="px-4 py-5 sm:p-6">
              <img class="object-cover w-24 h-24 mx-auto border border-gray-200 rounded-full" src="https://cdn.rareblocks.xyz/collection/clarity-ecommerce/images/categories/2/smart-phones.png" alt="">
              <p class="mt-5 text-base font-bold text-gray-900">
                <a href="#" title="">
                  Smart <br class="block sm:hidden xl:block">Phones
                  <span class="absolute inset-0" aria-hidden="true"></span>
                </a>
              </p>
            </div>
          </div>

          <div class="relative transition-all duration-300 bg-gray-100 rounded-xl hover:shadow-xl hover:bg-white">
            <div class="px-4 py-5 sm:p-6">
              <img class="object-cover w-24 h-24 mx-auto border border-gray-200 rounded-full" src="https://cdn.rareblocks.xyz/collection/clarity-ecommerce/images/categories/2/runnies-shoes.png" alt="">
              <p class="mt-5 text-base font-bold text-gray-900">
                <a href="#" title="">
                  Running Shoes
                  <span class="absolute inset-0" aria-hidden="true"></span>
                </a>
              </p>
            </div>
          </div>

          <div class="relative transition-all duration-300 bg-gray-100 rounded-xl hover:shadow-xl hover:bg-white">
            <div class="px-4 py-5 sm:p-6">
              <img class="object-cover w-24 h-24 mx-auto border border-gray-200 rounded-full" src="https://cdn.rareblocks.xyz/collection/clarity-ecommerce/images/categories/2/leather-items.png" alt="">
              <p class="mt-5 text-base font-bold text-gray-900">
                <a href="#" title="">
                  Leather Items
                  <span class="absolute inset-0" aria-hidden="true"></span>
                </a>
              </p>
            </div>
          </div>
        </div>

        <div class="block mt-8 text-center md:hidden">
          <a href="#" title="" class="inline-flex items-center p-1 -m-1 text-xs font-bold tracking-wide text-gray-400 uppercase transition-all duration-200 rounded hover:text-gray-900 focus:ring-2 focus:text-gray-900 focus:ring-gray-900 focus:ring-offset-2 focus:outline-none" role="button">
            All Categories
            <svg class="w-4 h-4 ml-1.5 -mt-0.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
          </a>
        </div>
      </div>
    </section>

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
