<?php
/**
 * Footer customizado para Storefront Child Eletronicos
 */
if (!defined('ABSPATH')) exit;
?>
<footer class="bg-white border-top pt-5 mt-5">
  <div class="container">
    <div class="row gy-4 pb-5 align-items-start">
      <div class="col-12 col-lg-4 mb-4 mb-lg-0">
        <h2 class="fw-bold mb-3"><?php bloginfo('name'); ?></h2>
        <p class="text-secondary mb-0" style="max-width: 350px;">Sua loja de eletrônicos e componentes. Qualidade, preço justo e entrega rápida.</p>
      </div>
      <div class="col-12 col-lg-4 mb-4 mb-lg-0">
        <ul class="list-unstyled">
          <li class="mb-2"><a href="#" class="text-reset text-decoration-none">Política de Privacidade</a></li>
          <li class="mb-2"><a href="#" class="text-reset text-decoration-none">Troca e Devolução</a></li>
          <li><a href="#" class="text-reset text-decoration-none">Entrega / Envio</a></li>
        </ul>
      </div>
      <div class="col-12 col-lg-4 d-flex justify-content-lg-end justify-content-center">
        <div>
          <a href="#" class="text-dark me-4" style="font-size: 3rem;"><i class="fab fa-instagram"></i></a>
          <a href="#" class="text-dark" style="font-size: 3rem;"><i class="fab fa-whatsapp"></i></a>
        </div>
      </div>
    </div>
    <hr class="my-0 mb-3" />
    <div class="row align-items-center small text-secondary">
      <div class="col text-center">
        &copy; <?php echo esc_html(date_i18n('Y')); ?> <?php bloginfo('name'); ?>. Todos os direitos reservados.
      </div>
    </div>
  </div>
</footer>
<?php wp_footer(); ?>
