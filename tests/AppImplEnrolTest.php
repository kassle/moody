<?php declare(strict_types=1);

require_once "include/MoodleRestMock.php";

use Loncat\Moody\AppImpl;
use Loncat\Moody\Contract;

class AppImplEnrolTest extends MoodleRestMock {
    function testGetEnrolUserShouldReturnUserList() {
        $courseId = "7744";
        $response = array(
            array(
                "id" => "id-1",
                "username" => "number-1",
                "email" => "mail-1",
                "roles" => array("roleid" => 10)
            ),
            array(
                "id" => "id-2",
                "username" => "number-2",
                "email" => "mail-2",
                "roles" => array("roleid" => 20)
            ),
            array(
                "id" => "id-3",
                "username" => "number-3",
                "email" => "mail-3",
                "roles" => array("roleid" => 30)
            )
        );

        $rest = $this->createMoodleRestMock();
        $rest->expects($this->once())
            ->method("request")
            ->with("core_enrol_get_enrolled_users", array("courseid" => $courseId))
            ->willReturn($response);

        $app = new AppImpl($rest);
        $result = $app->getEnroledUsersByCourseId($courseId);

        $this->assertEquals(0, sizeof($result["error"]));
        $this->assertEquals(3, sizeof($result["data"]));

        $this->assertEquals($response[0]["id"], $result["data"][0]["userid"]);
        $this->assertEquals($response[0]["username"], $result["data"][0]["username"]);
        $this->assertEquals($response[0]["email"], $result["data"][0]["email"]);
        $this->assertEquals($response[0]["roles"][0]["roleid"], $result["data"][0]["roleid"]);

        $this->assertEquals($response[1]["id"], $result["data"][1]["userid"]);
        $this->assertEquals($response[1]["username"], $result["data"][1]["username"]);
        $this->assertEquals($response[1]["email"], $result["data"][1]["email"]);
        $this->assertEquals($response[1]["roles"][0]["roleid"], $result["data"][1]["roleid"]);

        $this->assertEquals($response[2]["id"], $result["data"][2]["userid"]);
        $this->assertEquals($response[2]["username"], $result["data"][2]["username"]);
        $this->assertEquals($response[2]["email"], $result["data"][2]["email"]);
        $this->assertEquals($response[2]["roles"][0]["roleid"], $result["data"][2]["roleid"]);
    }

    public function testGetEnrolUserShouldReturnError400WhenException() {
        $rest = $this->createMoodleRestMockForRequestThatThrowException();
        
        $app = new AppImpl($rest);
        $result = $app->getEnroledUsersByCourseId("any");

        $this->assertEquals(0, sizeof($result["data"]));
        $this->assertEquals(400, $result["error"]["code"]);
    }

    public function testGetEnrolUserShouldReturnError500WhenResponseError() {
        $response = array(
            "errorcode" => "exception",
            "message" => "access denied"
        );
        $rest = $this->createMoodleRestMock();
        $rest->expects($this->once())
            ->method("request")
            ->willReturn($response);
        
        $app = new AppImpl($rest);
        $result = $app->getEnroledUsersByCourseId("any");

        $this->assertEquals(0, sizeof($result["data"]));
        $this->assertEquals(500, $result["error"]["code"]);
        $this->assertEquals($response["message"], $result["error"]["message"]);
    }

    public function testEnrolUserToCourseShouldSuccess() {
        $courseId = "9876";
        $userId = "6412";
        $roleId = Contract::ROLE_ID_TEACHER;

        $rest = $this->createMoodleRestMock();
        $rest->expects($this->once())
            ->method("request")
            ->with("enrol_manual_enrol_users", array("enrolments" => array(array(
                "courseid" => $courseId,
                "userid" => $userId,
                "roleid" => $roleId
            ))), MoodleRest::METHOD_POST)
            ->willReturn(array());
        
        $app = new AppImpl($rest);
        $result = $app->enrolUserToCourse($courseId, $userId, $roleId);

        $this->assertEquals(0, sizeof($result["error"]));
        $this->assertEquals(200, $result["data"]["code"]);
    }

    public function testEnrolUserToCourseShouldReturnError400WhenException() {
        $rest = $this->createMoodleRestMockForRequestThatThrowException();
        
        $app = new AppImpl($rest);
        $result = $app->enrolUserToCourse("courseid", "userid");

        $this->assertEquals(0, sizeof($result["data"]));
        $this->assertEquals(400, $result["error"]["code"]);
    }

    public function testEnrolUserToCourseShouldReturnError500WhenResponseError() {
        $response = array(
            "errorcode" => "exception",
            "message" => "access denied"
        );
        $rest = $this->createMoodleRestMock();
        $rest->expects($this->once())
            ->method("request")
            ->willReturn($response);
        
        $app = new AppImpl($rest);
        $result = $app->enrolUserToCourse("courseid", "userid", Contract::ROLE_ID_TEACHER);

        $this->assertEquals(0, sizeof($result["data"]));
        $this->assertEquals(500, $result["error"]["code"]);
        $this->assertEquals($response["message"], $result["error"]["message"]);
    }

    public function testUnEnrolUserToCourseShouldSuccess() {
        $courseId = "9876";
        $userId = "6412";
        $rest = $this->createMoodleRestMock();
        $rest->expects($this->once())
            ->method("request")
            ->with("enrol_manual_unenrol_users", array("enrolments" => array(array(
                "courseid" => $courseId,
                "userid" => $userId,
            ))), MoodleRest::METHOD_POST)
            ->willReturn(array());
        
        $app = new AppImpl($rest);
        $result = $app->unEnrolUserFromCourse($courseId, $userId);

        $this->assertEquals(0, sizeof($result["error"]));
        $this->assertEquals(200, $result["data"]["code"]);
    }

    public function testUnEnrolUserToCourseShouldReturnError400WhenException() {
        $rest = $this->createMoodleRestMockForRequestThatThrowException();
        
        $app = new AppImpl($rest);
        $result = $app->unEnrolUserFromCourse("courseid", "userid");

        $this->assertEquals(0, sizeof($result["data"]));
        $this->assertEquals(400, $result["error"]["code"]);
    }

    public function testUnEnrolUserToCourseShouldReturnError500WhenResponseError() {
        $response = array(
            "errorcode" => "exception",
            "message" => "access denied"
        );
        $rest = $this->createMoodleRestMock();
        $rest->expects($this->once())
            ->method("request")
            ->willReturn($response);
        
        $app = new AppImpl($rest);
        $result = $app->unEnrolUserFromCourse("courseid", "userid");

        $this->assertEquals(0, sizeof($result["data"]));
        $this->assertEquals(500, $result["error"]["code"]);
        $this->assertEquals($response["message"], $result["error"]["message"]);
    }
}