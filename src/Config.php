<?php declare(strict_types=1);

namespace Loncat\Moody;

interface Config {
    public function getMoodleBaseUrl() : string;
}