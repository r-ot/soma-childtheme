# Changelog


## 0.1.0


- Child-Theme-Basis auf Twenty Twenty-Five
- Frontpage-Grundstruktur
- Hero-Shortcode integriert
- Product-Family-Template-Override ergänzt
- `rbf_filters.php` ergänzt
- Grundlage für dynamische Product-Family-Cards geschaffen

## 0.1.1

-styling product family items


## 0.2.0

- Product-Family-Archive ergänzt
- Product-Family-Datenlogik zentralisiert
- `tire_dimension` und `chain_strength` als interne Aliases eingeführt
- persistenter Family-Index mit Learn/Delete ergänzt
- REST auf gelernte Family-Daten umgestellt
- Auto-Learn für ungelernte Product Families ergänzt


## 0.3.0

- Product-Family-Hero mit asynchronen Reifendimensionen finalisiert
- XR-/360°-Viewer als optionales Product-Family-Medium integriert
- XR-Darstellung und Schatten für die Hero-Stage ergänzt
- lokale Schriftfamilie Jost eingebunden
- XR-Funktionalität vollständig in `rbf-xr-viewer` ausgelagert


## 0.3.1

- Product-Family-Cards um Bilddarstellung ergänzt
- XR-Startframe als Family-Card-Fallback integriert
- Placeholder bleibt erhalten, wenn kein Family-Bild verfügbar ist

## 0.3.2

-prepared  templating for quotes pdf

## 0.3.3

PDF
- improved pdf template for quotes pdf


STYLE / header
- Added reusable `compact` hero variant
- Added automatic compact hero handling for WooCommerce Cart and Checkout
- Added featured-image fallback for singular hero contexts
- Added reusable hero breadcrumb context/helper
- Suppressed duplicate WooCommerce Cart/Checkout post titles via `rbf_filters.php`
- Added compact hero styling and diagonal media treatment
- Documented WooCommerce block-template integration


## 0.3.4

- pached pdf/quote.php

## 0.3.5

- Added reusable compact hero variant for WooCommerce Cart and Checkout
- Added automatic Cart / Checkout hero context detection
- Added singular featured-image fallback for compact hero media
- Added full-width compact hero integration without overriding WooCommerce block templates
- Added reusable hero eyebrow / breadcrumb rendering with context-based breadcrumb helper
- Added diagonal breadcrumb marker based on the existing hero cut geometry
- Suppressed duplicate WooCommerce Cart / Checkout `core/post-title` output via `rbf_filters.php`
- Added server-rendered WooCommerce cart item counter to the compact hero
- Added live cart-counter synchronization through the WooCommerce Blocks cart data store
- Added `assets/js/rbf-cart-header.js`
- Refined compact hero diagonal media, overlay and background styling

- site branding added (logo, upload in admin, b2b_haendler-logic)