# moody
the moodle integration

# Development
## build
composer install

## test
composer tests

## Install
### Add repository in composer.json
```
"repositories": [
    {
        "type": "vcs",
        "url": "https://github.com/kassle/moody"
    }
],
```
### Install moody
composer require loncat/moody:dev-main

## Example
```php
<?php declare(strict_types=1);

require_once 'vendor/autoload.php';

use Loncat\Moody\AppFactory;
use Loncat\Moody\Config;

$config = new Config("http://moodle.host.name/webservice/rest/server.php", "moodle.token");
$app = AppFactory::create($config);

$result = $app->getUserByEmail("example@email.com");

var_dump($result);
```