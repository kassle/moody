<?php declare(strict_types=1);

namespace Loncat\Moody;

interface App {
    public function getAllUsers() : array;
}