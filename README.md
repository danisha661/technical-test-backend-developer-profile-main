[![GitHub stars](https://img.shields.io/github/stars/vshymanskyy/StandWithUkraine.svg)](https://github.com/vshymanskyy/StandWithUkraine/stargazers)
[![Stand With Ukraine](https://raw.githubusercontent.com/vshymanskyy/StandWithUkraine/main/badges/StandWithUkraine.svg)](https://stand-with-ukraine.pp.ua)

# Technical test for the Backend Developer profile

As part of the recruitment process, you are required to complete a small technical test to showcase how you tackle a basic programming task.

[![Stand With Ukraine](https://raw.githubusercontent.com/vshymanskyy/StandWithUkraine/main/banner2-direct.svg)](https://stand-with-ukraine.pp.ua)

## Stack

- [Symfony](https://symfony.com/)
- [Api Platform](https://api-platform.com/)
- [MySQL](https://dev.mysql.com/doc/)
- [Pico.css](https://picocss.com/)
- [Bootstrap Icons](https://icons.getbootstrap.com/)

## Requirements

- `PHP` 8.0 or later
- `Composer` command (See [Composer Installation](https://getcomposer.org/doc/00-intro.md))
- `Git`

## How to Use

### Installation

```bash
$ git clone git@github.com:Ezar101/technical-test-backend-developer-profile.git
$ cd /path/to/technical-test-backend-developer-profile/

# Env variables
cp .env .env.local

# Edit .env.local
vim .env.local

# Install composant
$ composer install
$ php bin/console assets:install --symlink
$ php bin/console cache:clear
$ php bin/console doctrine:database:create --if-not-exists
$ php bin/console doctrine:migrations:migrate --no-interaction

#Insert datas fixtures
$ php bin/console doctrine:fixture:load

# Generate the SSL keys
$ php bin/console lexik:jwt:generate-keypair

# Run Server
$ php -S localhost:8000 -t public # Based to php server
$ symfony server:start # Based to symfony server 
```
### Users to Log in

* john@admin.fr / admin
* john@user.fr / user

### Useful commands

```bash
# Run symfony develop server
$ symfony console server:start

# Composer (e.g. composer update)
$ composer update

# SF console
$ php bin/console

# Generate the SSL keys
$ php bin/console lexik:jwt:generate-keypair --skip-if-exists # will silently do nothing if keys already exist.
$ php bin/console lexik:jwt:generate-keypair --overwrite # will overwrite your keys if they already exist.
```
