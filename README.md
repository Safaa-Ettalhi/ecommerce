# Projet de Plateforme E-Commerce

Avec l'essor du commerce en ligne, il devient indispensable pour les entreprises de disposer d'une plateforme e-commerce performante et intuitive. Ce projet vise à développer une application web permettant aux utilisateurs d'acheter des produits en ligne en toute simplicité.

## Contexte du Projet

L'objectif est de créer une application de e-commerce offrant une interface conviviale pour l'achat de produits en ligne, avec un système de gestion des utilisateurs, des produits, des commandes, ainsi qu'une solution de paiement sécurisée.

## Objectifs du Projet

- Concevoir et développer une plateforme e-commerce intuitive et responsive.
- Implémenter un système de gestion de produits (ajout, modification, suppression).
- Permettre aux utilisateurs de créer un compte et de passer des commandes en toute sécurité.
- Assurer un système de paiement en ligne sécurisé.
- Intégrer un système de gestion des stocks et des commandes.

## Fonctionnalités Principales

### 1. Gestion des Utilisateurs

- Inscription et connexion (avec validation par email).
- Profil utilisateur avec historique des commandes.
- Système d'authentification sécurisé basé sur les sessions (Auth Session de Laravel).
- Gestion des rôles et permissions pour l'administration.

### 2. Catalogue de Produits

- Liste des produits avec catégories et filtres.
- Détails des produits avec images, description et prix.
- Système d’évaluation et d’avis sur les produits.

### 3. Panier et Commandes

- Ajout/suppression de produits dans le panier.
- Gestion des quantités et estimation des frais de livraison.
- Processus de commande et suivi en temps réel.

### 4. Système de Paiement

- Intégration de passerelles de paiement (Stripe, PayPal, etc.).
- Facturation et génération de reçus.

### 5. Administration

- Tableau de bord pour la gestion des commandes et des utilisateurs.
- Ajout, modification et suppression de produits et catégories.
- Suivi des stocks et des ventes.

## Technologies Utilisées

- **Base de données** : PostgreSQL
- **ORM** : Eloquent
- **Moteur de template** : Blade
- **Authentification** : Auth Session de Laravel
- **Passerelles de paiement** : Stripe, PayPal

## Installation

1. Clonez le repository :

```bash
git clone https://github.com/Safaa-Ettalhi/ecommerce
