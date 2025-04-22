To Run:
Install Composer
composer install
cp .env.example .env
edit .env
php artisan key:generate
php artisan migrate:fresh
php artisan db:seed
php artisan passport:client --personal







<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## How to use
<p>Install Composer</p>
<p>Then Run</p>
<pre>composer install</pre>
<pre>cp .env.example .env</pre>
<p>Then edit .env file to match your database setting</p>
<pre>php artisan key:generate</pre>
<pre>php artisan migrate:fresh</pre>
<pre>php artisan db:seed</pre>
<pre>php artisan passport:client --personal</pre> 