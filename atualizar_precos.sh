#!/bin/bash
# Script para atualizar todos os preços dos produtos para valores em Real (R$)

WP="php wp-cli.phar"

# Lista de IDs dos produtos
IDS=$($WP post list --post_type=product --format=ids)

# Valor base para produtos
BASE=5

for ID in $IDS
  do
    # Gera um valor aleatório entre 5 e 100
    PRECO=$(shuf -i 5-100 -n 1)
    # Atualiza o preço do produto
    $WP post meta update $ID _price "$PRECO"
    $WP post meta update $ID _regular_price "$PRECO"
  done

echo "Todos os preços dos produtos foram atualizados para valores em Real (R$)."
