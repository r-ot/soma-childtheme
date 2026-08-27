# Twenty Twenty-Five Child

Child Theme für das SOMA WooCommerce B2B-Projekt auf Basis von Twenty Twenty-Five.

Aktuelle Version: `0.3.1`

## Architektur

Grundsätzliche Trennung:

- **WooCommerce / WordPress** = Produkt- und Taxonomie-Daten
- **rbf-site-core** = Datenlogik, Aggregationen, REST und funktionale Komponenten
- **rbf-xr-viewer** = XR-/360°-Viewer und XR-Asset-Auflösung
- **Child Theme** = Templates, Markup, CSS und Darstellung

Block-/FSE-Templates dienen hauptsächlich der Seitenstruktur.

Businesslogik und größere Datenabfragen gehören nicht ins Theme.

## Theme-Struktur

    twentytwentyfive-child/
    ├── functions.php
    ├── rbf_filters.php
    ├── rbf-debug.php
    ├── style.css
    ├── woo-style.css
    ├── theme.json
    ├── includes/
    │   ├── class-rbf-theme-shortcodes.php
    │   └── rot-core-helpers.php
    ├── template-parts/
    │   ├── hero.php
    │   └── product-family-item.php
    └── templates/
        ├── front-page.html
        ├── fullscreen-swiper-page.html
        └── taxonomy-product_family.html

`rbf-debug.php` ist ausschließlich für Development-/Reality-Checks vorgesehen
und wird nur bei aktivem `WP_DEBUG` geladen.

## Fonts

Die Schriftfamilie `Jost` wird lokal aus:

    assets/fonts/

geladen.

Variable Fonts werden über:

    assets/fonts/fonts.css

registriert.

## Hero-System

Die Hero-Ausgabe läuft zentral über:

    [rbf_hero]

Aktuelle Cases:

    frontpage
    product-family

Renderer:

    includes/class-rbf-theme-shortcodes.php
        ↓
    template-parts/hero.php

Das Hero-Template bleibt möglichst generisch und erhält vorbereitete Daten vom
jeweiligen Controller.

## Product-Family Landingpage

Die Landingpage verwendet:

    [rbf_product_families]
        ↓
    rbf-site-core REST
        ↓
    Plugin-JavaScript
        ↓
    natives HTML <template>
        ↓
    Childtheme-Override

Das Theme-Template liegt unter:

    template-parts/product-family-item.php

Die Zuordnung erfolgt über `rbf_filters.php`.

Funktionale JavaScript-Hooks verwenden `data-*`-Attribute.
CSS-Klassen bleiben Teil der Präsentationsschicht.

## Product-Family Archive

Eigenes FSE-Template:

    templates/taxonomy-product_family.html

Grundstruktur:

    Header
        ↓
    Product-Family Hero
        ↓
    Product Grid
        ↓
    Footer

Der normale Twenty-Twenty-Five-/WooCommerce-Fallback-Loop wird hier nicht
verwendet.

### Hero

Der Product-Family-Hero enthält aktuell:

- Term-Name
- Term-Beschreibung
- asynchron geladene Reifendimensionen
- zunächst drei sichtbare Dimensionen
- Expand/Collapse für weitere Dimensionen
- optionalen XR-/360°-Viewer

Die Datenlogik für Reifendimensionen liegt in `rbf-site-core`.

### XR Viewer

Ist für eine Product Family ein XR-Datensatz vorhanden, liefert
`rbf-xr-viewer` die Manifest-URL.

Das Theme ist ausschließlich verantwortlich für:

- Platzierung in der Hero-Stage
- Größe
- Schatten
- visuelle Integration
- späteren Fallback auf ein normales Produktbild

Viewer-JavaScript und XR-Asset-Verwaltung gehören nicht ins Theme.

## Product Cards

Als nächster größerer Archive-Baustein folgt ein wiederverwendbares Product Grid
mit eigenen Product Cards.

Die Card-Daten kommen aus WooCommerce bzw. `rbf-site-core`.

Die Darstellung bleibt im Childtheme.

## CSS

Projektbezogene WooCommerce- und Frontend-Styles liegen hauptsächlich in:

    woo-style.css

Neue responsive Komponenten werden mobile-first aufgebaut:

    Basis = Mobile
    @media (min-width: ...)
        → größere Viewports

Bestehende ältere Styles können davon abweichen und werden nur bei Bedarf
refaktoriert.

## Roadmap

- [x] Product-Family Landingpage
- [x] eigenes Product-Family Archive
- [x] Product-Family Hero
- [x] asynchrone Reifendimensionen
- [x] Expand/Collapse der Dimensionen
- [x] XR-/360°-Viewer im Hero
- [x] lokale Jost-Schrift
- [ ] Product Grid
- [ ] wiederverwendbare Product Cards
- [ ] Product Single
- [ ] interaktive Dimensionsfilter
- [ ] Fallback-Medium bei fehlendem XR

## Abhängigkeiten

Das Theme arbeitet aktuell insbesondere mit:

- WooCommerce
- `rbf-site-core`
- `rbf-xr-viewer`

Die Plugins besitzen die funktionale bzw. datenbezogene Logik.
Das Theme übernimmt deren visuelle Darstellung.