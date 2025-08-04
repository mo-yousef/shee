<?php
/**
 * Template Name: Contact Us
 *
 * @package SheCy
 */

get_header();
?>

<main id="primary" class="site-main">
	<div class="container mx-auto px-4 py-12">
		<div class="max-w-2xl mx-auto">

			<header class="text-center mb-8">
				<?php the_title( '<h1 class="entry-title text-4xl font-bold">', '</h1>' ); ?>
				<p class="mt-2 text-lg text-gray-600">We'd love to hear from you. Send us a message below.</p>
			</header>

			<div class="bg-white p-8 rounded-lg shadow-md">

				<?php // Display page content above the form, if any ?>
				<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
					<div class="entry-content prose max-w-none mb-8">
						<?php the_content(); ?>
					</div>
				<?php endwhile; endif; ?>

				<?php // Note: This is a UI-only form. Backend processing would require a plugin or custom handler. ?>
				<form id="contact-form" class="space-y-6">
					<div>
						<label for="contact_name" class="block text-sm font-medium text-gray-700">Your Name</label>
						<input type="text" name="contact_name" id="contact_name" required class="mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm">
					</div>
					<div>
						<label for="contact_email" class="block text-sm font-medium text-gray-700">Your Email</label>
						<input type="email" name="contact_email" id="contact_email" required class="mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm">
					</div>
					<div>
						<label for="contact_subject" class="block text-sm font-medium text-gray-700">Subject</label>
						<input type="text" name="contact_subject" id="contact_subject" required class="mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm">
					</div>
					<div>
						<label for="contact_message" class="block text-sm font-medium text-gray-700">Message</label>
						<textarea name="contact_message" id="contact_message" rows="5" required class="mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm"></textarea>
					</div>
					<div class="text-right">
						<button type="submit" class="inline-flex justify-center py-3 px-6 border border-transparent shadow-sm text-base font-medium rounded-md text-white bg-pink-500 hover:bg-pink-600">Send Message</button>
					</div>
				</form>
			</div>

		</div>
	</div>
</main>

<?php
get_footer();
?>
