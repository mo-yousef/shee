<?php
/**
 * Template Name: Submit Business
 *
 * @package SheCy
 */

// Redirect non-logged-in users.
if ( ! is_user_logged_in() ) {
	wp_redirect( home_url( '/login' ) );
	exit;
}

// Handle form submission
if ( 'POST' == $_SERVER['REQUEST_METHOD'] && ! empty( $_POST['action'] ) && $_POST['action'] == 'submit-business' ) {
	// Security check
	if ( ! isset( $_POST['submit_business_nonce'] ) || ! wp_verify_nonce( $_POST['submit_business_nonce'], 'submit_business' ) ) {
		wp_die( 'Security check failed.' );
	}

	// Sanitize and prepare post data
	$title       = sanitize_text_field( $_POST['business_name'] );
	$description = wp_kses_post( $_POST['business_description'] );
	$category_id = intval( $_POST['business_category'] );

	// Sanitize meta fields
	$services = sanitize_textarea_field( $_POST['business_services'] );
	$location = sanitize_text_field( $_POST['business_location'] );
	$phone    = sanitize_text_field( $_POST['business_phone'] );
	$email    = sanitize_email( $_POST['business_email'] );
	$website  = esc_url_raw( $_POST['business_website'] );

	$new_post = array(
		'post_title'    => $title,
		'post_content'  => $description,
		'post_status'   => 'draft',
		'post_type'     => 'shecy_business',
		'post_author'   => get_current_user_id(),
	);

	$post_id = wp_insert_post( $new_post );

	if ( $post_id && ! is_wp_error( $post_id ) ) {
		// Add meta and terms
		if ( ! empty( $services ) ) update_post_meta( $post_id, 'business_services', $services );
		if ( ! empty( $location ) ) update_post_meta( $post_id, 'business_location', $location );
		if ( ! empty( $phone ) ) update_post_meta( $post_id, 'business_phone', $phone );
		if ( ! empty( $email ) ) update_post_meta( $post_id, 'business_email', $email );
		if ( ! empty( $website ) ) update_post_meta( $post_id, 'business_website', $website );
		if ( ! empty( $category_id ) ) {
			wp_set_post_terms( $post_id, array( $category_id ), 'shecy_business_category' );
		}

		// Handle logo upload
		if ( ! empty( $_FILES['business_logo']['name'] ) ) {
			if ( ! function_exists( 'wp_handle_upload' ) ) {
				require_once( ABSPATH . 'wp-admin/includes/file.php' );
			}
			$uploadedfile = $_FILES['business_logo'];
			$upload_overrides = array( 'test_form' => false );
			$movefile = wp_handle_upload( $uploadedfile, $upload_overrides );

			if ( $movefile && ! isset( $movefile['error'] ) ) {
				$filename = $movefile['file'];
				$attachment = array(
					'post_mime_type' => $movefile['type'],
					'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $filename ) ),
					'post_content'   => '',
					'post_status'    => 'inherit',
				);
				$attach_id = wp_insert_attachment( $attachment, $filename, $post_id );
				require_once( ABSPATH . 'wp-admin/includes/image.php' );
				$attach_data = wp_generate_attachment_metadata( $attach_id, $filename );
				wp_update_attachment_metadata( $attach_id, $attach_data );
				set_post_thumbnail( $post_id, $attach_id );
			}
		}

		wp_redirect( home_url( '/dashboard?tab=businesses&submitted=true' ) );
		exit;
	} else {
		wp_die( 'Error creating business listing.' );
	}
}


get_header();
?>

<main id="primary" class="site-main">
	<div class="shecy-container shecy-mx-auto shecy-px-4 shecy-py-12">
		<div class="shecy-max-w-2xl shecy-mx-auto shecy-bg-white shecy-p-8 shecy-rounded-lg shecy-shadow-md">
			<header class="shecy-text-center shecy-mb-8">
				<h1 class="shecy-text-3xl shecy-font-bold">Promote Your Business</h1>
				<p class="shecy-text-gray-600 shecy-mt-2">Add your business to our directory by filling out the form below.</p>
			</header>

			<form id="submit-business-form" method="post" enctype="multipart/form-data">
				<input type="hidden" name="action" value="submit-business">
				<?php wp_nonce_field( 'submit_business', 'submit_business_nonce' ); ?>

				<div class="shecy-space-y-6">
					<div>
						<label for="business_name" class="shecy-block shecy-text-sm shecy-font-medium shecy-text-gray-700">Business Name <span class="shecy-text-red-500">*</span></label>
						<input type="text" name="business_name" id="business_name" required class="shecy-mt-1 shecy-block shecy-w-full shecy-py-2 shecy-px-3 shecy-border shecy-border-gray-300 shecy-rounded-md shecy-shadow-sm">
					</div>

					<div>
						<label for="business_description" class="shecy-block shecy-text-sm shecy-font-medium shecy-text-gray-700">Description <span class="shecy-text-red-500">*</span></label>
						<textarea name="business_description" id="business_description" rows="5" required class="shecy-mt-1 shecy-block shecy-w-full shecy-py-2 shecy-px-3 shecy-border shecy-border-gray-300 shecy-rounded-md shecy-shadow-sm"></textarea>
					</div>

					<div>
						<label for="business_services" class="shecy-block shecy-text-sm shecy-font-medium shecy-text-gray-700">Services</label>
						<textarea name="business_services" id="business_services" rows="3" class="shecy-mt-1 shecy-block shecy-w-full shecy-py-2 shecy-px-3 shecy-border shecy-border-gray-300 shecy-rounded-md shecy-shadow-sm" placeholder="e.g. Manicures, Facials, Haircuts"></textarea>
						<p class="shecy-mt-2 shecy-text-sm shecy-text-gray-500">Separate services with a comma.</p>
					</div>

					<div>
						<label for="business_category" class="shecy-block shecy-text-sm shecy-font-medium shecy-text-gray-700">Category <span class="shecy-text-red-500">*</span></label>
						<?php
						wp_dropdown_categories( array(
							'taxonomy'         => 'shecy_business_category',
							'name'             => 'business_category',
							'id'               => 'business_category',
							'required'         => true,
							'show_option_none' => 'Select a category',
							'hierarchical'     => true,
							'class'            => 'shecy-mt-1 shecy-block shecy-w-full shecy-py-2 shecy-px-3 shecy-border shecy-border-gray-300 shecy-rounded-md shecy-shadow-sm',
						) );
						?>
					</div>

					<div class="shecy-grid shecy-grid-cols-1 md:shecy-grid-cols-2 shecy-gap-6">
						<div>
							<label for="business_location" class="shecy-block shecy-text-sm shecy-font-medium shecy-text-gray-700">Location / Address</label>
							<input type="text" name="business_location" id="business_location" class="shecy-mt-1 shecy-block shecy-w-full shecy-py-2 shecy-px-3 shecy-border shecy-border-gray-300 shecy-rounded-md shecy-shadow-sm">
						</div>
						<div>
							<label for="business_phone" class="shecy-block shecy-text-sm shecy-font-medium shecy-text-gray-700">Phone Number</label>
							<input type="tel" name="business_phone" id="business_phone" class="shecy-mt-1 shecy-block shecy-w-full shecy-py-2 shecy-px-3 shecy-border shecy-border-gray-300 shecy-rounded-md shecy-shadow-sm">
						</div>
						<div>
							<label for="business_email" class="shecy-block shecy-text-sm shecy-font-medium shecy-text-gray-700">Contact Email</label>
							<input type="email" name="business_email" id="business_email" class="shecy-mt-1 shecy-block shecy-w-full shecy-py-2 shecy-px-3 shecy-border shecy-border-gray-300 shecy-rounded-md shecy-shadow-sm">
						</div>
						<div>
							<label for="business_website" class="shecy-block shecy-text-sm shecy-font-medium shecy-text-gray-700">Website URL</label>
							<input type="url" name="business_website" id="business_website" class="shecy-mt-1 shecy-block shecy-w-full shecy-py-2 shecy-px-3 shecy-border shecy-border-gray-300 shecy-rounded-md shecy-shadow-sm" placeholder="https://example.com">
						</div>
					</div>

					<div>
						<label for="business_logo" class="shecy-block shecy-text-sm shecy-font-medium shecy-text-gray-700">Business Logo / Image</label>
						<input type="file" name="business_logo" id="business_logo" accept="image/*" class="shecy-mt-1 shecy-block shecy-w-full shecy-text-sm shecy-text-gray-500 file:shecy-mr-4 file:shecy-py-2 file:shecy-px-4 file:shecy-rounded-full file:shecy-border-0 file:shecy-text-sm file:shecy-font-semibold file:shecy-bg-pink-50 file:shecy-text-pink-700 hover:file:shecy-bg-pink-100">
					</div>
				</div>

				<div class="shecy-mt-8">
					<button type="submit" class="shecy-w-full shecy-inline-flex shecy-justify-center shecy-py-3 shecy-px-4 shecy-border shecy-border-transparent shecy-shadow-sm shecy-text-base shecy-font-medium shecy-rounded-md shecy-text-white shecy-bg-pink-500 hover:shecy-bg-pink-600">Submit for Review</button>
				</div>
			</form>
		</div>
	</div>
</main>

<?php
get_footer();
?>
