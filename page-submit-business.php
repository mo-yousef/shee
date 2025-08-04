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
		'post_status'   => 'pending',
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
	<div class="container mx-auto px-4 py-12">
		<div class="max-w-2xl mx-auto bg-white p-8 rounded-lg shadow-md">
			<header class="text-center mb-8">
				<h1 class="text-3xl font-bold">Promote Your Business</h1>
				<p class="text-gray-600 mt-2">Add your business to our directory by filling out the form below.</p>
			</header>

			<form id="submit-business-form" method="post" enctype="multipart/form-data">
				<input type="hidden" name="action" value="submit-business">
				<?php wp_nonce_field( 'submit_business', 'submit_business_nonce' ); ?>

				<div class="space-y-6">
					<div>
						<label for="business_name" class="block text-sm font-medium text-gray-700">Business Name <span class="text-red-500">*</span></label>
						<input type="text" name="business_name" id="business_name" required class="mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:ring-violet-500 focus:border-violet-500">
					</div>

					<div>
						<label for="business_description" class="block text-sm font-medium text-gray-700">Description <span class="text-red-500">*</span></label>
						<textarea name="business_description" id="business_description" rows="5" required class="mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:ring-violet-500 focus:border-violet-500"></textarea>
					</div>

					<div>
						<label for="business_services" class="block text-sm font-medium text-gray-700">Services</label>
						<textarea name="business_services" id="business_services" rows="3" class="mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:ring-violet-500 focus:border-violet-500" placeholder="e.g. Manicures, Facials, Haircuts"></textarea>
						<p class="mt-2 text-sm text-gray-500">Separate services with a comma.</p>
					</div>

					<div>
						<label for="business_category" class="block text-sm font-medium text-gray-700">Category <span class="text-red-500">*</span></label>
						<?php
						wp_dropdown_categories( array(
							'taxonomy'         => 'shecy_business_category',
							'name'             => 'business_category',
							'id'               => 'business_category',
							'required'         => true,
							'show_option_none' => 'Select a category',
							'hierarchical'     => true,
							'class'            => 'mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm',
						) );
						?>
					</div>

					<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
						<div>
							<label for="business_location" class="block text-sm font-medium text-gray-700">Location / Address</label>
							<input type="text" name="business_location" id="business_location" class="mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:ring-violet-500 focus:border-violet-500">
						</div>
						<div>
							<label for="business_phone" class="block text-sm font-medium text-gray-700">Phone Number</label>
							<input type="tel" name="business_phone" id="business_phone" class="mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:ring-violet-500 focus:border-violet-500">
						</div>
						<div>
							<label for="business_email" class="block text-sm font-medium text-gray-700">Contact Email</label>
							<input type="email" name="business_email" id="business_email" class="mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:ring-violet-500 focus:border-violet-500">
						</div>
						<div>
							<label for="business_website" class="block text-sm font-medium text-gray-700">Website URL</label>
							<input type="url" name="business_website" id="business_website" class="mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:ring-violet-500 focus:border-violet-500" placeholder="https://example.com">
						</div>
					</div>

					<div>
						<label for="business_logo" class="block text-sm font-medium text-gray-700">Business Logo / Image</label>
						<input type="file" name="business_logo" id="business_logo" accept="image/*" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-violet-50 file:text-violet-700 hover:file:bg-violet-100">
					</div>
				</div>

				<div class="mt-8">
					<button type="submit" class="w-full inline-flex justify-center py-3 px-4 border border-transparent shadow-sm text-base font-medium rounded-md text-white bg-violet-500 hover:bg-violet-600">Submit for Review</button>
				</div>
			</form>
		</div>
	</div>
</main>

<?php
get_footer();
?>
