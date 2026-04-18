<?php if ( ! defined( 'ABSPATH' ) ) exit;

$top_cats = get_terms( [
	'taxonomy'   => 'product_cat',
	'hide_empty' => true,
	'orderby'    => 'count',
	'order'      => 'DESC',
	'number'     => 6,
	'exclude'    => get_option( 'default_product_cat' ),
] );

if ( empty( $top_cats ) || is_wp_error( $top_cats ) ) return;
?>
<section class="home-categories">
	<div class="col-full">
		<div class="category-grid">
			<?php foreach ( $top_cats as $cat ) :
				$thumb_id  = get_term_meta( $cat->term_id, 'thumbnail_id', true );
				$thumb_url = $thumb_id ? wp_get_attachment_image_url( $thumb_id, 'medium' ) : '';
				$cat_url   = get_term_link( $cat );
			?>
			<a href="<?php echo esc_url( $cat_url ); ?>" class="category-card">
				<?php if ( $thumb_url ) : ?>
					<img src="<?php echo esc_url( $thumb_url ); ?>" alt="<?php echo esc_attr( $cat->name ); ?>" class="category-thumb">
				<?php else : ?>
					<span class="category-icon"><?php echo eletronicos_category_icon( $cat->slug ); ?></span>
				<?php endif; ?>
				<span class="category-name"><?php echo esc_html( $cat->name ); ?></span>
			</a>
			<?php endforeach; ?>
		</div>
	</div>
</section>
