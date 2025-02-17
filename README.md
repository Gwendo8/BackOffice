I. Commandes pour le projet

1. Création du projet BackOffice :
   symfony new BackOffice --webapp
2. Création des entités avec cette commande :
   php bin/console make:entity Product
3. Liaison avec la base de données :
   Modification du fichier .env :
   DATABASE_URL="mysql://root@127.0.0.1:3306/BackOffice?serverVersion=8.0.32&charset=utf8mb4"
4. Génération des tables :
   php bin/console doctrine:migrations:diff
   php bin/console doctrine:migrations:migrate
5. Création des fixtures :
   composer require --dev doctrine/doctrine-fixtures-bundle
6. Pousser les fixtures dans la base de données :
   php bin/console doctrine:fixtures:load
7. Pour pouvoir effectuer des tests unitaires : 
   composer require --dev phpunit/phpunit
8. Pour éxécuter les tests unitaires : 
   php bin/phpunit
9. Pour importer un fichier csv : 
   php bin/console app:import-products src/csv/products.csv (le chemin d'où est placer le fichier csv)
10. Pour ajouter un client en ligne de commande : 
   php bin/console app:add-client

II. Fonctionnalitées

1. Gestion des utilisateurs 
	•	Ajout d’un onglet “Utilisateurs” (accessible uniquement par les administrateurs).
	•	Liste des utilisateurs (email, rôle, nom, prénom).
	•	Ajout, modification (sauf le mot de passe) et suppression d’un utilisateur.
	•	Sécurisation des accès avec un Voter Symfony pour restreindre les actions selon les rôles.

2. Gestion des produits 
	•	Ajout d’un onglet “Produits” (visible par tous les utilisateurs).
	•	Ajout, modification et suppression des produits (réservé aux administrateurs).
	•	Tri des produits par prix décroissant via une requête personnalisée.
	•	Exportation des produits au format CSV.
	•	Importation de produits via un fichier CSV placé dans le dossier src/csv ou directement à la racine du projet.
	•	Sécurisation des accès avec un Voter Symfony.

3. Gestion des clients 
	•	Ajout d’un onglet “Clients” (visible par les managers et administrateurs).
	•	Lister, ajouter et modifier les clients (admin et manager uniquement).
	•	Ajout de validations sur les champs :
	•	Vérification du format de l’email.
	•	Vérification du nom et prénom.
	•	Vérification de l’email client.
	•	Importation de clients via une commande Symfony (app:add-client).
	•	Ajout de fixtures pour des clients de test.

4. Sécurité et Authentification
	•	Création d’un formulaire de connexion et de création de compte pour sécuriser l’accès à l'application.
	•	Ajout de fixtures pour initialiser des utilisateurs avec les rôles suivants :
	•	ROLE_ADMIN : Administrateur
	•	ROLE_MANAGER : Manager
	•	ROLE_USER : Utilisateur 
	•	Implémentation d’une gestion des rôles pour restreindre les accès aux différentes sections.

5. Interface utilisateur 
	•	Menu de navigation à gauche avec les différentes sections (Utilisateurs, Produits, Clients, Déconnexion).
	•	Design réalisé avec Bootstrap.

6. Tests et Validation
	•	Écriture de tests unitaires pour :
	•	Vérifier un service du projet.
	•	Tester la création d’un utilisateur, produit et client.
	•	Installation et exécution des tests via PHPUnit.
