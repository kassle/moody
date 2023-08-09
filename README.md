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

## API
### GET USER BY USERID/USERNAME/EMAIL
Params: String of userId or username or email address
Result: array with following key
- data is empty if user not found
- error is not empty when there is an error

```
"data" => [
        "userid" => userid (str),
        "username" => username (str),
        "email" => email (str),
        "firstname" => firstname (str),
        "lastname" => lastname (str),
        "city" => city (str),
        "country" => country (str)
    ],
"error" => [
        "code" => error-code (int)
        "message" => error message (str)
    ]
```

### UPDATE USER DATA
Params:
- userid (required),
- password (put "" if no change)
- email (put "" if no change)
- firstname (put "" if no change)
- lastname (put "" if no change)
- city (put "" if no change)
- country (put "" if no change)
Result: array with following key
- data is empty if update success
- error is not empty when there is an error

```
"data" => [
        "code" => 200,
        "message" => "success"
    ],
"error" => [
        "code" => error-code (int)
        "message" => error message (str)
    ]
```

### GET COURSE'S ENROLLED USERS
Param: courseid
Result: array with following key
- data is empty if user not found
- error is not empty when there is an error

```
"data" => [
        [
            "userid" => userid (str),
            "username" => username (str),
            "email" => email (str),
            "roleid" => roleid (int)
        ],
        [
            "userid" => userid (str),
            "username" => username (str),
            "email" => email (str),
            "roleid" => roleid (int)
        ],
        ...
    ],
"error" => [
        "code" => error-code (int)
        "message" => error message (str)
    ]
```