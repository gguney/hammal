# ModelCourier - DatabaseModeller for Laravel

Get's databases table information

### Requirements

- ModelCourier works with PHP 5.6 or above.
- ModelCourier only works with PostgreSQL and MySQL.

### Installation

```bash
$ composer require gguney/model-courier
```

### Usage
Add package's service provider to your cofig/app.php

```php
...
        ModelCourier\ModelCourierProvider::class,
...
```

Then write this line on cmd.
```bash
$ php artisan vendor:publish
```

It will copy 2 migrations to your migrations folder. Articles and Categories.
And 1 Aricles.php to app/Http/DataModels/
And 1 Aricle.php to app/Http/Models/

### Author

Gökhan Güney - <gokhanguneygg@gmail.com><br />

### License

Konnex is licensed under the MIT License - see the `LICENSE` file for details
