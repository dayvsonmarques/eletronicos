<?php if ( ! defined( 'ABSPATH' ) ) exit;

$promo_query = new WP_Query( [
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
] );

if ( ! $promo_query->have_posts() ) return;
?>
<section class="home-promotions">
	<div class="col-full">
		<h2 class="section-title">Promoções</h2>
		<div class="promo-track-wrapper">
			<div class="promo-track" id="promo-track">
				<?php while ( $promo_query->have_posts() ) : $promo_query->the_post();
					$product      = wc_get_product( get_the_ID() );
					$regular      = (float) $product->get_regular_price();
					$sale         = (float) $product->get_sale_price();
					$discount_pct = $regular > 0 ? round( ( 1 - $sale / $regular ) * 100 ) : 0;
					$thumb        = get_the_post_thumbnail_url( get_the_ID(), 'medium' );
					if ( ! $thumb ) $thumb = 'https://via.placeholder.com/300x200?text=Produto';
				?>
				<a href="<?php the_permalink(); ?>" class="promo-card">
					<img src="<?php echo esc_url( $thumb ); ?>" alt="<?php the_title_attribute(); ?>">
					<div class="promo-body">
						<?php if ( $discount_pct > 0 ) : ?>
							<span class="promo-badge">-<?php echo $discount_pct; ?>%</span>
						<?php endif; ?>
						<div class="promo-title"><?php the_title(); ?></div>
						<?php if ( $regular > 0 ) : ?>
							<div class="promo-old-price"><?php echo wc_price( $regular ); ?></div>
						<?php endif; ?>
						<div class="promo-price"><?php echo wc_price( $sale ); ?></div>
					</div>
				</a>
				<?php endwhile; wp_reset_postdata(); ?>
			</div>
		</div>
	</div>
</section>
