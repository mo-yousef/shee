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
if ( 'POST' == $_SERVER['REQUEST_METHOD'] ) {
	// Handle image removal
	if ( isset( $_POST['remove_image'] ) ) {
		$attachment_id_to_remove = intval( $_POST['remove_image'] );
		$gallery_ids = get_post_meta( $business_id, 'business_gallery_ids', true );
		if ( ( $key = array_search( $attachment_id_to_remove, $gallery_ids ) ) !== false ) {
			unset( $gallery_ids[ $key ] );
		}
		update_post_meta( $business_id, 'business_gallery_ids', $gallery_ids );
		wp_delete_attachment( $attachment_id_to_remove, true );
		// Redirect to the same page to show the changes
		wp_redirect( get_permalink() );
		exit;
	}

	if ( ! empty( $_POST['action'] ) && $_POST['action'] == 'edit-business' ) {
		if ( ! isset( $_POST['edit_business_nonce'] ) || ! wp_verify_nonce( $_POST['edit_business_nonce'], 'edit_business_' . $business_id ) ) {
			wp_die( 'Security check failed.' );
		}

	$title       = sanitize_text_field( $_POST['business_name'] );
	$description = wp_kses_post( $_POST['business_description'] );
	$category_id = intval( $_POST['business_category'] );

	$services = sanitize_textarea_field( $_POST['business_services'] );
	$address = sanitize_text_field( $_POST['business_address'] );
	$city    = sanitize_text_field( $_POST['business_city'] );
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
		update_post_meta( $post_id, 'business_address', $address );
		update_post_meta( $post_id, 'business_city', $city );
		update_post_meta( $post_id, 'business_phone', $phone );
		update_post_meta( $post_id, 'business_email', $email );
		update_post_meta( $post_id, 'business_website', $website );
		if ( ! empty( $category_id ) ) {
			wp_set_post_terms( $post_id, array( $category_id ), 'shecy_business_category' );
		}

		// Handle image upload
		if ( ! empty( $_FILES['business_images']['name'][0] ) ) {
			if ( ! function_exists( 'wp_handle_upload' ) ) {
				require_once( ABSPATH . 'wp-admin/includes/file.php' );
				require_once( ABSPATH . 'wp-admin/includes/image.php' );
			}

			$files = $_FILES['business_images'];
			$attachment_ids = get_post_meta( $post_id, 'business_gallery_ids', true );
			if( !is_array($attachment_ids) ) {
				$attachment_ids = array();
			}

			foreach ( $files['name'] as $key => $value ) {
				if ( $files['name'][ $key ] ) {
					$file = array(
						'name'     => $files['name'][ $key ],
						'type'     => $files['type'][ $key ],
						'tmp_name' => $files['tmp_name'][ $key ],
						'error'    => $files['error'][ $key ],
						'size'     => $files['size'][ $key ],
					);

					$upload_overrides = array( 'test_form' => false );
					$movefile = wp_handle_upload( $file, $upload_overrides );

					if ( $movefile && ! isset( $movefile['error'] ) ) {
						$filename = $movefile['file'];
						$attachment = array(
							'post_mime_type' => $movefile['type'],
							'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $filename ) ),
							'post_content'   => '',
							'post_status'    => 'inherit',
						);
						$attach_id = wp_insert_attachment( $attachment, $filename, $post_id );
						$attach_data = wp_generate_attachment_metadata( $attach_id, $filename );
						wp_update_attachment_metadata( $attach_id, $attach_data );
						$attachment_ids[] = $attach_id;

						if ( ! has_post_thumbnail( $post_id ) ) {
							set_post_thumbnail( $post_id, $attach_id );
						}
					}
				}
			}
			if ( ! empty( $attachment_ids ) ) {
				update_post_meta( $post_id, 'business_gallery_ids', $attachment_ids );
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

<main id="primary" class="site-main bg-gray-50 py-12">
	<div class="container mx-auto px-4">
		<div class="max-w-3xl mx-auto bg-white p-8 rounded-2xl shadow-xl">
			<header class="text-center mb-10">
				<h1 class="text-4xl font-extrabold text-gray-800">Edit Business Listing</h1>
				<p class="text-gray-500 mt-2">Update the details of your business below.</p>
			</header>

			<form id="edit-business-form" method="post" enctype="multipart/form-data" class="space-y-8">
				<input type="hidden" name="action" value="edit-business">
				<?php wp_nonce_field( 'edit_business_' . $business_id, 'edit_business_nonce' ); ?>

				<div class="p-6 border border-gray-200 rounded-lg">
					<h3 class="text-lg font-semibold text-gray-700 mb-4">Business Information</h3>
					<div class="space-y-6">
						<div>
							<label for="business_name" class="block text-sm font-medium text-gray-700">Business Name <span class="text-red-500">*</span></label>
							<input type="text" name="business_name" id="business_name" value="<?php echo esc_attr( $post->post_title ); ?>" required class="mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:ring-violet-500 focus:border-violet-500">
						</div>

						<div>
							<label for="business_description" class="block text-sm font-medium text-gray-700">Description <span class="text-red-500">*</span></label>
							<textarea name="business_description" id="business_description" rows="5" required class="mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:ring-violet-500 focus:border-violet-500"><?php echo esc_textarea( $post->post_content ); ?></textarea>
						</div>
					</div>
				</div>

				<div class="p-6 border border-gray-200 rounded-lg">
					<h3 class="text-lg font-semibold text-gray-700 mb-4">Services & Category</h3>
					<div class="space-y-6">
						<div>
							<label for="business_services" class="block text-sm font-medium text-gray-700">Services</label>
							<textarea name="business_services" id="business_services" rows="3" class="mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:ring-violet-500 focus:border-violet-500" placeholder="e.g. Manicures, Facials, Haircuts"><?php echo esc_textarea( $business_services ); ?></textarea>
							<p class="mt-2 text-xs text-gray-500">Separate services with a comma.</p>
						</div>

						<div>
							<label for="business_category" class="block text-sm font-medium text-gray-700">Category <span class="text-red-500">*</span></label>
							<?php
							wp_dropdown_categories( array(
								'taxonomy'         => 'shecy_business_category',
								'name'             => 'business_category',
								'id'               => 'business_category',
								'required'         => true,
								'selected'         => $selected_category,
								'hierarchical'     => true,
								'class'            => 'mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-violet-500 focus:border-violet-500',
							) );
							?>
						</div>
					</div>
				</div>

				<div class="p-6 border border-gray-200 rounded-lg">
					<h3 class="text-lg font-semibold text-gray-700 mb-4">Contact & Location</h3>
					<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
						<div>
							<label for="business_address" class="block text-sm font-medium text-gray-700">Address</label>
							<input type="text" name="business_address" id="business_address" value="<?php echo esc_attr( get_post_meta( $business_id, 'business_address', true ) ); ?>" class="mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:ring-violet-500 focus:border-violet-500">
						</div>
						<div>
							<label for="business_city" class="block text-sm font-medium text-gray-700">City</label>
							<select name="business_city" id="business_city" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-violet-500 focus:border-violet-500">
								<option value="">Select a city</option>
								<?php
								$cities = shecy_get_cyprus_cities();
								$selected_city = get_post_meta( $business_id, 'business_city', true );
								foreach ( $cities as $city ) {
									echo '<option value="' . esc_attr( $city ) . '"' . selected( $selected_city, $city, false ) . '>' . esc_html( $city ) . '</option>';
								}
								?>
							</select>
						</div>
						<div>
							<label for="business_phone" class="block text-sm font-medium text-gray-700">Phone Number</label>
							<input type="tel" name="business_phone" id="business_phone" value="<?php echo esc_attr($business_phone); ?>" class="mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:ring-violet-500 focus:border-violet-500">
						</div>
						<div>
							<label for="business_email" class="block text-sm font-medium text-gray-700">Contact Email</label>
							<input type="email" name="business_email" id="business_email" value="<?php echo esc_attr($business_email); ?>" class="mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:ring-violet-500 focus:border-violet-500">
						</div>
						<div class="md:col-span-2">
							<label for="business_website" class="block text-sm font-medium text-gray-700">Website URL</label>
							<input type="url" name="business_website" id="business_website" value="<?php echo esc_attr($business_website); ?>" class="mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:ring-violet-500 focus:border-violet-500" placeholder="https://example.com">
						</div>
					</div>
				</div>

				<div class="p-6 border border-gray-200 rounded-lg">
					<h3 class="text-lg font-semibold text-gray-700 mb-4">Business Images</h3>
					<div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-5 gap-4">
						<?php
						$gallery_ids = get_post_meta( $business_id, 'business_gallery_ids', true );
						if ( ! empty( $gallery_ids ) ) {
							foreach ( $gallery_ids as $attachment_id ) {
								echo '<div class="relative group">';
								echo wp_get_attachment_image( $attachment_id, 'thumbnail', false, ['class' => 'w-full h-24 object-cover rounded-md'] );
								echo '<button type="submit" name="remove_image" value="' . esc_attr( $attachment_id ) . '" class="absolute top-1 right-1 bg-red-500 text-white rounded-full p-1 text-xs opacity-0 group-hover:opacity-100 transition-opacity">&times;</button>';
								echo '</div>';
							}
						} else {
							echo '<p>No images set.</p>';
						}
						?>
					</div>
					<div class="mt-6">
						<label for="business_images" class="block text-sm font-medium text-gray-700">Upload New Images</label>
						<input type="file" name="business_images[]" id="business_images" accept="image/*" multiple class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-violet-50 file:text-violet-700 hover:file:bg-violet-100">
						<p class="mt-2 text-xs text-gray-500">Upload new images to add to the gallery.</p>
					</div>
				</div>

				<div class="pt-5">
					<button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-lg text-base font-medium text-white bg-violet-600 hover:bg-violet-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-violet-500 transition-transform transform hover:scale-105">Save Changes</button>
				</div>
			</form>
		</div>
	</div>
</main>

<?php
get_footer();
?>
