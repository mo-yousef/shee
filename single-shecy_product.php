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
    <div class="product-page-container">

        <div class="product-page-breadcrumbs">
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a>
            <span>/</span>
            <a href="<?php echo get_post_type_archive_link('shecy_product'); ?>">Products</a>
            <span>/</span>
            <span><?php the_title(); ?></span>
        </div>

        <div class="product-page-grid">
            <div class="product-gallery-wrapper">
                <?php
                // This is the gallery code from the previous step, it remains the same.
                $gallery_ids = get_post_meta( get_the_ID(), 'product_gallery_ids', true );
                if ( ! empty( $gallery_ids ) ) :
                ?>
                    <!-- Swiper -->
                    <div style="--swiper-navigation-color: #333; --swiper-pagination-color: #333" class="swiper gallery-top">
                        <div class="swiper-wrapper">
                            <?php foreach ( $gallery_ids as $attachment_id ) : ?>
                                <div class="swiper-slide">
                                    <a href="<?php echo wp_get_attachment_url( $attachment_id ); ?>" data-fancybox="product-gallery">
                                        <?php echo wp_get_attachment_image( $attachment_id, 'large' ); ?>
                                    </a>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <div class="swiper-button-next"></div>
                        <div class="swiper-button-prev"></div>
                    </div>
                    <div class="swiper gallery-thumbs">
                        <div class="swiper-wrapper">
                            <?php foreach ( $gallery_ids as $attachment_id ) : ?>
                                <div class="swiper-slide">
                                    <?php echo wp_get_attachment_image( $attachment_id, 'thumbnail' ); ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php elseif ( has_post_thumbnail() ) : ?>
                    <div class="product-gallery-main-image">
                        <a href="<?php the_post_thumbnail_url('large'); ?>" data-fancybox="product-gallery">
                            <?php the_post_thumbnail( 'large' ); ?>
                        </a>
                    </div>
                <?php else : ?>
                    <div class="product-gallery-placeholder">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/hero-placeholder.jpg" alt="Default product image">
                    </div>
                <?php endif; ?>
            </div>

            <div class="product-details-wrapper">
                <h1 class="product-title"><?php the_title(); ?></h1>

                <?php
                $condition = get_post_meta( get_the_ID(), 'product_condition', true );
                if ( $condition ) : ?>
                    <div class="product-condition">
                        <span class="badge"><?php echo esc_html( ucwords( str_replace( '_', ' ', $condition ) ) ); ?></span>
                    </div>
                <?php endif; ?>

                <?php
                $price = get_post_meta( get_the_ID(), 'product_price', true );
                if ( $price ) : ?>
                <p class="product-price">$<?php echo esc_html($price); ?></p>
                <?php endif; ?>

                <div class="product-summary">
                    <?php // A short summary could go here if available, for now we show the beginning of the content ?>
                    <?php echo wp_trim_words( get_the_content(), 30, '...' ); ?>
                </div>

                <div class="product-actions">
                    <a href="#contact-seller-form" class="btn btn-primary">Contact Seller</a>
                </div>
            </div>
        </div>

        <div class="product-info-tabs" x-data="{ activeTab: 'description' }">
            <div class="tabs-list" role="tablist">
                <button @click="activeTab = 'description'" :class="{ 'active': activeTab === 'description' }" class="tab-trigger" role="tab" aria-controls="tab-description" :aria-selected="activeTab === 'description'">Description</button>
                <button @click="activeTab = 'details'" :class="{ 'active': activeTab === 'details' }" class="tab-trigger" role="tab" aria-controls="tab-details" :aria-selected="activeTab === 'details'">Details</button>
                <button @click="activeTab = 'seller'" :class="{ 'active': activeTab === 'seller' }" class="tab-trigger" role="tab" aria-controls="tab-seller" :aria-selected="activeTab === 'seller'">Seller Info</button>
            </div>

            <div id="tab-description" x-show="activeTab === 'description'" class="tab-content" role="tabpanel">
                <div class="prose">
                    <?php the_content(); ?>
                </div>
            </div>

            <div id="tab-details" x-show="activeTab === 'details'" class="tab-content" role="tabpanel" style="display: none;">
                <ul class="product-meta-list">
                    <?php
                    $categories = get_the_terms( get_the_ID(), 'shecy_product_category' );
                    if ( $categories && ! is_wp_error( $categories ) ) {
                        echo '<li><strong>Category:</strong> ';
                        $cat_links = array();
                        foreach ( $categories as $category ) {
                            $cat_links[] = '<a href="' . get_term_link( $category ) . '">' . esc_html( $category->name ) . '</a>';
                        }
                        echo implode( ', ', $cat_links ) . '</li>';
                    }
                    if ( $condition ) {
                        echo '<li><strong>Condition:</strong> ' . esc_html( ucwords( str_replace( '_', ' ', $condition ) ) ) . '</li>';
                    }
                    $brand = get_post_meta( get_the_ID(), 'product_brand', true );
                    if ( $brand ) {
                        echo '<li><strong>Brand:</strong> ' . esc_html( $brand ) . '</li>';
                    }
                    $location = get_post_meta( get_the_ID(), 'product_location', true );
                    if ( $location ) {
                        echo '<li><strong>Location:</strong> ' . esc_html( $location ) . '</li>';
                    }
                    ?>
                </ul>
            </div>

            <div id="tab-seller" x-show="activeTab === 'seller'" class="tab-content" role="tabpanel" style="display: none;">
                <div class="seller-info-box">
                    <div class="seller-info-content">
                        <div class="seller-avatar">
                            <?php echo get_avatar( get_the_author_meta( 'ID' ), 80 ); ?>
                        </div>
                        <div class="seller-details">
                            <p class="seller-name"><?php the_author(); ?></p>
                            <a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>" class="seller-link">View all products</a>
                            <?php if ( get_current_user_id() == $post->post_author ) : ?>
                                <a href="<?php echo home_url('/edit-product?product_id=' . get_the_ID()); ?>" class="btn btn-secondary edit-product-link">Edit Product</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="contact-seller-form" class="contact-seller-section">
            <div class="contact-seller-wrapper">
                <h2 class="section-title">Contact Seller</h2>
                <form class="contact-form">
                    <div class="form-group">
                        <label for="contact-name">Your Name</label>
                        <input type="text" name="contact-name" id="contact-name" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="contact-email">Your Email</label>
                        <input type="email" name="contact-email" id="contact-email" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="contact-message">Message</label>
                        <textarea name="contact-message" id="contact-message" rows="4" class="form-control"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary btn-full-width">Send Message</button>
                    <p class="form-note">(Note: Form is for display only and not functional yet)</p>
                </form>
            </div>
        </div>

    </div>
    <?php endwhile; ?>
</main>

<?php
get_footer();
?>
