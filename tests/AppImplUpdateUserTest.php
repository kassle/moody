<?php declare(strict_types=1);

require_once "include/MoodleRestMock.php";

use Loncat\Moody\AppImpl;

class AppImplUpdateUserTest extends MoodleRestMock {
    public function testUpdateUserShouldReturnSuccess() {
        $updates = array(
            "firstname" => "bruce",
            "lastname" => "wayne",
            "city" => "gotham",
            "id" => "8192"
        );
        $rest = $this->createMoodleRestMock();
        $rest->expects($this->once())
            ->method("request")
            ->with("core_user_update_users", $this->identicalTo(array("users" => array($updates))), MoodleRest::METHOD_POST)
            ->willReturn(array());

        $app = new AppImpl($rest);
        $result = $app->updateUser($updates["id"], "", "", $updates["firstname"], $updates["lastname"], $updates["city"], "");

        $this->assertEquals(0, sizeof($result["error"]));
        $this->assertEquals(200, $result["data"]["code"]);
    }

    public function testUpdateUserShouldReturnError400WhenException() {
        $rest = $this->createMoodleRestMockForRequestThatThrowException();
        
        $app = new AppImpl($rest);
        $result = $app->updateUser("any", "", "", "", "", "", "us");

        $this->assertEquals(0, sizeof($result["data"]));
        $this->assertEquals(400, $result["error"]["code"]);
    }

    public function testUpdateUserShouldReturnError400WhenNoDataChanged() {
        $rest = $this->createMoodleRestMock();
        
        $app = new AppImpl($rest);
        $result = $app->updateUser("any", "", "", "", "", "", "");

        $this->assertEquals(0, sizeof($result["data"]));
        $this->assertEquals(400, $result["error"]["code"]);
    }

    public function testUpdateUserShouldReturnError500WhenResponseError() {
        $response = array(
            "errorcode" => "exception",
            "message" => "access denied"
        );
        $rest = $this->createMoodleRestMock();
        $rest->expects($this->once())
            ->method("request")
            ->willReturn($response);
        
        $app = new AppImpl($rest);
        $result = $app->updateUser("any", "secret", "", "", "", "", "us");

        $this->assertEquals(0, sizeof($result["data"]));
        $this->assertEquals(500, $result["error"]["code"]);
        $this->assertEquals($response["message"], $result["error"]["message"]);
    }
}
