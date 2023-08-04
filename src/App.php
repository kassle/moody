<?php declare(strict_types=1);

namespace Loncat\Moody;

interface App {
    public function getUserById(string $id) : array;
    public function getUserByUsername(string $username) : array;
    public function getUserByEmail(string $email) : array;
}