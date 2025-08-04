<?php
/**
 * The template for displaying the business directory archive for shecy_business CPT
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
			<p class="text-lg text-gray-600 mt-2">Find and support businesses in the beauty and fashion industry.</p>
		</header>

		<?php
		// Filters Section - UI only for now
		?>
		<section class="mb-12 p-6 bg-gray-100 rounded-lg">
			<form role="search" method="get" action="<?php echo esc_url( get_post_type_archive_link( 'shecy_business' ) ); ?>" class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center">
				<div class="md:col-span-2">
					<label for="filter-category" class="block text-sm font-medium text-gray-700">Filter by Category</label>
					<?php
					wp_dropdown_categories(array(
						'show_option_none' => 'All Business Categories',
						'taxonomy'         => 'shecy_business_category',
						'name'             => 'shecy_business_category',
						'hierarchical'     => true,
						'class'            => 'mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-violet-500 focus:border-violet-500',
						'value_field'      => 'slug',
						'selected'         => get_query_var( 'shecy_business_category' ),
					));
					?>
				</div>
				<div class="text-right">
					<button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-violet-500 hover:bg-violet-600">Filter</button>
				</div>
			</form>
		</section>

		<?php if ( have_posts() ) : ?>
			<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
				<?php
				while ( have_posts() ) :
					the_post();
					?>
					<article class="bg-white rounded-lg shadow-md overflow-hidden group flex flex-col text-center">
						<a href="<?php the_permalink(); ?>" class="block p-6">
							<?php if (has_post_thumbnail()) : ?>
								<?php the_post_thumbnail('medium', ['class' => 'w-32 h-32 object-cover rounded-full mx-auto mb-4 shadow-lg']); ?>
							<?php else: ?>
								<div class="w-32 h-32 bg-gray-200 rounded-full mx-auto mb-4 flex items-center justify-center">
									<span class="text-gray-500">No Logo</span>
								</div>
							<?php endif; ?>
						</a>
						<div class="p-4 pt-0 flex-grow flex flex-col">
							<h3 class="font-bold text-xl mb-2"><a href="<?php the_permalink(); ?>" class="hover:text-violet-500"><?php the_title(); ?></a></h3>
							<div class="text-gray-600 flex-grow">
								<?php the_excerpt(); ?>
							</div>
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
					'screen_reader_text' => __( 'Businesses navigation' ),
				)
			);
			?>

		<?php else : ?>
			<div class="text-center py-16">
				<h2 class="text-2xl font-bold mb-4">Nothing Found</h2>
				<p class="text-gray-600">No businesses were found matching your criteria. Try adjusting your filters.</p>
			</div>
		<?php endif; ?>

	</div>
</main>

<?php get_footer(); ?>
