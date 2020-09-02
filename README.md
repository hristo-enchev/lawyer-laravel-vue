## About project

- Api-based application for clients and lawyers. 

## Features

- Laravel 7
- Vue + VueRouter + Vuex
- Login, register, email verification and password reset
- Authentication with Sanctum - Sanctum uses Laravel's built-in cookie based session authentication services. This provides the benefits of CSRF protection, session authentication, as well as protects against leakage of the authentication credentials via XSS.
- Bootstrap 4

## Installation

- Clone the project
- Edit `.env` and set your database connection details
- `composer intall`
- `php artisan key:generate`
- `php artisan migrate or php artisan migrate --seed`
- `npm install`
- `npm run dev`
- `php artisan serve`

> **Note:** SANCTUM_STATEFUL_DOMAINS should match with host
