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
