# EventHub - Application de Gestion des Événements et Inscriptions

[![Laravel](https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2-777BB4?style=for-the-badge&logo=php)](https://www.php.net)
[![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?style=for-the-badge&logo=mysql)](https://www.mysql.com)
[![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-3.4-38B2AC?style=for-the-badge&logo=tailwind-css)](https://tailwindcss.com)

> **IFRI - UAC (Institut de Formation et de Recherche en Informatique - Université d'Abomey-Calavi)**  
> **Filière :** Génie Logiciel (GL 3)  
> **Matière :** Projet d'initiation aux frameworks  
> **Enseignante :** GAHOU Maryse  
> **Année Académique :** 2026 - 2027  
> **Sujet attribué :** **Sujet B - Gestion des événements et inscriptions**  
> **Équipe N° 14**

---

## Membres de l'Équipe & Répartition des Tâches

| Nom & Prénoms                      | Rôle principal & Contribution                                                                                                                                                                                                                                              |
| :--------------------------------- | :------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| **Sènakpon Osvald Eléazar AKLOBO** | **Architecture & Base de données** : Modèle MVC, migrations de données, modèles Eloquent (`Event`, `Registration`), contrôleur des événements (`EventController`), système de recherche et filtres combinés.                                                               |
| **Roberto TALEHOU**                | **Gestion des Inscriptions** : Contrôleur des inscriptions (`RegistrationController`), logique de réservation en temps réel, contrôle de capacité et anti-doublon, annulation et libération de places.                                                                     |
| **Dieudonné ADOH**                 | **Interface Utilisateur (Blade & Responsive)** : Conception du layout principal, intégration Tailwind CSS & FontAwesome, vues Blade (`events.index`, `events.show`, `events.create`, `events.edit`, `registrations.my_registrations`), alertes flash et adaptation mobile. |

---

## Fonctionnalités Réalisées

Conformément au cahier des charges du **Sujet B** :

1. **Présentation & Découverte des événements :**
   - Affichage en grille moderne et responsive avec cartes dynamiques.
   - Badges colorés de catégories : Conférence, Concert, Sport, Atelier, Exposition, Autre.
   - Jauge visuelle et dynamique du **nombre de places restantes**.
   - Indicateurs d'état en temps réel : _Disponible_, _Bientôt complet_, _Complet_, _Passé_.

2. **Recherche et Filtres avancés :**
   - **Recherche textuelle** par mot-clé (titre, description, lieu).
   - **Filtres par catégorie** (boutons rapides + sélecteur).
   - **Filtres par date** (Événements à venir, Aujourd'hui, Passés).
   - Bouton de réinitialisation instantanée des filtres et gestion des états vides avec illustration.

3. **Consultation détaillée (Show) :**
   - Fiche complète avec date & heure formatées, lieu précis, illustration et texte explicatif.
   - Barre de progression du taux de remplissage.
   - Liste des participants déjà inscrits avec date d'enregistrement.
   - Boutons d'édition et de suppression.

4. **Simulation d'Inscription :**
   - Formulaire rapide (Nom complet, Email, Téléphone optionnel).
   - **Contrôles stricts :**
     - Vérification de la disponibilité (blocage immédiat si événement complet ou passé).
     - Contrôle d'unicité (impossible de s'inscrire deux fois avec le même email à un même événement).
     - Décrémentation automatique du nombre de places restantes.
   - Conforme aux exigences : _simulation sans paiement ni envoi réel de courriel_.

5. **Annulation d'une inscription :**
   - Page dédiée **"Mes Inscriptions"** permettant de rechercher ses réservations par adresse email.
   - Possibilité d'annuler une inscription en 1 clic (libère immédiatement la place pour les autres participants).

6. **Opérations CRUD complètes sur les événements :**
   - **Création** d'un nouvel événement avec validation stricte de tous les champs.
   - **Modification** d'un événement existant (avec contrôle de cohérence sur la capacité).
   - **Suppression** sécurisée avec confirmation et suppression en cascade des inscriptions.

7. **Qualité & Ergonomie :**
   - Messages flash compréhensibles (succès vert, avertissement orange, erreurs rouges).
   - Validation avec messages d'erreurs en français sous chaque champ.
   - Menu burger responsive pour une navigation fluide sur smartphone et tablette.

---

##  Choix Techniques

- **Framework Web :** Laravel 12 (PHP 8.2+)
- **Base de Données :** MySQL (conforme à l'exigence : _SQLite n'est pas autorisé_)
- **Moteur de Templates :** Laravel Blade (composants et layouts réutilisables)
- **Styling :** Tailwind CSS (CDN moderne & responsive-first) + Font Awesome 6
- **Seeders :** Données de démonstration réalistes situées au Bénin (Cotonou, IFRI - UAC, Palais des Congrès, etc.)
- **Tests Automatisés :** Suite de tests unitaires et fonctionnels PHPUnit complète (100% de réussite)

---

## Instructions d'Installation et de Lancement Local

### Prérequis

- **PHP 8.2** ou supérieur (disponible via XAMPP, WampServer ou PHP standalone)
- **Composer** (Gestionnaire de dépendances PHP)
- **MySQL** (Démarré via le panneau de configuration XAMPP ou service local)

---

### Étape 1 : Se positionner dans le dossier du projet

Ouvrez votre terminal (PowerShell ou Invite de commandes) :

```powershell
cd c:\Users\LENOVO-PC\Documents\gestion-evenements\gestion-evenements
```

---

### Étape 2 : Démarrer MySQL sur XAMPP

1. Ouvrez le **Panneau de configuration XAMPP** (_XAMPP Control Panel_).
2. Cliquez sur le bouton **Start** en face du module **MySQL** (le port par défaut est `3306`).

---

### Étape 3 : Créer la base de données MySQL

Dans votre terminal ou via phpMyAdmin (`http://localhost/phpmyadmin`) :

```sql
CREATE DATABASE IF NOT EXISTS gestion_evenements CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

---

### Étape 4 : Configurer le fichier d'environnement `.env`

Le fichier `.env` est déjà préconfiguré avec les paramètres MySQL :

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=gestion_evenements
DB_USERNAME=root
DB_PASSWORD=
```

_(Si vous avez un mot de passe pour l'utilisateur root de MySQL, spécifiez-le dans `DB_PASSWORD`)_.

Générez la clé d'application si nécessaire :

```powershell
php artisan key:generate
```

---

### Étape 5 : Exécuter les migrations et charger les données de test (Seeders)

Cette commande va créer toutes les tables dans MySQL et injecter des événements et inscriptions réalistes :

```powershell
php artisan migrate:fresh --seed
```

---

### Étape 6 : Lancer le serveur local Laravel

Démarrez le serveur de développement :

```powershell
php artisan serve
```

L'application est accessible dans votre navigateur à l'adresse :
 **http://127.0.0.1:8000** ou **http://localhost:8000**

---

###  Exécution des tests automatisés

Pour valider le bon fonctionnement de toutes les fonctionnalités :

```powershell
php vendor/bin/phpunit
```

Résultat : `OK (6 tests, 14 assertions)`

---

## Structure du Projet (Modèle MVC)

```text
gestion-evenements/
├── app/
│   ├── Http/
│   │   └── Controllers/
│   │       ├── EventController.php          # CRUD événements, filtres, recherche
│   │       └── RegistrationController.php   # Inscription, annulation, mes inscriptions
│   └── Models/
│       ├── Event.php                        # Modèle Événement, relations & scopes
│       └── Registration.php                 # Modèle Inscription & statut
├── database/
│   ├── migrations/
│   │   ├── 2026_11_01_132126_create_events_table.php
│   │   └── 2026_11_01_132607_create_registrations_table.php
│   └── seeders/
│       ├── DatabaseSeeder.php
│       ├── EventSeeder.php                  # Événements réalistes (Bénin)
│       └── RegistrationSeeder.php           # Inscriptions de test
├── resources/
│   └── views/
│       ├── layouts/
│       │   └── app.blade.php                # Layout commun, Navbar, Flash, Footer
│       ├── events/
│       │   ├── index.blade.php              # Liste, filtres & recherche
│       │   ├── show.blade.php               # Détails & formulaire d'inscription
│       │   ├── create.blade.php             # Formulaire d'ajout
│       │   └── edit.blade.php               # Formulaire de modification
│       └── registrations/
│           └── my_registrations.blade.php   # Consultation & annulation
├── routes/
│   └── web.php                              # Définition des routes web
└── tests/
    └── Feature/
        ├── ExampleTest.php
        └── EventManagementTest.php          # Tests de l'ensemble des fonctionnalités
```

---
