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
if ( 'POST' == $_SERVER['REQUEST_METHOD'] ) {
	// Handle image removal
	if ( isset( $_POST['remove_image'] ) ) {
		$attachment_id_to_remove = intval( $_POST['remove_image'] );
		$gallery_ids = get_post_meta( $product_id, 'product_gallery_ids', true );
		if ( ( $key = array_search( $attachment_id_to_remove, $gallery_ids ) ) !== false ) {
			unset( $gallery_ids[ $key ] );
		}
		update_post_meta( $product_id, 'product_gallery_ids', $gallery_ids );
		wp_delete_attachment( $attachment_id_to_remove, true );
		// Redirect to the same page to show the changes
		wp_redirect( get_permalink() );
		exit;
	}

	if ( ! empty( $_POST['action'] ) && $_POST['action'] == 'edit-product' ) {
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

		// Handle image upload
		if ( ! empty( $_FILES['product_images']['name'][0] ) ) {
			if ( ! function_exists( 'wp_handle_upload' ) ) {
				require_once( ABSPATH . 'wp-admin/includes/file.php' );
				require_once( ABSPATH . 'wp-admin/includes/image.php' );
			}

			$files = $_FILES['product_images'];
			$attachment_ids = get_post_meta( $post_id, 'product_gallery_ids', true );
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
				update_post_meta( $post_id, 'product_gallery_ids', $attachment_ids );
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

<main id="primary" class="site-main bg-gray-50 py-12">
	<div class="container mx-auto px-4">
		<div class="max-w-3xl mx-auto bg-white p-8 rounded-2xl shadow-xl">
			<header class="text-center mb-10">
				<h1 class="text-4xl font-extrabold text-gray-800">Edit Product</h1>
				<p class="text-gray-500 mt-2">Update the details of your product below.</p>
			</header>

			<form id="edit-product-form" method="post" enctype="multipart/form-data" class="space-y-8">
				<input type="hidden" name="action" value="edit-product">
				<?php wp_nonce_field( 'edit_product_' . $product_id, 'edit_product_nonce' ); ?>

				<div class="p-6 border border-gray-200 rounded-lg">
					<h3 class="text-lg font-semibold text-gray-700 mb-4">Core Details</h3>
					<div class="space-y-6">
						<div>
							<label for="product_title" class="block text-sm font-medium text-gray-700">Product Title <span class="text-red-500">*</span></label>
							<input type="text" name="product_title" id="product_title" value="<?php echo esc_attr( $post->post_title ); ?>" required class="mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:ring-violet-500 focus:border-violet-500">
						</div>

						<div>
							<label for="product_description" class="block text-sm font-medium text-gray-700">Description <span class="text-red-500">*</span></label>
							<textarea name="product_description" id="product_description" rows="5" required class="mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:ring-violet-500 focus:border-violet-500"><?php echo esc_textarea( $post->post_content ); ?></textarea>
							<p class="mt-2 text-xs text-gray-500">Provide a detailed description of your product, including its condition.</p>
						</div>
					</div>
				</div>

				<div class="p-6 border border-gray-200 rounded-lg">
					<h3 class="text-lg font-semibold text-gray-700 mb-4">Pricing & Category</h3>
					<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
						<div>
							<label for="product_price" class="block text-sm font-medium text-gray-700">Price ($) <span class="text-red-500">*</span></label>
							<input type="number" name="product_price" id="product_price" value="<?php echo esc_attr( $product_price ); ?>" step="0.01" min="0" required class="mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:ring-violet-500 focus:border-violet-500">
						</div>
						<div>
							<label for="product_category" class="block text-sm font-medium text-gray-700">Category <span class="text-red-500">*</span></label>
							<?php
							wp_dropdown_categories( array(
								'taxonomy'         => 'shecy_product_category',
								'name'             => 'product_category',
								'id'               => 'product_category',
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
					<h3 class="text-lg font-semibold text-gray-700 mb-4">Product Images</h3>
					<div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-5 gap-4">
						<?php
						$gallery_ids = get_post_meta( $product_id, 'product_gallery_ids', true );
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
						<label for="product_images" class="block text-sm font-medium text-gray-700">Upload New Images</label>
						<input type="file" name="product_images[]" id="product_images" accept="image/*" multiple class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-violet-50 file:text-violet-700 hover:file:bg-violet-100">
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
