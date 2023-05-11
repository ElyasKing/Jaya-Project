# Projet JAYA
Ce projet est une application de gestion et suivi de soutenances pour les étudiants, les enseignants et les administrateurs.

## Fonctionnalités
* Gestion des comptes utilisateurs avec différents profils (Administrateur, Responsable UE, Scolarité, Tuteur Universitaire, Étudiant, Invité, Enseignant Invité, Professionnel Invité)
* Planification des soutenances et gestion des plannings
* Suivi des soutenances et récapitulatif
* Gestion des invités (pour la Scolarité)
* Possibilité de changer de profil pour les utilisateurs ayant plusieurs rôles
* Notifications pour les événements importants

## Structure du projet
Le projet suit une structure de fichiers organisée de manière claire et logique :

- index.php : La page d'accueil du projet.
- stylizer.php : Le fichier de stylisation pour l'ensemble du projet. Toutes les autres pages doivent utiliser ce fichier pour garantir une apparence cohérente.
- home/ : Contient les différentes sections de l'application, telles que la gestion des comptes, la planification des soutenances et la gestion des invités.
- images/ : Contient toutes les images utilisées dans le projet, telles que les icônes et les logos.
- css/ : Contient tous les fichiers CSS du projet pour une meilleure organisation et une séparation claire du style et du contenu.
- js/ : Contient tous les fichiers JavaScript pour ajouter de l'interactivité et des fonctionnalités supplémentaires aux pages.

## Comment démarrer
Pour commencer à utiliser l'application, clonez ce dépôt et placez-le dans le répertoire de votre serveur web. Assurez-vous d'avoir une base de données MySQL configurée avec les tables nécessaires pour stocker les informations utilisateur et les données relatives aux soutenances.

Vous trouverez les fichiers jayaDatabaseDataset.sql et jayaDatabaseStructure.sql dans le dépôt. Utilisez-les pour configurer la structure de votre base de données et importer un ensemble de données initiales.

Naviguez ensuite vers index.php dans votre navigateur et connectez-vous en utilisant vos identifiants.

## Licence
Ce projet est sous licence MIT. Consultez le fichier LICENSE pour plus d'informations.