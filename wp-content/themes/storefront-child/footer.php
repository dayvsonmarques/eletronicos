<?php if (!defined('ABSPATH')) exit; ?>

    <?php if (!is_front_page()) : ?>
    </div><!-- .col-full -->
    <?php endif; ?>
  </div><!-- #content -->

  <?php do_action('storefront_before_footer'); ?>

  <footer id="colophon" class="site-footer" role="contentinfo">
    <div class="col-full">
      <div class="row gy-4 pb-5 pt-5">

        <div class="col-12 col-md-6 col-lg-3">
          <h3 class="footer-heading"><?php bloginfo('name'); ?></h3>
          <p class="footer-description">Sua loja de eletrônicos e componentes. Qualidade, preço justo e entrega rápida.</p>
          <div class="footer-social">
            <a href="#" class="footer-social-link" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
            <a href="#" class="footer-social-link ms-3" aria-label="WhatsApp"><i class="fab fa-whatsapp"></i></a>
          </div>
        </div>

        <div class="col-12 col-md-6 col-lg-3">
          <h3 class="footer-heading">Dúvidas</h3>
          <ul class="footer-links">
            <?php
            foreach (['condicoes-de-uso', 'politica-de-entrega', 'trocas-e-devolucoes', 'direitos-do-consumidor'] as $slug) :
              $page = get_page_by_path($slug);
              if ($page) : ?>
                <li><a href="<?php echo esc_url(get_permalink($page->ID)); ?>"><?php echo esc_html(get_the_title($page->ID)); ?></a></li>
              <?php endif;
            endforeach; ?>
          </ul>
        </div>

        <div class="col-12 col-md-6 col-lg-3">
          <h3 class="footer-heading">Formas de Pagamento</h3>
          <?php
          $payment_logos = get_posts([
            'post_type'      => 'payment_logo',
            'posts_per_page' => 20,
            'orderby'        => 'menu_order',
            'order'          => 'ASC',
          ]);
          ?>
          <div class="footer-payment-logos">
            <?php if ($payment_logos) : ?>
              <?php foreach ($payment_logos as $logo) :
                $img = get_the_post_thumbnail_url($logo->ID, 'medium');
              ?>
                <?php if ($img) : ?>
                  <img src="<?php echo esc_url($img); ?>" alt="<?php echo esc_attr($logo->post_title); ?>">
                <?php endif; ?>
              <?php endforeach; ?>
            <?php else : ?>
              <?php foreach (eletronicos_payment_logos_svg() as $name => $svg) : ?>
                <span class="payment-svg" title="<?php echo esc_attr($name); ?>"><?php echo $svg; ?></span>
              <?php endforeach; ?>
            <?php endif; ?>
          </div>
        </div>

        <div class="col-12 col-md-6 col-lg-3">
          <h3 class="footer-heading">Institucional</h3>
          <ul class="footer-links">
            <li><a href="#">Sobre nós</a></li>
            <li><a href="#">Trabalhe conosco</a></li>
            <li><a href="#">Fale conosco</a></li>
          </ul>
        </div>

      </div>

      <div class="footer-bottom">
        &copy; <?php echo esc_html(date_i18n('Y')); ?> <?php bloginfo('name'); ?>. Todos os direitos reservados.
      </div>
    </div>
  </footer><!-- #colophon -->

  <?php do_action('storefront_after_footer'); ?>

</div><!-- #page -->

<?php wp_footer(); ?>
