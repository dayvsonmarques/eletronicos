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
    billing_address_1:  'Avenida Paulista, 1578',
    billing_address_2:  '10º Andar',
    billing_postcode:   '01310-200',
    billing_city:       'São Paulo',
    billing_state:      'SP',
    billing_phone:      '(11) 98765-4321',
    billing_email:      'dayvson.marques@gmail.com',
  };

  var extra = {
    billing_persontype: '1',
    billing_cpf:        '529.982.247-25',
    billing_birthdate:  '01/01/1990',
  };

  // Cartão Asaas sandbox — Mastercard sempre aprovado
  var cardFields = {
    'asaas-cc-name':             'DAYVSON MARQUES',
    'asaas-cc-number':           '5162 3062 1937 8829', // pré-formatado para o IMask
    'asaas-cc-expiration-month': '12',
    'asaas-cc-expiration-year':  '2030',
    'asaas-cc-security-code':    '318',
  };

  // ── Helpers ──────────────────────────────────────────────────────────────────

  // Preenche um input contornando o value setter do IMask v7
  var nativeSetter = Object.getOwnPropertyDescriptor(HTMLInputElement.prototype, 'value').set;

  function setInput(el, value) {
    if (!el) return false;
    nativeSetter.call(el, value);
    // InputEvent com insertFromPaste é o inputType que IMask aceita para entrada programática
    el.dispatchEvent(new InputEvent('input',  { bubbles: true, inputType: 'insertFromPaste', data: value }));
    el.dispatchEvent(new Event('change', { bubbles: true }));
    return true;
  }

  // ── Fase 1: preenche billing ─────────────────────────────────────────────────
  Object.keys(billing).forEach(function (id) {
    var el = document.getElementById(id);
    if (!el) return;
    if (id === 'billing_postcode') {
      // Apenas 'change' — evita disparar o autocomplete de CEP
      el.value = billing[id];
      el.dispatchEvent(new Event('change', { bubbles: true }));
    } else {
      setInput(el, billing[id]);
    }
  });

  setInput(document.getElementById('billing_persontype'), extra.billing_persontype);
  setInput(document.getElementById('billing_cpf'),        extra.billing_cpf);
  setInput(document.getElementById('billing_birthdate'),  extra.billing_birthdate);

  // ── Fase 2: seleciona Asaas ANTES de triggerar update_checkout ───────────────
  // Crítico: o AJAX do WC serializa o form no momento do trigger.
  // Se Asaas não estiver selecionado, o AJAX retorna sem os campos do cartão.
  var $asaasRadio = $('input[name="payment_method"][value="asaas-credit-card"]');
  if ($asaasRadio.length) {
    $asaasRadio.prop('checked', true).trigger('change');
  }

  // ── Fase 3: preenche os campos do cartão ─────────────────────────────────────
  function fillCard() {
    var filled = 0;
    Object.keys(cardFields).forEach(function (id) {
      var el = document.getElementById(id);
      if (!el) return;
      // Tenta via nativeSetter + InputEvent primeiro
      if (!setInput(el, cardFields[id])) return;
      // Fallback: força o valor direto caso IMask tenha resetado
      if (el.value === '') el.value = cardFields[id];
      filled++;
    });
    return filled;
  }

  // ── Fase 4: MutationObserver — re-preenche se o WC re-renderizar ─────────────
  // O WC substitui o HTML de #payment após cada update_checkout AJAX.
  // O observer detecta a troca e re-preenche automaticamente.
  var cardObserver = null;

  function watchPaymentSection() {
    if (cardObserver) return;
    var paymentDiv = document.getElementById('payment');
    if (!paymentDiv) return;

    cardObserver = new MutationObserver(function (mutations) {
      var hasRelevantChange = mutations.some(function (m) {
        return m.type === 'childList' && m.addedNodes.length > 0;
      });
      if (!hasRelevantChange) return;

      // Aguarda o IMask inicializar nos novos inputs antes de preencher
      setTimeout(function () {
        // Re-seleciona Asaas caso o WC tenha alternado o método
        var $r = $('input[name="payment_method"][value="asaas-credit-card"]');
        if ($r.length && !$r.is(':checked')) {
          $r.prop('checked', true).trigger('change');
        }
        var n = fillCard();
        if (n > 0) {
          console.log('%c[DEV] Re-preencheu ' + n + '/5 campos do cartão após re-render', 'color:#0ea5e9');
        }
      }, 150);
    });

    cardObserver.observe(paymentDiv, { childList: true, subtree: true });
  }

  // ── Fase 5: polling até os campos do cartão aparecerem no DOM ────────────────
  var attempts = 0;

  function poll() {
    attempts++;

    var ccEl = document.getElementById('asaas-cc-number');
    if (ccEl) {
      // Aguarda 150 ms para o IMask inicializar antes de preencher
      setTimeout(function () {
        var n = fillCard();
        watchPaymentSection();
        console.log(
          '%c[DEV] Checkout preenchido — ' + n + '/5 campos do cartão OK',
          'color:#16a34a;font-weight:bold;font-size:13px;'
        );
        console.table({
          'Número':   cardFields['asaas-cc-number'],
          'Mês/Ano':  cardFields['asaas-cc-expiration-month'] + '/' + cardFields['asaas-cc-expiration-year'],
          'CVV':      cardFields['asaas-cc-security-code'],
          'CPF':      extra.billing_cpf,
        });
      }, 150);
      return;
    }

    if (attempts <= 30) { // até 6 s (30 × 200 ms)
      setTimeout(poll, 200);
    } else {
      console.warn('[DEV] #asaas-cc-number não encontrado após 6 s. O método Asaas está ativo no sandbox?');
    }
  }

  // Dispara update_checkout com Asaas já selecionado
  $('body').trigger('update_checkout');
  setTimeout(poll, 500);

}(jQuery));
