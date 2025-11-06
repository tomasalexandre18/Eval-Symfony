Installation

pour l'installation il faut le client symfony disponible dans les variables d'environment
***[installation](https://symfony.com/download)***

git clone

creer .env
avec
```ini
DEFAULT_URI=http://localhost
APP_ENV=dev
APP_SECRET=
MESSENGER_TRANSPORT_DSN=doctrine://default?auto_setup=0
MAILER_DSN=null://null

DATABASE_URL="mysql://username:password@127.0.0.1:3306/database?serverVersion=10.11.2-MariaDB&charset=utf8mb4"

```

Remplacer DATABASE_URL avec la bonne url

executer

```bash
symfony composer install
symfony console doctrine:database:create
symfony console doctrine:migrations:migrate
symfony console app:create-admin admin@test.com admin
```

cela permet de mettre en place la DB avec un utilisateur admin
email: admin@test.com
mdp: admin

Lancer le server

```bash
symfony serve
```
