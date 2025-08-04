<?php
/**
 * Template Name: Edit Product
 *
 * @package SheCy
 */

// --- Authentication & Authorization ---
if ( ! is_user_logged_in() ) {
	wp_redirect( home_url( '/login' ) );
	exit;
}

// Get the product ID from the URL.
$product_id = isset( $_GET['product_id'] ) ? intval( $_GET['product_id'] ) : 0;

// If no product ID, redirect to dashboard.
if ( ! $product_id ) {
	wp_redirect( home_url( '/dashboard' ) );
	exit;
}

// Get the post object.
$post = get_post( $product_id );

// Security check: ensure the current user is the author of the post.
if ( ! $post || $post->post_author != get_current_user_id() || $post->post_type !== 'shecy_product' ) {
	wp_die( 'You do not have permission to edit this product.', 'Permission Denied', array( 'response' => 403 ) );
}


// --- Form Processing ---
if ( 'POST' == $_SERVER['REQUEST_METHOD'] && ! empty( $_POST['action'] ) && $_POST['action'] == 'edit-product' ) {
	// Security check
	if ( ! isset( $_POST['edit_product_nonce'] ) || ! wp_verify_nonce( $_POST['edit_product_nonce'], 'edit_product_' . $product_id ) ) {
		wp_die( 'Security check failed.' );
	}

	// Sanitize and prepare post data
	$title       = sanitize_text_field( $_POST['product_title'] );
	$description = wp_kses_post( $_POST['product_description'] );
	$price       = sanitize_text_field( $_POST['product_price'] );
	$category_id = intval( $_POST['product_category'] );

	$updated_post = array(
		'ID'           => $product_id,
		'post_title'   => $title,
		'post_content' => $description,
		// 'post_status' remains unchanged, admin controls publishing.
	);

	$post_id = wp_update_post( $updated_post, true );

	if ( ! is_wp_error( $post_id ) ) {
		// Update meta and terms
		if ( ! empty( $price ) ) update_post_meta( $post_id, 'product_price', $price );
		if ( ! empty( $category_id ) ) wp_set_post_terms( $post_id, array( $category_id ), 'shecy_product_category' );

		// Handle image upload (if a new one is provided)
		if ( ! empty( $_FILES['product_image']['name'] ) ) {
			// ... (same upload handling logic as submit-product.php) ...
			if ( ! function_exists( 'wp_handle_upload' ) ) require_once( ABSPATH . 'wp-admin/includes/file.php' );
			$movefile = wp_handle_upload( $_FILES['product_image'], array( 'test_form' => false ) );
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

		wp_redirect( home_url( '/dashboard?tab=products&updated=true' ) );
		exit;
	} else {
		wp_die( 'Error updating product: ' . $post_id->get_error_message() );
	}
}


get_header();

// --- Pre-populate form data ---
$product_price = get_post_meta( $product_id, 'product_price', true );
$product_terms = wp_get_post_terms( $product_id, 'shecy_product_category' );
$selected_category = ! empty( $product_terms ) ? $product_terms[0]->term_id : 0;
?>

<main id="primary" class="site-main">
	<div class="container mx-auto px-4 py-12">
		<div class="max-w-2xl mx-auto bg-white p-8 rounded-lg shadow-md">
			<header class="text-center mb-8">
				<h1 class="text-3xl font-bold">Edit Product</h1>
			</header>

			<form id="edit-product-form" method="post" enctype="multipart/form-data">
				<input type="hidden" name="action" value="edit-product">
				<?php wp_nonce_field( 'edit_product_' . $product_id, 'edit_product_nonce' ); ?>

				<div class="space-y-6">
					<div>
						<label for="product_title" class="block text-sm font-medium text-gray-700">Product Title</label>
						<input type="text" name="product_title" id="product_title" value="<?php echo esc_attr( $post->post_title ); ?>" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-violet-500 focus:border-violet-500">
					</div>

					<div>
						<label for="product_description" class="block text-sm font-medium text-gray-700">Description</label>
						<textarea name="product_description" id="product_description" rows="5" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-violet-500 focus:border-violet-500"><?php echo esc_textarea( $post->post_content ); ?></textarea>
					</div>

					<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
						<div>
							<label for="product_price" class="block text-sm font-medium text-gray-700">Price ($)</label>
							<input type="number" name="product_price" id="product_price" value="<?php echo esc_attr( $product_price ); ?>" step="0.01" min="0" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-violet-500 focus:border-violet-500">
						</div>
						<div>
							<label for="product_category" class="block text-sm font-medium text-gray-700">Category</label>
							<?php
							wp_dropdown_categories( array(
								'taxonomy'         => 'shecy_product_category',
								'name'             => 'product_category',
								'id'               => 'product_category',
								'required'         => true,
								'selected'         => $selected_category,
								'hierarchical'     => true,
								'class'            => 'mt-1 block w-full border-gray-300 rounded-md shadow-sm',
							) );
							?>
						</div>
					</div>

					<div>
						<label class="block text-sm font-medium text-gray-700">Current Image</label>
						<div class="mt-1">
							<?php if ( has_post_thumbnail( $product_id ) ) : ?>
								<?php echo get_the_post_thumbnail( $product_id, 'thumbnail' ); ?>
							<?php else: ?>
								<p>No image set.</p>
							<?php endif; ?>
						</div>
						<label for="product_image" class="block text-sm font-medium text-gray-700 mt-4">Upload New Image</label>
						<input type="file" name="product_image" id="product_image" accept="image/*" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-violet-50 file:text-violet-700 hover:file:bg-violet-100">
						<p class="mt-1 text-xs text-gray-500">Only upload a new image if you want to replace the current one.</p>
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
