<?php declare(strict_types=1);

use Loncat\Moody\AppImpl;
use PHPUnit\Framework\TestCase;

class AppImplTest extends TestCase {
    public function testGetUserByIdShouldCallMoodleRestWithCorrectFunctionAndParameters() {
        $response = $this->createUserResponse();
        $id = $response["id"];

        $rest = $this->createMoodleRestMockForRequestGetUserByField("id", $id, array($response));

        $app = new AppImpl($rest);
        $result = $app->getUserById($id);

        $this->assertEquals($response["id"], $result["data"]["userid"]);
        $this->assertEquals($response["username"], $result["data"]["username"]);
        $this->assertEquals($response["email"], $result["data"]["email"]);
        $this->assertEquals($response["firstname"], $result["data"]["firstname"]);
        $this->assertEquals($response["lastname"], $result["data"]["lastname"]);
        $this->assertEquals($response["city"], $result["data"]["city"]);
        $this->assertEquals($response["country"], $result["data"]["country"]);
        $this->assertEquals(0, sizeof($result["error"]));
    }

    public function testGetUserByIdShouldReturnError400WhenExceptionHappen() {
        $rest = $this->createMoodleRestMockForRequestThatThrowException();
        
        $app = new AppImpl($rest);
        $result = $app->getUserById("any");

        $this->assertEquals(0, sizeof($result["data"]));
        $this->assertEquals(400, $result["error"]["code"]);
    }

    public function testGetUserByIdShouldReturnError404WhenResponseIsEmpty() {
        $id = "8192";
        $rest = $this->createMoodleRestMockForRequestGetUserByField("id", $id, array());

        $app = new AppImpl($rest);
        $result = $app->getUserById($id);

        $this->assertEquals(0, sizeof($result["data"]));
        $this->assertEquals(404, $result["error"]["code"]);
    }
    
    public function testGetUserByIdShouldReturnError500WhenResponseIsError() {
        $id = "8192";
        $message = "server error";
        $response = array(
            "errorcode" => "exception",
            "message" => $message
        );
        $rest = $this->createMoodleRestMockForRequestGetUserByField("id", $id, $response);

        $app = new AppImpl($rest);
        $result = $app->getUserById($id);

        $this->assertEquals(0, sizeof($result["data"]));
        $this->assertEquals(500, $result["error"]["code"]);
        $this->assertEquals($message, $result["error"]["message"]);
    }

    public function testGetUserByUsernameShouldCallMoodleRestWithCorrectFunctionAndParameters() {
        $response = $this->createUserResponse();
        $user = $response["username"];

        $rest = $this->createMoodleRestMockForRequestGetUserByField("username", $user, array($response));

        $app = new AppImpl($rest);
        $result = $app->getUserByUsername($user);

        $this->assertEquals($response["id"], $result["data"]["userid"]);
        $this->assertEquals($response["username"], $result["data"]["username"]);
        $this->assertEquals($response["email"], $result["data"]["email"]);
        $this->assertEquals($response["firstname"], $result["data"]["firstname"]);
        $this->assertEquals($response["lastname"], $result["data"]["lastname"]);
        $this->assertEquals($response["city"], $result["data"]["city"]);
        $this->assertEquals($response["country"], $result["data"]["country"]);
        $this->assertEquals(0, sizeof($result["error"]));
    }

    public function testGetUserByEmailShouldCallMoodleRestWithCorrectFunctionAndParameters() {
        $response = $this->createUserResponse();
        $email = $response["email"];

        $rest = $this->createMoodleRestMockForRequestGetUserByField("email", $email, array($response));

        $app = new AppImpl($rest);
        $result = $app->getUserByEmail($email);

        $this->assertEquals($response["id"], $result["data"]["userid"]);
        $this->assertEquals($response["username"], $result["data"]["username"]);
        $this->assertEquals($response["email"], $result["data"]["email"]);
        $this->assertEquals($response["firstname"], $result["data"]["firstname"]);
        $this->assertEquals($response["lastname"], $result["data"]["lastname"]);
        $this->assertEquals($response["city"], $result["data"]["city"]);
        $this->assertEquals($response["country"], $result["data"]["country"]);
        $this->assertEquals(0, sizeof($result["error"]));
    }

    private function createUserResponse() : array {
        return array(
            "id" => "8192",
            "username" => "batman",
            "email" => "batman@gotham.com",
            "firstname" => "bruce",
            "lastname" => "wayne",
            "city" => "gotham",
            "country" => "dc"
        );
    }

    private function createMoodleRestMockForRequestGetUserByField(string $field, string $value, array $result) : MoodleRest {
        $rest = $this->createMoodleRestMock();
        $rest->expects($this->once())
            ->method("request")
            ->with($this->identicalTo("core_user_get_users_by_field"),
                $this->identicalTo(array("field" => $field, "values" => array($value))))
            ->willReturn($result);

        return $rest;
    }

    // public function testUpdateUserShouldReturnSuccess() {
    //     $rest = $this->createMoodleRestMock();
    //     $rest->expect()
    // }

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

    private function createMoodleRestMockForRequestThatThrowException() {
        $rest = $this->createMoodleRestMock();
        $rest->expects($this->once())
            ->method("request")
            ->will($this->throwException(new Exception()));

        return $rest;
    }

    private function createMoodleRestMock() : MoodleRest {
        return $this->createMock(MoodleRest::class);
    }
}