<?php declare(strict_types=1);

namespace Loncat\Moody;

use MoodleRest;

class AppImpl implements App {
    private MoodleRest $rest;

    function __construct(MoodleRest $rest) {
        $this->rest = $rest;
    }

    public function getUserById(string $id): array {
        return $this->getUserByField("id", $id);
    }

    public function getUserByUsername(string $username): array {
        return $this->getUserByField("username", $username);
    }

    public function getUserByEmail(string $email) : array {
        return $this->getUserByField("email", $email);
    }

    private function getUserByField(string $field, string $value) {
        $result = $this->rest->request('core_user_get_users_by_field', array("field" => $field, "values" => array($value)));

        if (is_array($result)) {
            return $result;
        } else {
            return array();
        }
    }
}