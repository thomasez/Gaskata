Gaskata - Installation
======================

* Set up apache/nginx as you like.
* Create a db user and the database (preferrably Postgres, but anything supported by Doctrine ORM should work.
* git clone this repository into a convenient place.
* cd Gaskata/ (Or if you specified a directory..)
* If you don't have composer installed: 
  * curl -sS https://getcomposer.org/installer | php
* ./composer.phar install
  Here you will be asked questions, as the database name, user and password you created at the first step.
* And now the hard part: Making sure permissions on app/cache and app/logs are properly set. I have used method number 3 on my (Linux)boxes.
  http://symfony.com/doc/current/book/installation.html#checking-symfony-application-configuration-and-setup

