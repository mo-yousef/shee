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
		<form method="post" enctype="multipart/form-data">
			<input type="hidden" name="action" value="submit-business">
			<?php wp_nonce_field( 'submit_business', 'submit_business_nonce' ); ?>
			<div class="space-y-12">
				<div class="border-b border-gray-900/10 pb-12">
					<h2 class="text-base font-semibold leading-7 text-gray-900">Promote Your Business</h2>
					<p class="mt-1 text-sm leading-6 text-gray-600">Add your business to our directory by filling out the form below.</p>

					<div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
						<div class="sm:col-span-4">
							<label for="business_name" class="block text-sm font-medium leading-6 text-gray-900">Business Name</label>
							<div class="mt-2">
								<input type="text" name="business_name" id="business_name" required class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
							</div>
						</div>

						<div class="col-span-full">
							<label for="business_description" class="block text-sm font-medium leading-6 text-gray-900">Description</label>
							<div class="mt-2">
								<textarea id="business_description" name="business_description" rows="3" required class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"></textarea>
							</div>
							<p class="mt-3 text-sm leading-6 text-gray-600">Write a few sentences about the business.</p>
						</div>

						<div class="col-span-full">
							<label for="business_services" class="block text-sm font-medium leading-6 text-gray-900">Services</label>
							<div class="mt-2">
								<textarea id="business_services" name="business_services" rows="3" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" placeholder="e.g. Manicures, Facials, Haircuts"></textarea>
							</div>
							<p class="mt-3 text-sm leading-6 text-gray-600">Separate services with a comma.</p>
						</div>

						<div class="sm:col-span-3">
							<label for="business_category" class="block text-sm font-medium leading-6 text-gray-900">Category</label>
							<div class="mt-2">
								<?php
								shecy_ensure_categories_exist('shecy_business_category');
								wp_dropdown_categories( array(
									'taxonomy'         => 'shecy_business_category',
									'name'             => 'business_category',
									'id'               => 'business_category',
									'required'         => true,
									'show_option_none' => 'Select a category',
									'hierarchical'     => true,
									'class'            => 'block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6',
								) );
								?>
							</div>
						</div>

						<div class="sm:col-span-3">
							<label for="business_location" class="block text-sm font-medium leading-6 text-gray-900">Location / Address</label>
							<div class="mt-2">
								<input type="text" name="business_location" id="business_location" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
							</div>
						</div>

						<div class="sm:col-span-2 sm:col-start-1">
							<label for="business_phone" class="block text-sm font-medium leading-6 text-gray-900">Phone Number</label>
							<div class="mt-2">
								<input type="tel" name="business_phone" id="business_phone" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
							</div>
						</div>

						<div class="sm:col-span-2">
							<label for="business_email" class="block text-sm font-medium leading-6 text-gray-900">Contact Email</label>
							<div class="mt-2">
								<input type="email" name="business_email" id="business_email" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
							</div>
						</div>

						<div class="sm:col-span-2">
							<label for="business_website" class="block text-sm font-medium leading-6 text-gray-900">Website URL</label>
							<div class="mt-2">
								<input type="url" name="business_website" id="business_website" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" placeholder="https://example.com">
							</div>
						</div>

						<div class="col-span-full">
							<label for="business_logo" class="block text-sm font-medium leading-6 text-gray-900">Business Logo / Image</label>
							<div class="mt-2 flex justify-center rounded-lg border border-dashed border-gray-900/25 px-6 py-10">
								<div class="text-center">
									<svg class="mx-auto h-12 w-12 text-gray-300" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
										<path fill-rule="evenodd" d="M1.5 6a2.25 2.25 0 012.25-2.25h16.5A2.25 2.25 0 0122.5 6v12a2.25 2.25 0 01-2.25 2.25H3.75A2.25 2.25 0 011.5 18V6zM3 16.06V18c0 .414.336.75.75.75h16.5a.75.75 0 00.75-.75v-1.94l-2.69-2.69a1.5 1.5 0 00-2.12 0l-.88.879.97.97a.75.75 0 11-1.06 1.06l-5.16-5.159a1.5 1.5 0 00-2.12 0L3 16.061zm10.125-7.81a1.125 1.125 0 112.25 0 1.125 1.125 0 01-2.25 0z" clip-rule="evenodd" />
									</svg>
									<div class="mt-4 flex text-sm leading-6 text-gray-600">
										<label for="business_logo" class="relative cursor-pointer rounded-md bg-white font-semibold text-indigo-600 focus-within:outline-none focus-within:ring-2 focus-within:ring-indigo-600 focus-within:ring-offset-2 hover:text-indigo-500">
											<span>Upload a file</span>
											<input id="business_logo" name="business_logo" type="file" class="sr-only">
										</label>
										<p class="pl-1">or drag and drop</p>
									</div>
									<p class="text-xs leading-5 text-gray-600">PNG, JPG, GIF up to 10MB</p>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="mt-6 flex items-center justify-end gap-x-6">
				<button type="button" class="text-sm font-semibold leading-6 text-gray-900">Cancel</button>
				<button type="submit" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Submit for Review</button>
			</div>
		</form>
	</div>
</main>

<?php
get_footer();
?>
