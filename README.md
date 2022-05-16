# ToDoList_new

[![Codacy Badge](https://api.codacy.com/project/badge/Grade/21ad8a15c68f4ff4a9ef3e4ec971f6d6)](https://app.codacy.com/gh/olpok/ToDoList_new?utm_source=github.com&utm_medium=referral&utm_content=olpok/ToDoList_new&utm_campaign=Badge_Grade_Settings)

ToDoAndCo

About the project

Requirements

PHP 8.0.13 
Symfony 6.0 
MySQL 5.7.36 
Composer 2.2.6

Tested with phpunit

Metrics with Blackfire

1. Clone the repository: https://github.com/olpok/ToDoList_new.git
2. Install dependencies: composer install
3. Create a .env.local file at the root of the project
4. Copy .env code and past in .env.local
5. Modify the line DATABASE_URL= with your login/password and your database name.
6. Create the database: php bin / console doctrine: database: create
7. Run first php bin/console make:migration then run php bin/console doctrine:migrations:migrate
8. Run php bin/console doctrine:fixtures:load
9. Start the internal server: php bin / console server: start

Enjoy!
