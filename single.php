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
	<div class="shecy-container shecy-mx-auto shecy-px-4 shecy-py-12">
		<div class="shecy-max-w-3xl shecy-mx-auto">

			<?php
			while ( have_posts() ) :
				the_post();
				?>
				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					<header class="shecy-entry-header shecy-mb-8 shecy-text-center">
						<div class="shecy-entry-meta shecy-text-sm shecy-text-gray-500 shecy-mb-2">
							<span><?php echo get_the_date(); ?></span>
							<span class="shecy-mx-1">&bull;</span>
							<span><?php echo get_the_category_list(', '); ?></span>
						</div>
						<?php the_title( '<h1 class="shecy-entry-title shecy-text-4xl shecy-font-bold">', '</h1>' ); ?>
						<p class="shecy-mt-2 shecy-text-lg shecy-text-gray-600">
							by <?php the_author_posts_link(); ?>
						</p>
					</header>

					<?php if ( has_post_thumbnail() ) : ?>
						<div class="shecy-post-thumbnail shecy-mb-8 shecy-rounded-lg shecy-shadow-lg shecy-overflow-hidden">
							<?php the_post_thumbnail( 'large' ); ?>
						</div>
					<?php endif; ?>

					<div class="shecy-entry-content shecy-prose lg:shecy-prose-xl shecy-max-w-none">
						<?php
						the_content();

						wp_link_pages(
							array(
								'before' => '<div class="shecy-page-links">' . esc_html__( 'Pages:', 'shecy' ),
								'after'  => '</div>',
							)
						);
						?>
					</div>

					<footer class="shecy-entry-footer shecy-mt-8 shecy-pt-8 shecy-border-t shecy-border-gray-200">
						<div class="shecy-tags-links">
							<?php the_tags( '<span class="shecy-font-bold">Tags:</span> <span class="shecy-tag-list">', ' ', '</span>' ); ?>
						</div>
					</footer>

					<?php // Author Box
					$author_bio = get_the_author_meta('description');
					if ($author_bio) :
					?>
					<div class="shecy-author-box shecy-mt-8 shecy-p-6 shecy-bg-gray-50 shecy-rounded-lg shecy-flex shecy-items-center">
						<div class="shecy-author-avatar shecy-mr-6">
							<?php echo get_avatar( get_the_author_meta( 'ID' ), 96, '', '', ['class' => 'shecy-rounded-full'] ); ?>
						</div>
						<div class="shecy-author-info">
							<h3 class="shecy-author-title shecy-text-xl shecy-font-bold">
								<a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>" rel="author"><?php echo get_the_author(); ?></a>
							</h3>
							<p class="shecy-author-bio shecy-text-gray-600">
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
