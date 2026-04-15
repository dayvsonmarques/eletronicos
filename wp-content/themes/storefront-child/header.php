<?php
if (!defined('ABSPATH')) exit;
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>

<?php wp_body_open(); ?>

<?php do_action('storefront_before_site'); ?>

<div id="page" class="hfeed site">

  <?php do_action('storefront_before_header'); ?>

  <header id="masthead" class="site-header" role="banner">
    <div class="col-full">
      <div class="d-flex align-items-center justify-content-between flex-wrap">
        <a href="<?php echo esc_url(home_url('/')); ?>" class="fw-bold fs-3 text-decoration-none mb-2 mb-md-0 site-logo-text">
          <?php bloginfo('name'); ?>
        </a>
        <div class="d-flex align-items-center gap-3">
          <nav>
            <?php
              wp_nav_menu([
                'theme_location' => 'primary',
                'container'      => false,
                'menu_class'     => 'nav gap-2',
                'fallback_cb'    => 'wp_page_menu',
              ]);
            ?>
          </nav>
          <a href="<?php echo esc_url(wc_get_cart_url()); ?>" class="header-cart-link text-decoration-none position-relative" aria-label="Carrinho">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true">
              <path d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .491.592l-1.5 8A.5.5 0 0 1 13 12H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5zM3.102 4l1.313 7h8.17l1.313-7H3.102zM5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4zM5 13a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm7 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
            </svg>
            <?php $count = WC()->cart ? WC()->cart->get_cart_contents_count() : 0; ?>
            <span class="cart-count badge"><?php echo $count > 0 ? esc_html($count) : ''; ?></span>
          </a>
        </div>
      </div>
    </div>
  </header>

  <?php do_action('storefront_before_content'); ?>

  <div id="content" class="site-content" tabindex="-1">

  <?php if (is_front_page()) :
    $banner_query = new WP_Query([
      'post_type'      => 'banner_home',
      'posts_per_page' => 5,
      'orderby'        => 'menu_order date',
      'order'          => 'ASC',
    ]);
    $banners = $banner_query->have_posts() ? $banner_query->posts : [];
    if (empty($banners)) {
      $banners = [
        ['url' => 'https://via.placeholder.com/1920x600?text=Banner+1', 'alt' => 'Banner 1', 'link' => '', 'text' => ''],
        ['url' => 'https://via.placeholder.com/1920x600?text=Banner+2', 'alt' => 'Banner 2', 'link' => '', 'text' => ''],
        ['url' => 'https://via.placeholder.com/1920x600?text=Banner+3', 'alt' => 'Banner 3', 'link' => '', 'text' => ''],
      ];
    }
  ?>
  <section id="banner-section">
    <div id="banner-carousel" class="carousel slide" data-bs-ride="carousel">
      <div class="carousel-inner">
        <?php $i = 0; foreach ($banners as $banner) :
          if (isset($banner->ID)) {
            $img_url  = get_the_post_thumbnail_url($banner->ID, 'full');
            $img_alt  = esc_attr(get_the_title($banner->ID));
            $img_link = get_post_meta($banner->ID, '_banner_link', true);
            $img_text = get_post_meta($banner->ID, '_banner_text', true);
          } else {
            $img_url  = $banner['url'];
            $img_alt  = $banner['alt'];
            $img_link = $banner['link'];
            $img_text = $banner['text'];
          }
        ?>
        <div class="carousel-item<?php if ($i++ === 0) echo ' active'; ?>">
          <?php if ($img_link) : ?><a href="<?php echo esc_url($img_link); ?>"><?php endif; ?>
            <img src="<?php echo esc_url($img_url); ?>" class="d-block w-100" alt="<?php echo $img_alt; ?>">
            <?php if ($img_text) : ?>
              <div class="carousel-caption d-none d-md-block bg-dark bg-opacity-50 rounded p-3">
                <span class="fs-4 text-white"><?php echo esc_html($img_text); ?></span>
              </div>
            <?php endif; ?>
          <?php if ($img_link) : ?></a><?php endif; ?>
        </div>
        <?php endforeach; ?>
      </div>
      <button class="carousel-control-prev" type="button" data-bs-target="#banner-carousel" data-bs-slide="prev">
        <span class="carousel-arrow-icon" aria-hidden="true">&#x2039;</span>
        <span class="visually-hidden">Anterior</span>
      </button>
      <button class="carousel-control-next" type="button" data-bs-target="#banner-carousel" data-bs-slide="next">
        <span class="carousel-arrow-icon" aria-hidden="true">&#x203A;</span>
        <span class="visually-hidden">Próximo</span>
      </button>
    </div>
  </section>

  <div id="after-banner-sentinel"></div>

  <?php else : ?>
    <div class="col-full">
      <?php do_action('storefront_content_top'); ?>
  <?php endif; ?>
