# Projet-Chrysalide-ProgServ2
Chrysalide une application web qui permet aux auteur·ice·s de publier leurs histoires écrites, et aux lecteur·ice·s de les découvrir.
# 🦋 Chrysalide - Plateforme de lecture et d'écriture d'histoires

## 📋 Description

Chrysalide est une plateforme web développée en PHP permettant aux utilisateurs de lire et écrire des histoires. Le projet a été réalisé dans le cadre du cours **ProgServ2** à la **HEIG-VD**.

---

## 🌐 Démo en ligne

**URL** : https://heig-chrysalide.ch/public/

---

## ✨ Fonctionnalités

### Pour tous les utilisateurs :
- ✅ Consulter le catalogue d'histoires publiées
- ✅ Lire les histoires
- ✅ Créer un compte (lecteur ou auteur)
- ✅ Confirmation par email
- ✅ Interface bilingue (français/anglais)

### Pour les auteurs :
- ✅ Créer des histoires
- ✅ Modifier ses histoires
- ✅ Supprimer ses histoires
- ✅ Publier/dépublier ses histoires
- ✅ Gérer ses brouillons

---

## 🛠️ Technologies utilisées

- **Backend** : PHP 8.2+ (POO)
- **Base de données** : MySQL
- **Frontend** : HTML5, CSS3 (design custom)
- **Emails** : PHPMailer via SMTP
- **Serveur** : Apache (Infomaniak)
- **Architecture** : MVC simplifié

---

## 📂 Structure du projet

```
Projet-Chrysalide-ProgServ2/
├── public/                    # Pages publiques
│   ├── index.php             # Page d'accueil
│   ├── login.php             # Connexion
│   ├── register.php          # Inscription
│   ├── confirm.php           # Confirmation email
│   ├── dashboard.php         # Espace membre
│   ├── my_stories.php        # Gestion des histoires (auteurs)
│   ├── create_story.php      # Création d'histoire
│   ├── edit_story.php        # Modification d'histoire
│   ├── read_story.php        # Lecture d'une histoire
│   ├── delete_story.php      # Suppression d'histoire
│   ├── logout.php            # Déconnexion
│   ├── templates/            # Templates réutilisables
│   │   ├── header.php        # En-tête du site
│   │   └── footer.php        # Pied de page
│   └── assets/               # Ressources statiques
│       ├── css/
│       └── logo_chrysalide.png
└── src/                      # Code source backend
    ├── Classes/              # Classes PHP
    │   ├── Database.php      # Connexion PDO
    │   ├── EmailService.php  # Envoi d'emails
    │   └── PHPMailer/        # Bibliothèque PHPMailer
    ├── config/               # Configuration
    │   ├── app.php           # Config générale
    │   ├── database.ini      # Config BDD
    │   └── mail.ini          # Config SMTP
    └── i18n.php              # Système de traduction
```

---

## 🗄️ Base de données

### Tables principales :

#### `users`
- `id` (PK)
- `username`
- `email` (unique)
- `password_hash`
- `role` (reader | author)
- `is_confirmed`
- `confirmation_token`
- `confirmed_at`
- `created_at`
- `updated_at`

#### `stories`
- `id` (PK)
- `author_id` (FK → users.id)
- `title`
- `summary`
- `content`
- `is_published`
- `published_at`
- `created_at`
- `updated_at`

---

## ⚙️ Installation

### 1. Cloner le projet
```bash
git clone https://github.com/votre-username/Projet-Chrysalide-ProgServ2.git
cd Projet-Chrysalide-ProgServ2
```

### 2. Configurer la base de données

1. Créer une base de données MySQL
2. Importer le schéma SQL
3. Copier `src/config/database.ini.example` en `database.ini`
4. Remplir avec vos identifiants

### 3. Configurer les emails

1. Copier `src/config/mail.ini.example` en `mail.ini`
2. Remplir avec vos identifiants SMTP

### 4. Développement local

**Avec MAMP/XAMPP** :
- Placer le projet dans `htdocs/`
- Accéder via `http://localhost/Projet-Chrysalide-ProgServ2/public/`

**Avec Mailpit (pour tester les emails)** :
- Télécharger Mailpit
- Lancer : `./mailpit`
- Interface : http://localhost:8025

---

## 🚀 Déploiement

1. **Uploader** les fichiers sur le serveur via FTP/SFTP
2. **Configurer** `mail.ini` avec les vrais identifiants SMTP
3. **Configurer** `database.ini` avec les identifiants de production
4. **Tester** l'inscription et l'envoi d'emails

---

## 🌍 Multilingue

Le site est disponible en :
- 🇫🇷 Français
- 🇬🇧 Anglais

Le changement de langue se fait via les boutons en haut à droite.

---

## 🔐 Sécurité

- ✅ Mots de passe hashés avec `password_hash()` (bcrypt)
- ✅ Requêtes préparées (protection SQL injection)
- ✅ Échappement HTML avec `htmlspecialchars()`
- ✅ Sessions sécurisées
- ✅ Vérification des rôles et autorisations
- ✅ Confirmation par email obligatoire

---

## 👤 Autrices

**Noms** : Lilou et Aissya    
**Cours** : ProgServ2 - HEIG-VD  
**Année** : 2024-2025

---

## 📝 Licence

Projet académique réalisé dans le cadre du cours ProgServ2 à la HEIG-VD.