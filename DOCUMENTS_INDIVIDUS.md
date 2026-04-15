# Système de Gestion Hiérarchique des Documents avec Dossiers Individuels

## Vue d'ensemble

Ce système permet aux points focaux et administrateurs d'enregistrer, gérer et consulter les documents selon une organisation hiérarchique complète : **Ménage → Pochette → Dossiers individuels → Classeurs → Documents**. Cette structure offre une traçabilité optimale et une organisation personnalisée pour chaque individu du ménage.

## Architecture du Système

### Hiérarchie des Documents
```
Ménage (Chef de famille)
├── Pochette (1:1) - Conteneur principal unique
│   ├── Dossiers individuels (1:N)
│   │   ├── Dossier "Jean Dupont"
│   │   │   ├── Classeur "État civil"
│   │   │   │   ├── Document 1 (Acte de naissance)
│   │   │   │   └── Document 2 (Acte de mariage)
│   │   │   ├── Classeur "Identité"
│   │   │   │   ├── Document 1 (CNI)
│   │   │   │   └── Document 2 (Passeport)
│   │   │   └── Classeur "Santé"
│   │   └── Dossier "Marie Dupont"
│   │       ├── Classeur "État civil"
│   │       └── Classeur "Scolarité"
│   └── Classeurs du ménage (documents communs)
│       ├── Classeur "Foncier" (titre de propriété)
│       └── Classeur "Finances" (documents bancaires)
```

### Niveaux d'Organisation

1. **Ménage** : Unité familiale de base
2. **Pochette** : Conteneur unique par ménage (créée automatiquement)
3. **Dossiers individuels** : Un dossier personnel par individu du ménage
4. **Classeurs** : Organisation thématique des documents (soit dans un dossier individuel, soit au niveau ménage)
5. **Documents** : Fichiers individuels avec traçabilité complète

## Fonctionnalités

### 1. Gestion des Pochettes

#### Routes disponibles :
- `GET /menages/{menage}/pochette` - Affichage de la pochette
- `GET /menages/{menage}/pochette/edit` - Formulaire de modification
- `PUT /menages/{menage}/pochette` - Mise à jour de la pochette

**Caractéristiques :**
- Une pochette unique par ménage (contrainte en base)
- Création automatique lors du premier accès
- Vue d'ensemble des dossiers individuels et classeurs du ménage

### 2. Gestion des Dossiers Individuels

#### Routes disponibles :
- `GET /menages/{menage}/dossiers` - Liste des dossiers individuels
- `GET /menages/{menage}/dossiers/create` - Formulaire de création
- `POST /menages/{menage}/dossiers` - Enregistrement d'un nouveau dossier
- `GET /menages/{menage}/dossiers/{dossier}` - Détails d'un dossier
- `GET /menages/{menage}/dossiers/{dossier}/edit` - Formulaire de modification
- `PUT /menages/{menage}/dossiers/{dossier}` - Mise à jour d'un dossier
- `DELETE /menages/{menage}/dossiers/{dossier}` - Suppression d'un dossier

**Caractéristiques :**
- Un dossier unique par individu par pochette (contrainte en base)
- Nom généré automatiquement : "Dossier de [Prénom] [Nom]"
- Traçabilité complète des documents personnels

### 3. Gestion des Classeurs

#### Routes pour classeurs du ménage :
- `GET /menages/{menage}/classeurs` - Liste des classeurs du ménage
- `POST /menages/{menage}/classeurs` - Création d'un classeur du ménage

#### Routes pour classeurs individuels :
- `GET /menages/{menage}/dossiers/{dossier}/classeurs/create` - Création dans un dossier
- `POST /menages/{menage}/dossiers/{dossier}/classeurs` - Enregistrement dans un dossier
- `GET /menages/{menage}/dossiers/{dossier}/classeurs/{classeur}` - Détails d'un classeur individuel

**Thèmes disponibles :**
- État civil (actes de naissance, mariage, décès...)
- Identité (CNI, passeport, permis...)
- Santé (carnets de vaccination, certificats médicaux...)
- Scolarité (diplômes, bulletins, attestations...)
- Foncier (titres de propriété, contrats de bail...)
- Justice (extraits de casier, jugements...)
- Travail (contrats, fiches de paie...)
- Finances (relevés bancaires, attestations...)
- Assurance (polices, attestations...)
- Autre (documents divers)

### 4. Gestion des Documents

#### Routes disponibles :
- `GET /menages/{menage}/classeurs/{classeur}/documents/create` - Ajout dans un classeur
- `POST /menages/{menage}/classeurs/{classeur}/documents` - Enregistrement
- `GET /menages/{menage}/classeurs/{classeur}/documents/{document}` - Détails
- `PUT /menages/{menage}/classeurs/{classeur}/documents/{document}` - Mise à jour
- `DELETE /menages/{menage}/classeurs/{classeur}/documents/{document}` - Suppression
- `GET /menages/{menage}/classeurs/{classeur}/documents/{document}/download` - Téléchargement

**Caractéristiques des documents :**
- Peuvent concerner un individu spécifique ou le ménage entier
- Stockage organisé par ménage : `storage/app/public/documents/menages/{menage_id}/`
- Formats supportés : PDF, JPG, JPEG, PNG, DOC, DOCX
- Taille maximale : 5MB par fichier
- Traçabilité complète (auteur, date, contexte)

### 5. Contrôles d'Accès

#### Points Focaux :
- Accès aux ménages de leur village uniquement
- Peuvent créer, modifier et supprimer des dossiers, classeurs et documents
- Héritent automatiquement de la géolocalisation de leur village assigné

#### Administrateurs :
- Accès complet à tous les ménages et documents
- Peuvent supprimer des dossiers, classeurs et documents
- Gestion globale du système

## Structure de la Base de Données

### Table `pochettes`
```sql
- id (bigint, primary key)
- menage_id (foreign key, unique) - Ménage propriétaire
- libelle (varchar 150) - Nom de la pochette
- description (text, nullable) - Description
- created_at, updated_at (timestamps)
```

### Table `dossiers`
```sql
- id (bigint, primary key)
- pochette_id (foreign key) - Pochette parente
- individu_menage_id (foreign key) - Individu propriétaire
- nom (varchar 200) - Nom du dossier
- description (text, nullable) - Description
- created_at, updated_at (timestamps)
- UNIQUE(pochette_id, individu_menage_id) - Un seul dossier par individu par pochette
```

### Table `classeurs` (mise à jour)
```sql
- id (bigint, primary key)
- pochette_id (foreign key, nullable) - Pochette parente (pour classeurs du ménage)
- dossier_id (foreign key, nullable) - Dossier parent (pour classeurs individuels)
- theme (varchar 100) - Thème du classeur
- description (text, nullable) - Description
- created_at, updated_at (timestamps)
- CHECK: (pochette_id IS NOT NULL AND dossier_id IS NULL) OR (dossier_id IS NOT NULL)
- UNIQUE INDEX sur (COALESCE(pochette_id, 0), COALESCE(dossier_id, 0), theme)
```

### Table `documents` (existante)
```sql
- id (bigint, primary key)
- libelle (varchar 150) - Nom du document
- numero (varchar 25, nullable) - Numéro du document
- nom_fichier (varchar 150, nullable) - Nom original du fichier
- fichier (varchar, nullable) - Chemin de stockage du fichier
- type_document_id (foreign key) - Type de document
- user_id (foreign key) - Utilisateur qui a ajouté le document
- classeur_id (foreign key, nullable) - Classeur de rangement
- menage_id (foreign key, nullable) - Ménage concerné
- individu_menage_id (foreign key, nullable) - Individu concerné
- date_ajout (timestamp, nullable) - Date d'ajout du document
- created_at, updated_at (timestamps)
```

### Relations
- Un ménage a une pochette (1:1)
- Une pochette a plusieurs dossiers individuels (1:N)
- Une pochette a plusieurs classeurs du ménage (1:N)
- Un dossier individuel a plusieurs classeurs (1:N)
- Un classeur a plusieurs documents (1:N)
- Un document appartient à un type de document
- Un document appartient à un utilisateur (auteur)
- Un document peut concerner un individu spécifique ou le ménage entier

## Utilisation

### 1. Accéder à la Pochette d'un Ménage

1. Aller sur la page de détails d'un ménage
2. Cliquer sur "Pochette documents"
3. La pochette est créée automatiquement si elle n'existe pas
4. Vue d'ensemble avec navigation vers dossiers individuels et classeurs du ménage

### 2. Créer un Dossier Individuel

1. Depuis la pochette, cliquer sur "Gérer les dossiers"
2. Cliquer sur "Nouveau dossier"
3. Sélectionner l'individu concerné
4. Le nom est généré automatiquement ou peut être personnalisé
5. Enregistrer

### 3. Créer un Classeur

**Pour un individu :**
1. Aller dans le dossier de l'individu
2. Cliquer sur "Nouveau classeur"
3. Sélectionner un thème dans la liste prédéfinie
4. Personnaliser la description si nécessaire

**Pour le ménage :**
1. Depuis la pochette, cliquer sur "Gérer les classeurs"
2. Cliquer sur "Nouveau classeur"
3. Sélectionner un thème pour les documents communs du ménage

### 4. Ajouter un Document

1. Aller dans un classeur spécifique (individuel ou du ménage)
2. Cliquer sur "Ajouter document"
3. Remplir le formulaire :
   - Libellé du document (obligatoire)
   - Type de document (obligatoire)
   - Numéro du document (optionnel)
   - Fichier (obligatoire)
   - Individu concerné (optionnel - si vide, concerne le ménage)
4. Enregistrer

### 5. Navigation dans la Hiérarchie

- **Ménage** → Bouton "Pochette documents"
- **Pochette** → Navigation vers "Dossiers individuels" ou "Classeurs du ménage"
- **Dossier individuel** → Liste des classeurs personnels
- **Classeur** → Liste des documents avec actions
- **Document** → Détails avec aperçu du fichier

## Contrôleurs

### PochetteController
- Gestion des pochettes de ménages
- Création automatique lors du premier accès
- Vue d'ensemble avec navigation vers dossiers et classeurs

### DossierController
- CRUD complet des dossiers individuels
- Validation de l'unicité par individu par pochette
- Gestion des permissions par village

### ClasseurController (étendu)
- CRUD des classeurs du ménage (liés à la pochette)
- CRUD des classeurs individuels (liés aux dossiers)
- Validation des thèmes uniques par contexte
- Gestion des permissions par village

### MenageDocumentController
- Gestion des documents dans tous types de classeurs
- Support des documents liés aux individus ou au ménage
- Contrôle d'accès basé sur la géolocalisation

## Vues

### Pochettes
- `show.blade.php` - Vue principale avec navigation vers dossiers et classeurs

### Dossiers
- `index.blade.php` - Liste des dossiers individuels avec statistiques
- `create.blade.php` - Création avec sélection d'individu
- `show.blade.php` - Détails avec liste des classeurs personnels

### Classeurs
- `index.blade.php` - Liste des classeurs (ménage ou individuels)
- `create.blade.php` - Création pour le ménage
- `create-dossier.blade.php` - Création pour un dossier individuel
- `show.blade.php` - Détails avec liste des documents
- `show-dossier.blade.php` - Détails d'un classeur individuel

### Documents
- `create.blade.php` - Ajout avec sélection individu/ménage
- `show.blade.php` - Détails du document
- `edit.blade.php` - Modification du document

## Migration et Déploiement

1. Exécuter les migrations : `php artisan migrate`
2. Exécuter les seeders : 
   ```bash
   php artisan db:seed --class=Type_documentSeeder
   php artisan db:seed --class=ClasseurSeeder
   ```
3. Créer le lien symbolique pour le stockage : `php artisan storage:link`
4. Vérifier les permissions du dossier `storage/`

## Sécurité

### Stockage des Fichiers
- Organisation par ménage : `storage/app/public/documents/menages/{menage_id}/`
- Noms de fichiers uniques avec timestamp
- Accès contrôlé par les permissions utilisateur

### Validation
- Vérification des types de fichiers
- Limitation de la taille des fichiers (5MB)
- Validation des permissions d'accès par village
- Contraintes d'unicité sur les dossiers et thèmes de classeurs
- Validation des relations hiérarchiques

### Contrôle d'Accès
- Points focaux : accès limité à leur village
- Vérification des relations ménage/pochette/dossier/classeur/document
- Validation des permissions à chaque niveau hiérarchique

## Maintenance

### Nettoyage des Fichiers
- Suppression automatique des fichiers lors de la suppression des documents
- Organisation par dossier de ménage pour faciliter la maintenance
- Suppression en cascade des classeurs lors de la suppression des dossiers

### Sauvegarde
- Inclure le dossier `storage/app/public/documents/menages/` dans les sauvegardes
- Sauvegarder la base de données avec toute la hiérarchie

## Évolutions Futures

- Export de dossiers individuels complets en PDF
- Recherche globale dans tous les documents d'un individu
- Notifications de documents manquants par individu
- Statistiques par type de document et classeur par individu
- Archivage automatique des anciens documents
- Modèles de dossiers prédéfinis par type d'individu
- Partage sécurisé de documents entre dossiers