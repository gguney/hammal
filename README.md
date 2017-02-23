# Hammal - DatabaseModeller for Laravel

Get's databases table information

### Requirements

- Hammal works with PHP 5.6 or above.
- Hammal only works with PostgreSQL and MySQL.

### Installation

```bash
$ composer require gguney/hammal
```

### Preparation
Add package's service provider to your config/app.php

```php
...
        Hammal\HammalServiceProvider::class,
...
```

Then write this line on cmd.
```bash
$ php artisan vendor:publish
```

### Usage

```bash
$ php artisan make:dataModel YourModelName --m --fill
```
YourModelName variable is the name of your model.
--m (optional) option also creates the Model. 
--fill (optional) option is to fill the field variable arrays of the DataModel from Database table.

### Author

Gökhan Güney - <gokhanguneygg@gmail.com><br />

### License

Konnex is licensed under the MIT License - see the `LICENSE` file for details
