<?php
/**
 * The template for the blog index (the posts page).
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package SheCy
 */

get_header(); ?>

<main id="primary" class="site-main">
	<div class="shecy-container shecy-mx-auto shecy-px-4 shecy-py-12">

		<header class="shecy-text-center shecy-mb-12">
			<h1 class="shecy-text-4xl shecy-font-bold">The She Cy Blog</h1>
			<p class="shecy-text-lg shecy-text-gray-600 shecy-mt-2">Your source for beauty, fashion tips, and trends.</p>
		</header>

		<?php if ( have_posts() ) : ?>
			<div class="shecy-grid shecy-grid-cols-1 md:shecy-grid-cols-2 lg:shecy-grid-cols-3 shecy-gap-8">
				<?php
				while ( have_posts() ) :
					the_post();
					?>
					<article id="post-<?php the_ID(); ?>" <?php post_class('shecy-bg-white shecy-rounded-lg shecy-shadow-md shecy-overflow-hidden shecy-flex shecy-flex-col'); ?>>
						<?php if ( has_post_thumbnail() ) : ?>
							<a href="<?php the_permalink(); ?>" class="shecy-block">
								<?php the_post_thumbnail( 'medium_large', ['class' => 'shecy-w-full shecy-h-56 shecy-object-cover'] ); ?>
							</a>
						<?php endif; ?>

						<div class="shecy-p-6 shecy-flex-grow shecy-flex shecy-flex-col">
							<header class="shecy-entry-header shecy-mb-4">
								<div class="shecy-entry-meta shecy-text-sm shecy-text-gray-500 shecy-mb-2">
									<span><?php echo get_the_date(); ?></span>
									<span class="shecy-mx-1">&bull;</span>
									<span><?php echo get_the_category_list(', '); ?></span>
								</div>
								<?php the_title( sprintf( '<h2 class="shecy-entry-title shecy-text-xl shecy-font-bold"><a href="%s" rel="bookmark" class="hover:shecy-text-pink-500">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
							</header>

							<div class="shecy-entry-summary shecy-text-gray-700 shecy-flex-grow">
								<?php the_excerpt(); ?>
							</div>

							<footer class="shecy-entry-footer shecy-mt-4">
								<a href="<?php the_permalink(); ?>" class="shecy-text-pink-500 hover:shecy-text-pink-600 shecy-font-semibold">Read More &rarr;</a>
							</footer>
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
					'screen_reader_text' => __( 'Posts navigation' ),
				)
			);
			?>

		<?php else : ?>
			<section class="shecy-no-results shecy-not-found shecy-text-center shecy-py-16">
				<header class="shecy-page-header">
					<h1 class="shecy-page-title shecy-text-2xl shecy-font-bold shecy-mb-4"><?php esc_html_e( 'Nothing Found', 'shecy' ); ?></h1>
				</header>
				<div class="shecy-page-content">
					<p><?php esc_html_e( 'It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'shecy' ); ?></p>
					<?php get_search_form(); ?>
				</div>
			</section>
		<?php endif; ?>

	</div>
</main>

<?php get_footer(); ?>
