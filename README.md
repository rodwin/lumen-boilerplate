[![Build Status](https://travis-ci.org/benjamincrozat/lumen-boilerplate.svg?branch=master)](https://travis-ci.org/benjamincrozat/lumen-boilerplate)
[![Latest Stable Version](https://poser.pugx.org/benjamincrozat/lumen-boilerplate/v/stable)](https://packagist.org/packages/benjamincrozat/lumen-boilerplate)
[![License](https://poser.pugx.org/benjamincrozat/lumen-boilerplate/license)](https://packagist.org/packages/benjamincrozat/lumen-boilerplate)
[![Total Downloads](https://poser.pugx.org/benjamincrozat/lumen-boilerplate/downloads)](https://packagist.org/packages/benjamincrozat/lumen-boilerplate)

# Lumen Boilerplate

Quick start your next Lumen project.

## Why?

Lumen is a very lightweight version of Laravel. Even if Taylor managed to keep a lot of useful things for real world needs, it's still too light to me. So in this project, I added some dependencies and boilerplated some useful things to make them work out of the box.

## Server requirements

- PHP 7.1

You can [read more about the requirements here](https://lumen.laravel.com/docs/5.5/installation#server-requirements).

## Usage

You can run Lumen Boilerplate on [Laravel Valet](https://laravel.com/docs/valet), [Laravel Homestead](https://laravel.com/docs/homestead), [Docker](https://www.docker.com/) or whatever you like.

To get started, create a project via Composer to speed things up.

```bash
composer create-project benjamincrozat/lumen-boilerplate example
```

Once you have set up your `.env` file, run your migrations. You can even seed some fake data for users:

```bash
php artisan migrate --seed
```

Assuming you're using Laravel Valet, the project can immediately be tested at [http://example.test](http://example.dev).

The API also works out of the box with a basic token for authentication. First, get your token:

```bash
mysql -u root

mysql> USE example; SELECT * FROM users WHERE id = 1;

+----+----------------+----------------------+--------------------------------------------------------------+--------------------------------------------------------------+----------------+---------------------+---------------------+
| id | name           | email                | password                                                     | api_token                                                    | remember_token | created_at          | updated_at          |
+----+----------------+----------------------+--------------------------------------------------------------+--------------------------------------------------------------+----------------+---------------------+---------------------+
|  1 | Mr. Kamron Toy | fkoelpin@example.org | $2y$10$ckbnX6iSFDyC4lHUT.ClwOC59zOkqzbv8sfkTIoNbmRRE.RakRq.K | Z1m3r3Xw6ejiSZwKwJxTXQCdcGThp78Crs4HoviKUxoGNkPNN7rbo8IliU5u | U6udLlAqAx     | 2017-11-17 22:46:11 | 2017-11-17 22:46:11 |
+----+----------------+----------------------+--------------------------------------------------------------+--------------------------------------------------------------+----------------+---------------------+---------------------+
```

And make a basic GET request on `http://example.dev/api/v1/user`:

```bash
curl --request GET http://example.dev/api/v1/user?api_token=Z1m3r3Xw6ejiSZwKwJxTXQCdcGThp78Crs4HoviKUxoGNkPNN7rbo8IliU5u

{"data":{"id": 1,"name":"Mr. Kamron Toy","email":"fkoelpin@example.org"}}
```

## License

[WTFPL](http://www.wtfpl.net/about/)
