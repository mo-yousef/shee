<?php
/**
 * Template Name: Submit Product
 *
 * @package SheCy
 */

// Redirect non-logged-in users.
if ( ! is_user_logged_in() ) {
	wp_redirect( home_url( '/login' ) );
	exit;
}

// Handle form submission
if ( 'POST' == $_SERVER['REQUEST_METHOD'] && ! empty( $_POST['action'] ) && $_POST['action'] == 'submit-product' ) {
	// Security check
	if ( ! isset( $_POST['submit_product_nonce'] ) || ! wp_verify_nonce( $_POST['submit_product_nonce'], 'submit_product' ) ) {
		wp_die( 'Security check failed.' );
	}

	// Sanitize and prepare post data
	$title       = sanitize_text_field( $_POST['product_title'] );
	$description = wp_kses_post( $_POST['product_description'] );
	$price       = sanitize_text_field( $_POST['product_price'] );
	$category_id = intval( $_POST['product_category'] );

	// Create the post array
	$new_post = array(
		'post_title'    => $title,
		'post_content'  => $description,
		'post_status'   => 'draft', // Save as draft for admin review
		'post_type'     => 'shecy_product',
		'post_author'   => get_current_user_id(),
	);

	// Insert the post into the database
	$post_id = wp_insert_post( $new_post );

	if ( $post_id && ! is_wp_error( $post_id ) ) {
		// Post was created successfully, now add meta and terms
		if ( ! empty( $price ) ) {
			update_post_meta( $post_id, 'product_price', $price );
		}
		if ( ! empty( $category_id ) ) {
			wp_set_post_terms( $post_id, array( $category_id ), 'shecy_product_category' );
		}

		// Handle image upload
		if ( ! empty( $_FILES['product_image']['name'] ) ) {
			if ( ! function_exists( 'wp_handle_upload' ) ) {
				require_once( ABSPATH . 'wp-admin/includes/file.php' );
			}
			$uploadedfile = $_FILES['product_image'];
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

		// Redirect to dashboard with a success message
		wp_redirect( home_url( '/dashboard?tab=products&submitted=true' ) );
		exit;
	} else {
		// Handle error
		wp_die( 'Error creating product.' );
	}
}


get_header();
?>

<main id="primary" class="site-main">
	<div class="container mx-auto px-4 py-12">
		<div class="max-w-2xl mx-auto bg-white p-8 rounded-lg shadow-md">
			<header class="text-center mb-8">
				<h1 class="text-3xl font-bold">Submit a New Product</h1>
				<p class="text-gray-600 mt-2">Fill out the form below to add your product to the marketplace.</p>
			</header>

			<form id="submit-product-form" method="post" enctype="multipart/form-data">
				<input type="hidden" name="action" value="submit-product">
				<?php wp_nonce_field( 'submit_product', 'submit_product_nonce' ); ?>

				<div class="space-y-6">
					<div>
						<label for="product_title" class="block text-sm font-medium text-gray-700">Product Title <span class="text-red-500">*</span></label>
						<input type="text" name="product_title" id="product_title" required class="mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm">
					</div>

					<div>
						<label for="product_description" class="block text-sm font-medium text-gray-700">Description <span class="text-red-500">*</span></label>
						<textarea name="product_description" id="product_description" rows="5" required class="mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm"></textarea>
					</div>

					<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
						<div>
							<label for="product_price" class="block text-sm font-medium text-gray-700">Price ($) <span class="text-red-500">*</span></label>
							<input type="number" name="product_price" id="product_price" step="0.01" min="0" required class="mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm">
						</div>
						<div>
							<label for="product_category" class="block text-sm font-medium text-gray-700">Category <span class="text-red-500">*</span></label>
							<?php
							wp_dropdown_categories( array(
								'taxonomy'         => 'shecy_product_category',
								'name'             => 'product_category',
								'id'               => 'product_category',
								'required'         => true,
								'show_option_none' => 'Select a category',
								'hierarchical'     => true,
								'class'            => 'mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm',
							) );
							?>
						</div>
					</div>

					<div>
						<label for="product_image" class="block text-sm font-medium text-gray-700">Product Image</label>
						<input type="file" name="product_image" id="product_image" accept="image/*" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-pink-50 file:text-pink-700 hover:file:bg-pink-100">
					</div>
				</div>

				<div class="mt-8">
					<button type="submit" class="w-full inline-flex justify-center py-3 px-4 border border-transparent shadow-sm text-base font-medium rounded-md text-white bg-pink-500 hover:bg-pink-600">Submit for Review</button>
				</div>
			</form>
		</div>
	</div>
</main>

<?php
get_footer();
?>
