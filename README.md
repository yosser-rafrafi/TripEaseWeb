🌍 TRIPEASE

  

Description

TRIPEASE est une application web et desktop conçue pour faciliter la planification et la gestion des voyages d'affaires. Elle permet aux employés de soumettre des demandes, aux managers de valider et d'organiser les déplacements, et inclut un outil de conversion de devises pour un suivi précis des dépenses.

Table des matières

Installation

Utilisation

Fonctionnalités

Démonstration

Tech Stack

Contribution

Licence

Topics

Installation

1. Cloner le dépôt

git clone https://github.com/<utilisateur>/VoyagePro.git
cd VoyagePro

2. Configuration du backend (Symfony)

# Installer les dépendances PHP
composer install
# Copier et personnaliser l'environnement
cp .env.example .env
# Créer et migrer la base de données
symfony console doctrine:database:create
symfony console doctrine:migrations:migrate
# Lancer le serveur
symfony server:start

3. Configuration du frontend (optionnel)

npm install    # si un frontend JS est présent
npm run build  # compiler les assets

4. Application desktop JavaFX

cd desktop
mvn clean package        # ou mvn javafx:run
java -jar target/VoyagePro.jar

Utilisation

Interface Web : accéder à http://localhost:8000

Employé : employe@exemple.com / employe123

Manager : manager@exemple.com / manager123

Application Desktop : lancer le JAR et suivre les instructions à l’écran.

Fonctionnalités

Gestion des voyages (CRUD)

Sélection d'hôtels

Choix des moyens de transport

Soumission et validation des avances de frais

Conversion de devises

Rapports et export

Démonstration



Pour un aperçu animé, placez workflow.gif dans assets/ et ajoutez :

![Workflow](assets/workflow.gif)

Tech Stack

Backend : Symfony 6.x, PHP 8.x

Base de données : MySQL (WAMP)

Frontend : Twig, HTML, CSS, JavaScript

Desktop : Java 17, JavaFX

Outils : Composer, Git, Maven

Contribution

Forkez le repository

Créez une branche : git checkout -b feature/ma-fonctionnalite

Commitez vos changements : git commit -m "Ajout de <fonctionnalité>"

Pushez : git push origin feature/ma-fonctionnalite

Ouvrez une Pull Request

Licence

Ce projet est sous licence MIT. Voir le fichier LICENSE pour plus de détails.

Topics

Symfony PHP JavaFX MySQL Java Planification-Voyage Esprit-School-of-Engineering































































