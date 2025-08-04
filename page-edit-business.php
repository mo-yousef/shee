<?php
/**
 * Template Name: Edit Business
 *
 * @package SheCy
 */

// --- Authentication & Authorization ---
if ( ! is_user_logged_in() ) {
	wp_redirect( home_url( '/login' ) );
	exit;
}

$business_id = isset( $_GET['business_id'] ) ? intval( $_GET['business_id'] ) : 0;
if ( ! $business_id ) {
	wp_redirect( home_url( '/dashboard' ) );
	exit;
}

$post = get_post( $business_id );
if ( ! $post || $post->post_author != get_current_user_id() || $post->post_type !== 'shecy_business' ) {
	wp_die( 'You do not have permission to edit this business listing.', 'Permission Denied', array( 'response' => 403 ) );
}

// --- Form Processing ---
if ( 'POST' == $_SERVER['REQUEST_METHOD'] && ! empty( $_POST['action'] ) && $_POST['action'] == 'edit-business' ) {
	if ( ! isset( $_POST['edit_business_nonce'] ) || ! wp_verify_nonce( $_POST['edit_business_nonce'], 'edit_business_' . $business_id ) ) {
		wp_die( 'Security check failed.' );
	}

	$title       = sanitize_text_field( $_POST['business_name'] );
	$description = wp_kses_post( $_POST['business_description'] );
	$category_id = intval( $_POST['business_category'] );

	$services = sanitize_textarea_field( $_POST['business_services'] );
	$location = sanitize_text_field( $_POST['business_location'] );
	$phone    = sanitize_text_field( $_POST['business_phone'] );
	$email    = sanitize_email( $_POST['business_email'] );
	$website  = esc_url_raw( $_POST['business_website'] );

	$updated_post = array(
		'ID'           => $business_id,
		'post_title'   => $title,
		'post_content' => $description,
	);

	$post_id = wp_update_post( $updated_post, true );

	if ( ! is_wp_error( $post_id ) ) {
		update_post_meta( $post_id, 'business_services', $services );
		update_post_meta( $post_id, 'business_location', $location );
		update_post_meta( $post_id, 'business_phone', $phone );
		update_post_meta( $post_id, 'business_email', $email );
		update_post_meta( $post_id, 'business_website', $website );
		if ( ! empty( $category_id ) ) {
			wp_set_post_terms( $post_id, array( $category_id ), 'shecy_business_category' );
		}

		if ( ! empty( $_FILES['business_logo']['name'] ) ) {
			if ( ! function_exists( 'wp_handle_upload' ) ) require_once( ABSPATH . 'wp-admin/includes/file.php' );
			$movefile = wp_handle_upload( $_FILES['business_logo'], array( 'test_form' => false ) );
			if ( $movefile && ! isset( $movefile['error'] ) ) {
				$filename = $movefile['file'];
				$attachment = array('post_mime_type' => $movefile['type'], 'post_title' => preg_replace( '/\.[^.]+$/', '', basename( $filename ) ), 'post_content' => '', 'post_status' => 'inherit');
				$attach_id = wp_insert_attachment( $attachment, $filename, $post_id );
				require_once( ABSPATH . 'wp-admin/includes/image.php' );
				$attach_data = wp_generate_attachment_metadata( $attach_id, $filename );
				wp_update_attachment_metadata( $attach_id, $attach_data );
				set_post_thumbnail( $post_id, $attach_id );
			}
		}

		wp_redirect( home_url( '/dashboard?tab=businesses&updated=true' ) );
		exit;
	} else {
		wp_die( 'Error updating business listing: ' . $post_id->get_error_message() );
	}
}

get_header();

// --- Pre-populate form data ---
$business_services = get_post_meta( $business_id, 'business_services', true );
$business_location = get_post_meta( $business_id, 'business_location', true );
$business_phone = get_post_meta( $business_id, 'business_phone', true );
$business_email = get_post_meta( $business_id, 'business_email', true );
$business_website = get_post_meta( $business_id, 'business_website', true );
$business_terms = wp_get_post_terms( $business_id, 'shecy_business_category' );
$selected_category = ! empty( $business_terms ) ? $business_terms[0]->term_id : 0;
?>

<main id="primary" class="site-main">
	<div class="container mx-auto px-4 py-12">
		<form method="post" enctype="multipart/form-data">
			<input type="hidden" name="action" value="edit-business">
			<?php wp_nonce_field( 'edit_business_' . $business_id, 'edit_business_nonce' ); ?>
			<div class="space-y-12">
				<div class="border-b border-gray-900/10 pb-12">
					<h2 class="text-base font-semibold leading-7 text-gray-900">Edit Business Listing</h2>
					<p class="mt-1 text-sm leading-6 text-gray-600">Update the information for your business.</p>

					<div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
						<div class="sm:col-span-4">
							<label for="business_name" class="block text-sm font-medium leading-6 text-gray-900">Business Name</label>
							<div class="mt-2">
								<input type="text" name="business_name" id="business_name" value="<?php echo esc_attr( $post->post_title ); ?>" required class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
							</div>
						</div>

						<div class="col-span-full">
							<label for="business_description" class="block text-sm font-medium leading-6 text-gray-900">Description</label>
							<div class="mt-2">
								<textarea id="business_description" name="business_description" rows="3" required class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"><?php echo esc_textarea( $post->post_content ); ?></textarea>
							</div>
							<p class="mt-3 text-sm leading-6 text-gray-600">Write a few sentences about the business.</p>
						</div>

						<div class="col-span-full">
							<label for="business_services" class="block text-sm font-medium leading-6 text-gray-900">Services</label>
							<div class="mt-2">
								<textarea id="business_services" name="business_services" rows="3" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" placeholder="e.g. Manicures, Facials, Haircuts"><?php echo esc_textarea( $business_services ); ?></textarea>
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
									'selected'         => $selected_category,
									'hierarchical'     => true,
									'class'            => 'block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6',
								) );
								?>
							</div>
						</div>

						<div class="sm:col-span-3">
							<label for="business_location" class="block text-sm font-medium leading-6 text-gray-900">Location / Address</label>
							<div class="mt-2">
								<input type="text" name="business_location" id="business_location" value="<?php echo esc_attr($business_location); ?>" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
							</div>
						</div>

						<div class="sm:col-span-2 sm:col-start-1">
							<label for="business_phone" class="block text-sm font-medium leading-6 text-gray-900">Phone Number</label>
							<div class="mt-2">
								<input type="tel" name="business_phone" id="business_phone" value="<?php echo esc_attr($business_phone); ?>" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
							</div>
						</div>

						<div class="sm:col-span-2">
							<label for="business_email" class="block text-sm font-medium leading-6 text-gray-900">Contact Email</label>
							<div class="mt-2">
								<input type="email" name="business_email" id="business_email" value="<?php echo esc_attr($business_email); ?>" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
							</div>
						</div>

						<div class="sm:col-span-2">
							<label for="business_website" class="block text-sm font-medium leading-6 text-gray-900">Website URL</label>
							<div class="mt-2">
								<input type="url" name="business_website" id="business_website" value="<?php echo esc_attr($business_website); ?>" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" placeholder="https://example.com">
							</div>
						</div>

						<div class="col-span-full">
							<label for="business_logo" class="block text-sm font-medium leading-6 text-gray-900">Business Logo / Image</label>
							<div class="mt-2 flex items-center gap-x-3">
								<?php if ( has_post_thumbnail( $business_id ) ) : ?>
									<?php echo get_the_post_thumbnail( $business_id, 'thumbnail', ['class' => 'h-24 w-24 object-cover rounded-md'] ); ?>
								<?php else: ?>
									<svg class="h-24 w-24 text-gray-300" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
										<path fill-rule="evenodd" d="M1.5 6a2.25 2.25 0 012.25-2.25h16.5A2.25 2.25 0 0122.5 6v12a2.25 2.25 0 01-2.25 2.25H3.75A2.25 2.25 0 011.5 18V6zM3 16.06V18c0 .414.336.75.75.75h16.5a.75.75 0 00.75-.75v-1.94l-2.69-2.69a1.5 1.5 0 00-2.12 0l-.88.879.97.97a.75.75 0 11-1.06 1.06l-5.16-5.159a1.5 1.5 0 00-2.12 0L3 16.061zm10.125-7.81a1.125 1.125 0 112.25 0 1.125 1.125 0 01-2.25 0z" clip-rule="evenodd" />
									</svg>
								<?php endif; ?>
								<label for="business_logo" class="relative cursor-pointer rounded-md bg-white font-semibold text-indigo-600 focus-within:outline-none focus-within:ring-2 focus-within:ring-indigo-600 focus-within:ring-offset-2 hover:text-indigo-500">
									<span>Change</span>
									<input id="business_logo" name="business_logo" type="file" class="sr-only">
								</label>
							</div>
							<p class="mt-3 text-sm leading-6 text-gray-600">Only upload a new image if you want to replace the current one.</p>
						</div>
					</div>
				</div>
			</div>

			<div class="mt-6 flex items-center justify-end gap-x-6">
				<a href="<?php echo home_url('/dashboard?tab=businesses'); ?>" class="text-sm font-semibold leading-6 text-gray-900">Cancel</a>
				<button type="submit" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Save Changes</button>
			</div>
		</form>
	</div>
</main>

<?php
get_footer();
?>
