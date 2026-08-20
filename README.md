# Twenty Twenty-Five Child

RBF WooCommerce Child Theme auf Basis von Twenty Twenty-Five.

## Fokus

- Custom Frontpage
- WooCommerce-Frontend
- Hero-/Swiper-Ausgabe
- dynamische Product-Family-Komponenten
- Darstellung über native HTML-`<template>`-Elemente

## Architektur

Grundsatz:

- Plugin = Daten / Businesslogik / REST
- Theme = Darstellung / CSS / Template-Overrides

Dynamische Product-Family-Daten kommen als JSON aus `rbf-site-core`.

Das Theme stellt dafür eigene Template-Overrides bereit, z. B.:

template-parts/product-family-item.php

Filter-Registrierungen liegen in:

rbf_filters.php


## Version

Aktuell: 0.1.0
