<?php
/**
 * The template for displaying a single shecy_business
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
			<article id="post-<?php the_ID(); ?>" <?php post_class('max-w-4xl mx-auto'); ?>>

				<?php // Page Header ?>
				<header class="text-center mb-8">
					<?php if ( has_post_thumbnail() ) : ?>
						<div class="w-32 h-32 mx-auto mb-4">
							<?php the_post_thumbnail( 'medium', ['class' => 'rounded-full shadow-lg'] ); ?>
						</div>
					<?php endif; ?>
					<?php the_title( '<h1 class="text-4xl lg:text-5xl font-bold">', '</h1>' ); ?>
					<div class="mt-4">
						<?php
						$categories = get_the_terms( get_the_ID(), 'shecy_business_category' );
						if ( $categories && ! is_wp_error( $categories ) ) {
							$cat_links = array();
							foreach ( $categories as $category ) {
								$cat_links[] = '<a href="' . get_term_link( $category ) . '" class="inline-block bg-gray-200 rounded-full px-3 py-1 text-sm font-semibold text-gray-700 mr-2 mb-2 hover:bg-violet-200">' . esc_html( $category->name ) . '</a>';
							}
							echo implode( '', $cat_links );
						}
						// Views
						$views = get_post_meta( get_the_ID(), 'shecy_post_views', true );
						if ( $views ) {
							echo '<div class="inline-block bg-gray-200 rounded-full px-3 py-1 text-sm font-semibold text-gray-700 mr-2 mb-2">';
							echo 'Views: ' . esc_html( $views );
							echo '</div>';
						}
						?>
					</div>
				</header>

				<?php // Main Content Body ?>
				<div class="bg-white shadow-lg rounded-lg p-8 lg:p-12">
					<div class="prose lg:prose-xl max-w-none mb-8">
						<h2 class="font-bold text-2xl">About the Business</h2>
						<?php the_content(); ?>
					</div>

					<?php
					$gallery_ids = get_post_meta( get_the_ID(), 'business_gallery_ids', true );
					if ( ! empty( $gallery_ids ) ) :
					?>
					<div class="mb-8">
						<h3 class="font-bold text-2xl mb-4">Gallery</h3>
						<div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
							<?php foreach ( $gallery_ids as $attachment_id ) : ?>
								<a href="<?php echo wp_get_attachment_url( $attachment_id ); ?>" data-fancybox="business-gallery">
									<?php echo wp_get_attachment_image( $attachment_id, 'medium_large', false, ['class' => 'w-full h-auto rounded-lg shadow-md'] ); ?>
								</a>
							<?php endforeach; ?>
						</div>
					</div>
					<?php endif; ?>

					<?php // Services Section
					$services = get_post_meta( get_the_ID(), 'business_services', true );
					if( $services ): ?>
						<div class="mb-8">
							<h3 class="font-bold text-2xl mb-4">Services</h3>
							<ul class="list-disc list-inside space-y-1">
								<?php
								$services_array = explode(',', $services);
								foreach($services_array as $service): ?>
									<li class="text-gray-700"><?php echo esc_html(trim($service)); ?></li>
								<?php endforeach; ?>
							</ul>
						</div>
					<?php endif; ?>

					<?php // Contact & Location Section ?>
					<div class="mb-8">
						<h3 class="font-bold text-2xl mb-4">Contact & Location</h3>
						<div class="space-y-3">
							<?php
							$location = get_post_meta( get_the_ID(), 'business_location', true );
							$phone = get_post_meta( get_the_ID(), 'business_phone', true );
							$email = get_post_meta( get_the_ID(), 'business_email', true );
							$website = get_post_meta( get_the_ID(), 'business_website', true );

							if($location) echo '<p class="flex items-center"><span class="mr-3 text-violet-500">📍</span>' . esc_html($location) . '</p>';
							if($phone) echo '<p class="flex items-center"><span class="mr-3 text-violet-500">📞</span>' . esc_html($phone) . '</p>';
							if($email) echo '<p class="flex items-center"><span class="mr-3 text-violet-500">✉️</span><a href="mailto:'.esc_attr($email).'" class="hover:underline">'.esc_html($email).'</a></p>';
							if($website) echo '<p class="flex items-center"><span class="mr-3 text-violet-500">🌐</span><a href="'.esc_url($website).'" target="_blank" rel="noopener noreferrer" class="hover:underline">'.esc_html($website).'</a></p>';
							?>
						</div>
					</div>

					<?php // Submitter Information ?>
					<div class="submitter-info pt-8 border-t border-gray-200">
						<h3 class="text-xl font-bold mb-4">Listed By</h3>
						<div class="flex items-center justify-between">
							<div class="flex items-center">
								<div class="mr-4">
									<?php echo get_avatar( get_the_author_meta( 'ID' ), 48, '', '', ['class' => 'rounded-full'] ); ?>
								</div>
								<div>
									<p class="font-bold"><?php the_author(); ?></p>
									<a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>" class="text-violet-500 hover:underline text-sm">View profile</a>
								</div>
							</div>
							<?php if ( get_current_user_id() == $post->post_author ) : ?>
							<a href="<?php echo home_url('/edit-business?business_id=' . get_the_ID()); ?>" class="inline-block bg-violet-500 text-white hover:bg-violet-600 py-2 px-4 rounded-md text-sm font-medium">Edit Business</a>
							<?php endif; ?>
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
