<?php declare(strict_types=1);

namespace Loncat\Moody;

class AppImpl implements App {
    private Config $config;

    function __construct(Config $config) {
        $this->config = $config;
    }

    public function getAllUsers() : array {
        return array();
    }
}