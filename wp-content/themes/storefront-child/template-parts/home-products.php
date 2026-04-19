<?php if ( ! defined( 'ABSPATH' ) ) exit;

$products_query = new WP_Query( [
	'post_type'      => 'product',
	'post_status'    => 'publish',
	'posts_per_page' => 8,
	'orderby'        => 'date',
	'order'          => 'DESC',
] );

if ( ! $products_query->have_posts() ) return;
?>
<section class="home-products">
	<div class="col-full">
		<h2 class="section-title">Produtos em Destaque</h2>
		<div class="row g-3">
			<?php while ( $products_query->have_posts() ) : $products_query->the_post();
				$product = wc_get_product( get_the_ID() );
			?>
			<div class="col-6 col-md-3">
				<a href="<?php the_permalink(); ?>" class="card h-100 text-decoration-none text-dark">
					<?php if ( has_post_thumbnail() ) :
						the_post_thumbnail( 'medium', [ 'class' => 'card-img-top' ] );
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
