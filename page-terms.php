<?php
/**
 * Template Name: Terms and Conditions
 *
 * @package SheCy
 */

get_header();
?>

<main id="primary" class="site-main">
	<div class="shecy-container shecy-mx-auto shecy-px-4 shecy-py-12">
		<div class="shecy-max-w-3xl shecy-mx-auto">

			<header class="shecy-entry-header shecy-text-center shecy-mb-8">
				<?php the_title( '<h1 class="shecy-entry-title shecy-text-4xl shecy-font-bold">', '</h1>' ); ?>
			</header>

			<div class="shecy-bg-white shecy-p-8 shecy-rounded-lg shecy-shadow-md">
				<div class="shecy-entry-content shecy-prose lg:shecy-prose-xl shecy-max-w-none">
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
