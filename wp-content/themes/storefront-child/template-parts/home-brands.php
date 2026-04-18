<?php if ( ! defined( 'ABSPATH' ) ) exit;

$brands = get_posts( [
	'post_type'      => 'brand',
	'posts_per_page' => 20,
	'orderby'        => 'menu_order',
	'order'          => 'ASC',
] );

if ( empty( $brands ) ) return;
?>
<section class="home-brands">
	<div class="col-full">
		<h2 class="section-title">Marcas</h2>
		<div class="brands-grid">
			<?php foreach ( $brands as $brand ) :
				$logo = get_the_post_thumbnail_url( $brand->ID, 'medium' );
			?>
			<div class="brand-item">
				<?php if ( $logo ) : ?>
					<img src="<?php echo esc_url( $logo ); ?>" alt="<?php echo esc_attr( $brand->post_title ); ?>">
				<?php else : ?>
					<span class="brand-name-text"><?php echo esc_html( $brand->post_title ); ?></span>
				<?php endif; ?>
			</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
