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

    <section class="hero-v2">
        <div class="section-container">
            <div class="hero-v2__content">
                <h1 class="hero-v2__title">Handcrafted Goods, Unforgettable Gifts</h1>
                <p class="hero-v2__subtitle">Discover unique, high-quality items from independent creators around the world.</p>
                <div class="hero-v2__actions">
                    <a href="<?php echo get_post_type_archive_link('shecy_product'); ?>" class="btn btn-primary btn-lg">Shop New Arrivals</a>
                    <a href="#featured-collections" class="btn btn-secondary btn-lg">Explore Collections</a>
                </div>
            </div>
        </div>
    </section>

    <section class="page-section">
        <div class="section-container">
            <div class="section-header">
                <h2 class="section-title">New Arrivals</h2>
                <p class="section-subtitle">Check out the latest additions to our marketplace.</p>
            </div>
            <div class="product-grid">
                <?php
                $latest_products_query = new WP_Query(array(
                    'post_type' => 'shecy_product',
                    'posts_per_page' => 8,
                    'orderby' => 'date',
                    'order' => 'DESC',
                ));

                if ($latest_products_query->have_posts()) :
                    while ($latest_products_query->have_posts()) : $latest_products_query->the_post();
                        get_template_part( 'template-parts/content', 'product-card' );
                    endwhile;
                    wp_reset_postdata();
                else :
                    echo '<p>No new products found.</p>';
                endif;
                ?>
            </div>
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

    <section class="page-section key-features">
        <div class="section-container">
            <div class="key-features__grid">
                <div class="feature-item">
                    <h3 class="feature-item__title">Handcrafted Quality</h3>
                    <p class="feature-item__text">Every item is made with love and care by independent creators.</p>
                </div>
                <div class="feature-item">
                    <h3 class="feature-item__title">Secure Payments</h3>
                    <p class="feature-item__text">Your transactions are safe with our secure payment gateway.</p>
                </div>
                <div class="feature-item">
                    <h3 class="feature-item__title">Worldwide Shipping</h3>
                    <p class="feature-item__text">We ship to every corner of the globe, right to your doorstep.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="page-section testimonials">
        <div class="section-container">
            <div class="section-header">
                <h2 class="section-title">What Our Customers Say</h2>
            </div>
            <div class="testimonials__grid">
                <div class="testimonial-card">
                    <p class="testimonial-card__text">"I found the most beautiful handmade necklace here. The quality is amazing and I get so many compliments!"</p>
                    <p class="testimonial-card__author">- Sarah L.</p>
                </div>
                <div class="testimonial-card">
                    <p class="testimonial-card__text">"A great platform to discover unique items and support small businesses. Highly recommended!"</p>
                    <p class="testimonial-card__author">- Michael B.</p>
                </div>
                <div class="testimonial-card">
                    <p class="testimonial-card__text">"The shipping was fast and the item was even more beautiful in person. I will definitely be back for more."</p>
                    <p class="testimonial-card__author">- Emily R.</p>
                </div>
            </div>
        </div>
    </section>

</main><!-- #main -->

<?php
get_footer();
?>
