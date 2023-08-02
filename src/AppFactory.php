<?php declare(strict_types=1);

namespace Loncat\Moody;

use Loncat\Moody\Config;

class AppFactory {
    private function __construct() { }
    public static function create(Config $config) : App {
        return new AppImpl($config);
    }
}