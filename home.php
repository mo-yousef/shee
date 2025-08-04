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
	<div class="container mx-auto px-4 py-12">

		<header class="text-center mb-12">
			<h1 class="text-4xl font-bold">The She Cy Blog</h1>
			<p class="text-lg text-gray-600 mt-2">Your source for beauty, fashion tips, and trends.</p>
		</header>

		<?php if ( have_posts() ) : ?>
			<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
				<?php
				while ( have_posts() ) :
					the_post();
					?>
					<article id="post-<?php the_ID(); ?>" <?php post_class('bg-white rounded-lg shadow-md overflow-hidden flex flex-col'); ?>>
						<?php if ( has_post_thumbnail() ) : ?>
							<a href="<?php the_permalink(); ?>" class="block">
								<?php the_post_thumbnail( 'medium_large', ['class' => 'w-full h-56 object-cover'] ); ?>
							</a>
						<?php endif; ?>

						<div class="p-6 flex-grow flex flex-col">
							<header class="entry-header mb-4">
								<div class="entry-meta text-sm text-gray-500 mb-2">
									<span><?php echo get_the_date(); ?></span>
									<span class="mx-1">&bull;</span>
									<span><?php echo get_the_category_list(', '); ?></span>
								</div>
								<?php the_title( sprintf( '<h2 class="entry-title text-xl font-bold"><a href="%s" rel="bookmark" class="hover:text-pink-500">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
							</header>

							<div class="entry-summary text-gray-700 flex-grow">
								<?php the_excerpt(); ?>
							</div>

							<footer class="entry-footer mt-4">
								<a href="<?php the_permalink(); ?>" class="text-pink-500 hover:text-pink-600 font-semibold">Read More &rarr;</a>
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
					'prev_text' => '<span class="sr-only">Previous</span><svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>',
					'next_text' => '<span class="sr-only">Next</span><svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" /></svg>',
					'screen_reader_text' => __( 'Posts navigation' ),
				)
			);
			?>

		<?php else : ?>
			<section class="no-results not-found text-center py-16">
				<header class="page-header">
					<h1 class="page-title text-2xl font-bold mb-4"><?php esc_html_e( 'Nothing Found', 'shecy' ); ?></h1>
				</header>
				<div class="page-content">
					<p><?php esc_html_e( 'It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'shecy' ); ?></p>
					<?php get_search_form(); ?>
				</div>
			</section>
		<?php endif; ?>

	</div>
</main>

<?php get_footer(); ?>
