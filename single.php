<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package SheCy
 */

get_header();
?>

<main id="primary" class="site-main">
	<div class="container mx-auto px-4 py-12">
		<div class="max-w-3xl mx-auto">

			<?php
			while ( have_posts() ) :
				the_post();
				?>
				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					<header class="entry-header mb-8 text-center">
						<div class="entry-meta text-sm text-gray-500 mb-2">
							<span><?php echo get_the_date(); ?></span>
							<span class="mx-1">&bull;</span>
							<span><?php echo get_the_category_list(', '); ?></span>
						</div>
						<?php the_title( '<h1 class="entry-title text-4xl font-bold">', '</h1>' ); ?>
						<p class="mt-2 text-lg text-gray-600">
							by <?php the_author_posts_link(); ?>
						</p>
					</header>

					<?php if ( has_post_thumbnail() ) : ?>
						<div class="post-thumbnail mb-8 rounded-lg shadow-lg overflow-hidden">
							<?php the_post_thumbnail( 'large' ); ?>
						</div>
					<?php endif; ?>

					<div class="entry-content prose lg:prose-xl max-w-none">
						<?php
						the_content();

						wp_link_pages(
							array(
								'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'shecy' ),
								'after'  => '</div>',
							)
						);
						?>
					</div>

					<footer class="entry-footer mt-8 pt-8 border-t border-gray-200">
						<div class="tags-links">
							<?php the_tags( '<span class="font-bold">Tags:</span> <span class="tag-list">', ' ', '</span>' ); ?>
						</div>
					</footer>

					<?php // Author Box
					$author_bio = get_the_author_meta('description');
					if ($author_bio) :
					?>
					<div class="author-box mt-8 p-6 bg-gray-50 rounded-lg flex items-center">
						<div class="author-avatar mr-6">
							<?php echo get_avatar( get_the_author_meta( 'ID' ), 96, '', '', ['class' => 'rounded-full'] ); ?>
						</div>
						<div class="author-info">
							<h3 class="author-title text-xl font-bold">
								<a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>" rel="author"><?php echo get_the_author(); ?></a>
							</h3>
							<p class="author-bio text-gray-600">
								<?php echo $author_bio; ?>
							</p>
						</div>
					</div>
					<?php endif; ?>

				</article>

				<?php
				// If comments are open or we have at least one comment, load up the comment template.
				if ( comments_open() || get_comments_number() ) :
					comments_template();
				endif;

			endwhile;
			?>
		</div>
	</div>
</main>

<?php
get_footer();
?>
