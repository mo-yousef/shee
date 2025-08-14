<?php
/**
 * The template for displaying the homepage
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package SheCy
 */

get_header();
?>

<main id="primary" class="site-main">

    <section class="hero-section">
        <div class="hero-background">
            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/hero-placeholder.jpg" alt="Hero background">
            <div class="hero-overlay"></div>
        </div>
        <div class="hero-content">
            <h1 class="hero-title">Find your next favorite thing</h1>
            <p class="hero-subtitle">A community-driven marketplace for unique and handcrafted items.</p>
            <form role="search" method="get" class="hero-search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
                <input type="search" class="hero-search-input" placeholder="Search products..." value="<?php echo get_search_query(); ?>" name="s" />
                <button type="submit" class="btn btn-primary">Search</button>
                <input type="hidden" name="post_type" value="shecy_product" />
            </form>
        </div>
    </section>

    <section class="page-section popular-categories">
        <div class="section-container">
            <div class="section-header">
                <h2 class="section-title">Popular Categories</h2>
                <p class="section-subtitle">Choose from a wide variety of items</p>
                <a href="#" class="section-header-link">All Categories &rarr;</a>
            </div>
            <div class="popular-categories__grid">
                <?php
                // This is static for now as per the original request.
                // In a real-world scenario, this would be a dynamic loop.
                $categories = [
                    ['name' => 'Smart<br>Watches', 'image' => 'https://cdn.rareblocks.xyz/collection/clarity-ecommerce/images/categories/2/smart-watches.png'],
                    ['name' => 'True Wireless<br>Earphones', 'image' => 'https://cdn.rareblocks.xyz/collection/clarity-ecommerce/images/categories/2/wireless-earphone.png'],
                    ['name' => 'Wireless<br>Headphones', 'image' => 'https://cdn.rareblocks.xyz/collection/clarity-ecommerce/images/categories/2/wireless-headphone.png'],
                    ['name' => 'Smart<br>Phones', 'image' => 'https://cdn.rareblocks.xyz/collection/clarity-ecommerce/images/categories/2/smart-phones.png'],
                    ['name' => 'Running<br>Shoes', 'image' => 'https://cdn.rareblocks.xyz/collection/clarity-ecommerce/images/categories/2/runnies-shoes.png'],
                    ['name' => 'Leather<br>Items', 'image' => 'https://cdn.rareblocks.xyz/collection/clarity-ecommerce/images/categories/2/leather-items.png'],
                ];
                foreach ($categories as $category) :
                ?>
                <div class="category-card">
                    <a href="#" class="category-card__link">
                        <img src="<?php echo $category['image']; ?>" alt="" class="category-card__image">
                        <p class="category-card__name"><?php echo $category['name']; ?></p>
                    </a>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section class="page-section featured-products">
        <div class="section-container">
            <div class="section-header">
                <h2 class="section-title">Featured Products</h2>
                <a href="<?php echo get_post_type_archive_link('shecy_product'); ?>" class="section-header-link">View More &rarr;</a>
            </div>
            <div class="product-grid">
                <?php
                $latest_products_query = new WP_Query(array(
                    'post_type' => 'shecy_product',
                    'posts_per_page' => 4,
                    'orderby' => 'date',
                    'order' => 'DESC',
                ));

                if ($latest_products_query->have_posts()) :
                    while ($latest_products_query->have_posts()) : $latest_products_query->the_post();
                        get_template_part( 'template-parts/content', 'product-card' );
                    endwhile;
                    wp_reset_postdata();
                else :
                    echo '<p>No products found in the marketplace yet.</p>';
                endif;
                ?>
            </div>
        </div>
    </section>

    <section class="page-section trending-products">
        <div class="section-container">
            <div class="section-header">
                <h2 class="section-title">Trending Products</h2>
            </div>
            <div class="swiper-container trending-products-swiper">
                <div class="swiper-wrapper">
                    <?php
                    $trending_products_query = new WP_Query(array(
                        'post_type' => 'shecy_product',
                        'posts_per_page' => 10,
                        'orderby' => 'meta_value_num',
                        'meta_key' => 'shecy_post_views',
                        'order' => 'DESC',
                    ));

                    if ($trending_products_query->have_posts()) :
                        while ($trending_products_query->have_posts()) : $trending_products_query->the_post();
                            ?>
                            <div class="swiper-slide">
                                <?php get_template_part( 'template-parts/content', 'product-card' ); ?>
                            </div>
                            <?php
                        endwhile;
                        wp_reset_postdata();
                    endif;
                    ?>
                </div>
                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
            </div>
        </div>
    </section>

</main><!-- #main -->

<?php
get_footer();
?>
