<?php
/*
 * Template Name: Home Custom Eletronicos
 * Description: Página inicial personalizada para Storefront Child Eletronicos
 */
get_header();
?>
<div class="container my-5">
  <!-- Banner -->
  <section class="mb-5">
    <div class="banner-home text-center p-4 bg-primary text-white rounded">
      <h2>Bem-vindo à Eletrônicos!</h2>
      <p>Os melhores componentes e ferramentas para você.</p>
      <img src="https://via.placeholder.com/1200x300?text=Banner+Eletronicos" class="img-fluid rounded" alt="Banner principal">
    </div>
  </section>
  <!-- Resumo da loja -->
  <section class="mb-5 text-center">
    <h3>Sobre a Loja</h3>
    <p class="lead">Especializada em eletrônicos, componentes e ferramentas. Qualidade, preço justo e entrega rápida para todo o Brasil.</p>
  </section>
  <!-- Produtos mais vistos -->
  <section class="mb-5">
    <h3 class="mb-4 text-center">Produtos mais vistos</h3>
    <div class="row">
      <?php
      $args = array(
        'post_type' => 'product',
        'posts_per_page' => 8,
        'orderby' => 'date',
        'order' => 'DESC',
      );
      $loop = new WP_Query($args);
      if ($loop->have_posts()) :
        while ($loop->have_posts()) : $loop->the_post();
          $product = wc_get_product(get_the_ID());
      ?>
        <div class="col-6 col-md-3 mb-4">
          <div class="card h-100">
            <a href="<?php the_permalink(); ?>">
              <?php if (has_post_thumbnail()) {
                the_post_thumbnail('medium', ['class' => 'card-img-top']);
              } else {
                echo '<img src="https://via.placeholder.com/300x300?text=Produto" class="card-img-top" alt="Produto">';
              } ?>
            </a>
            <div class="card-body text-center">
              <h5 class="card-title"><?php the_title(); ?></h5>
              <span class="price text-success fw-bold"><?php echo $product->get_price_html(); ?></span>
            </div>
          </div>
        </div>
      <?php endwhile; wp_reset_postdata(); endif; ?>
    </div>
  </section>
</div>
<?php get_footer(); ?>
