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
    <?php while ( have_posts() ) : the_post(); ?>
    <section class="py-12 bg-gray-50 sm:py-16">
        <div class="px-4 mx-auto sm:px-6 lg:px-8 max-w-7xl">
            <nav class="flex">
                <ol role="list" class="flex items-center space-x-0.5">
                    <li>
                        <div class="-m-1">
                            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="p-1 text-sm font-medium text-gray-500 rounded-md focus:outline-none focus:ring-2 focus:text-gray-900 focus:ring-gray-900 hover:text-gray-700">
                                Home
                            </a>
                        </div>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="flex-shrink-0 w-5 h-5 text-gray-300" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true"><path d="M5.555 17.776l8-16 .894.448-8 16-.894-.448z"></path></svg>
                            <a href="<?php echo get_post_type_archive_link('shecy_product'); ?>" class="p-1 ml-0.5 text-sm font-medium text-gray-500 rounded-md focus:outline-none focus:ring-2 focus:text-gray-900 focus:ring-gray-900 hover:text-gray-700">
                                Products
                            </a>
                        </div>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="flex-shrink-0 w-5 h-5 text-gray-300" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true"><path d="M5.555 17.776l8-16 .894.448-8 16-.894-.448z"></path></svg>
                            <a href="<?php the_permalink(); ?>" class="p-1 ml-0.5 text-sm font-medium text-gray-500 rounded-md focus:outline-none focus:ring-2 focus:text-gray-900 focus:ring-gray-900 hover:text-gray-700" aria-current="page">
                                <?php the_title(); ?>
                            </a>
                        </div>
                    </li>
                </ol>
            </nav>

            <div class="grid grid-cols-1 mt-8 lg:grid-rows-1 gap-y-12 lg:mt-12 lg:grid-cols-5 lg:gap-y-16 lg:gap-x-12 xl:gap-x-16">
                <div class="lg:col-span-3 lg:row-end-1">
                    <div class="lg:flex lg:items-start">
                        <div class="lg:order-2 lg:ml-5">
                            <div class="overflow-hidden border-2 border-transparent rounded-lg">
                                <?php if (has_post_thumbnail()) : ?>
                                    <img class="object-cover w-full h-full" src="<?php the_post_thumbnail_url('large'); ?>" alt="<?php the_title_attribute(); ?>">
                                <?php else : ?>
                                    <img class="object-cover w-full h-full" src="<?php echo get_template_directory_uri(); ?>/assets/images/hero-placeholder.jpg" alt="Default product image">
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="flex-1 lg:order-1">
                            <div class="grid grid-cols-5 gap-2">
                                <?php
                                $gallery_ids = get_post_meta( get_the_ID(), 'product_gallery_ids', true );
                                if ( ! empty( $gallery_ids ) ) :
                                    foreach ( $gallery_ids as $attachment_id ) : ?>
                                        <div class="overflow-hidden border-2 border-transparent rounded-lg">
                                            <a href="<?php echo wp_get_attachment_url( $attachment_id ); ?>" data-fancybox="product-gallery">
                                                <?php echo wp_get_attachment_image( $attachment_id, 'thumbnail', false, ['class' => 'w-full h-full object-cover'] ); ?>
                                            </a>
                                        </div>
                                <?php endforeach; endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-2 lg:row-end-2 lg:row-span-2">
                    <h1 class="text-2xl font-bold text-gray-900 sm:text-3xl">
                        <?php the_title(); ?>
                    </h1>

                    <?php
                    $price = get_post_meta( get_the_ID(), 'product_price', true );
                    if ( $price ) : ?>
                    <div class="flex items-center mt-8">
                        <p class="text-3xl font-bold text-gray-900">
                            $<?php echo esc_html($price); ?>
                        </p>
                    </div>
                    <?php endif; ?>

                    <div class="mt-8">
                        <h2 class="text-base font-bold text-gray-900">Description</h2>
                        <div class="mt-4 prose">
                            <?php the_content(); ?>
                        </div>
                    </div>

                    <div class="product-meta border-t border-b border-gray-200 py-4 my-6">
                        <h2 class="text-base font-bold text-gray-900 mb-4">Product Details</h2>
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
                            ?>
                        </div>
                    </div>

                    <div class="flex items-center mt-10 space-x-4">
                        <a href="#contact-seller-form" class="inline-flex items-center justify-center px-12 py-3 text-base font-bold leading-7 text-white transition-all duration-200 bg-gray-900 border-2 border-transparent rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-900 hover:bg-gray-700">
                            Contact Seller
                        </a>
                    </div>

                    <div class="mt-8 border-t pt-8">
                        <h3 class="text-xl font-bold mb-4">Seller Information</h3>
                        <div class="flex items-center">
                            <div class="mr-4">
                                <?php echo get_avatar( get_the_author_meta( 'ID' ), 64, '', '', ['class' => 'rounded-full'] ); ?>
                            </div>
                            <div>
                                <p class="font-bold"><?php the_author(); ?></p>
                                <a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>" class="text-violet-500 hover:underline">View all products from this seller</a>
                                <?php if ( get_current_user_id() == $post->post_author ) : ?>
                                    <a href="<?php echo home_url('/edit-product?product_id=' . get_the_ID()); ?>" class="mt-2 inline-block bg-violet-500 text-white hover:bg-violet-600 py-1 px-3 rounded-md text-xs font-medium">Edit Product</a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <div id="contact-seller-form" class="mt-12 max-w-lg mx-auto">
                <div class="contact-seller p-6 bg-white rounded-lg shadow-md">
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
    </section>
    <?php endwhile; ?>
</main>

<?php
get_footer();
?>
