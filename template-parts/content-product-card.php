<div class="relative overflow-hidden transition-all duration-300 bg-white border border-gray-100 rounded-lg group hover:shadow-xl">
    <div class="overflow-hidden aspect-w-1 aspect-h-1">
        <a href="<?php the_permalink(); ?>">
            <?php if (has_post_thumbnail()) : ?>
                <?php the_post_thumbnail('medium_large', ['class' => 'object-cover w-full h-full transition-all duration-300 group-hover:scale-125']); ?>
            <?php else : ?>
                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/hero-placeholder.jpg" alt="Default product image" class="object-cover w-full h-full">
            <?php endif; ?>
        </a>
    </div>
    <div class="px-3 py-4 sm:px-4 sm:py-5">
        <p class="text-xs font-bold text-gray-400 sm:text-sm">
            <?php
            $categories = get_the_terms(get_the_ID(), 'shecy_product_category');
            if ($categories && !is_wp_error($categories)) {
                echo esc_html($categories[0]->name);
            }
            ?>
        </p>
        <h3 class="mt-3 text-xs font-bold text-gray-900 sm:text-sm md:text-base">
            <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
                <?php the_title(); ?>
                <span class="absolute inset-0" aria-hidden="true"></span>
            </a>
        </h3>
        <p class="mt-3 text-xs font-bold text-gray-900 sm:text-sm">
            <?php
            $price = get_post_meta(get_the_ID(), 'product_price', true);
            echo $price ? '$' . esc_html($price) : 'Price not set';
            ?>
        </p>
    </div>
</div>
