# Project Guidelines

## Main Theme

The primary theme is **store front child** (`wp-content/themes/storefront-child`).
All frontend customizations go in this theme.

## Git Commit Standard

- All commit messages must be in English.
- Maximum 12 words per commit message.
- Always include a task prefix.

| Prefix | When to use |
|--------|-------------|
| `feature:` | New functionality |
| `fix:` | Bug fix |
| `chore:` | Maintenance, configs, scripts |
| `docs:` | Documentation only |
| `style:` | Visual/CSS changes |
| `refactor:` | Code restructuring without behavior change |

Examples:
- `feature: add product import script`
- `fix: correct .htaccess rewrite rules`
- `chore: update .gitignore rules`

## Code Documentation

- Do **not** write inline comments inside code files.
- When documentation is needed, create a dedicated file in `docs/`.
- Name the file after the subject: `docs/checkout-flow.md`, `docs/hooks.md`, etc.

## WordPress / WooCommerce

- Only custom themes and documentation files are versioned.
- Core WordPress files and plugins are ignored by `.gitignore`.
- Required plugins: WooCommerce, WooCommerce Stripe Gateway, WooCommerce Correios (see `plugins-instrucoes.txt`).
- To install plugins: use the WordPress admin panel or WP-CLI.
- To register demo products: run `cadastrar_produtos.sh`.

## Development

- Use the theme `wp-content/themes/storefront-child` for all customizations.
- For local development, ensure Apache `mod_rewrite` is enabled and permalinks are set to "Post name".
- The site language is `pt-BR` (set in `wp-config.php`).

## General

- Do not commit sensitive files (e.g., `wp-config.php` with real credentials).
- Always review changes before pushing.
- Keep this file updated with new standards as the project evolves.
