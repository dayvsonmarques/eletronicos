<?php
defined( 'ABSPATH' ) || exit;

$customer_id = get_current_user_id();

if ( ! wc_ship_to_billing_address_only() && wc_shipping_enabled() ) {
	$get_addresses = apply_filters(
		'woocommerce_my_account_get_addresses',
		array(
			'billing'  => __( 'Billing address', 'woocommerce' ),
			'shipping' => __( 'Shipping address', 'woocommerce' ),
		),
		$customer_id
	);
} else {
	$get_addresses = apply_filters(
		'woocommerce_my_account_get_addresses',
		array(
			'billing' => __( 'Billing address', 'woocommerce' ),
		),
		$customer_id
	);
}
?>

<div class="address-grid">
	<?php foreach ( $get_addresses as $name => $address_title ) : ?>
		<?php $address = wc_get_account_formatted_address( $name ); ?>

		<div class="address-card <?php echo ! $address ? 'address-card--empty' : ''; ?>">
			<?php if ( ! $address ) : ?>

				<a href="<?php echo esc_url( wc_get_endpoint_url( 'edit-address', $name ) ); ?>" class="address-card__add-link">
					<span class="address-card__add-icon">
						<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
							<path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
						</svg>
					</span>
					<span class="address-card__add-label">Adicionar <?php echo esc_html( strtolower( $address_title ) ); ?></span>
				</a>

			<?php else : ?>

				<div class="address-card__header">
					<span class="address-card__type"><?php echo esc_html( $address_title ); ?></span>
				</div>

				<address class="address-card__address">
					<?php echo wp_kses_post( $address ); ?>
					<?php do_action( 'woocommerce_my_account_after_my_address', $name ); ?>
				</address>

				<div class="address-card__footer">
					<a href="<?php echo esc_url( wc_get_endpoint_url( 'edit-address', $name ) ); ?>" class="address-card__action">
						Alterar
					</a>
				</div>

			<?php endif; ?>
		</div>

	<?php endforeach; ?>
</div>
