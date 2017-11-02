Sa Cuina de na Loky
========================



Made with:
--------------

- Symfony
- Symfony forms
- Doctrine
- DoctrineFixtures Bundle
- phpUnit

- mySQL

- jQuery
- Bootstrap


How to deploy:
--------------

* Download the project

* Use composer to prepare the symfony project, creating the pararmeters.yml and other files.
```
composer install
```
* Check "app/config/parameters.yml" and set your mySQL database parameters.

* Upload Doctrine Schema for the Database
```
php bin/console doctrine:schema:update --force
```
* load fixtures (mock database data)
```
php bin/console doctrine:fixtures:load
```

* launch functional tests with phpunit
```
on root, launch phpunit (you need to install it globally)
```

* start dev server
```
php bin/console server:run
```

it can be accessed on http://127.0.0.1:8000/

Assets:
-------

Images with CC0 Creative Commons, from https://pixabay.com/
