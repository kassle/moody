<?php declare(strict_types=1);

namespace Loncat\Moody;

use Loncat\Moody\Config;
use MoodleRest;

class AppFactory {
    private function __construct() { }
    public static function create(Config $config) : App {
        $rest = new MoodleRest($config->getMoodleBaseUrl(), $config->getMoodleToken(), MoodleRest::RETURN_ARRAY);
        return new AppImpl($rest);
    }
}