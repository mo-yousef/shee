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
	<div class="shecy-container shecy-mx-auto shecy-px-4 shecy-py-12">

		<?php
		while ( have_posts() ) :
			the_post();
			?>
			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<div class="shecy-grid shecy-grid-cols-1 md:shecy-grid-cols-2 shecy-gap-8 lg:shecy-gap-12">

					<?php // Left Column: Product Images ?>
					<div class="shecy-product-images">
						<?php if ( has_post_thumbnail() ) : ?>
							<div class="shecy-w-full shecy-h-auto shecy-rounded-lg shecy-shadow-lg shecy-overflow-hidden">
								<?php the_post_thumbnail( 'large' ); ?>
							</div>
						<?php else : ?>
							<div class="shecy-w-full shecy-h-96 shecy-bg-gray-200 shecy-flex shecy-items-center shecy-justify-center shecy-rounded-lg">
								<span class="shecy-text-gray-500">No Image</span>
							</div>
						<?php endif; ?>
						<?php // Placeholder for a gallery below the main image ?>
					</div>

					<?php // Right Column: Product Details ?>
					<div class="shecy-product-details">
						<?php the_title( '<h1 class="shecy-text-3xl lg:shecy-text-4xl shecy-font-bold shecy-mb-4">', '</h1>' ); ?>

						<?php
						// Price
						$price = get_post_meta( get_the_ID(), 'product_price', true );
						if ( $price ) {
							echo '<p class="shecy-text-3xl shecy-font-bold shecy-text-pink-500 shecy-mb-4">$' . esc_html( $price ) . '</p>';
						}
						?>

						<div class="shecy-product-meta shecy-flex shecy-flex-wrap shecy-items-center shecy-text-sm shecy-text-gray-600 shecy-mb-6">
							<?php
							// Categories
							$categories = get_the_terms( get_the_ID(), 'shecy_product_category' );
							if ( $categories && ! is_wp_error( $categories ) ) {
								echo '<div class="shecy-mr-4 shecy-mb-2">';
								echo '<strong>Category:</strong> ';
								$cat_links = array();
								foreach ( $categories as $category ) {
									$cat_links[] = '<a href="' . get_term_link( $category ) . '" class="hover:shecy-text-pink-500">' . esc_html( $category->name ) . '</a>';
								}
								echo implode( ', ', $cat_links );
								echo '</div>';
							}

							// Conditions
							$conditions = get_the_terms( get_the_ID(), 'shecy_product_condition' );
							if ( $conditions && ! is_wp_error( $conditions ) ) {
								echo '<div class="shecy-mr-4 shecy-mb-2">';
								echo '<strong>Condition:</strong> ';
								$cond_links = array();
								foreach ( $conditions as $condition ) {
									$cond_links[] = '<a href="' . get_term_link( $condition ) . '" class="hover:shecy-text-pink-500">' . esc_html( $condition->name ) . '</a>';
								}
								echo implode( ', ', $cond_links );
								echo '</div>';
							}
							?>
						</div>

						<div class="shecy-prose lg:shecy-prose-lg shecy-max-w-none shecy-mb-8">
							<?php the_content(); ?>
						</div>

						<?php // Seller Information ?>
						<div class="shecy-seller-info shecy-p-6 shecy-bg-gray-50 shecy-rounded-lg shecy-mb-8">
							<h3 class="shecy-text-xl shecy-font-bold shecy-mb-4">Seller Information</h3>
							<div class="shecy-flex shecy-items-center">
								<div class="shecy-mr-4">
									<?php echo get_avatar( get_the_author_meta( 'ID' ), 64, '', '', ['class' => 'shecy-rounded-full'] ); ?>
								</div>
								<div>
									<p class="shecy-font-bold"><?php the_author(); ?></p>
									<a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>" class="shecy-text-pink-500 hover:shecy-underline">View all products from this seller</a>
								</div>
							</div>
						</div>

						<?php // Optional: Contact Seller Form ?>
						<div class="shecy-contact-seller shecy-p-6 shecy-bg-gray-50 shecy-rounded-lg">
							<h3 class="shecy-text-xl shecy-font-bold shecy-mb-4">Contact Seller</h3>
							<form class="shecy-space-y-4">
								<div>
									<label for="contact-name" class="shecy-block shecy-text-sm shecy-font-medium shecy-text-gray-700">Your Name</label>
									<input type="text" name="contact-name" id="contact-name" class="shecy-mt-1 shecy-block shecy-w-full shecy-py-2 shecy-px-3 shecy-border shecy-border-gray-300 shecy-rounded-md shecy-shadow-sm focus:shecy-ring-pink-500 focus:shecy-border-pink-500">
								</div>
								<div>
									<label for="contact-email" class="shecy-block shecy-text-sm shecy-font-medium shecy-text-gray-700">Your Email</label>
									<input type="email" name="contact-email" id="contact-email" class="shecy-mt-1 shecy-block shecy-w-full shecy-py-2 shecy-px-3 shecy-border shecy-border-gray-300 shecy-rounded-md shecy-shadow-sm focus:shecy-ring-pink-500 focus:shecy-border-pink-500">
								</div>
								<div>
									<label for="contact-message" class="shecy-block shecy-text-sm shecy-font-medium shecy-text-gray-700">Message</label>
									<textarea name="contact-message" id="contact-message" rows="4" class="shecy-mt-1 shecy-block shecy-w-full shecy-py-2 shecy-px-3 shecy-border shecy-border-gray-300 shecy-rounded-md shecy-shadow-sm focus:shecy-ring-pink-500 focus:shecy-border-pink-500"></textarea>
								</div>
								<div>
									<button type="submit" class="shecy-w-full shecy-inline-flex shecy-justify-center shecy-py-2 shecy-px-4 shecy-border shecy-border-transparent shecy-shadow-sm shecy-text-sm shecy-font-medium shecy-rounded-md shecy-text-white shecy-bg-pink-500 hover:shecy-bg-pink-600">Send Message</button>
									<p class="shecy-text-xs shecy-text-gray-500 shecy-mt-2 shecy-text-center">(Note: Form is for display only and not functional yet)</p>
								</div>
							</form>
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
