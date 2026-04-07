#!/bin/bash
# Script para atualizar categorias dos produtos WooCommerce (exemplo: pai > filho)
# Exemplo: Eletrônicos > Resistores, Eletrônicos > Capacitores, etc.

WP="php wp-cli.phar"


# Cria categoria pai e obtém o ID
ELETRONICOS_ID=$($WP term create product_cat "Eletrônicos" --porcelain)

# Cria categorias filhas usando o ID do pai
RESISTORES_ID=$($WP term create product_cat "Resistores" --parent=$ELETRONICOS_ID --porcelain)
CAPACITORES_ID=$($WP term create product_cat "Capacitores" --parent=$ELETRONICOS_ID --porcelain)
TRANSISTORES_ID=$($WP term create product_cat "Transistores" --parent=$ELETRONICOS_ID --porcelain)
FERRAMENTAS_ID=$($WP term create product_cat "Ferramentas" --parent=$ELETRONICOS_ID --porcelain)

# Se já existirem, pega os IDs
if [ -z "$RESISTORES_ID" ]; then RESISTORES_ID=$($WP term list product_cat --name="Resistores" --field=term_id); fi
if [ -z "$CAPACITORES_ID" ]; then CAPACITORES_ID=$($WP term list product_cat --name="Capacitores" --field=term_id); fi
if [ -z "$TRANSISTORES_ID" ]; then TRANSISTORES_ID=$($WP term list product_cat --name="Transistores" --field=term_id); fi
if [ -z "$FERRAMENTAS_ID" ]; then FERRAMENTAS_ID=$($WP term list product_cat --name="Ferramentas" --field=term_id); fi


# Atualiza todos os produtos para exemplo (distribui entre as categorias filhas)
IDS=($($WP post list --post_type=product --format=ids))
COUNT=${#IDS[@]}
for i in "${!IDS[@]}"; do
  ID=${IDS[$i]}
  case $((i % 4)) in
    0) CAT=$RESISTORES_ID;;
    1) CAT=$CAPACITORES_ID;;
    2) CAT=$TRANSISTORES_ID;;
    3) CAT=$FERRAMENTAS_ID;;
  esac
  $WP post term set $ID product_cat $CAT
  echo "Produto $ID atualizado para categoria $CAT"
done
