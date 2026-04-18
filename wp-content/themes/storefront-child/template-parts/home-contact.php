<?php if ( ! defined( 'ABSPATH' ) ) exit;

$phone    = get_theme_mod( 'contact_phone', '' );
$whatsapp = get_theme_mod( 'contact_whatsapp', '' );
$address  = get_theme_mod( 'contact_address', '' );
$fallback = 'Configure em Aparência &gt; Personalizar';
?>
<section class="home-contact">
	<div class="col-full">
		<div class="row g-0">

			<div class="col-12 col-md-4 contact-item contact-item--phone">
				<div class="contact-icon">&#9990;</div>
				<div class="contact-label">Compre por telefone</div>
				<div class="contact-value">
					<?php if ( $phone ) : ?>
						<a href="tel:<?php echo esc_attr( preg_replace( '/\D/', '', $phone ) ); ?>"><?php echo esc_html( $phone ); ?></a>
					<?php else : ?>
						<?php echo $fallback; ?>
					<?php endif; ?>
				</div>
			</div>

			<div class="col-12 col-md-4 contact-item contact-item--whatsapp">
				<div class="contact-icon">&#128172;</div>
				<div class="contact-label">Fale por WhatsApp</div>
				<div class="contact-value">
					<?php if ( $whatsapp ) : ?>
						<a href="https://wa.me/<?php echo esc_attr( preg_replace( '/\D/', '', $whatsapp ) ); ?>" target="_blank" rel="noopener noreferrer"><?php echo esc_html( $whatsapp ); ?></a>
					<?php else : ?>
						<?php echo $fallback; ?>
					<?php endif; ?>
				</div>
			</div>

			<div class="col-12 col-md-4 contact-item contact-item--store">
				<div class="contact-icon">&#128205;</div>
				<div class="contact-label">Nossa loja física</div>
				<div class="contact-value">
					<?php echo $address ? nl2br( esc_html( $address ) ) : $fallback; ?>
				</div>
			</div>

		</div>
	</div>
</section>
