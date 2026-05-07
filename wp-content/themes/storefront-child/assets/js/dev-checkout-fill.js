/**
 * DEV ONLY — Checkout test data filler
 *
 * Paste in the browser console on /finalizar-compra/ to auto-fill billing
 * fields with fake data. A floating panel shows Stripe test card details.
 *
 * Stripe card fields live inside iframes and cannot be filled via JS.
 * Use the test card shown in the panel to complete payment.
 *
 * DO NOT enqueue this file in production.
 */
/* global jQuery */
(function ($) {
  'use strict';

  // ── Billing data ────────────────────────────────────────────────────────────
  var billing = {
    billing_first_name: 'João',
    billing_last_name:  'Silva',
    billing_country:    'BR',
    billing_address_1:  'Avenida Paulista, 1578',
    billing_address_2:  '10º Andar',
    billing_postcode:   '01310-200',
    billing_city:       'São Paulo',
    billing_state:      'SP',
    billing_phone:      '(11) 98765-4321',
    billing_email:      'teste@exemplo.com.br',
  };

  // Fill fields — trigger only 'change' on postcode to skip CEP autocomplete
  Object.keys(billing).forEach(function (id) {
    var $el = $('#' + id);
    if (!$el.length) return;
    $el.val(billing[id]).trigger(id === 'billing_postcode' ? 'change' : 'input change');
  });

  // Select Stripe (or first available payment method)
  var $stripe = $('input[name="payment_method"][value*="stripe"]');
  var $payment = $stripe.length ? $stripe : $('input[name="payment_method"]').first();
  if ($payment.length) {
    $payment.prop('checked', true).trigger('change');
  }

  $('body').trigger('update_checkout');

  // ── Floating test card panel ────────────────────────────────────────────────
  var card = {
    number:  '4242 4242 4242 4242',
    expiry:  '12 / 30',
    cvc:     '123',
    name:    'JOAO SILVA',
  };

  var panelId = '__dev_card_panel';
  $('#' + panelId).remove();

  var rows = [
    ['Número',      card.number,  'number'],
    ['Validade',    card.expiry,  'expiry'],
    ['CVC',         card.cvc,     'cvc'],
    ['Nome',        card.name,    'name'],
  ];

  var rowsHtml = rows.map(function (r) {
    return '<tr>' +
      '<td style="padding:4px 8px 4px 0;color:#6b7280;white-space:nowrap;font-size:11px;">' + r[0] + '</td>' +
      '<td style="padding:4px 0;font-family:monospace;letter-spacing:.05em;">' + r[1] + '</td>' +
      '<td style="padding:4px 0 4px 8px;">' +
        '<button data-val="' + r[1] + '" style="font-size:10px;padding:1px 6px;background:#f3f4f6;border:1px solid #d1d5db;border-radius:4px;cursor:pointer;color:#374151;" onclick="navigator.clipboard.writeText(this.dataset.val).then(function(){var b=this;b.textContent=\'✓\';setTimeout(function(){b.textContent=\'Copiar\'},1200)}.bind(this))">Copiar</button>' +
      '</td>' +
    '</tr>';
  }).join('');

  var $panel = $('<div id="' + panelId + '"></div>').css({
    position:     'fixed',
    bottom:       '20px',
    right:        '20px',
    zIndex:       99999,
    background:   '#ffffff',
    border:       '1px solid #e5e7eb',
    borderRadius: '10px',
    boxShadow:    '0 4px 20px rgba(0,0,0,.12)',
    padding:      '14px 16px',
    fontFamily:   'system-ui,sans-serif',
    fontSize:     '13px',
    minWidth:     '260px',
    color:        '#111827',
  });

  $panel.html(
    '<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:10px;">' +
      '<strong style="font-size:12px;color:#374151;">🧪 Cartão de teste (Stripe)</strong>' +
      '<button onclick="document.getElementById(\'' + panelId + '\').remove()" style="background:none;border:none;cursor:pointer;color:#9ca3af;font-size:16px;line-height:1;padding:0;">×</button>' +
    '</div>' +
    '<table style="border-collapse:collapse;width:100%;">' + rowsHtml + '</table>' +
    '<p style="margin:10px 0 0;font-size:10px;color:#9ca3af;">Campos de billing preenchidos automaticamente.</p>'
  );

  $('body').append($panel);

  console.log('%c[DEV] Checkout preenchido — use o cartão Stripe 4242 4242 4242 4242', 'color:#2563eb;font-weight:bold;');
}(jQuery));
