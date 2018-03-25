<p align="center"><img src="https://laravel.com/assets/img/components/logo-laravel.svg"></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/d/total.svg" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/v/stable.svg" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/license.svg" alt="License"></a>
</p>

## How to setup
Run this steps in the project folder;
1. php composer.phar update
2. make a copy of .env.example and name it as .env
3. update database credentials in the .env
4. update value of APP_URL and APP_EMAIL
5. update values of mail config
6. create your local database and name it as raffles
7. run these commands
  * php artisan migrate
  * php artisan db:seed
  * composer update
  * php artisan vendor:publish --tag=ckeditor-ass
  * npm install

  to compile the assets, run this
  * **npm run production**


#### Notes:
* make sure you have nodejs and npm installed
* Default username/password: admin@default.com / adm1n

