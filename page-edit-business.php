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
		<div class="max-w-2xl mx-auto bg-white p-8 rounded-lg shadow-md">
			<header class="text-center mb-8">
				<h1 class="text-3xl font-bold">Edit Business Listing</h1>
			</header>

			<form id="edit-business-form" method="post" enctype="multipart/form-data">
				<input type="hidden" name="action" value="edit-business">
				<?php wp_nonce_field( 'edit_business_' . $business_id, 'edit_business_nonce' ); ?>

				<div class="space-y-6">
					<div>
						<label for="business_name">Business Name</label>
						<input type="text" name="business_name" id="business_name" value="<?php echo esc_attr( $post->post_title ); ?>" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
					</div>
					<div>
						<label for="business_description">Description</label>
						<textarea name="business_description" id="business_description" rows="5" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"><?php echo esc_textarea( $post->post_content ); ?></textarea>
					</div>
					<div>
						<label for="business_services">Services</label>
						<textarea name="business_services" id="business_services" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"><?php echo esc_textarea( $business_services ); ?></textarea>
					</div>
					<div>
						<label for="business_category">Category</label>
						<?php wp_dropdown_categories( array('taxonomy' => 'shecy_business_category', 'name' => 'business_category', 'id' => 'business_category', 'required' => true, 'selected' => $selected_category, 'hierarchical' => true, 'class' => 'mt-1 block w-full border-gray-300 rounded-md shadow-sm') ); ?>
					</div>
					<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
						<div>
							<label for="business_location">Location</label>
							<input type="text" name="business_location" id="business_location" value="<?php echo esc_attr($business_location); ?>" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
						</div>
						<div>
							<label for="business_phone">Phone</label>
							<input type="tel" name="business_phone" id="business_phone" value="<?php echo esc_attr($business_phone); ?>" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
						</div>
						<div>
							<label for="business_email">Email</label>
							<input type="email" name="business_email" id="business_email" value="<?php echo esc_attr($business_email); ?>" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
						</div>
						<div>
							<label for="business_website">Website</label>
							<input type="url" name="business_website" id="business_website" value="<?php echo esc_attr($business_website); ?>" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
						</div>
					</div>
					<div>
						<label>Current Logo</label>
						<div class="mt-1"><?php if ( has_post_thumbnail( $business_id ) ) { echo get_the_post_thumbnail( $business_id, 'thumbnail' ); } else { echo '<p>No logo set.</p>'; } ?></div>
						<label for="business_logo" class="mt-4">Upload New Logo</label>
						<input type="file" name="business_logo" id="business_logo" accept="image/*" class="mt-1 block w-full">
					</div>
				</div>

				<div class="mt-8">
					<button type="submit" class="w-full inline-flex justify-center py-3 px-4 border border-transparent shadow-sm text-base font-medium rounded-md text-white bg-violet-500 hover:bg-violet-600">Save Changes</button>
				</div>
			</form>
		</div>
	</div>
</main>

<?php
get_footer();
?>
