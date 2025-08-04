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
		'post_status'   => 'pending', // Save as pending for admin review
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
		<form method="post" enctype="multipart/form-data">
			<input type="hidden" name="action" value="submit-product">
			<?php wp_nonce_field( 'submit_product', 'submit_product_nonce' ); ?>
			<div class="space-y-12">
				<div class="border-b border-gray-900/10 pb-12">
					<h2 class="text-base font-semibold leading-7 text-gray-900">Submit a New Product</h2>
					<p class="mt-1 text-sm leading-6 text-gray-600">Fill out the form below to add your product to the marketplace.</p>

					<div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
						<div class="sm:col-span-4">
							<label for="product_title" class="block text-sm font-medium leading-6 text-gray-900">Product Title</label>
							<div class="mt-2">
								<input type="text" name="product_title" id="product_title" required class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
							</div>
						</div>

						<div class="col-span-full">
							<label for="product_description" class="block text-sm font-medium leading-6 text-gray-900">Description</label>
							<div class="mt-2">
								<textarea id="product_description" name="product_description" rows="3" required class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"></textarea>
							</div>
							<p class="mt-3 text-sm leading-6 text-gray-600">Write a few sentences about the product.</p>
						</div>

						<div class="sm:col-span-3">
							<label for="product_price" class="block text-sm font-medium leading-6 text-gray-900">Price ($)</label>
							<div class="mt-2">
								<input type="number" name="product_price" id="product_price" step="0.01" min="0" required class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
							</div>
						</div>

						<div class="sm:col-span-3">
							<label for="product_category" class="block text-sm font-medium leading-6 text-gray-900">Category</label>
							<div class="mt-2">
								<?php
								shecy_ensure_categories_exist('shecy_product_category');
								wp_dropdown_categories( array(
									'taxonomy'         => 'shecy_product_category',
									'name'             => 'product_category',
									'id'               => 'product_category',
									'required'         => true,
									'show_option_none' => 'Select a category',
									'hierarchical'     => true,
									'class'            => 'block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6',
								) );
								?>
							</div>
						</div>

						<div class="col-span-full">
							<label for="cover-photo" class="block text-sm font-medium leading-6 text-gray-900">Product Image</label>
							<div class="mt-2 flex justify-center rounded-lg border border-dashed border-gray-900/25 px-6 py-10">
								<div class="text-center">
									<svg class="mx-auto h-12 w-12 text-gray-300" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
										<path fill-rule="evenodd" d="M1.5 6a2.25 2.25 0 012.25-2.25h16.5A2.25 2.25 0 0122.5 6v12a2.25 2.25 0 01-2.25 2.25H3.75A2.25 2.25 0 011.5 18V6zM3 16.06V18c0 .414.336.75.75.75h16.5a.75.75 0 00.75-.75v-1.94l-2.69-2.69a1.5 1.5 0 00-2.12 0l-.88.879.97.97a.75.75 0 11-1.06 1.06l-5.16-5.159a1.5 1.5 0 00-2.12 0L3 16.061zm10.125-7.81a1.125 1.125 0 112.25 0 1.125 1.125 0 01-2.25 0z" clip-rule="evenodd" />
									</svg>
									<div class="mt-4 flex text-sm leading-6 text-gray-600">
										<label for="product_image" class="relative cursor-pointer rounded-md bg-white font-semibold text-indigo-600 focus-within:outline-none focus-within:ring-2 focus-within:ring-indigo-600 focus-within:ring-offset-2 hover:text-indigo-500">
											<span>Upload a file</span>
											<input id="product_image" name="product_image" type="file" class="sr-only">
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
