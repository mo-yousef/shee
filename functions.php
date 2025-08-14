<?php
/**
 * She Cy functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package SheCy
 */

if ( ! defined( 'SHECY_VERSION' ) ) {
	// Replace the version number of the theme on each release.
	define( 'SHECY_VERSION', '1.0.1' );
}

if ( ! function_exists( 'shecy_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function shecy_setup() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on She Cy, use a find and replace
		 * to change 'shecy' to the name of your theme in all the template files.
		 */
		load_theme_textdomain( 'shecy', get_template_directory() . '/languages' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );

		// This theme uses wp_nav_menu() in one location.
		register_nav_menus(
			array(
				'menu-1' => esc_html__( 'Primary', 'shecy' ),
				'footer' => esc_html__( 'Footer Menu', 'shecy' ),
			)
		);

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support(
			'html5',
			array(
				'search-form',
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
				'style',
				'script',
			)
		);

		// Set up the WordPress core custom background feature.
		add_theme_support(
			'custom-background',
			apply_filters(
				'shecy_custom_background_args',
				array(
					'default-color' => 'ffffff',
					'default-image' => '',
				)
			)
		);

		// Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );

		/**
		 * Add support for core custom logo.
		 *
		 * @link https://codex.wordpress.org/Theme_Logo
		 */
		add_theme_support(
			'custom-logo',
			array(
				'height'      => 250,
				'width'       => 250,
				'flex-width'  => true,
				'flex-height' => true,
			)
		);
	}
endif;
add_action( 'after_setup_theme', 'shecy_setup' );

/**
 * Enqueue scripts and styles.
 */
function shecy_scripts() {
	wp_enqueue_style( 'style', get_stylesheet_uri(), array(), SHECY_VERSION );
	wp_enqueue_script( 'main', get_template_directory_uri() . '/assets/js/main.js', array(), SHECY_VERSION, true );
	wp_enqueue_script( 'alpine', 'https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js', array(), null, true );
	wp_enqueue_style( 'fancybox', 'https://cdn.jsdelivr.net/npm/@fancyapps/ui@4.0/dist/fancybox.css', array(), '4.0' );
	wp_enqueue_script( 'fancybox', 'https://cdn.jsdelivr.net/npm/@fancyapps/ui@4.0/dist/fancybox.umd.js', array(), '4.0', true );
	wp_enqueue_style( 'swiper', 'https://unpkg.com/swiper/swiper-bundle.min.css', array(), '10.3.1' );
	wp_enqueue_script( 'swiper', 'https://unpkg.com/swiper/swiper-bundle.min.js', array(), '10.3.1', true );

	if ( is_singular( 'shecy_product' ) ) {
		wp_enqueue_script( 'product-gallery', get_template_directory_uri() . '/assets/js/product-gallery.js', array( 'swiper' ), SHECY_VERSION, true );
	}

	if ( is_front_page() ) {
		wp_enqueue_script( 'home-carousel', get_template_directory_uri() . '/assets/js/home-carousel.js', array( 'swiper' ), SHECY_VERSION, true );
	}
}
add_action( 'wp_enqueue_scripts', 'shecy_scripts' );

/**
 * A custom walker for the mobile navigation menu.
 */
class SheCy_Mobile_Nav_Walker extends Walker_Nav_Menu {
    function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
        $output .= "<div>";
        $output .= '<a href="' . $item->url . '" class="block py-2 px-3 text-base font-medium rounded-md text-gray-700 hover:bg-gray-50 hover:text-gray-900">';
        $output .= $item->title;
        $output .= '</a>';
        $output .= "</div>";
    }
}

/**
 * Custom template tags for this theme.
 */
// require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
// require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
// require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
// if ( defined( 'JETPACK__VERSION' ) ) {
// 	require get_template_directory() . '/inc/jetpack.php';
// }

/**
 * Register Custom Post Types and Taxonomies
 */
function shecy_register_post_types() {

	// CPT: Secondhand Product
	$product_labels = array(
		'name'                  => _x( 'Products', 'Post type general name', 'shecy' ),
		'singular_name'         => _x( 'Product', 'Post type singular name', 'shecy' ),
		'menu_name'             => _x( 'Marketplace', 'Admin Menu text', 'shecy' ),
		'name_admin_bar'        => _x( 'Product', 'Add New on Toolbar', 'shecy' ),
		'add_new'               => __( 'Add New', 'shecy' ),
		'add_new_item'          => __( 'Add New Product', 'shecy' ),
		'new_item'              => __( 'New Product', 'shecy' ),
		'edit_item'             => __( 'Edit Product', 'shecy' ),
		'view_item'             => __( 'View Product', 'shecy' ),
		'all_items'             => __( 'All Products', 'shecy' ),
		'search_items'          => __( 'Search Products', 'shecy' ),
		'parent_item_colon'     => __( 'Parent Products:', 'shecy' ),
		'not_found'             => __( 'No products found.', 'shecy' ),
		'not_found_in_trash'    => __( 'No products found in Trash.', 'shecy' ),
		'featured_image'        => _x( 'Product Image', 'Overrides the “Featured Image” phrase for this post type.', 'shecy' ),
		'set_featured_image'    => _x( 'Set product image', 'Overrides the “Set featured image” phrase for this post type.', 'shecy' ),
		'remove_featured_image' => _x( 'Remove product image', 'Overrides the “Remove featured image” phrase for this post type.', 'shecy' ),
		'use_featured_image'    => _x( 'Use as product image', 'Overrides the “Use as featured image” phrase for this post type.', 'shecy' ),
		'archives'              => _x( 'Product archives', 'The post type archive label used in nav menus.', 'shecy' ),
		'insert_into_item'      => _x( 'Insert into product', 'Overrides the “Insert into post”/”Insert into page” phrase (used when inserting media into a post).', 'shecy' ),
		'uploaded_to_this_item' => _x( 'Uploaded to this product', 'Overrides the “Uploaded to this post”/”Uploaded to this page” phrase (used when viewing media attached to a post).', 'shecy' ),
		'filter_items_list'     => _x( 'Filter products list', 'Screen reader text for the filter links heading on the post type listing screen.', 'shecy' ),
		'items_list_navigation' => _x( 'Products list navigation', 'Screen reader text for the pagination heading on the post type listing screen.', 'shecy' ),
		'items_list'            => _x( 'Products list', 'Screen reader text for the items list heading on the post type listing screen.', 'shecy' ),
	);

	$product_args = array(
		'labels'             => $product_labels,
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'marketplace' ),
		'capability_type'    => 'post',
		'has_archive'        => 'marketplace',
		'hierarchical'       => false,
		'menu_position'      => 5,
		'supports'           => array( 'title', 'editor', 'thumbnail', 'custom-fields', 'author' ),
		'menu_icon'          => 'dashicons-cart',
	);

	register_post_type( 'shecy_product', $product_args );

	// CPT: Business Directory
	$business_labels = array(
		'name'                  => _x( 'Businesses', 'Post type general name', 'shecy' ),
		'singular_name'         => _x( 'Business', 'Post type singular name', 'shecy' ),
		'menu_name'             => _x( 'Directory', 'Admin Menu text', 'shecy' ),
		'name_admin_bar'        => _x( 'Business', 'Add New on Toolbar', 'shecy' ),
		'add_new'               => __( 'Add New', 'shecy' ),
		'add_new_item'          => __( 'Add New Business', 'shecy' ),
		'new_item'              => __( 'New Business', 'shecy' ),
		'edit_item'             => __( 'Edit Business', 'shecy' ),
		'view_item'             => __( 'View Business', 'shecy' ),
		'all_items'             => __( 'All Businesses', 'shecy' ),
		'search_items'          => __( 'Search Businesses', 'shecy' ),
		'parent_item_colon'     => __( 'Parent Businesses:', 'shecy' ),
		'not_found'             => __( 'No businesses found.', 'shecy' ),
		'not_found_in_trash'    => __( 'No businesses found in Trash.', 'shecy' ),
		'featured_image'        => _x( 'Business Logo', 'Overrides the “Featured Image” phrase for this post type.', 'shecy' ),
		'set_featured_image'    => _x( 'Set business logo', 'Overrides the “Set featured image” phrase for this post type.', 'shecy' ),
		'remove_featured_image' => _x( 'Remove business logo', 'Overrides the “Remove featured image” phrase for this post type.', 'shecy' ),
		'use_featured_image'    => _x( 'Use as business logo', 'Overrides the “Use as featured image” phrase for this post type.', 'shecy' ),
		'archives'              => _x( 'Business archives', 'The post type archive label used in nav menus.', 'shecy' ),
		'insert_into_item'      => _x( 'Insert into business', 'Overrides the “Insert into post”/”Insert into page” phrase (used when inserting media into a post).', 'shecy' ),
		'uploaded_to_this_item' => _x( 'Uploaded to this business', 'Overrides the “Uploaded to this post”/”Uploaded to this page” phrase (used when viewing media attached to a post).', 'shecy' ),
		'filter_items_list'     => _x( 'Filter businesses list', 'Screen reader text for the filter links heading on the post type listing screen.', 'shecy' ),
		'items_list_navigation' => _x( 'Businesses list navigation', 'Screen reader text for the pagination heading on the post type listing screen.', 'shecy' ),
		'items_list'            => _x( 'Businesses list', 'Screen reader text for the items list heading on the post type listing screen.', 'shecy' ),
	);

	$business_args = array(
		'labels'             => $business_labels,
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'directory' ),
		'capability_type'    => 'post',
		'has_archive'        => 'directory',
		'hierarchical'       => false,
		'menu_position'      => 6,
		'supports'           => array( 'title', 'editor', 'thumbnail', 'custom-fields', 'author' ),
		'menu_icon'          => 'dashicons-store',
	);

	register_post_type( 'shecy_business', $business_args );

	// Taxonomy: Product Category
	$product_cat_labels = array(
		'name'              => _x( 'Product Categories', 'taxonomy general name', 'shecy' ),
		'singular_name'     => _x( 'Product Category', 'taxonomy singular name', 'shecy' ),
		'search_items'      => __( 'Search Product Categories', 'shecy' ),
		'all_items'         => __( 'All Product Categories', 'shecy' ),
		'parent_item'       => __( 'Parent Product Category', 'shecy' ),
		'parent_item_colon' => __( 'Parent Product Category:', 'shecy' ),
		'edit_item'         => __( 'Edit Product Category', 'shecy' ),
		'update_item'       => __( 'Update Product Category', 'shecy' ),
		'add_new_item'      => __( 'Add New Product Category', 'shecy' ),
		'new_item_name'     => __( 'New Product Category Name', 'shecy' ),
		'menu_name'         => __( 'Product Categories', 'shecy' ),
	);
	$product_cat_args = array(
		'hierarchical'      => true,
		'labels'            => $product_cat_labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => array( 'slug' => 'marketplace/category' ),
	);
	register_taxonomy( 'shecy_product_category', array( 'shecy_product' ), $product_cat_args );

	// Taxonomy: Product Condition
	$product_cond_labels = array(
		'name'              => _x( 'Product Conditions', 'taxonomy general name', 'shecy' ),
		'singular_name'     => _x( 'Product Condition', 'taxonomy singular name', 'shecy' ),
		'search_items'      => __( 'Search Product Conditions', 'shecy' ),
		'all_items'         => __( 'All Product Conditions', 'shecy' ),
		'edit_item'         => __( 'Edit Product Condition', 'shecy' ),
		'update_item'       => __( 'Update Product Condition', 'shecy' ),
		'add_new_item'      => __( 'Add New Product Condition', 'shecy' ),
		'new_item_name'     => __( 'New Product Condition Name', 'shecy' ),
		'menu_name'         => __( 'Product Conditions', 'shecy' ),
	);
	$product_cond_args = array(
		'hierarchical'      => false, // Like tags
		'labels'            => $product_cond_labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => array( 'slug' => 'marketplace/condition' ),
	);
	register_taxonomy( 'shecy_product_condition', array( 'shecy_product' ), $product_cond_args );

	// Taxonomy: Business Category
	$business_cat_labels = array(
		'name'              => _x( 'Business Categories', 'taxonomy general name', 'shecy' ),
		'singular_name'     => _x( 'Business Category', 'taxonomy singular name', 'shecy' ),
		'search_items'      => __( 'Search Business Categories', 'shecy' ),
		'all_items'         => __( 'All Business Categories', 'shecy' ),
		'parent_item'       => __( 'Parent Business Category', 'shecy' ),
		'parent_item_colon' => __( 'Parent Business Category:', 'shecy' ),
		'edit_item'         => __( 'Edit Business Category', 'shecy' ),
		'update_item'       => __( 'Update Business Category', 'shecy' ),
		'add_new_item'      => __( 'Add New Business Category', 'shecy' ),
		'new_item_name'     => __( 'New Business Category Name', 'shecy' ),
		'menu_name'         => __( 'Business Categories', 'shecy' ),
	);
	$business_cat_args = array(
		'hierarchical'      => true,
		'labels'            => $business_cat_labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => array( 'slug' => 'directory/category' ),
	);
	register_taxonomy( 'shecy_business_category', array( 'shecy_business' ), $business_cat_args );

	// Taxonomy: Affiliate Product Brand
	$brand_labels = array(
		'name'              => _x( 'Brands', 'taxonomy general name', 'shecy' ),
		'singular_name'     => _x( 'Brand', 'taxonomy singular name', 'shecy' ),
		'search_items'      => __( 'Search Brands', 'shecy' ),
		'all_items'         => __( 'All Brands', 'shecy' ),
		'parent_item'       => __( 'Parent Brand', 'shecy' ),
		'parent_item_colon' => __( 'Parent Brand:', 'shecy' ),
		'edit_item'         => __( 'Edit Brand', 'shecy' ),
		'update_item'       => __( 'Update Brand', 'shecy' ),
		'add_new_item'      => __( 'Add New Brand', 'shecy' ),
		'new_item_name'     => __( 'New Brand Name', 'shecy' ),
		'menu_name'         => __( 'Brands', 'shecy' ),
	);
	$brand_args = array(
		'hierarchical'      => true,
		'labels'            => $brand_labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => array( 'slug' => 'shop/brand' ),
	);
	register_taxonomy( 'product_brand', array( 'post' ), $brand_args );
}
add_action( 'init', 'shecy_register_post_types' );

/**
 * Filters the password reset email to use the custom reset page.
 */
function shecy_password_reset_message( $message, $key, $user_login, $user_data ) {
    $reset_url = home_url( '/reset-password' );
    $message = "Someone has requested a password reset for the following account:\r\n\r\n";
    $message .= network_home_url( '/' ) . "\r\n\r\n";
    $message .= sprintf( 'Username: %s', $user_login ) . "\r\n\r\n";
    $message .= "If this was a mistake, just ignore this email and nothing will happen.\r\n\r\n";
    $message .= "To reset your password, visit the following address:\r\n\r\n";
    $message .= esc_url( add_query_arg( array( 'key' => $key, 'login' => rawurlencode( $user_login ) ), $reset_url ) ) . "\r\n";

    return $message;
}
add_filter( 'retrieve_password_message', 'shecy_password_reset_message', 10, 4 );

/**
 * Create pages dynamically when the theme is activated.
 */
function shecy_create_system_pages() {
    $pages_to_create = array(
        array(
            'title' => 'About',
            'slug' => 'about',
            'template' => 'page-about.php'
        ),
        array(
            'title' => 'Contact',
            'slug' => 'contact',
            'template' => 'page-contact.php'
        ),
        array(
            'title' => 'Dashboard',
            'slug' => 'dashboard',
            'template' => 'page-dashboard.php'
        ),
        array(
            'title' => 'Edit Business',
            'slug' => 'edit-business',
            'template' => 'page-edit-business.php'
        ),
        array(
            'title' => 'Edit Product',
            'slug' => 'edit-product',
            'template' => 'page-edit-product.php'
        ),
        array(
            'title' => 'Login',
            'slug' => 'login',
            'template' => 'page-login.php'
        ),
        array(
            'title' => 'Privacy Policy',
            'slug' => 'privacy-policy',
            'template' => 'page-privacy.php'
        ),
        array(
            'title' => 'Profile',
            'slug' => 'profile',
            'template' => 'page-profile.php'
        ),
        array(
            'title' => 'Register',
            'slug' => 'register',
            'template' => 'page-register.php'
        ),
        array(
            'title' => 'Reset Password',
            'slug' => 'reset-password',
            'template' => 'page-reset-password.php'
        ),
        array(
            'title' => 'Shop',
            'slug' => 'shop',
            'template' => 'page-shop.php'
        ),
        array(
            'title' => 'Submit Business',
            'slug' => 'submit-business',
            'template' => 'page-submit-business.php'
        ),
        array(
            'title' => 'Submit Product',
            'slug' => 'submit-product',
            'template' => 'page-submit-product.php'
        ),
        array(
            'title' => 'Terms and Conditions',
            'slug' => 'terms-and-conditions',
            'template' => 'page-terms.php'
        )
    );

    foreach ( $pages_to_create as $page ) {
        // Check if the page already exists
        $existing_page = get_page_by_path( $page['slug'] );

        if ( ! $existing_page ) {
            // Create the page
            $page_data = array(
                'post_title'    => $page['title'],
                'post_name'     => $page['slug'],
                'post_status'   => 'publish',
                'post_type'     => 'page',
                'page_template' => $page['template']
            );
            wp_insert_post( $page_data );
        }
    }
}

function shecy_get_cyprus_cities() {
    return array(
        'Nicosia',
        'Limassol',
        'Larnaca',
        'Paphos',
        'Famagusta',
        'Kyrenia',
    );
}

function shecy_track_post_views() {
    if ( ! is_single() ) return;
    global $post;
    $post_id = $post->ID;

    // Use a cookie to track unique views
    if ( isset( $_COOKIE['shecy_viewed_post_' . $post_id] ) ) {
        return;
    }

    $count = get_post_meta( $post_id, 'shecy_post_views', true );
    $count = empty( $count ) ? 1 : $count + 1;
    update_post_meta( $post_id, 'shecy_post_views', $count );

    // Set a cookie for 1 hour
    setcookie( 'shecy_viewed_post_' . $post_id, 'true', time() + 3600, '/' );
}
add_action( 'template_redirect', 'shecy_track_post_views' );
add_action( 'after_switch_theme', 'shecy_create_system_pages' );

/**
 * Create default categories for products and businesses on theme activation.
 */
function shecy_create_default_categories() {
    $product_categories = array(
        'Clothing',
        'Shoes',
        'Accessories',
        'Beauty',
        'Home',
    );

    foreach ( $product_categories as $category ) {
        if ( ! term_exists( $category, 'shecy_product_category' ) ) {
            wp_insert_term( $category, 'shecy_product_category' );
        }
    }

    $business_categories = array(
        'Salons',
        'Spas',
        'Boutiques',
        'Designers',
        'Stylists',
    );

    foreach ( $business_categories as $category ) {
        if ( ! term_exists( $category, 'shecy_business_category' ) ) {
            wp_insert_term( $category, 'shecy_business_category' );
        }
    }
}
add_action( 'after_switch_theme', 'shecy_create_default_categories' );

function shecy_ensure_categories_exist( $taxonomy ) {
    if ( ! taxonomy_exists( $taxonomy ) ) {
        return;
    }

    $terms = get_terms( array( 'taxonomy' => $taxonomy, 'hide_empty' => false ) );

    if ( empty( $terms ) ) {
        if ( $taxonomy === 'shecy_product_category' ) {
            $categories = array( 'Clothing', 'Shoes', 'Accessories', 'Beauty', 'Home' );
        } elseif ( $taxonomy === 'shecy_business_category' ) {
            $categories = array( 'Salons', 'Spas', 'Boutiques', 'Designers', 'Stylists' );
        } else {
            return;
        }

        foreach ( $categories as $category ) {
            if ( ! term_exists( $category, $taxonomy ) ) {
                wp_insert_term( $category, $taxonomy );
            }
        }
    }
}
