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

  // ── Dados ────────────────────────────────────────────────────────────────────
  var billing = {
    billing_first_name: 'Dayvson',
    billing_last_name:  'Marques',
    billing_country:    'BR',
    billing_address_1:  'Rua do Bom Jesus',
    billing_number:     '197',
    billing_address_2:  'Apto 12',
    billing_postcode:   '50030-170',
    billing_city:       'Recife',
    billing_state:      'PE',
    billing_phone:      '(81) 98765-4321',
    billing_email:      'dayvson.marques@gmail.com',
  };

  var extra = {
    billing_persontype: '1',
    billing_cpf:        '529.982.247-25',
    billing_birthdate:  '01/01/1990',
  };

  // Asaas sandbox — cartão oficial aprovado (docs.asaas.com/docs/testing-credit-card-payment)
  var rawCard = {
    asaas_cc_name:             'DAYVSON MARQUES',
    asaas_cc_number:           '4444444444444444',
    asaas_cc_expiration_month: '12',
    asaas_cc_expiration_year:  '2030',
    asaas_cc_security_code:    '123',
  };

  // ── Helpers ──────────────────────────────────────────────────────────────────

  // Preenche campo sem máscara (billing)
  function setBillingField(el, value) {
    if (!el) return;
    el.value = value;
    el.dispatchEvent(new Event('input',  { bubbles: true }));
    el.dispatchEvent(new Event('change', { bubbles: true }));
  }

  // Preenche campo com IMask:
  //   1. Foca o elemento (IMask verifica activeElement internamente)
  //   2. Seleciona todo o conteúdo existente
  //   3. execCommand('insertText') gera evento input com isTrusted=true
  //      que o IMask processa exatamente como digitação do usuário
  function setIMaskField(el, value) {
    if (!el) return false;
    el.focus();
    el.select();
    var ok = document.execCommand('insertText', false, value);
    if (!ok) {
      // Fallback para navegadores que bloqueiam execCommand
      var nativeSetter = Object.getOwnPropertyDescriptor(HTMLInputElement.prototype, 'value').set;
      nativeSetter.call(el, value);
      el.dispatchEvent(new InputEvent('input', { bubbles: true, inputType: 'insertFromPaste' }));
    }
    return true;
  }

  // ── Fase 1: billing ──────────────────────────────────────────────────────────
  Object.keys(billing).forEach(function (id) {
    var el = document.getElementById(id);
    if (!el) return;
    if (id === 'billing_postcode') {
      el.value = billing[id];
      el.dispatchEvent(new Event('change', { bubbles: true }));
    } else {
      setBillingField(el, billing[id]);
    }
  });

  setBillingField(document.getElementById('billing_persontype'), extra.billing_persontype);
  setBillingField(document.getElementById('billing_cpf'),        extra.billing_cpf);
  setBillingField(document.getElementById('billing_birthdate'),  extra.billing_birthdate);

  // ── Fase 2: seleciona Asaas ANTES do update_checkout ────────────────────────
  // O WC serializa o form no momento do trigger — Asaas deve estar selecionado
  // para o AJAX retornar os campos do cartão.
  $('input[name="payment_method"][value="asaas-credit-card"]')
    .prop('checked', true).trigger('change');

  // ── Fase 3: intercepta submissão AJAX — garantia definitiva ─────────────────
  // Independente do estado visual dos inputs, injeta os valores corretos
  // diretamente no payload enviado ao servidor.
  $(document).on('ajaxSend.devFill', function (_event, _jqxhr, settings) {
    if (!settings.url || settings.url.indexOf('wc-ajax=checkout') === -1) return;
    if (!settings.data) return;
    var extra = Object.keys(rawCard).map(function (key) {
      return encodeURIComponent(key) + '=' + encodeURIComponent(rawCard[key]);
    }).join('&');
    settings.data += '&' + extra;
  });

  // ── Fase 4: preenche inputs visualmente ─────────────────────────────────────
  // Mapeamento: id HTML → chave em rawCard
  var cardIdMap = {
    'asaas-cc-name':             'asaas_cc_name',
    'asaas-cc-number':           'asaas_cc_number',
    'asaas-cc-expiration-month': 'asaas_cc_expiration_month',
    'asaas-cc-expiration-year':  'asaas_cc_expiration_year',
    'asaas-cc-security-code':    'asaas_cc_security_code',
  };

  function fillCardVisual() {
    var filled = 0;
    Object.keys(cardIdMap).forEach(function (id) {
      var el  = document.getElementById(id);
      var val = rawCard[cardIdMap[id]];
      if (!el || !val) return;
      // Nome não tem máscara — set direto
      if (id === 'asaas-cc-name') {
        el.value = val;
      } else {
        setIMaskField(el, val);
      }
      filled++;
    });
    return filled;
  }

  // MutationObserver: re-preenche se WC re-renderizar o bloco de pagamento
  function watchPayment() {
    var paymentDiv = document.getElementById('payment');
    if (!paymentDiv || paymentDiv._devObserver) return;
    paymentDiv._devObserver = true;

    new MutationObserver(function (mutations) {
      var added = mutations.some(function (m) { return m.addedNodes.length > 0; });
      if (!added) return;
      setTimeout(function () {
        $('input[name="payment_method"][value="asaas-credit-card"]')
          .prop('checked', true).trigger('change');
        fillCardVisual();
      }, 200);
    }).observe(paymentDiv, { childList: true, subtree: true });
  }

  // Polling: aguarda os campos do cartão estarem no DOM
  var tries = 0;
  function poll() {
    tries++;
    if (document.getElementById('asaas-cc-number')) {
      setTimeout(function () {
        var n = fillCardVisual();
        watchPayment();
        console.log(
          '%c[DEV] Checkout OK — ' + n + '/5 campos do cartão preenchidos visualmente\n' +
          '       Interceptor ajaxSend ativo — valores corretos garantidos na submissão.',
          'color:#16a34a;font-weight:bold;font-size:12px;'
        );
      }, 200);
      return;
    }
    if (tries <= 30) setTimeout(poll, 200);
    else console.warn('[DEV] #asaas-cc-number não encontrado. Asaas ativo no sandbox?');
  }

  $('body').trigger('update_checkout');
  setTimeout(poll, 500);

}(jQuery));
