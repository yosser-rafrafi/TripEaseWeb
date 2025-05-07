<!--
Merci d'utiliser cette template pour votre README. Pour toute amélioration, forkez ce projet et ouvrez une PR ou un issue avec le label "enhancement".
-->

<div align="center">

  <img src="public/assets/images/tripEaseLogo.png" alt="TripEase Logo" width="200" />
  <h1>🌍 TripEase</h1>
  <p>Application web pour planifier vos voyages d'affaires avec simplicité et efficacité.</p>

  <!-- Badges -->

  <p>
    <a href="https://github.com/yosser-rafrafi/TripEaseWeb/graphs/contributors">
      <img src="https://img.shields.io/github/contributors/yosser-rafrafi/TripEaseWeb" alt="Contributors" />
    </a>
    <a href="https://github.com/yosser-rafrafi/TripEaseWeb/commits/main">
      <img src="https://img.shields.io/github/last-commit/yosser-rafrafi/TripEaseWeb" alt="Last Commit" />
    </a>
    <a href="https://github.com/yosser-rafrafi/TripEaseWeb/network/members">
      <img src="https://img.shields.io/github/forks/yosser-rafrafi/TripEaseWeb" alt="Forks" />
    </a>
    <a href="https://github.com/yosser-rafrafi/TripEaseWeb/stargazers">
      <img src="https://img.shields.io/github/stars/yosser-rafrafi/TripEaseWeb" alt="Stars" />
    </a>
    <a href="https://github.com/yosser-rafrafi/TripEaseWeb/issues">
      <img src="https://img.shields.io/github/issues/yosser-rafrafi/TripEaseWeb" alt="Open Issues" />
    </a>
    <a href="https://github.com/yosser-rafrafi/TripEaseWeb/blob/main/LICENSE">
      <img src="https://img.shields.io/github/license/yosser-rafrafi/TripEaseWeb" alt="License" />
    </a>
  </p>

  <h4>
    <a href="#star2-about-the-project">Présentation</a>
    <span> · </span>
    <a href="#toolbox-getting-started">Installation</a>
    <span> · </span>
    <a href="#eyes-usage">Usage</a>
    <span> · </span>
    <a href="#handshake-contact">Contact</a>
  </h4>

</div>

<br />

# \:notebook\_with\_decorative\_cover: Table of Contents

* [⭐️ About the Project](#star2-about-the-project)

  * [📷 Screenshots](#camera-screenshots)
  * [👨‍💻 Tech Stack](#space_invader-tech-stack)
  * [🎯 Features](#dart-features)
  * [🔑 Environment Variables](#key-environment-variables)
* [🧰 Getting Started](#toolbox-getting-started)

  * [‼️ Prerequisites](#bangbang-prerequisites)
  * [⚙️ Installation](#gear-installation)
  * [🏃 Run Locally](#running-run-locally)
* [👀 Usage](#eyes-usage)
* [🛣️ Roadmap](#compass-roadmap)
* [🤝 Contributing](#wave-contributing)

  * [📜 Code of Conduct](#scroll-code-of-conduct)
* [❓ FAQ](#grey_question-faq)
* [⚠️ License](#warning-license)
* [🤝 Contact](#handshake-contact)
* [💎 Acknowledgements](#gem-acknowledgements)

## ⭐️ About the Project

TripEase est une application web complète pour la planification et la gestion des voyages d'affaires :

* Portail **Web** (Symfony/PHP) pour les employés et managers
* Gestion des demandes d'avance de frais et workflow de validation
* Intégration d'un convertisseur de devises pour un suivi précis des dépenses

### 📷 Screenshots

<div align="center">
  <img src="assets/screenshot.png" alt="Dashboard TripEase" width="600" />
</div>

### 👨‍💻 Tech Stack

<details>
<summary>Web Backend</summary>
<ul>
  <li><a href="https://symfony.com/">Symfony 6.x</a></li>
  <li><a href="https://www.php.net/">PHP 8.x</a></li>
</ul>
</details>

<details>
<summary>Web Frontend</summary>
<ul>
  <li><a href="https://twig.symfony.com/">Twig</a></li>
  <li>HTML, CSS, JavaScript</li>
</ul>
</details>

<details>
<summary>Database</summary>
<ul>
  <li><a href="https://www.mysql.com/">MySQL</a> (via WAMP)</li>
</ul>
</details>

<details>
<summary>Dev Tools</summary>
<ul>
  <li><a href="https://getcomposer.org/">Composer</a></li>
  <li><a href="https://git-scm.com/">Git</a></li>
</ul>
</details>

### 🎯 Features

* CRUD des voyages d'affaires
* Sélection d'hôtels et moyens de transport
* Soumission & validation des avances de frais
* Conversion de devises intégrée
* Génération de rapports PDF

### 🔑 Environment Variables

```bash
# Dans .env
DATABASE_URL=mysql://user:pass@127.0.0.1:3306/tripease
```

## 🧰 Getting Started

### ‼️ Prerequisites

* PHP 8.x, Composer
* Symfony CLI (`symfony`)
* Node.js & npm (optionnel)
* MySQL (via WAMP sur Windows)

### ⚙️ Installation

1. Clonez le dépôt :

   ```bash
   git clone https://github.com/yosser-rafrafi/TripEaseWeb.git
   cd TripEaseWeb
   ```
2. Backend Symfony :

   ```bash
   composer install
   cp .env.example .env
   symfony console doctrine:database:create
   symfony console doctrine:migrations:migrate
   symfony server:start
   ```
3. Frontend (si besoin) :

   ```bash
   npm install
   npm run build
   ```

### 🏃 Run Locally

Après installation, accédez à [http://localhost:8000](http://localhost:8000) pour l’interface web.

## 👀 Usage

* **Web** :

  * Employé : `employe@exemple.com` / `employe123`
  * Manager : `manager@exemple.com` / `manager123`

## 🛣️ Roadmap

* [x] Implémenter CRUD voyages
* [x] Gestion des avances de frais
* [ ] Authentification OAuth
* [ ] Notifications par e-mail

## 🤝 Contributing

Contributions sont les bienvenues !

1. Forkez le projet
2. Créez une branche : `git checkout -b feature/ma-fonctionnalite`
3. Commitez : `git commit -m "Ajout de X"`
4. Pushez : `git push origin feature/ma-fonctionnalite`
5. Ouvrez une PR

### 📜 Code of Conduct

Veuillez lire le [Code of Conduct](CODE_OF_CONDUCT.md).

## ❓ FAQ

**Q1 : Puis-je utiliser PostgreSQL ?**
R1 : Oui, modifiez `DATABASE_URL` dans `.env`.

**Q2 : Comment générer un build desktop ?**
R2 : Non applicable pour la version web.

## ⚠️ License

Distribué sous licence MIT. Voir [LICENSE](LICENSE).

## 🤝 Contact

Auteur – **Yosser Rafrafi** – [github.com/yosser-rafrafi](https://github.com/yosser-rafrafi) – [yosser@example.com](mailto:yosser@example.com)

Project Link: [https://github.com/yosser-rafrafi/TripEaseWeb](https://github.com/yosser-rafrafi/TripEaseWeb)

## 💎 Acknowledgements

* [Shields.io](https://shields.io/)
* [Symfony](https://symfony.com/)
* [Awesome README](https://github.com/matiassingers/awesome-readme)
