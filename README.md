[![Codacy Badge](https://app.codacy.com/project/badge/Grade/8e2cbee22dad49e8983645203eeea229)](https://www.codacy.com/gh/olpok/ToDoList_new/dashboard?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=olpok/ToDoList_new&amp;utm_campaign=Badge_Grade)

# ToDoList_new

ToDoAndCo

About the project

Requirements

PHP 8.0.13 
Symfony 6.0 
MySQL 5.7.36 
Composer 2.2.6

Tested with phpunit

Metrics with Blackfire

1. Clone the repository : https://github.com/olpok/ToDoList_new.git  
2. Install dependencies - `composer install`   
3. Create a `.env.local` file at the root of the project   
4. Copy `.env` code and past in `.env.local`   
5. Modify the line `DATABASE_URL=` with your login/password and your database name.   
6. Create the database `php bin/console doctrine:database:create`   
7. Run first `php bin/console make:migration` then run `php bin/console doctrine:migrations:migrate`   
8. Run `php bin/console doctrine:fixtures:load`   
9. Start the internal server `php bin/console server:start`

Enjoy!
