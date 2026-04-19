<?php
add_action('init', function() {
    register_post_type('banner_home', [
        'labels' => [
            'name' => 'Banners',
            'singular_name' => 'Banner',
            'add_new' => 'Adicionar novo',
            'add_new_item' => 'Adicionar novo banner',
            'edit_item' => 'Editar banner',
            'new_item' => 'Novo banner',
            'view_item' => 'Ver banner',
            'search_items' => 'Buscar banners',
            'not_found' => 'Nenhum banner encontrado',
            'not_found_in_trash' => 'Nenhum banner na lixeira',
        ],
        'public' => false,
        'show_ui' => true,
        'menu_icon' => 'dashicons-images-alt2',
        'supports' => ['title', 'thumbnail'],
        'show_in_rest' => false,
    ]);
});

add_action('add_meta_boxes', function() {
    add_meta_box('banner_home_link', 'Link do Banner (opcional)', function($post) {
        $value = get_post_meta($post->ID, '_banner_link', true);
        echo '<input type="url" name="banner_link" value="' . esc_attr($value) . '" class="banner-meta-field" placeholder="https://...">';
    }, 'banner_home', 'normal');
    add_meta_box('banner_home_text', 'Texto do Banner (opcional)', function($post) {
        $value = get_post_meta($post->ID, '_banner_text', true);
        echo '<textarea name="banner_text" class="banner-meta-field" rows="2" placeholder="Texto opcional do banner">' . esc_textarea($value) . '</textarea>';
    }, 'banner_home', 'normal');
});

add_action('save_post_banner_home', function($post_id) {
    if (isset($_POST['banner_link'])) {
        update_post_meta($post_id, '_banner_link', esc_url_raw($_POST['banner_link']));
    }
    if (isset($_POST['banner_text'])) {
        update_post_meta($post_id, '_banner_text', sanitize_text_field($_POST['banner_text']));
    }
});
