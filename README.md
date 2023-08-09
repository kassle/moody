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

## REQUIREMENT
### MOODLE ACCESS TOKEN AND FUNCTION
Need to create token in Moodle Site Administration and the following function:
- core_course_create_courses
- core_course_delete_courses
- core_course_update_courses
- core_enrol_get_enrolled_users
- core_user_get_users_by_field
- core_user_update_users
- enrol_manual_enrol_users
- enrol_manual_unenrol_users

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

```php
$result = $app->getUserById("1234");
$result = $app->getUserByUsername("batman");
$result = $app->getUserByEmail("batman@gotham.com");
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
- data is not empty if update success
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

```php
$result = $app->updateUser("1234", "", "", "", "", "gotham-city", "dc");
```

### CREATE COURSE
Param:
- courseid (required)
- shortname (put "" if no change)
- fullname (put "" if no change)
- categoryId (put -1 if no change)
- summary (put "" if no change)
- startDate (put `new DateTime(date(DATE_RFC2822, 0))` if no change)
- endDate (put `new DateTime(date(DATE_RFC2822, 0))` if no change)

Result: array with following key
- data is not empty if create success include the created **courseid**
- error is not empty when there is an error

```
"data" => [
        "code" => 200,
        "courseid" => course-id (str),
        "message" => "success"
    ],
"error" => [
        "code" => error-code (int)
        "message" => error message (str)
    ]
```

```php
$result = $app->createCourse(
    "Orizuru",
    "Origami Crane or Paper Crane",
    13,
    "Folding paper to shape a crane",
    new DateTime(),
    new DateTime("2024-08-17 23:59:59"));
```

### UPDATE COURSE
Param:
- shortname
- fullname
- categoryId
- summary
- startDate
- endDate

Result: array with following key
- data is not empty if create success include the created **courseid**
- error is not empty when there is an error

```
"data" => [
        "code" => 200,
        "courseid" => course-id (str),
        "message" => "success"
    ],
"error" => [
        "code" => error-code (int)
        "message" => error message (str)
    ]
```

### DELETE COURSE
Param: courseid

Result: array with following key
- data is not empty if delete success
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

```php
$result = $app->deleteCourse("111");
```

### GET COURSE'S ENROLLED USERS
Param: courseid

Result: array with following key
- data is empty if course not found
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

```php
$result = $app->getEnroledUsersByCourseId("1234");
```

Notes: Course with no enrolled users will return error not found (code: 404)

### ENROL USER TO COURSE
Param:
- courseid (required),
- userid (required),
- roleid (required, default = student(11)), see const value in Contract class

Result: array with following key
- data is not empty if enrol success
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

```php
$result = $app->enrolUserToCourse("111", "1234", Contract::ROLE_ID_STUDENT);
```

### UNENROL USER FROM COURSE
Param:
- courseid (required),
- userid (required)

Result: array with following key
- data is not empty if unenrol success
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

```php
$result = $app->unEnrolUserFromCourse("111", "1234");
```
