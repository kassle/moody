<?php declare(strict_types=1);

namespace Loncat\Moody;

use MoodleRest;
use Throwable;

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
        try {
            $result = $this->rest->request("core_user_get_users_by_field", array("field" => $field, "values" => array($value)));

            if (is_array($result) && sizeof($result) > 0) {
                if (array_key_exists("errorcode", $result)) {
                    return array(
                        "data" => [],
                        "error" => [
                            "code" => 500,
                            "message" => $result["message"]
                        ]);
                } else {
                    $data = $result[0];
                    return array("data" => [
                        "userid" => strval($data["id"]),
                        "username" => $data["username"],
                        "email" => $data["email"],
                        "firstname" => $data["firstname"],
                        "lastname" => $data["lastname"],
                        "city" => $data["city"],
                        "country" => $data["country"]
                    ], "error" => []);
                }
            } else {
                return array(
                    "data" => [],
                    "error" => [
                        "code" => 404,
                        "message" => "Not Found"
                    ]
                );
            }
        } catch (Throwable $error) {
            return array(
                "data" => [],
                "error" => [
                    "code" => 400,
                    "message" => $error->getMessage()
                ]);
        }
    }

    public function updateUser(string $id, string $password, string $email, string $firstname, string $lastname, string $city, string $country) : array {
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

        if ($this->isStringValid($country)) {
            $changes["country"] = $country;
        }

        try {
            if (sizeof($changes) > 0) {
                $changes["id"] = $id;
                $result = $this->rest->request(
                    "core_user_update_users",
                    array("users" => array($changes)),
                    MoodleRest::METHOD_POST);
                
                if (is_array($result) && sizeof($result) > 0 && array_key_exists("errorcode", $result)) {
                    return array(
                        "data" => [],
                        "error" => [
                            "code" => 500,
                            "message" => $result["message"]
                        ]);
                } else {
                    return array(
                        "data" => [
                            "code" => 200,
                            "message" => "update user success"
                        ],
                        "error" => []);
                }
            } else {
                return array(
                    "data" => [ ],
                    "error" => [
                        "code" => 400,
                        "message" => "no field is changed, check params"
                    ]);
            }
        } catch (Throwable $error) {
            return array(
                "data" => [],
                "error" => [
                    "code" => 400,
                    "message" => $error->getMessage()
                ]);
        }
    }

    private function isStringValid(string $str) {
        return (strlen(trim($str)) > 0);
    }

    public function getEnroledUsersByCourseId(string $courseId) : array {
        try {
            $result = $this->rest->request("core_enrol_get_enrolled_users", array("courseid" => $courseId));
            if (is_array($result) && sizeof($result) > 0) {
                if (array_key_exists("errorcode", $result)) {
                    return array(
                        "data" => [],
                        "error" => [
                            "code" => 500,
                            "message" => $result["message"]
                        ]);
                } else {
                    $output = array();
                    $index = 0;
                    foreach ($result as $data) {
                        $output[$index] = array(
                            "userid" => strval($data["id"]),
                            "username" => $data["username"],
                            "email" => $data["email"],
                            "roleid" => $data["roles"][0]["roleid"]);
                        $index++;
                    }
                    return $output;
                }
            } else {
                return array(
                    "data" => [],
                    "error" => [
                        "code" => 404,
                        "message" => "Not Found"
                    ]
                );
            }
        } catch (Throwable $error) {
            return array(
                "data" => [],
                "error" => [
                    "code" => 400,
                    "message" => $error->getMessage()
                ]);
        }
        

        if (is_array($result)) {
            return $result;
        } else {
            return array();
        }
    }

    public function enrolUserToCourse(string $courseId, string $userId, string $roleId = Contract::ROLE_ID_STUDENT) : array {
        try {
            $result = $this->rest->request("enrol_manual_enrol_users",
                array("enrolments" => array(array(
                    "courseid" => $courseId,
                    "userid" => $userId,
                    "roleid" => $roleId
                ))), MoodleRest::METHOD_POST);

            if (is_array($result) && sizeof($result) > 0 && array_key_exists("errorcode", $result)) {
                return array(
                    "data" => [],
                    "error" => [
                        "code" => 500,
                        "message" => $result["message"]
                    ]);
            } else {
                return array(
                    "data" => [
                        "code" => 200,
                        "message" => "enrol success"
                    ],
                    "error" => []);
            }
        } catch (Throwable $error) {
            return array(
                "data" => [],
                "error" => [
                    "code" => 400,
                    "message" => $error->getMessage()
                ]);
        }
    }

    public function unEnrolUserFromCourse(string $courseId, string $userId) : array {
        try {
            $result = $this->rest->request("enrol_manual_unenrol_users",
                array("enrolments" => array(array(
                    "courseid" => $courseId,
                    "userid" => $userId
                ))), MoodleRest::METHOD_POST);

            if (is_array($result) && sizeof($result) > 0 && array_key_exists("errorcode", $result)) {
                return array(
                    "data" => [],
                    "error" => [
                        "code" => 500,
                        "message" => $result["message"]
                    ]);
            } else {
                return array(
                    "data" => [
                        "code" => 200,
                        "message" => "unenrol success"
                    ],
                    "error" => []);
            }
        } catch (Throwable $error) {
            return array(
                "data" => [],
                "error" => [
                    "code" => 400,
                    "message" => $error->getMessage()
                ]);
        }
    }
}