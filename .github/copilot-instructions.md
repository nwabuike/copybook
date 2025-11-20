# Copilot Instructions for MagicBook Codebase

## Overview
This is a PHP-based web application for selling the "Sank Magic Copy Book" and related educational products. The codebase is structured as a classic monolithic site with minimal backend logic and a focus on static and dynamic content delivery.

## Architecture
- **Entry Points:**
  - `index.php`: Main landing and sales page, contains most product, testimonial, and offer content.
  - `customers.php`, `customers_lists.php`, `thankYou.php`: Customer management and post-order pages.
- **Backend Logic:**
  - All backend PHP scripts are in the `php/` directory (e.g., `db.php`, `order.php`, `mailer.php`).
  - No modern framework; direct PHP includes and procedural code are used.
- **Frontend:**
  - CSS in `css/` (with subfolders for plugins, icons, and fonts).
  - JavaScript in `js/` (with `custom.js` for site-specific logic and `plugins/` for third-party libraries).
  - Images in `images/`.

## Key Patterns & Conventions
- **No build step:** All files are served as-is. No asset pipeline or transpilation.
- **jQuery-centric JS:** All dynamic frontend behavior uses jQuery and plugins (see `js/custom.js`).
- **Bootstrap-based layout:** Uses Bootstrap CSS for grid and components, with custom overrides in `css/style.css`.
- **Direct HTML/PHP mixing:** Most pages are a mix of HTML and inline PHP for dynamic content (e.g., date, customer info).
- **No routing:** Each PHP file is a separate page; navigation is via direct links.
- **Minimal backend validation:** Most form handling and validation is done client-side or in simple PHP scripts.

## Developer Workflows
- **Local development:**
  - Place code in a local web server root (e.g., Laragon, XAMPP, WAMP).
  - Access via `http://localhost/magicbook/`.
- **No automated tests or CI/CD:** All testing is manual via browser.
- **Debugging:** Use browser dev tools and `error_log`/`var_dump` in PHP as needed.

## Integration Points
- **External JS/CSS:**
  - Relies on CDN and local copies of Bootstrap, FontAwesome, jQuery, and various plugins.
  - Twitter feed integration via `js/plugins/twitterFetcher_min.js` and custom callback in `js/custom.js`.
- **WhatsApp/Contact:**
  - Contact links use WhatsApp deep links for customer inquiries.

## Examples
- To add a new product section, edit `index.php` and add corresponding images to `images/`.
- To update styles, modify `css/style.css` or add new rules in `css/responsive.css`.
- To add JS behavior, use jQuery in `js/custom.js`.

## Cautions
- Avoid introducing frameworks or build tools; keep to the current flat-file, PHP/jQuery/Bootstrap stack.
- Maintain compatibility with PHP 5.x+ and legacy browsers.
- Do not remove inline PHP in HTML files; this is used for dynamic content (e.g., current year, customer data).

---
For further questions, review the main entry points (`index.php`, `php/` scripts, `js/custom.js`) and follow the established patterns.
