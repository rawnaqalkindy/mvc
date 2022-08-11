# evo
Expansion on the mothercode logic

## Original functionalities:
1. Authentication
2. Restriction if user not authenticated
3. Basic CRUD operations of a module
4. Fancy Error Display (display a 404, 403 or 500 message and a Request ID to submit to the Administrator)
5. Middleware checking authorization

## In Process:
1. Error display should consider whether user is logged in or not
2. Use alternate between bootstrap and tailwind display

## Pending:
1. Create different namespaces for different roles
2. Access HASHING_SECRET_KEY and SMTP_ variables from .env

## Minor features
1. Create a light/dark theme
2. Create small templates such as confirmation modals, forms, error display etc
3. JE, bado kuna some OG PHP errors occurring despite the logs ???

## Installing the framework
1. Pull the repo from https://github.com/jeankyle/mvc
2. Create a database using phpmyadmin
3. Import /evo.sql (for the updated SQL DB); will be located at /Core/System/Files/Sql/evo_initial.sql in the future
4. Duplicate .env.example to create .env, and make changes according to how you named your database
5. Open your project in an IDE, preferably PHPStorm
6. Run composer install in your IDE's terminal to create the vendor folder
   1. Create a virtual host for the project in C:\xampp\apache\conf\extra\httpd-vhosts.conf:
      <VirtualHost *:80>
          DocumentRoot "C:/xampp/htdocs/evo"
          ServerName evo.dev
      </VirtualHost>
7. Define your URL in C:\Windows\System32\drivers\etc\hosts:
       127.0.0.1       sample.mvc
8. Restart Apache and MySQL
9. Open your browser, and run evo.dev
10. Login using the following credentials:
       Email: adam@example.com
       Password: password123
11. Et voila!
# mvc
