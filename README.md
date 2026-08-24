# Twenty Twenty-Five Child

RBF WooCommerce Child Theme auf Basis von Twenty Twenty-Five für das SOMA B2B-Schneeketten-Projekt.

## Fokus

* Custom Frontpage
* WooCommerce-Frontend
* Product-Family Landingpage
* Product-Family Taxonomy Archive
* wiederverwendbares Hero-System
* dynamische Product-Family-Komponenten
* Darstellung über native HTML-`<template>`-Elemente
* klare Trennung zwischen Datenlogik und Präsentation

## Architektur

Grundsatz:

* **WooCommerce / WordPress** = Source of Truth für Produkt- und Taxonomie-Daten
* **`rbf-site-core`** = Datenlogik, Aggregationen, REST, funktionale Komponenten
* **Child Theme** = Darstellung, Templates, CSS und projektspezifische Overrides

Das Theme soll keine eigene WooCommerce-Datenlogik implementieren, wenn diese sinnvoll im Core gekapselt werden kann.

Block-Theme-/FSE-Templates dienen primär der Verdrahtung.

Businesslogik und Queries laufen weiterhin über PHP-Controller, Shortcodes und generische Core-Klassen.

---

## Product-Family Landingpage

Die bestehende Landingpage-Komponente verwendet:

```text
[rbf_product_families]
        ↓
rbf-site-core REST
        ↓
JSON
        ↓
Plugin-JavaScript
        ↓
natives HTML <template>
        ↓
Childtheme-Override
```

Das Theme überschreibt das Default-Template des Plugins über:

```text
template-parts/product-family-item.php
```

Der Filter dafür liegt in:

```text
rbf_filters.php
```

Das Plugin-JavaScript arbeitet nur mit `data-*`-Hooks. CSS-Klassen und visuelles Markup bleiben damit vollständig im Theme.

---

## Product-Family Archive

Für die Taxonomie:

```text
product_family
```

existiert ein eigenes Block-Theme-Template:

```text
templates/taxonomy-product_family.html
```

Aktueller Aufbau:

```text
Header
↓
[rbf_hero case="product-family"]
↓
Main / Product-Grid-Bereich
↓
Footer
```

Damit wird der vorherige Twenty-Twenty-Five-/WordPress-Fallback-Archive-Flow vollständig ersetzt.

Das Archive rendert nicht mehr automatisch den normalen Post-/Product-Content-Loop.

### Hero

Das vorhandene Hero-System wird wiederverwendet:

```text
[rbf_hero case="product-family"]
        ↓
Rbf_Theme_Shortcodes
        ↓
template-parts/hero.php
```

Der Product-Family-Case verwendet aktuell:

* aktuellen Term-Namen
* Term-Beschreibung
* Product-Family-spezifische Daten

Es wird bewusst kein separates Product-Family-Hero-System gebaut.

Das Hero-Template bleibt generisch und bekommt fertige Daten vom Controller.

### Reifendimensionen

Die Reifendimensionen werden fachlich über den internen Alias:

```text
tire_dimension
```

angesprochen.

Der aktuell dahinterliegende WooCommerce-Testdaten-Key lautet:

```text
tire_dimension_fit
```

Dieser externe Key soll im Theme nicht verwendet werden.

Die persistente Aggregation und das Mapping gehören zu `rbf-site-core`.

Der Archive-Hero ist aktuell grundsätzlich verdrahtet. Die Darstellung der aggregierten Reifendimensionen wird als nächster Schritt auf einen asynchronen REST-Flow umgestellt, damit der initiale Seitenrender auch bei Product Families mit mehreren hundert Produkten nicht blockiert wird.

Geplante UX:

```text
erste 3 Dimensionen sichtbar
↓
…
↓
weitere Dimensionen auf Klick ein-/ausblenden
```

---

## Hero-System

Die Frontpage verwendet:

```text
[rbf_hero case="frontpage"]
```

Product-Family-Archive verwenden:

```text
[rbf_hero case="product-family"]
```

Das System ist bewusst für weitere Fälle vorbereitet, unter anderem spätere Product-Single-Seiten.

Kleine projektspezifische Anpassungen können über Filter in:

```text
rbf_filters.php
```

erfolgen.

Größere Hero-Cases bleiben im PHP-Controller.

---

## Aktuelle Theme-Struktur

```text
twentytwentyfive-child/
├── functions.php
├── rbf_filters.php
├── rbf-debug.php
├── README.md
├── CHANGELOG.md
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
```

`rbf-debug.php` dient ausschließlich kontrollierten Entwicklungs-/Reality-Checks und wird nur bei aktivem `WP_DEBUG` verwendet.

---

## Product Cards

Das Product-Family Archive soll künftig ein echtes Product Grid verwenden.

Es wird ausdrücklich nicht versucht, das normale WooCommerce-/Single-Product-Markup per CSS auf Card-Größe zu reduzieren.

Geplant ist eine wiederverwendbare Product Card mit ungefähr:

```text
Product Card
├── Bild
├── Name
├── Gliederstärke
├── Verfügbarkeit
├── relevante Reifendimensionen / Kurzinfos
├── Preis
└── Details / spätere B2B-Aktion
```

Die Präsentation gehört ins Theme.

Die Datenlogik bleibt im Core bzw. WooCommerce.

---

## Roadmap

### 1. Product-Family Landingpage

* [x] Product-Family Cards
* [x] REST + JSON
* [x] natives HTML-`<template>`
* [x] Plugin-JavaScript
* [x] Childtheme-Override
* [x] Gliederstärken
* [x] Self-Healing für noch nicht gelernte Family-Daten

### 2. Product-Family Archive

* [x] eigenes `taxonomy-product_family.html`
* [x] Parent-/Fallback-Archive ersetzen
* [x] bestehenden Hero wiederverwenden
* [x] Term-Name und Term-Beschreibung
* [x] Datenbasis für aggregierte Reifendimensionen
* [ ] Reifendimensionen im Hero asynchron laden
* [ ] 3 Dimensionen + Expand-UX
* [ ] Product Grid
* [ ] wiederverwendbare Product Cards
* [ ] Styling/Figma-Finish

### 3. Product Single

Danach als separates Modul:

* eigener Produktkopf / Hero
* Bildergalerie
* Preis
* Gliederstärke
* Verfügbarkeit
* passende Reifendimensionen
* Anwendungen / Icons
* B2B-Anfrage- bzw. Warenkorb-Flow

### 4. Interaktive Suche / Filter

Später:

* gewählte Reifendimension berücksichtigen
* Product Families nach Dimension einschränken
* Product Grid dynamisch filtern
* REST für interaktive Zustände verwenden

---

## Version

Aktuell:

```text
0.2.0
```
