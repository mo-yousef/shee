<div class="product-card">
    <a href="<?php the_permalink(); ?>" class="product-card__image-link">
        <div class="product-card__image-wrapper">
            <?php if (has_post_thumbnail()) : ?>
                <?php the_post_thumbnail('medium_large', ['class' => 'product-card__image']); ?>
            <?php else : ?>
                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/hero-placeholder.jpg" alt="Default product image" class="product-card__image">
            <?php endif; ?>
        </div>
    </a>
    <div class="product-card__content">
        <p class="product-card__category">
            <?php
            $categories = get_the_terms(get_the_ID(), 'shecy_product_category');
            if ($categories && !is_wp_error($categories)) {
                echo esc_html($categories[0]->name);
            }
            ?>
        </p>
        <h3 class="product-card__title">
            <a href="<?php the_permalink(); ?>">
                <?php the_title(); ?>
            </a>
        </h3>
        <p class="product-card__price">
            <?php
            $price = get_post_meta(get_the_ID(), 'product_price', true);
            echo $price ? '$' . esc_html($price) : 'Price not set';
            ?>
        </p>
    </div>
</div>
