<?php
require_once get_stylesheet_directory() . '/inc-banner-cpt.php';
require_once get_stylesheet_directory() . '/inc-google-auth.php';
require_once get_stylesheet_directory() . '/inc-cpts.php';
require_once get_stylesheet_directory() . '/inc-customizer.php';
require_once get_stylesheet_directory() . '/inc-helpers.php';

add_filter('show_admin_bar', '__return_false');

add_action('wp_enqueue_scripts', function () {
    wp_enqueue_style('bootstrap-cdn', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css');
    wp_enqueue_style('storefront-child-main', get_stylesheet_directory_uri() . '/assets/css/main.css', ['storefront-style', 'bootstrap-cdn'], '2.0.0');
    wp_enqueue_script('bootstrap-cdn', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js', [], null, true);
    wp_enqueue_script('storefront-child-main', get_stylesheet_directory_uri() . '/assets/js/main.js', [], '2.0.0', true);

    if (is_front_page()) {
        $pb_image = get_theme_mod('promo_banner_image', '');
        if ($pb_image) {
            wp_add_inline_style('storefront-child-main', '.home-promo-banner{background-image:url(' . esc_url($pb_image) . ')}');
        }
    }

    if (is_checkout()) {
        wp_enqueue_script('storefront-checkout', get_stylesheet_directory_uri() . '/assets/js/checkout.js', ['jquery'], '1.0.0', true);
        if (defined('WP_DEBUG') && WP_DEBUG) {
            wp_enqueue_script('storefront-checkout-dev', get_stylesheet_directory_uri() . '/assets/js/dev-checkout-fill.js', ['jquery'], time(), true);
        }
    }
});

add_filter('woocommerce_add_to_cart_fragments', function ($fragments) {
    $count = WC()->cart->get_cart_contents_count();
    ob_start(); ?>
    <span class="cart-count badge"><?php echo $count > 0 ? esc_html($count) : ''; ?></span>
    <?php $fragments['.header-cart-link .cart-count'] = ob_get_clean();
    return $fragments;
});

add_action('admin_enqueue_scripts', function ($hook) {
    if (in_array($hook, ['post.php', 'post-new.php'])) {
        $screen = get_current_screen();
        if ($screen && $screen->post_type === 'banner_home') {
            wp_enqueue_style('storefront-child-admin', get_stylesheet_directory_uri() . '/assets/css/admin.css');
        }
    }
});

add_action('widgets_init', function () {
    unregister_sidebar('sidebar-1');
    unregister_sidebar('sidebar-2');
    unregister_sidebar('homepage');
}, 20);

add_action('init', function () {
    if (get_option('eletronicos_pages_v1_created')) return;
    foreach ([
        'condicoes-de-uso'       => 'Condições de uso do site',
        'politica-de-entrega'    => 'Política de entrega',
        'trocas-e-devolucoes'    => 'Trocas e devoluções',
        'direitos-do-consumidor' => 'Direitos do consumidor',
    ] as $slug => $title) {
        if (!get_page_by_path($slug)) {
            wp_insert_post([
                'post_title'   => $title,
                'post_name'    => $slug,
                'post_status'  => 'publish',
                'post_type'    => 'page',
                'post_content' => '',
            ]);
        }
    }
    update_option('eletronicos_pages_v1_created', true);
}, 1);

add_filter('woocommerce_checkout_get_value', function ($value, $input) {
    if ($value || !is_user_logged_in()) return $value;
    $user = wp_get_current_user();
    $map  = [
        'billing_first_name' => $user->first_name,
        'billing_last_name'  => $user->last_name,
        'billing_email'      => $user->user_email,
    ];
    return $map[$input] ?? $value;
}, 10, 2);

add_filter('woocommerce_cancel_unpaid_order', function ($cancel, $order) {
    return $order->get_payment_method() === 'asaas-pix' ? false : $cancel;
}, 10, 2);

add_filter('gettext', function ($translation, $text, $domain) {
    if ($domain === 'woocommerce' && $text === 'Username or email') {
        return 'Usuário ou email';
    }
    return $translation;
}, 10, 3);

add_filter('woocommerce_checkout_registration_required', '__return_true');
add_filter('pre_option_woocommerce_enable_guest_checkout', fn() => 'no');

remove_action('woocommerce_checkout_terms_and_conditions', 'wc_checkout_privacy_policy_text', 20);

add_action('woocommerce_review_order_after_submit', function () {
    $privacy_page_id = wc_privacy_policy_page_id();
    $link = $privacy_page_id
        ? '<a href="' . esc_url(get_permalink($privacy_page_id)) . '" target="_blank">política de privacidade</a>'
        : 'política de privacidade';
    echo '<p class="checkout-privacy-notice">Seus dados pessoais serão usados para processar seu pedido, oferecer suporte à sua experiência em todo este site e para outros fins descritos em nossa ' . $link . '.</p>';
});

add_action('woocommerce_thankyou_asaas-pix', function ($order_id) {
    $order = wc_get_order($order_id);
    if (!$order || !$order->has_status(['processing', 'completed'])) return;
    $gateways = WC()->payment_gateways()->payment_gateways();
    if (!isset($gateways['asaas-pix'])) return;
    remove_action('woocommerce_thankyou_asaas-pix', [$gateways['asaas-pix'], 'append_html_to_thankyou_page'], 10);
}, 1);

add_action('woocommerce_view_order', function ($order_id) {
    $order = wc_get_order($order_id);
    if (!$order || !$order->has_status(['processing', 'completed'])) return;
    $gateways = WC()->payment_gateways()->payment_gateways();
    if (!isset($gateways['asaas-pix'])) return;
    remove_action('woocommerce_view_order', [$gateways['asaas-pix'], 'append_html_to_thankyou_page'], 10);
}, 1);

add_action('woocommerce_register_form_start', function () {
    if (!empty($_GET['email'])) {
        $prefill = sanitize_email(wp_unslash($_GET['email']));
        if (is_email($prefill)) {
            echo '<script>document.addEventListener("DOMContentLoaded",function(){var f=document.getElementById("reg_email");if(f&&!f.value)f.value=' . wp_json_encode($prefill) . ';});</script>';
        }
    }
});
