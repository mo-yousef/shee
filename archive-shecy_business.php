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
	<div class="shecy-container shecy-mx-auto shecy-px-4 shecy-py-12">

		<header class="shecy-text-center shecy-mb-12">
			<h1 class="shecy-text-4xl shecy-font-bold"><?php post_type_archive_title(); ?></h1>
			<p class="shecy-text-lg shecy-text-gray-600 shecy-mt-2">Find and support businesses in the beauty and fashion industry.</p>
		</header>

		<?php
		// Filters Section - UI only for now
		?>
		<section class="shecy-mb-12 shecy-p-6 shecy-bg-gray-100 shecy-rounded-lg">
			<form role="search" method="get" action="<?php echo esc_url( get_post_type_archive_link( 'shecy_business' ) ); ?>" class="shecy-grid shecy-grid-cols-1 md:shecy-grid-cols-3 shecy-gap-4 shecy-items-center">
				<div class="md:shecy-col-span-2">
					<label for="filter-category" class="shecy-block shecy-text-sm shecy-font-medium shecy-text-gray-700">Filter by Category</label>
					<?php
					wp_dropdown_categories(array(
						'show_option_none' => 'All Business Categories',
						'taxonomy'         => 'shecy_business_category',
						'name'             => 'shecy_business_category',
						'hierarchical'     => true,
						'class'            => 'shecy-mt-1 shecy-block shecy-w-full shecy-py-2 shecy-px-3 shecy-border shecy-border-gray-300 shecy-bg-white shecy-rounded-md shecy-shadow-sm focus:shecy-outline-none focus:shecy-ring-pink-500 focus:shecy-border-pink-500',
						'value_field'      => 'slug',
						'selected'         => get_query_var( 'shecy_business_category' ),
					));
					?>
				</div>
				<div class="shecy-text-right">
					<button type="submit" class="shecy-inline-flex shecy-justify-center shecy-py-2 shecy-px-4 shecy-border shecy-border-transparent shecy-shadow-sm shecy-text-sm shecy-font-medium shecy-rounded-md shecy-text-white shecy-bg-pink-500 hover:shecy-bg-pink-600">Filter</button>
				</div>
			</form>
		</section>

		<?php if ( have_posts() ) : ?>
			<div class="shecy-grid shecy-grid-cols-1 sm:shecy-grid-cols-2 lg:shecy-grid-cols-3 shecy-gap-8">
				<?php
				while ( have_posts() ) :
					the_post();
					?>
					<article class="shecy-bg-white shecy-rounded-lg shecy-shadow-md shecy-overflow-hidden shecy-group shecy-flex shecy-flex-col shecy-text-center">
						<a href="<?php the_permalink(); ?>" class="shecy-block shecy-p-6">
							<?php if (has_post_thumbnail()) : ?>
								<?php the_post_thumbnail('medium', ['class' => 'shecy-w-32 shecy-h-32 shecy-object-cover shecy-rounded-full shecy-mx-auto shecy-mb-4 shecy-shadow-lg']); ?>
							<?php else: ?>
								<div class="shecy-w-32 shecy-h-32 shecy-bg-gray-200 shecy-rounded-full shecy-mx-auto shecy-mb-4 shecy-flex shecy-items-center shecy-justify-center">
									<span class="shecy-text-gray-500">No Logo</span>
								</div>
							<?php endif; ?>
						</a>
						<div class="shecy-p-4 shecy-pt-0 shecy-flex-grow shecy-flex shecy-flex-col">
							<h3 class="shecy-font-bold shecy-text-xl shecy-mb-2"><a href="<?php the_permalink(); ?>" class="hover:shecy-text-pink-500"><?php the_title(); ?></a></h3>
							<div class="shecy-text-gray-600 shecy-flex-grow">
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
					'prev_text' => '<span class="shecy-sr-only">Previous</span><svg class="shecy-h-5 shecy-w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>',
					'next_text' => '<span class="shecy-sr-only">Next</span><svg class="shecy-h-5 shecy-w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" /></svg>',
					'screen_reader_text' => __( 'Businesses navigation' ),
				)
			);
			?>

		<?php else : ?>
			<div class="shecy-text-center shecy-py-16">
				<h2 class="shecy-text-2xl shecy-font-bold shecy-mb-4">Nothing Found</h2>
				<p class="shecy-text-gray-600">No businesses were found matching your criteria. Try adjusting your filters.</p>
			</div>
		<?php endif; ?>

	</div>
</main>

<?php get_footer(); ?>
