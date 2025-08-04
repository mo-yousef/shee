<?php
/**
 * Template Name: Privacy Policy
 *
 * @package SheCy
 */

get_header();
?>

<main id="primary" class="site-main">
	<div class="container mx-auto px-4 py-12">
		<div class="max-w-3xl mx-auto">

			<header class="entry-header text-center mb-8">
				<?php the_title( '<h1 class="entry-title text-4xl font-bold">', '</h1>' ); ?>
			</header>

			<div class="bg-white p-8 rounded-lg shadow-md">
				<div class="entry-content prose lg:prose-xl max-w-none">
					<?php
					while ( have_posts() ) :
						the_post();
						the_content();
					endwhile;
					?>
				</div>
			</div>

		</div>
	</div>
</main>

<?php
get_footer();
?>
