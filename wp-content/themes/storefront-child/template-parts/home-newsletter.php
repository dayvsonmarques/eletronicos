<?php if ( ! defined( 'ABSPATH' ) ) exit;

$account_url = wc_get_page_permalink( 'myaccount' );
?>
<section class="home-newsletter">
	<div class="col-full">
		<div class="newsletter-inner">
			<h2 class="newsletter-title">FIQUE POR DENTRO DAS NOSSAS OFERTAS</h2>
			<form class="newsletter-form" action="<?php echo esc_url( $account_url ); ?>" method="get">
				<div class="newsletter-field-group">
					<span class="newsletter-icon" aria-hidden="true">✉</span>
					<input
						type="email"
						name="newsletter_email"
						placeholder="Digite seu endereço de email"
						class="newsletter-input"
						autocomplete="email"
					>
				</div>
				<button type="submit" class="newsletter-btn">Assinar</button>
			</form>
		</div>
	</div>
</section>
