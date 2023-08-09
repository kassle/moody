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

    private function getUserByField(string $field, string $value) : array {
        $result = $this->rest->request("core_user_get_users_by_field", array("field" => $field, "values" => array($value)));

        if (is_array($result)) {
            return $result;
        } else {
            return array();
        }
    }

    public function updateUser(string $id, string $password, string $email, string $firstname, string $lastname, string $city) : mixed {
        $changes = array();
        
        if ($this->isStringValid($password)) {
            $changes["password"] = $password;
        }

        if ($this->isStringValid($email)) {
            $changes["email"] = $email;
        }

        if ($this->isStringValid($firstname)) {
            $changes["firstname"] = $firstname;
        }

        if ($this->isStringValid($lastname)) {
            $changes["lastname"] = $lastname;
        }

        if ($this->isStringValid($city)) {
            $changes["city"] = $city;
        }

        if (sizeof($changes) > 0) {
            $changes["id"] = $id;
            $result = $this->rest->request("core_user_update_users", array("users" => array($changes)), MoodleRest::METHOD_POST);
        } else {
            $result = array();
        }
        
        return $result;
    }

    private function isStringValid(string $str) {
        return (strlen(trim($str)) > 0);
    }

    public function getEnroledUsersByCourseId(string $courseId) : array {
        $result = $this->rest->request("core_enrol_get_enrolled_users", array("courseid" => $courseId));

        if (is_array($result)) {
            return $result;
        } else {
            return array();
        }
    }

    public function enrolUserToCourse(string $courseId, string $userId, string $roleId) : mixed {
        $result = $this->rest->request("enrol_manual_enrol_users",
            array("enrolments" => array(array(
                "courseid" => $courseId,
                "userid" => $userId,
                "roleid" => $roleId
            ))), MoodleRest::METHOD_POST);
        return $result;
    }

    public function unEnrolUserFromCourse(string $courseId, string $userId) : mixed {
        $result = $this->rest->request("enrol_manual_unenrol_users",
            array("enrolments" => array(array(
                "courseid" => $courseId,
                "userid" => $userId
            ))), MoodleRest::METHOD_POST);
        return $result;
    }
}