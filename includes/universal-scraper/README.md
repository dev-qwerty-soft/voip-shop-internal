# Universal Site Scraper

Modular scraper for any website content (products, articles, services) for WordPress/WooCommerce.

## Ì≥¶ What's Included

- `site-scraper.php` - Class for scraping content and categories
- `content-importer.php` - Class for importing to WooCommerce/WordPress
- `admin-page.php` - WordPress admin panel
- `admin.js` - JavaScript for admin panel
- `admin.css` - Styles for admin panel
- `init.php` - Initialization file for all modules

## Ì∫Ä How to Use

### Option 1: Current Theme

Already connected! Just go to WordPress Admin ‚Üí Content Scraper

### Option 2: Transfer to Another Theme

1. Copy `voipx3-parser` folder to `wp-content/themes/YOUR-THEME/includes/`

2. In your theme's `functions.php` add:
```php
require_once get_template_directory() . '/includes/voipx3-parser/init.php';
```

3. Done! Scraper page will appear in admin menu

## Ì≥ã Features

- ‚úÖ Scrape items from sitemap
- ‚úÖ Scrape categories
- ‚úÖ Automatic item-to-category matching
- ‚úÖ Import items to WooCommerce
- ‚úÖ Import categories to WooCommerce
- ‚úÖ Image downloading
- ‚úÖ Meta data saving
- ‚úÖ Debug mode for testing

## Ì¥ß Configuration

To change base URL edit in `site-scraper.php`:
```php
private $base_url = 'https://yoursite.com';
```

To add new categories edit `$category_keywords` array in `scrape_products_by_categories()` method.

## Ì≥ù Requirements

- WordPress 5.0+
- WooCommerce 3.0+
- PHP 7.0+
- DOMDocument PHP extension
- allow_url_fopen or cURL

## Ì∞õ Troubleshooting

If scraping doesn't work:
1. Check WooCommerce is activated
2. Check write permissions on theme folder
3. Check site can make external HTTP requests
4. Use "Debug HTML" button to check content

## Ì≥Ñ License

Free to use and modify.
