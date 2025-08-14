<?php
/**
 * The template for displaying a single shecy_product
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package SheCy
 */

get_header();
?>

<main id="primary" class="site-main">
	<div class="container mx-auto px-4 py-12">

		<?php
		while ( have_posts() ) :
			the_post();
			?>
			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<div class="grid grid-cols-1 md:grid-cols-2 gap-8 lg:gap-12">

					<?php // Left Column: Product Images ?>
					<div class="product-images">
						<?php
						$gallery_ids = get_post_meta( get_the_ID(), 'product_gallery_ids', true );
						if ( ! empty( $gallery_ids ) ) :
						?>
							<!-- Swiper -->
							<div style="--swiper-navigation-color: #fff; --swiper-pagination-color: #fff" class="swiper gallery-top mb-4 rounded-lg shadow-lg overflow-hidden">
								<div class="swiper-wrapper">
									<?php foreach ( $gallery_ids as $attachment_id ) : ?>
										<div class="swiper-slide">
											<a href="<?php echo wp_get_attachment_url( $attachment_id ); ?>" data-fancybox="product-gallery">
												<?php echo wp_get_attachment_image( $attachment_id, 'large', false, ['class' => 'w-full h-auto'] ); ?>
											</a>
										</div>
									<?php endforeach; ?>
								</div>
								<!-- Add Arrows -->
								<div class="swiper-button-next"></div>
								<div class="swiper-button-prev"></div>
							</div>
							<div class="swiper gallery-thumbs h-24">
								<div class="swiper-wrapper">
									<?php foreach ( $gallery_ids as $attachment_id ) : ?>
										<div class="swiper-slide cursor-pointer rounded-lg overflow-hidden">
											<?php echo wp_get_attachment_image( $attachment_id, 'thumbnail', false, ['class' => 'w-full h-full object-cover'] ); ?>
										</div>
									<?php endforeach; ?>
								</div>
							</div>
						<?php elseif ( has_post_thumbnail() ) : ?>
							<div class="w-full h-auto rounded-lg shadow-lg overflow-hidden">
								<a href="<?php the_post_thumbnail_url('large'); ?>" data-fancybox="product-gallery">
									<?php the_post_thumbnail( 'large' ); ?>
								</a>
							</div>
						<?php else : ?>
							<div class="w-full h-96 bg-gray-200 flex items-center justify-center rounded-lg">
								<span class="text-gray-500">No Image</span>
							</div>
						<?php endif; ?>
					</div>

					<?php // Right Column: Product Details ?>
					<div class="product-details">
						<?php the_title( '<h1 class="text-3xl lg:text-4xl font-bold mb-4">', '</h1>' ); ?>

						<?php
						// Price
						$price = get_post_meta( get_the_ID(), 'product_price', true );
						if ( $price ) {
							echo '<p class="text-3xl font-bold text-violet-500 mb-4">$' . esc_html( $price ) . '</p>';
						}
						?>

						<div class="product-meta border-t border-b border-gray-200 py-4 my-6">
							<div class="grid grid-cols-2 gap-4 text-sm">
								<?php
								// Categories
								$categories = get_the_terms( get_the_ID(), 'shecy_product_category' );
								if ( $categories && ! is_wp_error( $categories ) ) {
									echo '<div><strong>Category:</strong></div>';
									$cat_links = array();
									foreach ( $categories as $category ) {
										$cat_links[] = '<a href="' . get_term_link( $category ) . '" class="text-violet-500 hover:underline">' . esc_html( $category->name ) . '</a>';
									}
									echo '<div>' . implode( ', ', $cat_links ) . '</div>';
								}

								// Condition
								$condition = get_post_meta( get_the_ID(), 'product_condition', true );
								if ( $condition ) {
									echo '<div><strong>Condition:</strong></div>';
									echo '<div>' . esc_html( ucwords( str_replace( '_', ' ', $condition ) ) ) . '</div>';
								}

								// Brand
								$brand = get_post_meta( get_the_ID(), 'product_brand', true );
								if ( $brand ) {
									echo '<div><strong>Brand:</strong></div>';
									echo '<div>' . esc_html( $brand ) . '</div>';
								}

								// Location
								$location = get_post_meta( get_the_ID(), 'product_location', true );
								if ( $location ) {
									echo '<div><strong>Location:</strong></div>';
									echo '<div>' . esc_html( $location ) . '</div>';
								}

								// Phone
								$phone = get_post_meta( get_the_ID(), 'product_phone', true );
								if ( $phone ) {
									echo '<div><strong>Phone:</strong></div>';
									echo '<div><a href="tel:' . esc_attr( $phone ) . '" class="text-violet-500 hover:underline">' . esc_html( $phone ) . '</a></div>';
								}

								// Views
								$views = get_post_meta( get_the_ID(), 'shecy_post_views', true );
								if ( $views ) {
									echo '<div><strong>Views:</strong></div>';
									echo '<div>' . esc_html( $views ) . '</div>';
								}
								?>
							</div>
						</div>

						<div class="prose lg:prose-lg max-w-none mb-8">
							<?php the_content(); ?>
						</div>

						<div class="mt-8">
							<?php if ( get_current_user_id() == $post->post_author ) : ?>
							<a href="<?php echo home_url('/edit-product?product_id=' . get_the_ID()); ?>" class="inline-block bg-violet-500 text-white hover:bg-violet-600 py-2 px-4 rounded-md text-sm font-medium mb-8">Edit Product</a>
							<?php endif; ?>

							<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
								<?php // Seller Information ?>
								<div class="seller-info p-6 bg-gray-50 rounded-lg">
									<h3 class="text-xl font-bold mb-4">Seller Information</h3>
									<div class="flex items-center">
										<div class="mr-4">
											<?php echo get_avatar( get_the_author_meta( 'ID' ), 64, '', '', ['class' => 'rounded-full'] ); ?>
										</div>
										<div>
											<p class="font-bold"><?php the_author(); ?></p>
											<a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>" class="text-violet-500 hover:underline">View all products from this seller</a>
										</div>
									</div>
								</div>

								<?php // Optional: Contact Seller Form ?>
								<div class="contact-seller p-6 bg-gray-50 rounded-lg">
									<h3 class="text-xl font-bold mb-4">Contact Seller</h3>
									<form class="space-y-4">
										<div>
											<label for="contact-name" class="block text-sm font-medium text-gray-700">Your Name</label>
											<input type="text" name="contact-name" id="contact-name" class="mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:ring-violet-500 focus:border-violet-500">
										</div>
										<div>
											<label for="contact-email" class="block text-sm font-medium text-gray-700">Your Email</label>
											<input type="email" name="contact-email" id="contact-email" class="mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:ring-violet-500 focus:border-violet-500">
										</div>
										<div>
											<label for="contact-message" class="block text-sm font-medium text-gray-700">Message</label>
											<textarea name="contact-message" id="contact-message" rows="4" class="mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:ring-violet-500 focus:border-violet-500"></textarea>
										</div>
										<div>
											<button type="submit" class="w-full inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-violet-500 hover:bg-violet-600">Send Message</button>
											<p class="text-xs text-gray-500 mt-2 text-center">(Note: Form is for display only and not functional yet)</p>
										</div>
									</form>
								</div>
							</div>
						</div>

					</div>

				</div>
			</article>
			<?php
		endwhile;
		?>

	</div>
</main>

<?php
get_footer();
?>
