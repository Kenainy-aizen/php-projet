# 💊 G-Pharm — Système de Gestion de Pharmacie

Application web de gestion de pharmacie développée en **PHP / MySQL** suivant le patron **MVC**.  
Elle permet de gérer les médicaments, les ventes (achats), les entrées de stock et de visualiser des statistiques en temps réel.

---

## 📋 Table des matières

1. [Aperçu des fonctionnalités](#-aperçu-des-fonctionnalités)
2. [Technologies utilisées](#-technologies-utilisées)
3. [Prérequis](#-prérequis)
4. [Installation](#-installation)
5. [Configuration de la base de données](#-configuration-de-la-base-de-données)
6. [Configuration de l'application](#-configuration-de-lapplication)
7. [Configuration Apache](#-configuration-apache)
8. [Structure du projet](#-structure-du-projet)
9. [Routes disponibles](#-routes-disponibles)
10. [Schéma de la base de données](#-schéma-de-la-base-de-données)

---

## ✨ Aperçu des fonctionnalités

### 🏠 Tableau de bord
- **4 indicateurs KPI** : nombre de médicaments, total des factures, ruptures de stock, recette totale
- **Graphique interactif** des recettes des 5 derniers mois (Chart.js)
- **Top 5** des médicaments les plus vendus
- **Alertes automatiques** pour les médicaments en rupture de stock (< 5 unités)

### 💊 Gestion des médicaments
- Liste complète avec indicateur visuel du niveau de stock (🟢 OK / 🟡 Faible / 🔴 Critique)
- **Ajout** via modal avec vérification de doublon (AJAX)
- **Modification** de la désignation et du prix unitaire
- **Suppression** avec confirmation SweetAlert2
- **Recherche** par désignation
- **Vue des ruptures de stock** avec statut ÉPUISÉ / CRITIQUE

### 🛒 Gestion des ventes (achats)
- Liste des ventes groupées par numéro de facture
- **Création de facture** multi-articles : ajout article par article avec aperçu en temps réel
- **Génération de PDF** de la facture (FPDF)
- **Suppression** avec restauration automatique du stock
- **Recherche** par nom de client
- Numérotation automatique (`ACH-001`, `ACH-002`, …)

### 📦 Gestion des entrées de stock
- Historique de toutes les entrées
- **Ajout de stock** avec mise à jour automatique du stock du médicament
- **Modification** d'une entrée avec recalcul du stock
- **Suppression** avec annulation de l'impact sur le stock
- Numérotation automatique (`ENT-001`, `ENT-002`, …)

### 📊 Rapports
- **Histogramme des recettes** (5 derniers mois) avec Chart.js
- Tableau de détail par mois avec barres de progression et pourcentages
- Cards récapitulatives : total période, moyenne mensuelle, meilleur mois

---

## 🛠 Technologies utilisées

| Catégorie | Technologie | Version |
|---|---|---|
| Backend | PHP | 8.0+ |
| Base de données | MySQL / MariaDB | 5.7+ |
| Serveur web | Apache | 2.4+ |
| Accès BDD | PDO | — |
| PDF | FPDF | 1.86 |
| Alertes | SweetAlert2 | (local) |
| Graphiques | Chart.js | 4.4.0 (CDN) |
| Frontend | HTML5 / CSS3 / JS vanilla | — |
| Architecture | MVC (Model-View-Controller) | — |

---

## 📦 Prérequis

- **PHP** ≥ 8.0 avec l'extension PDO et PDO_MySQL activée
- **MySQL** ≥ 5.7 ou **MariaDB** ≥ 10.3
- **Apache** ≥ 2.4 avec `mod_rewrite` activé
- Accès à un terminal (pour les commandes d'installation)

---

## 🚀 Installation

### 1. Cloner ou copier le projet

```bash
# Cloner dans le dossier web d'Apache
git clone <url-du-repo> /srv/http/ProjetPharma

# Ou copier manuellement le dossier dans /srv/http/
```

### 2. Vérifier les permissions

```bash
# Donner les droits de lecture à Apache
chmod -R 755 /srv/http/ProjetPharma
```

### 3. Accéder à l'application

Ouvrir le navigateur à l'adresse :

```
http://localhost/ProjetPharma/
```

---

## 🗄 Configuration de la base de données

### 1. Créer la base de données

```sql
CREATE DATABASE pharmacie CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE pharmacie;
```

### 2. Créer les tables

```sql
-- Table médicaments
CREATE TABLE medicament (
    numMedoc     VARCHAR(10)    NOT NULL PRIMARY KEY,
    Design       VARCHAR(100)   NOT NULL,
    prix_unitaire DECIMAL(10,2) NOT NULL DEFAULT 0,
    stock        INT            NOT NULL DEFAULT 0
);

-- Table achats (ventes)
CREATE TABLE achat (
    numAchat   VARCHAR(10)  NOT NULL,
    numMedoc   VARCHAR(10)  NOT NULL,
    nomClient  VARCHAR(100) NOT NULL,
    nbr        INT          NOT NULL,
    dateAchat  DATE         NOT NULL,
    PRIMARY KEY (numAchat, numMedoc, nbr),
    FOREIGN KEY (numMedoc) REFERENCES medicament(numMedoc)
);

-- Table entrées de stock
CREATE TABLE entree (
    numEntree   VARCHAR(10) NOT NULL PRIMARY KEY,
    numMedoc    VARCHAR(10) NOT NULL,
    stockEntree INT         NOT NULL,
    dateEntree  DATETIME    NOT NULL DEFAULT NOW(),
    FOREIGN KEY (numMedoc) REFERENCES medicament(numMedoc)
);
```

### 3. (Optionnel) Insérer des données de test

```sql
INSERT INTO medicament (numMedoc, Design, prix_unitaire, stock) VALUES
('MED-001', 'Paracétamol 500mg', 500,  120),
('MED-002', 'Amoxicilline 1g',   1200, 45),
('MED-003', 'Ibuprofène 400mg',  800,  3),
('MED-004', 'Aspégic 100mg',     600,  0),
('MED-005', 'Doliprane 1g',      700,  80);
```

### 4. Configurer les identifiants de connexion

Modifier le fichier `config/db.php` :

```php
// config/db.php
private $host     = 'localhost';   // Hôte MySQL
private $db_name  = 'pharmacie';   // Nom de la base
private $username = 'root';        // Utilisateur MySQL
private $password = '';            // Mot de passe MySQL
```

---

## ⚙️ Configuration de l'application

Le fichier principal de configuration est **`config/app.php`**.

```php
// config/app.php

// Chemin de base selon la configuration Apache :
// ─ Si DocumentRoot pointe sur /srv/http/ProjetPharma  →  ''
// ─ Si le projet est dans un sous-dossier (défaut)     →  '/ProjetPharma'
define('BASE_URL', '/ProjetPharma');
```

> **Important :** `BASE_URL` contrôle le préfixe de tous les chemins vers les assets CSS et JS.  
> À modifier selon votre configuration Apache (voir section suivante).

---

## 🌐 Configuration Apache

### Option A — Sous-dossier (configuration par défaut)

Avec le `DocumentRoot` Apache par défaut (`/srv/http`), le projet est accessible à :

```
http://localhost/ProjetPharma/
```

**→ Laisser `BASE_URL = '/ProjetPharma'`** dans `config/app.php`. Aucune modification Apache nécessaire.

---

### Option B — Racine du serveur (performance optimale)

Pour accéder directement à `http://localhost/`, ajouter dans `/etc/httpd/conf/httpd.conf` :

```apache
Include /srv/http/ProjetPharma/pharma.conf
```

Puis redémarrer Apache :

```bash
sudo systemctl restart httpd
```

**→ Changer `BASE_URL = ''`** dans `config/app.php`.

> Le fichier `pharma.conf` est déjà présent à la racine du projet avec la configuration complète.

---

## 📁 Structure du projet

```
ProjetPharma/
│
├── index.php                  # Point d'entrée unique (routeur)
├── pharma.conf                # Configuration Apache (Option B)
│
├── config/
│   ├── app.php                # Configuration globale (BASE_URL)
│   └── db.php                 # Connexion PDO à la base de données
│
├── Controllers/               # Contrôleurs MVC
│   ├── AccueilController.php  # Tableau de bord & statistiques
│   ├── MedicamentController.php
│   ├── AchatController.php
│   └── EntreeController.php
│
├── Models/                    # Modèles (accès base de données)
│   ├── accueil.php            # Statistiques, KPI, graphiques
│   ├── Medicament.php         # CRUD médicaments
│   ├── Achat.php              # CRUD ventes, génération PDF
│   └── Entree.php             # CRUD entrées de stock
│
├── Views1/                    # Vues actives (interface v2)
│   ├── shared/
│   │   ├── global.css         # Système de design complet
│   │   └── sidebar.php        # Sidebar partagée (navigation)
│   │
│   ├── acceuil/
│   │   ├── read.php           # Tableau de bord
│   │   └── cssAcceuil.css
│   │
│   ├── medicament/
│   │   ├── read.php           # Liste + modals CRUD
│   │   ├── read.css
│   │   └── script.js
│   │
│   ├── achat/
│   │   ├── read.php           # Liste des ventes
│   │   ├── read.css
│   │   ├── create1.php        # Formulaire nouvelle vente
│   │   └── script.js
│   │
│   └── entree/
│       ├── read.php           # Liste des entrées de stock
│       ├── read.css
│       └── script.js
│
├── Views/                     # Vues secondaires
│   ├── medicament/
│   │   └── resultRuptureDeStock.php
│   └── achat/
│       └── affichageDeHistogramme.php
│
├── lib/
│   └── sweetalert2/           # SweetAlert2 (local)
│
└── pdf/
    └── fpdf.php               # Librairie FPDF pour génération PDF
```

---

## 🔗 Routes disponibles

Toutes les routes passent par `index.php` avec les paramètres `entity` et `action`.

| URL | Description |
|---|---|
| `index.php` | → Tableau de bord (accueil) |
| `index.php?entity=acceuil` | Tableau de bord |
| `index.php?entity=medicament` | Liste des médicaments |
| `index.php?entity=medicament&action=rechercher` | Recherche médicament (POST) |
| `index.php?entity=medicament&action=create` | Ajouter un médicament (POST/JSON) |
| `index.php?entity=medicament&action=update&id=MED-001` | Modifier un médicament (POST) |
| `index.php?entity=medicament&action=delete&id=MED-001` | Supprimer un médicament (GET/JSON) |
| `index.php?entity=medicament&action=ruptureDeStock` | Vue ruptures de stock |
| `index.php?entity=achat` | Liste des ventes |
| `index.php?entity=achat&action=create` | Nouvelle vente (formulaire facture) |
| `index.php?entity=achat&action=delete&id=X&param1=Y&param2=Z` | Supprimer une ligne de vente |
| `index.php?entity=achat&action=CreatePdf&id=ACH-001` | Générer PDF facture |
| `index.php?entity=achat&action=afficherHistogrammeRecettes` | Graphique des recettes |
| `index.php?entity=entree` | Liste des entrées de stock |
| `index.php?entity=entree&action=create` | Ajouter une entrée (POST) |
| `index.php?entity=entree&action=update&id=ENT-001` | Modifier une entrée (POST) |
| `index.php?entity=entree&action=delete&id=ENT-001` | Supprimer une entrée (GET/JSON) |

---

## 🗃 Schéma de la base de données

```
┌─────────────────────────────┐
│         medicament          │
├─────────────────────────────┤
│ numMedoc   VARCHAR(10)  PK  │ ← Format : MED-001
│ Design     VARCHAR(100)     │
│ prix_unitaire DECIMAL(10,2) │
│ stock      INT              │
└─────────────┬───────────────┘
              │  1
              │
        ┌─────┴──────────────────────────┐
        │                                │
        ▼  N                             ▼  N
┌───────────────────────┐    ┌───────────────────────┐
│         achat         │    │        entree         │
├───────────────────────┤    ├───────────────────────┤
│ numAchat  VARCHAR(10) │    │ numEntree VARCHAR(10) │ ← Format : ENT-001
│ numMedoc  VARCHAR(10) │    │ numMedoc  VARCHAR(10) │
│ nomClient VARCHAR(100)│    │ stockEntree  INT      │
│ nbr       INT         │    │ dateEntree DATETIME   │
│ dateAchat DATE        │    └───────────────────────┘
└───────────────────────┘
  PK : (numAchat, numMedoc, nbr)
  Format numAchat : ACH-001
```

> **Note sur le stock :**  
> Le stock dans la table `medicament` est géré automatiquement :  
> - **Achat** → stock diminue du nombre d'unités vendues  
> - **Entrée** → stock augmente du nombre d'unités entrées  
> - **Suppression achat** → stock est restauré  
> - **Suppression entrée** → stock est décrémenté

---

## 👤 Auteur

Projet développé dans le cadre d'un cours de développement web PHP/MySQL.

---

*G-Pharm v2.0 — Interface redessinée avec système de design personnalisé*
