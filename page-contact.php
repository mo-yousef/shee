<?php
/**
 * Template Name: Contact Us
 *
 * @package SheCy
 */

get_header();
?>

<main id="primary" class="site-main">
	<div class="shecy-container shecy-mx-auto shecy-px-4 shecy-py-12">
		<div class="shecy-max-w-2xl shecy-mx-auto">

			<header class="shecy-text-center shecy-mb-8">
				<?php the_title( '<h1 class="shecy-entry-title shecy-text-4xl shecy-font-bold">', '</h1>' ); ?>
				<p class="shecy-mt-2 shecy-text-lg shecy-text-gray-600">We'd love to hear from you. Send us a message below.</p>
			</header>

			<div class="shecy-bg-white shecy-p-8 shecy-rounded-lg shecy-shadow-md">

				<?php // Display page content above the form, if any ?>
				<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
					<div class="shecy-entry-content shecy-prose shecy-max-w-none shecy-mb-8">
						<?php the_content(); ?>
					</div>
				<?php endwhile; endif; ?>

				<?php // Note: This is a UI-only form. Backend processing would require a plugin or custom handler. ?>
				<form id="contact-form" class="shecy-space-y-6">
					<div>
						<label for="contact_name" class="shecy-block shecy-text-sm shecy-font-medium shecy-text-gray-700">Your Name</label>
						<input type="text" name="contact_name" id="contact_name" required class="shecy-mt-1 shecy-block shecy-w-full shecy-py-2 shecy-px-3 shecy-border shecy-border-gray-300 shecy-rounded-md shecy-shadow-sm">
					</div>
					<div>
						<label for="contact_email" class="shecy-block shecy-text-sm shecy-font-medium shecy-text-gray-700">Your Email</label>
						<input type="email" name="contact_email" id="contact_email" required class="shecy-mt-1 shecy-block shecy-w-full shecy-py-2 shecy-px-3 shecy-border shecy-border-gray-300 shecy-rounded-md shecy-shadow-sm">
					</div>
					<div>
						<label for="contact_subject" class="shecy-block shecy-text-sm shecy-font-medium shecy-text-gray-700">Subject</label>
						<input type="text" name="contact_subject" id="contact_subject" required class="shecy-mt-1 shecy-block shecy-w-full shecy-py-2 shecy-px-3 shecy-border shecy-border-gray-300 shecy-rounded-md shecy-shadow-sm">
					</div>
					<div>
						<label for="contact_message" class="shecy-block shecy-text-sm shecy-font-medium shecy-text-gray-700">Message</label>
						<textarea name="contact_message" id="contact_message" rows="5" required class="shecy-mt-1 shecy-block shecy-w-full shecy-py-2 shecy-px-3 shecy-border shecy-border-gray-300 shecy-rounded-md shecy-shadow-sm"></textarea>
					</div>
					<div class="shecy-text-right">
						<button type="submit" class="shecy-inline-flex shecy-justify-center shecy-py-3 shecy-px-6 shecy-border shecy-border-transparent shecy-shadow-sm shecy-text-base shecy-font-medium shecy-rounded-md shecy-text-white shecy-bg-pink-500 hover:shecy-bg-pink-600">Send Message</button>
					</div>
				</form>
			</div>

		</div>
	</div>
</main>

<?php
get_footer();
?>
