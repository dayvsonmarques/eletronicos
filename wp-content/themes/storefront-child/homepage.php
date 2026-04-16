<?php
/* Template Name: Home Custom Eletronicos */
get_header();
?>

<?php
$top_cats = get_terms([
  'taxonomy'   => 'product_cat',
  'hide_empty' => true,
  'orderby'    => 'count',
  'order'      => 'DESC',
  'number'     => 6,
  'exclude'    => get_option('default_product_cat'),
]);
?>
<section class="home-categories">
  <div class="col-full">
    <div class="category-grid">
      <?php foreach ($top_cats as $cat) :
        $thumb_id  = get_term_meta($cat->term_id, 'thumbnail_id', true);
        $thumb_url = $thumb_id ? wp_get_attachment_image_url($thumb_id, 'medium') : '';
        $cat_url   = get_term_link($cat);
      ?>
      <a href="<?php echo esc_url($cat_url); ?>" class="category-card">
        <?php if ($thumb_url) : ?>
          <img src="<?php echo esc_url($thumb_url); ?>" alt="<?php echo esc_attr($cat->name); ?>" class="category-thumb">
        <?php else : ?>
          <span class="category-icon"><?php echo eletronicos_category_icon($cat->slug); ?></span>
        <?php endif; ?>
        <span class="category-name"><?php echo esc_html($cat->name); ?></span>
      </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<?php
$promo_query = new WP_Query([
  'post_type'      => 'product',
  'post_status'    => 'publish',
  'posts_per_page' => 10,
  'meta_query'     => [
    'relation' => 'AND',
    [
      'key'     => '_sale_price',
      'value'   => '',
      'compare' => '!=',
    ],
    [
      'key'     => '_sale_price',
      'compare' => 'EXISTS',
    ],
  ],
]);

if ($promo_query->have_posts()) :
?>
<section class="home-promotions">
  <div class="col-full">
    <h2 class="section-title">Promoções</h2>
    <div class="promo-track-wrapper">
      <div class="promo-track" id="promo-track">
        <?php while ($promo_query->have_posts()) : $promo_query->the_post();
          $product      = wc_get_product(get_the_ID());
          $regular      = (float) $product->get_regular_price();
          $sale         = (float) $product->get_sale_price();
          $discount_pct = $regular > 0 ? round((1 - $sale / $regular) * 100) : 0;
          $thumb        = get_the_post_thumbnail_url(get_the_ID(), 'medium');
          if (!$thumb) $thumb = 'https://via.placeholder.com/300x200?text=Produto';
        ?>
        <a href="<?php the_permalink(); ?>" class="promo-card">
          <img src="<?php echo esc_url($thumb); ?>" alt="<?php the_title_attribute(); ?>">
          <div class="promo-body">
            <?php if ($discount_pct > 0) : ?>
              <span class="promo-badge">-<?php echo $discount_pct; ?>%</span>
            <?php endif; ?>
            <div class="promo-title"><?php the_title(); ?></div>
            <?php if ($regular > 0) : ?>
              <div class="promo-old-price"><?php echo wc_price($regular); ?></div>
            <?php endif; ?>
            <div class="promo-price"><?php echo wc_price($sale); ?></div>
          </div>
        </a>
        <?php endwhile; wp_reset_postdata(); ?>
      </div>
    </div>
  </div>
</section>
<?php endif; ?>

<?php
$products_query = new WP_Query([
  'post_type'      => 'product',
  'post_status'    => 'publish',
  'posts_per_page' => 8,
  'orderby'        => 'date',
  'order'          => 'DESC',
]);
?>
<section class="home-products">
  <div class="col-full">
    <h2 class="section-title">Produtos em Destaque</h2>
    <div class="row g-3">
      <?php while ($products_query->have_posts()) : $products_query->the_post();
        $product = wc_get_product(get_the_ID());
      ?>
      <div class="col-6 col-md-3">
        <a href="<?php the_permalink(); ?>" class="card h-100 text-decoration-none text-dark">
          <?php if (has_post_thumbnail()) :
            the_post_thumbnail('medium', ['class' => 'card-img-top']);
          else : ?>
            <img src="https://via.placeholder.com/300x300?text=Produto" class="card-img-top" alt="Produto">
          <?php endif; ?>
          <div class="card-body text-center">
            <h5 class="card-title"><?php the_title(); ?></h5>
            <span class="price text-success fw-bold"><?php echo $product->get_price_html(); ?></span>
          </div>
        </a>
      </div>
      <?php endwhile; wp_reset_postdata(); ?>
    </div>
  </div>
</section>

<?php
$pb_title    = get_theme_mod('promo_banner_title', '');
$pb_subtitle = get_theme_mod('promo_banner_subtitle', '');
$pb_btn_text = get_theme_mod('promo_banner_btn_text', '');
$pb_btn_link = get_theme_mod('promo_banner_btn_link', '');
$pb_has_bg   = (bool) get_theme_mod('promo_banner_image', '');

if ($pb_title || $pb_has_bg) :
?>
<section class="home-promo-banner">
  <div class="col-full">
    <?php if ($pb_title) : ?>
      <h2 class="promo-banner-title"><?php echo esc_html($pb_title); ?></h2>
    <?php endif; ?>
    <?php if ($pb_subtitle) : ?>
      <p class="promo-banner-subtitle"><?php echo esc_html($pb_subtitle); ?></p>
    <?php endif; ?>
    <?php if ($pb_btn_link && $pb_btn_text) : ?>
      <a href="<?php echo esc_url($pb_btn_link); ?>" class="btn-promo"><?php echo esc_html($pb_btn_text); ?></a>
    <?php endif; ?>
  </div>
</section>
<?php endif; ?>

<?php
$brands = get_posts([
  'post_type'      => 'brand',
  'posts_per_page' => 20,
  'orderby'        => 'menu_order',
  'order'          => 'ASC',
]);

if ($brands) :
?>
<section class="home-brands">
  <div class="col-full">
    <h2 class="section-title">Marcas</h2>
    <div class="brands-grid">
      <?php foreach ($brands as $brand) :
        $logo = get_the_post_thumbnail_url($brand->ID, 'medium');
      ?>
      <div class="brand-item">
        <?php if ($logo) : ?>
          <img src="<?php echo esc_url($logo); ?>" alt="<?php echo esc_attr($brand->post_title); ?>">
        <?php else : ?>
          <span class="brand-name-text"><?php echo esc_html($brand->post_title); ?></span>
        <?php endif; ?>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<section class="home-contact">
  <div class="col-full">
    <div class="row g-0">
      <div class="col-12 col-md-4 contact-item contact-item--phone">
        <div class="contact-icon">&#9990;</div>
        <div class="contact-label">Compre por telefone</div>
        <?php $phone = get_theme_mod('contact_phone', ''); ?>
        <?php if ($phone) : ?>
          <div class="contact-value"><a href="tel:<?php echo esc_attr(preg_replace('/\D/', '', $phone)); ?>"><?php echo esc_html($phone); ?></a></div>
        <?php else : ?>
          <div class="contact-value">Configure em Aparência &gt; Personalizar</div>
        <?php endif; ?>
      </div>
      <div class="col-12 col-md-4 contact-item contact-item--whatsapp">
        <div class="contact-icon">&#128172;</div>
        <div class="contact-label">Fale por WhatsApp</div>
        <?php $whatsapp = get_theme_mod('contact_whatsapp', ''); ?>
        <?php if ($whatsapp) : ?>
          <div class="contact-value"><a href="https://wa.me/<?php echo esc_attr(preg_replace('/\D/', '', $whatsapp)); ?>" target="_blank" rel="noopener"><?php echo esc_html($whatsapp); ?></a></div>
        <?php else : ?>
          <div class="contact-value">Configure em Aparência &gt; Personalizar</div>
        <?php endif; ?>
      </div>
      <div class="col-12 col-md-4 contact-item contact-item--store">
        <div class="contact-icon">&#128205;</div>
        <div class="contact-label">Nossa loja física</div>
        <?php $address = get_theme_mod('contact_address', ''); ?>
        <div class="contact-value"><?php echo $address ? nl2br(esc_html($address)) : 'Configure em Aparência &gt; Personalizar'; ?></div>
      </div>
    </div>
  </div>
</section>

<?php get_footer(); ?>
