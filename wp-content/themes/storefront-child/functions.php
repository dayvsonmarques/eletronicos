<?php
// CPT e campos extras para banners gerenciáveis
require_once get_stylesheet_directory() . '/inc-banner-cpt.php';
// Carrega estilos do tema pai
add_action('wp_enqueue_scripts', function() {
    wp_enqueue_style('storefront-parent', get_template_directory_uri() . '/style.css');
    wp_enqueue_style('bootstrap-cdn', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css');
    wp_enqueue_style('storefront-child-main', get_stylesheet_directory_uri() . '/assets/css/main.css', ['storefront-parent', 'bootstrap-cdn'], '1.0.0');
    wp_enqueue_script('bootstrap-cdn', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js', [], null, true);
    wp_enqueue_script('storefront-child-main', get_stylesheet_directory_uri() . '/assets/js/main.js', [], '1.0.0', true);
});
// Remove widgets da home
add_action('widgets_init', function() {
    unregister_sidebar('sidebar-1');
    unregister_sidebar('sidebar-2');
    unregister_sidebar('homepage');
}, 20);
