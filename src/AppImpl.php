<?php declare(strict_types=1);

namespace Loncat\Moody;

use MoodleRest;

class AppImpl implements App {
    private Config $config;
    private MoodleRest $rest;

    function __construct(Config $config) {
        $this->config = $config;
        $this->rest = new MoodleRest($config->getMoodleBaseUrl(), $config->getMoodleToken(), MoodleRest::RETURN_ARRAY);
        // $this->rest->setDebug(true);
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