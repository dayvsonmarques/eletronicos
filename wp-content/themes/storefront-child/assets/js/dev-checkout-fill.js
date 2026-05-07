/**
 * DEV ONLY — Checkout test data filler (Asaas)
 *
 * Paste in the browser console on /finalizar-compra/ to auto-fill all
 * checkout fields with fake test data, including Asaas credit card fields.
 *
 * Asaas sandbox test card: 5162306219378829 (Mastercard — always approved)
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

  // CPF fictício válido e campos brasileiros extras
  var extra = {
    billing_persontype: '1',              // 1 = Pessoa Física
    billing_cpf:        '529.982.247-25',
    billing_birthdate:  '01/01/1990',
  };

  // Cartão de teste Asaas sandbox (Mastercard — sempre aprovado)
  var card = {
    name:   'DAYVSON MARQUES',
    number: '5162306219378829', // sem espaços — IMask formata
    month:  '12',
    year:   '2030',
    cvv:    '318',
  };

  // ── Fase 1: preenche billing ─────────────────────────────────────────────────
  Object.keys(billing).forEach(function (id) {
    var $el = $('#' + id);
    if (!$el.length) return;
    // Não dispara 'input' no CEP para não acionar o autocomplete de endereço
    $el.val(billing[id]).trigger(id === 'billing_postcode' ? 'change' : 'input change');
  });

  // Campos extras brasileiros
  $('#billing_persontype').val(extra.billing_persontype).trigger('change');
  $('#billing_cpf').val(extra.billing_cpf).trigger('input change');
  $('#billing_birthdate').val(extra.billing_birthdate).trigger('input change');

  // Seleciona cartão de crédito Asaas
  var $asaas = $('input[name="payment_method"][value="asaas-credit-card"]');
  if ($asaas.length) {
    $asaas.prop('checked', true).trigger('change');
  }

  // ── Fase 2: preenche cartão após WC atualizar o checkout ────────────────────
  function fillCard() {
    var fields = [
      { id: 'asaas-cc-name',             val: card.name   },
      { id: 'asaas-cc-number',           val: card.number },
      { id: 'asaas-cc-expiration-month', val: card.month  },
      { id: 'asaas-cc-expiration-year',  val: card.year   },
      { id: 'asaas-cc-security-code',    val: card.cvv    },
    ];

    var found = 0;
    fields.forEach(function (f) {
      var el = document.getElementById(f.id);
      if (!el) return;
      found++;
      el.value = f.val;
      // Dispara 'input' para que IMask processe e formate o valor
      el.dispatchEvent(new Event('input',  { bubbles: true }));
      el.dispatchEvent(new Event('change', { bubbles: true }));
    });

    return found;
  }

  // Aguarda updated_checkout (WC dispara após renderizar campos de pagamento)
  $(document).one('updated_checkout', function () {
    // Re-seleciona o método (WC pode ter resetado após o update)
    if ($asaas.length) {
      $('input[name="payment_method"][value="asaas-credit-card"]')
        .prop('checked', true).trigger('change');
    }

    var filled = fillCard();

    // Se os campos de cartão ainda não estavam no DOM, tenta após 500 ms
    if (!filled) {
      setTimeout(fillCard, 500);
    }

    console.log(
      '%c[DEV] Checkout preenchido com dados fictícios — Asaas sandbox',
      'color:#16a34a;font-weight:bold;font-size:13px;'
    );
    console.table({
      'Cartão':   card.number,
      'Validade': card.month + '/' + card.year,
      'CVV':      card.cvv,
      'CPF':      extra.billing_cpf,
    });
  });

  // Dispara update_checkout para renderizar campos do gateway selecionado
  $('body').trigger('update_checkout');

}(jQuery));
