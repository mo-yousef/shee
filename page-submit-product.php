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

		// Save additional meta data
		if ( ! empty( $_POST['product_condition'] ) ) {
			update_post_meta( $post_id, 'product_condition', sanitize_text_field( $_POST['product_condition'] ) );
		}
		if ( ! empty( $_POST['product_brand'] ) ) {
			update_post_meta( $post_id, 'product_brand', sanitize_text_field( $_POST['product_brand'] ) );
		}
		if ( ! empty( $_POST['product_location'] ) ) {
			update_post_meta( $post_id, 'product_location', sanitize_text_field( $_POST['product_location'] ) );
		}
		if ( ! empty( $_POST['product_phone'] ) ) {
			update_post_meta( $post_id, 'product_phone', sanitize_text_field( $_POST['product_phone'] ) );
		}

		// Handle image upload
		if ( ! empty( $_FILES['product_images']['name'][0] ) ) {
			if ( ! function_exists( 'wp_handle_upload' ) ) {
				require_once( ABSPATH . 'wp-admin/includes/file.php' );
				require_once( ABSPATH . 'wp-admin/includes/image.php' );
			}

			$files = $_FILES['product_images'];
			$attachment_ids = array();
			$first_image = true;

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

						if ( $first_image ) {
							set_post_thumbnail( $post_id, $attach_id );
							$first_image = false;
						}
					}
				}
			}
			if ( ! empty( $attachment_ids ) ) {
				update_post_meta( $post_id, 'product_gallery_ids', $attachment_ids );
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

<main id="primary" class="site-main bg-gray-50 py-12">
	<div class="container mx-auto px-4">
		<div class="max-w-3xl mx-auto bg-white p-8 rounded-2xl shadow-xl">
			<header class="text-center mb-10">
				<h1 class="text-4xl font-extrabold text-gray-800">Submit a New Product</h1>
				<p class="text-gray-500 mt-2">Showcase your item to the community. Fill out the details below.</p>
			</header>

			<form id="submit-product-form" method="post" enctype="multipart/form-data" class="space-y-8">
				<input type="hidden" name="action" value="submit-product">
				<?php wp_nonce_field( 'submit_product', 'submit_product_nonce' ); ?>

				<div class="p-6 border border-gray-200 rounded-lg">
					<h3 class="text-lg font-semibold text-gray-700 mb-4">Core Details</h3>
					<div class="space-y-6">
						<div>
							<label for="product_title" class="block text-sm font-medium text-gray-700">Product Title <span class="text-red-500">*</span></label>
							<input type="text" name="product_title" id="product_title" required class="mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:ring-violet-500 focus:border-violet-500">
						</div>

						<div>
							<label for="product_description" class="block text-sm font-medium text-gray-700">Description <span class="text-red-500">*</span></label>
							<textarea name="product_description" id="product_description" rows="5" required class="mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:ring-violet-500 focus:border-violet-500"></textarea>
							<p class="mt-2 text-xs text-gray-500">Provide a detailed description of your product, including its condition.</p>
						</div>
					</div>
				</div>

				<div class="p-6 border border-gray-200 rounded-lg">
					<h3 class="text-lg font-semibold text-gray-700 mb-4">Additional Details</h3>
					<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
						<div>
							<label for="product_condition" class="block text-sm font-medium text-gray-700">Condition</label>
							<select name="product_condition" id="product_condition" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-violet-500 focus:border-violet-500">
								<option value="">Select Condition</option>
								<option value="new">New</option>
								<option value="used_like_new">Used - Like New</option>
								<option value="used_good">Used - Good</option>
								<option value="used_fair">Used - Fair</option>
							</select>
						</div>
						<div>
							<label for="product_brand" class="block text-sm font-medium text-gray-700">Brand</label>
							<input type="text" name="product_brand" id="product_brand" class="mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:ring-violet-500 focus:border-violet-500">
						</div>
						<div>
							<label for="product_location" class="block text-sm font-medium text-gray-700">Location</label>
							<input type="text" name="product_location" id="product_location" class="mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:ring-violet-500 focus:border-violet-500">
						</div>
						<div>
							<label for="product_phone" class="block text-sm font-medium text-gray-700">Phone Number</label>
							<input type="tel" name="product_phone" id="product_phone" class="mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:ring-violet-500 focus:border-violet-500">
						</div>
					</div>
				</div>

				<div class="p-6 border border-gray-200 rounded-lg">
					<h3 class="text-lg font-semibold text-gray-700 mb-4">Pricing & Category</h3>
					<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
						<div>
							<label for="product_price" class="block text-sm font-medium text-gray-700">Price ($) <span class="text-red-500">*</span></label>
							<input type="number" name="product_price" id="product_price" step="0.01" min="0" required class="mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:ring-violet-500 focus:border-violet-500">
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
								'class'            => 'mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-violet-500 focus:border-violet-500',
							) );
							?>
						</div>
					</div>
				</div>

				<div class="p-6 border border-gray-200 rounded-lg">
					<h3 class="text-lg font-semibold text-gray-700 mb-4">Product Images</h3>
					<div>
						<label for="product_images" class="block text-sm font-medium text-gray-700">Upload one or more images</label>
						<input type="file" name="product_images[]" id="product_images" accept="image/*" multiple class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-violet-50 file:text-violet-700 hover:file:bg-violet-100">
						<p class="mt-2 text-xs text-gray-500">The first image will be the main display image.</p>
					</div>
				</div>

				<div class="pt-5">
					<button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-lg text-base font-medium text-white bg-violet-600 hover:bg-violet-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-violet-500 transition-transform transform hover:scale-105">Submit for Review</button>
				</div>
			</form>
		</div>
	</div>
</main>

<?php
get_footer();
?>
