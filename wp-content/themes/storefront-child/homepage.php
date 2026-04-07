<?php
/*
 * Template Name: Home Custom Eletronicos
 * Description: Página inicial personalizada para Storefront Child Eletronicos
 */
get_header();
?>

<?php
// ─── Categories ──────────────────────────────────────────────────────────────
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
    <h2 class="section-title">Categorias</h2>
    <div class="category-grid">
      <?php foreach ($top_cats as $cat) :
        $thumb_id  = get_term_meta($cat->term_id, 'thumbnail_id', true);
        $thumb_url = $thumb_id ? wp_get_attachment_image_url($thumb_id, 'thumbnail') : '';
        $cat_url   = get_term_link($cat);
      ?>
      <a href="<?php echo esc_url($cat_url); ?>" class="category-card">
        <?php if ($thumb_url) : ?>
          <img src="<?php echo esc_url($thumb_url); ?>" alt="<?php echo esc_attr($cat->name); ?>" class="category-thumb">
        <?php else : ?>
          <span class="category-icon">&#9711;</span>
        <?php endif; ?>
        <span class="category-name"><?php echo esc_html($cat->name); ?></span>
        <span class="category-count"><?php echo (int) $cat->count; ?> produtos</span>
      </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<?php
// ─── Promotions slider ───────────────────────────────────────────────────────
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
// ─── Featured products ───────────────────────────────────────────────────────
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

<?php get_footer(); ?>
