<?php
require_once get_stylesheet_directory() . '/inc-banner-cpt.php';

add_filter('show_admin_bar', '__return_false');

add_action('wp_enqueue_scripts', function() {
    wp_enqueue_style('bootstrap-cdn', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css');
    wp_enqueue_style('storefront-child-main', get_stylesheet_directory_uri() . '/assets/css/main.css', ['storefront-style', 'bootstrap-cdn'], '1.0.0');
    wp_enqueue_script('bootstrap-cdn', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js', [], null, true);
    wp_enqueue_script('storefront-child-main', get_stylesheet_directory_uri() . '/assets/js/main.js', [], '1.0.0', true);
});

add_filter('woocommerce_add_to_cart_fragments', function($fragments) {
    $count = WC()->cart->get_cart_contents_count();
    ob_start(); ?>
    <span class="cart-count badge"><?php echo $count > 0 ? esc_html($count) : ''; ?></span>
    <?php $fragments['.header-cart-link .cart-count'] = ob_get_clean();
    return $fragments;
});

add_action('admin_enqueue_scripts', function($hook) {
    if (in_array($hook, ['post.php', 'post-new.php'])) {
        $screen = get_current_screen();
        if ($screen && $screen->post_type === 'banner_home') {
            wp_enqueue_style('storefront-child-admin', get_stylesheet_directory_uri() . '/assets/css/admin.css');
        }
    }
});

add_action('widgets_init', function() {
    unregister_sidebar('sidebar-1');
    unregister_sidebar('sidebar-2');
    unregister_sidebar('homepage');
}, 20);
