# Twenty Twenty-Five Child

Child Theme für das SOMA WooCommerce B2B-Projekt auf Basis von Twenty Twenty-Five.

Aktuelle Version: `0.3.5`

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
	├── assets/
	│   ├── bootstrap/
	│   ├── imgs/
	│   ├── fonts/
	│   ├── logos/
	│   └── js/
	│       └── rbf-cart-header.js
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

Variable Fonts für das normale Frontend werden über:

    assets/fonts/fonts.css

registriert.

Für PDF-Dokumente werden zusätzlich statische TTF-Schnitte verwendet:

    assets/fonts/static/Jost-Regular.ttf
    assets/fonts/static/Jost-Bold.ttf

Diese werden vom Plugin `rbf-shop-documents` über Dompdf registriert und
in den PDF-Templates verwendet.


## Header / Site Branding

- Twenty Twenty-Five `core/site-title` wird dynamisch über `render_block_core/site-title` gefiltert (rbf_filters.php).
- Kein Header-Template-Override notwendig.
- Der Header verwendet nun den zentralen Branding-Contract aus `rbf-site-core`.
- Logo-Auflösung:
  - eingeloggter B2B-User mit Händlerlogo → Händlerlogo
  - B2B-User ohne Händlerlogo → SOMA-Standardlogo
  - Gast / normaler Admin → SOMA-Standardlogo
  - kein globales oder individuelles Logo → originaler Twenty Twenty-Five Site Title
- Das tatsächlich gerenderte Logo-Markup kommt vollständig aus `RbfSiteCoreB2BBranding`.
- Styling und Größensteuerung bleiben bewusst auf Theme-Ebene.




## Hero-System

Die Hero-Ausgabe läuft zentral über:

    [rbf_hero]

Aktuelle Cases:

    frontpage
    product-family
    compact

Renderer:

    includes/class-rbf-theme-shortcodes.php
        ↓
    template-parts/hero.php

Das Hero-Template bleibt möglichst generisch und erhält vorbereitete Daten vom
jeweiligen Controller.

### Compact Hero

Der Case `compact` stellt eine reduzierte Variante des bestehenden Hero-Systems
für System- und Content-Seiten bereit.

Aktuell wird er für WooCommerce Cart und Checkout verwendet.

Der Compact Hero verwendet weiterhin dieselbe grundlegende Hero-Architektur:

- Full-Width-Ausgabe über `alignfull`
- Featured Image der aktuellen Seite als Hero-Medium
- bestehende diagonale Cut-/Overlay-Geometrie
- Riffelblech-Hintergrund
- eigene kompaktere Höhen- und Layoutparameter
- Seitentitel als H1 im Hero

Cart und Checkout werden automatisch erkannt und benötigen keinen eigenen
Shortcode-Case im WooCommerce-Template.

Die WooCommerce-eigenen Block-Templates bleiben dabei unangetastet.

### Hero Eyebrow / Breadcrumbs

Der Hero-Parameter:

    eyebrow

unterstützt neben normalem Text auch den reservierten Wert:

    crumbs

Beispiel:

    'eyebrow' => 'crumbs'

In diesem Fall erzeugt der Hero-Controller kontextbezogene Breadcrumb-Daten und
übergibt sie als strukturiertes Array an das Hero-Template.

Die Breadcrumb-Ermittlung ist in:

    Rbf_Theme_Shortcodes::get_hero_breadcrumbs()

gekapselt.

Der Helper erhält den vollständigen Hero-Context statt ausschließlich einer
Post-ID. Dadurch kann die Breadcrumb-Logik später auch für kontextabhängige
Cases wie Product Family Archives und Product Singles erweitert werden.

Die visuelle Breadcrumb-Ausgabe enthält zusätzlich einen diagonalen Marker,
dessen Neigung auf derselben Cut-Geometrie wie der Hero basiert.




## WooCommerce Cart / Checkout

WooCommerce behält seine eigenen Block-Templates für Cart und Checkout.

Das Child Theme überschreibt diese Templates bewusst nicht.

Stattdessen ergänzt das Theme die benötigte Präsentationslogik über bestehende
WordPress-/WooCommerce-Schnittstellen.

### Post Title

WooCommerce rendert in seinen Cart-/Checkout-Templates einen eigenen
`core/post-title`.

Da der Compact Hero den Seitentitel bereits als H1 ausgibt, wird dieser
zusätzliche Titel in:

    rbf_filters.php

über den dynamischen Block-Filter:

    render_block_core/post-title

für Cart und Checkout unterdrückt.

Damit bleibt genau eine H1 im Dokument, ohne WooCommerce-eigene Templates zu
kopieren oder zu überschreiben.

### Cart Counter im Hero

Der Compact Hero kann die aktuelle Anzahl der Produkte im Warenkorb anzeigen:

    2 Produkte
    im Warenkorb

Beim initialen Seitenrender kommt die Anzahl serverseitig aus dem aktuellen
WooCommerce Cart.

Da der WooCommerce Cart Block Mengenänderungen und das Entfernen von Produkten
asynchron verarbeitet, wird der Counter anschließend clientseitig mit dem
WooCommerce Cart Data Store synchronisiert.

Theme-JavaScript:

    assets/js/rbf-cart-header.js

Flow:

    PHP Initial Render
        ↓
    WooCommerce Cart Store
        ↓
    Mengenänderung / Remove
        ↓
    itemsCount aktualisiert
        ↓
    Hero Counter aktualisiert sich ohne Reload

Die funktionalen DOM-Hooks verwenden `data-rbf-*`-Attribute.

Es werden keine eigenen AJAX-Requests und kein DOM-Polling benötigt.







## Theme-Filter / WooCommerce-Integration

Theme-seitige Render- und Integrationsfilter liegen zentral in:

    rbf_filters.php

Für Cart und Checkout rendert WooCommerce über seine Block-Templates zusätzlich
einen `core/post-title` vor dem eigentlichen Seiteninhalt.

Da der RBF Compact Hero den Seitentitel bereits als H1 ausgibt, wird dieser
zusätzliche Post-Title gezielt über den dynamischen WordPress-Blockfilter
unterdrückt:

    render_block_core/post-title

Der Filter greift ausschließlich bei:

    is_cart()
    is_checkout()

Damit bleibt die Zuständigkeit klar getrennt:

    WooCommerce
        → besitzt weiterhin Cart-/Checkout-Block-Templates

    Child Theme
        → ergänzt den RBF Compact Hero
        → verhindert dort einen doppelten Seitentitel / eine doppelte H1

Es werden bewusst keine eigenen `page-cart.html`- oder
`page-checkout.html`-Overrides benötigt.



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


## Shop Documents / Angebots-PDF

PDF- und Dokumentenlogik liegt im separaten Plugin:

    rbf-shop-documents

Das Plugin übernimmt:

- PDF-Erzeugung mit Dompdf
- Dokumenten-/Angebotslogik
- Datenaufbereitung
- ein eigenes Fallback-PDF-Template

Das Child Theme kann die visuelle PDF-Darstellung überschreiben.

Aktueller Theme-Override:

    rbf-shop-documents/pdf/quote.php

Template-Auflösung:

    Child Theme Override
        ↓
    Plugin Fallback
        ↓
    Dompdf

Damit bleibt die Trennung bestehen:

- `rbf-shop-documents` = PDF- und Dokumentenlogik
- Child Theme = PDF-Markup, Typografie und visuelle Gestaltung

Der aktuelle Stand ist ein Proof of Concept für Angebots-PDFs.

Geplant sind später unter anderem:

- Warenkorb als Angebotsgrundlage
- Wiederverkäufer-spezifisches Logo und Absenderdaten
- individuelle Akzent- und Buttonfarben
- Angebotsnummern
- Angebots-Snapshots
- Gültigkeitszeiträume
- Mehrseiten- und Sonderfallbehandlung










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
- `rbf-shop-documents`

Die Plugins besitzen die funktionale bzw. datenbezogene Logik.
Das Theme übernimmt deren visuelle Darstellung.