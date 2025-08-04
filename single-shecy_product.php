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
						<?php if ( has_post_thumbnail() ) : ?>
							<div class="w-full h-auto rounded-lg shadow-lg overflow-hidden">
								<?php the_post_thumbnail( 'large' ); ?>
							</div>
						<?php else : ?>
							<div class="w-full h-96 bg-gray-200 flex items-center justify-center rounded-lg">
								<span class="text-gray-500">No Image</span>
							</div>
						<?php endif; ?>
						<?php // Placeholder for a gallery below the main image ?>
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

						<div class="product-meta flex flex-wrap items-center text-sm text-gray-600 mb-6">
							<?php
							// Categories
							$categories = get_the_terms( get_the_ID(), 'shecy_product_category' );
							if ( $categories && ! is_wp_error( $categories ) ) {
								echo '<div class="mr-4 mb-2">';
								echo '<strong>Category:</strong> ';
								$cat_links = array();
								foreach ( $categories as $category ) {
									$cat_links[] = '<a href="' . get_term_link( $category ) . '" class="hover:text-violet-500">' . esc_html( $category->name ) . '</a>';
								}
								echo implode( ', ', $cat_links );
								echo '</div>';
							}

							// Conditions
							$conditions = get_the_terms( get_the_ID(), 'shecy_product_condition' );
							if ( $conditions && ! is_wp_error( $conditions ) ) {
								echo '<div class="mr-4 mb-2">';
								echo '<strong>Condition:</strong> ';
								$cond_links = array();
								foreach ( $conditions as $condition ) {
									$cond_links[] = '<a href="' . get_term_link( $condition ) . '" class="hover:text-violet-500">' . esc_html( $condition->name ) . '</a>';
								}
								echo implode( ', ', $cond_links );
								echo '</div>';
							}
							?>
						</div>

						<div class="prose lg:prose-lg max-w-none mb-8">
							<?php the_content(); ?>
						</div>

						<?php // Seller Information ?>
						<div class="seller-info p-6 bg-gray-50 rounded-lg mb-8">
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
			</article>
			<?php
		endwhile;
		?>

	</div>
</main>

<?php
get_footer();
?>
