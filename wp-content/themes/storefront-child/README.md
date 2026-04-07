# Storefront Child Eletronicos

WordPress child theme built on [Storefront](https://woocommerce.com/storefront/), customized for an electronics e-commerce store.

- **Author:** Dayvson Marques
- **Version:** 1.0.0
- **Parent theme:** Storefront
- **Stack:** WordPress + WooCommerce, Bootstrap 5.3, Dart Sass, Vanilla JS

---

## Requirements

- WordPress 6.x
- WooCommerce 8.x
- Storefront parent theme installed and active
- Node.js (for SCSS compilation)

---

## Setup

Install Node dependencies and compile SCSS:

```bash
cd wp-content/themes/storefront-child
npm install
npm run build
```

To watch for changes during development:

```bash
npm run watch
```

> `assets/css/main.css` is the compiled output — never edit it directly. It is gitignored.

---

## File structure

```
storefront-child/
├── assets/
│   ├── css/          # Compiled output (gitignored)
│   ├── img/          # Theme images
│   ├── js/
│   │   └── main.js   # Sticky header + promo slider
│   └── scss/
│       ├── main.scss              # Entry point
│       ├── abstracts/             # Variables, mixins
│       ├── base/                  # Reset, typography, buttons
│       ├── layout/                # Header, footer
│       ├── components/            # Cards, buttons
│       └── pages/                 # _home.scss
├── docs/
│   ├── storefront-structure.md    # HTML skeleton and enqueue rules
│   └── scss-architecture.md       # SCSS conventions and build setup
├── functions.php
├── header.php
├── footer.php
├── homepage.php                   # Template: Home Custom Eletronicos
├── front-page.php
├── inc-banner-cpt.php
├── package.json
└── style.css                      # Theme declaration
```

---

## Homepage template

The `homepage.php` template (`Home Custom Eletronicos`) renders three sections:

| Section | Source |
|---|---|
| Categories | Top 6 WooCommerce categories by product count |
| Promotions | Up to 10 products with an active sale price, infinite draggable slider |
| Featured products | 8 most recent products in a Bootstrap grid |

---

## JavaScript

`assets/js/main.js` has two features, both in vanilla JS (no jQuery):

- **Sticky header** — uses `IntersectionObserver` on `#after-banner-sentinel` (injected after the banner in `header.php`) to toggle `.is-sticky` on `#masthead`
- **Infinite promo slider** — clones `#promo-track` children, loops via `requestAnimationFrame`, supports mouse drag and touch swipe

---

## SCSS variables (abstracts/_variables.scss)

| Token | Value |
|---|---|
| `$color-primary` | `#0d6efd` |
| `$color-danger` | `#dc3545` |
| `$font-family-base` | Segoe UI / Verdana / sans-serif |
| `$bp-md` | `768px` |
| `$bp-lg` | `992px` |

Full variable list in [assets/scss/abstracts/_variables.scss](assets/scss/abstracts/_variables.scss).

---

## Docs

- [Storefront structure and enqueue rules](docs/storefront-structure.md)
- [SCSS architecture and build setup](docs/scss-architecture.md)
