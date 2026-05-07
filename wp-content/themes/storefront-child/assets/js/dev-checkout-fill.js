/**
 * DEV ONLY — Checkout test data filler (Asaas)
 *
 * Paste in browser console on /finalizar-compra/:
 *   fetch('/wp-content/themes/storefront-child/assets/js/dev-checkout-fill.js').then(r=>r.text()).then(eval)
 *
 * DO NOT enqueue this file in production.
 */
/* global jQuery */
(function ($) {
  'use strict';

  // ── Dados fictícios ──────────────────────────────────────────────────────────
  var billing = {
    billing_first_name: 'Dayvson',
    billing_last_name:  'Marques',
    billing_country:    'BR',
    billing_address_1:  'Avenida Paulista, 1578',
    billing_address_2:  '10º Andar',
    billing_postcode:   '01310-200',
    billing_city:       'São Paulo',
    billing_state:      'SP',
    billing_phone:      '(11) 98765-4321',
    billing_email:      'dayvson.marques@gmail.com',
  };

  var extra = {
    billing_persontype: '1',              // 1 = Pessoa Física
    billing_cpf:        '529.982.247-25',
    billing_birthdate:  '01/01/1990',
  };

  // Cartão Asaas sandbox — Mastercard sempre aprovado
  var card = {
    'asaas-cc-name':             'DAYVSON MARQUES',
    'asaas-cc-number':           '5162306219378829',
    'asaas-cc-expiration-month': '12',
    'asaas-cc-expiration-year':  '2030',
    'asaas-cc-security-code':    '318',
  };

  // ── Helpers ──────────────────────────────────────────────────────────────────

  // Setter nativo do HTMLInputElement — funciona com IMask v7 e React-controlled inputs
  var nativeSetter = Object.getOwnPropertyDescriptor(HTMLInputElement.prototype, 'value').set;

  function setField(el, value) {
    if (!el) return false;
    nativeSetter.call(el, value);
    el.dispatchEvent(new InputEvent('input',  { bubbles: true, inputType: 'insertText' }));
    el.dispatchEvent(new Event('change', { bubbles: true }));
    return true;
  }

  function setJqField(id, value) {
    var $el = $('#' + id);
    if (!$el.length) return false;
    $el.val(value).trigger('change');
    return true;
  }

  // ── Fase 1: preenche billing imediatamente ───────────────────────────────────
  Object.keys(billing).forEach(function (id) {
    // Não dispara 'input' no CEP para não acionar o autocomplete
    if (id === 'billing_postcode') {
      setJqField(id, billing[id]);
    } else {
      setField(document.getElementById(id), billing[id]);
    }
  });

  setField(document.getElementById('billing_persontype'), extra.billing_persontype);
  setField(document.getElementById('billing_cpf'),        extra.billing_cpf);
  setField(document.getElementById('billing_birthdate'),  extra.billing_birthdate);

  // ── Fase 2: seleciona Asaas e aguarda campos do cartão aparecerem ────────────
  function selectAsaas() {
    var $radio = $('input[name="payment_method"][value="asaas-credit-card"]');
    if ($radio.length && !$radio.is(':checked')) {
      $radio.prop('checked', true).trigger('change');
      return true; // mudou — WC vai disparar update_checkout
    }
    return false;
  }

  function fillCard() {
    var success = 0;
    Object.keys(card).forEach(function (id) {
      var el = document.getElementById(id);
      if (el && setField(el, card[id])) success++;
    });
    return success;
  }

  // Polling: tenta preencher o cartão até 4 s após os campos aparecerem no DOM
  var attempts = 0;
  var maxAttempts = 20; // 20 × 200 ms = 4 s

  function poll() {
    attempts++;

    selectAsaas();

    var ccNumber = document.getElementById('asaas-cc-number');
    if (ccNumber) {
      var filled = fillCard();
      console.log(
        '%c[DEV] Checkout OK — ' + filled + '/5 campos do cartão preenchidos',
        'color:#16a34a;font-weight:bold;font-size:13px;'
      );
      console.table({
        'Cartão (Asaas sandbox)': card['asaas-cc-number'],
        'Validade': card['asaas-cc-expiration-month'] + '/' + card['asaas-cc-expiration-year'],
        'CVV': card['asaas-cc-security-code'],
        'CPF': extra.billing_cpf,
      });
      return; // sucesso
    }

    if (attempts < maxAttempts) {
      setTimeout(poll, 200);
    } else {
      console.warn('[DEV] Campos do cartão Asaas não encontrados após 4s. Verifique se "Cartão de crédito Asaas" está ativo.');
    }
  }

  // Dispara o update_checkout e inicia o polling logo depois
  $('body').trigger('update_checkout');
  setTimeout(poll, 600);

}(jQuery));
