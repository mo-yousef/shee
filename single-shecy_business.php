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
	<div class="shecy-container shecy-mx-auto shecy-px-4 shecy-py-12">

		<?php
		while ( have_posts() ) :
			the_post();
			?>
			<article id="post-<?php the_ID(); ?>" <?php post_class('shecy-max-w-4xl shecy-mx-auto'); ?>>

				<?php // Page Header ?>
				<header class="shecy-text-center shecy-mb-8">
					<?php if ( has_post_thumbnail() ) : ?>
						<div class="shecy-w-32 shecy-h-32 shecy-mx-auto shecy-mb-4">
							<?php the_post_thumbnail( 'medium', ['class' => 'shecy-rounded-full shecy-shadow-lg'] ); ?>
						</div>
					<?php endif; ?>
					<?php the_title( '<h1 class="shecy-text-4xl lg:shecy-text-5xl shecy-font-bold">', '</h1>' ); ?>
					<div class="shecy-mt-4">
						<?php
						$categories = get_the_terms( get_the_ID(), 'shecy_business_category' );
						if ( $categories && ! is_wp_error( $categories ) ) {
							$cat_links = array();
							foreach ( $categories as $category ) {
								$cat_links[] = '<a href="' . get_term_link( $category ) . '" class="shecy-inline-block shecy-bg-gray-200 shecy-rounded-full shecy-px-3 shecy-py-1 shecy-text-sm shecy-font-semibold shecy-text-gray-700 shecy-mr-2 shecy-mb-2 hover:shecy-bg-pink-200">' . esc_html( $category->name ) . '</a>';
							}
							echo implode( '', $cat_links );
						}
						?>
					</div>
				</header>

				<?php // Main Content Body ?>
				<div class="shecy-bg-white shecy-shadow-lg shecy-rounded-lg shecy-p-8 lg:shecy-p-12">

					<div class="shecy-prose lg:shecy-prose-xl shecy-max-w-none shecy-mb-8">
						<h2 class="shecy-font-bold shecy-text-2xl">About the Business</h2>
						<?php the_content(); ?>
					</div>

					<?php // Services Section
					$services = get_post_meta( get_the_ID(), 'business_services', true );
					if( $services ): ?>
						<div class="shecy-mb-8">
							<h3 class="shecy-font-bold shecy-text-2xl shecy-mb-4">Services</h3>
							<ul class="shecy-list-disc shecy-list-inside shecy-space-y-1">
								<?php
								$services_array = explode(',', $services);
								foreach($services_array as $service): ?>
									<li class="shecy-text-gray-700"><?php echo esc_html(trim($service)); ?></li>
								<?php endforeach; ?>
							</ul>
						</div>
					<?php endif; ?>

					<?php // Contact & Location Section ?>
					<div class="shecy-mb-8">
						<h3 class="shecy-font-bold shecy-text-2xl shecy-mb-4">Contact & Location</h3>
						<div class="shecy-space-y-3">
							<?php
							$location = get_post_meta( get_the_ID(), 'business_location', true );
							$phone = get_post_meta( get_the_ID(), 'business_phone', true );
							$email = get_post_meta( get_the_ID(), 'business_email', true );
							$website = get_post_meta( get_the_ID(), 'business_website', true );

							if($location) echo '<p class="shecy-flex shecy-items-center"><span class="shecy-mr-3 shecy-text-pink-500">📍</span>' . esc_html($location) . '</p>';
							if($phone) echo '<p class="shecy-flex shecy-items-center"><span class="shecy-mr-3 shecy-text-pink-500">📞</span>' . esc_html($phone) . '</p>';
							if($email) echo '<p class="shecy-flex shecy-items-center"><span class="shecy-mr-3 shecy-text-pink-500">✉️</span><a href="mailto:'.esc_attr($email).'" class="hover:shecy-underline">'.esc_html($email).'</a></p>';
							if($website) echo '<p class="shecy-flex shecy-items-center"><span class="shecy-mr-3 shecy-text-pink-500">🌐</span><a href="'.esc_url($website).'" target="_blank" rel="noopener noreferrer" class="hover:shecy-underline">'.esc_html($website).'</a></p>';
							?>
						</div>
					</div>

					<?php // Submitter Information ?>
					<div class="shecy-submitter-info shecy-pt-8 shecy-border-t shecy-border-gray-200">
						<h3 class="shecy-text-xl shecy-font-bold shecy-mb-4">Listed By</h3>
						<div class="shecy-flex shecy-items-center">
							<div class="shecy-mr-4">
								<?php echo get_avatar( get_the_author_meta( 'ID' ), 48, '', '', ['class' => 'shecy-rounded-full'] ); ?>
							</div>
							<div>
								<p class="shecy-font-bold"><?php the_author(); ?></p>
								<a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>" class="shecy-text-pink-500 hover:shecy-underline shecy-text-sm">View profile</a>
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
