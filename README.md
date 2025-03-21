<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

# 📌 Code Tacker

## 📖 Description

**CodeTacker** est une application innovante permettant aux développeurs de suivre le temps qu'ils consacrent à leurs projets de programmation. L'outil combine un site web et une extension **Visual Studio Code** pour automatiser le suivi du temps et générer des rapports exploitables.

---

## 🎯 Objectifs

- ⏳ **Suivi Précis** : Enregistrer le temps passé sur chaque projet.
- 📊 **Analyse des Performances** : Génération de rapports et graphiques d'activité.
- 🔄 **Automatisation** : Collecte des données en temps réel.
- 🚀 **Productivité** : Aider les développeurs à mieux gérer leur temps.

---

## 🛠️ Fonctionnalités

### 🌐 **Site Web**

| Fonctionnalité                  | Description                                          |
| ------------------------------- | ---------------------------------------------------- |
| 👤 **Gestion des Utilisateurs** | Inscription, connexion et gestion des profils.       |
| 📊 **Tableau de Bord**          | Vue globale des projets avec graphiques interactifs. |
| 📝 **Gestion des Projets**      | Création, modification et suppression de projets.    |
| 📂 **Exportation de Rapports**  | Génération de rapports en PDF et Excel.              |

### 🔌 **Extension VS Code**

| Fonctionnalité             | Description                                              |
| -------------------------- | -------------------------------------------------------- |
| ⏱️ **Suivi Automatique**   | Enregistrement précis du temps actif sur chaque fichier. |
| 📂 **Gestion des Projets** | Association des fichiers à des projets définis.          |
| 🔄 **Synchronisation**     | Transmission des données vers le site web en temps réel. |
| 🚀 **Mode Hors Ligne**     | Continuité de suivi même sans connexion.                 |

### ⚙️ **Administration**

- 📊 **Surveillance des activités** : Suivi des logs et des données utilisateur.
- 🔄 **Gestion des données** : Réinitialisation des informations en cas de besoin.

---

## 🏗️ Stack Technologique

| Composant               | Technologie                   |
| ----------------------- | ----------------------------- |
| 🖥️ **Frontend**        | HTML, CSS, JavaScript (React) |
| ⚙️ **Backend**          | PHP avec Laravel              |
| 🗄️ **Base de Données** | MySQL                         |
| 🖥️ **Extension**       | TypeScript pour VS Code       |
| 📊 **Graphiques**       | Chart.js / D3.js              |
| 🔗 **API**              | RESTful                       |

---

## 🔒 Sécurité

- 🔐 **Chiffrement HTTPS** : Protection des communications.
- 🔑 **Authentification JWT** : Sécurisation des connexions utilisateur.
- 🛡️ **Protection XSS, CSRF, SQL Injection** : Renforcement de la sécurité des données.

---

## ✅ Critères de Réussite

✔️ Le site web et l'extension sont fonctionnels et synchronisés.
✔️ Interface intuitive et ergonomique.
✔️ Rapports exploitables et précis.
✔️ Synchronisation fiable et sans perte de données.

---

## 📂 Installation & Déploiement

1. **Cloner le projet** 🛠️
```sh
 git clone https://github.com/Sala7-dine/CodeTracker.git
 cd CodeTracker
```
2. **Installer les dépendances** 📦
```sh
composer install
npm install
```
3. **Configurer l'environnement** ⚙️
```sh
cp .env.example .env
php artisan key:generate
```
4. **Configurer la base de données** 🗄️
```sh
php artisan migrate --seed
```
5. **Lancer l'application** 🚀
```sh
php artisan serve
```

🚀 **Happy Coding!** 🎉

