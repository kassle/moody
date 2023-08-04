<?php declare(strict_types=1);

use Loncat\Moody\AppImpl;
use PHPUnit\Framework\TestCase;

class AppImplTest extends TestCase {
    public function testGetUserByIdShouldCallMoodleRestWithCorrectFunctionAndParameters() {
        $id = "8192";
        $expected = array("id" => $id);

        $rest = $this->createMoodleRestMockForRequestGetUserByField("id", $id, $expected);

        $app = new AppImpl($rest);
        $result = $app->getUserById($id);

        $this->assertEquals($expected, $result);
    }

    public function testGetUserByUsernameShouldCallMoodleRestWithCorrectFunctionAndParameters() {
        $username = "batman";
        $expected = array("username" => $username);

        $rest = $this->createMoodleRestMockForRequestGetUserByField("username", $username, $expected);

        $app = new AppImpl($rest);
        $result = $app->getUserByUsername($username);

        $this->assertEquals($expected, $result);
    }

    public function testGetUserByEmailShouldCallMoodleRestWithCorrectFunctionAndParameters() {
        $email = "batman@gotham.com";
        $expected = array("email" => $email);

        $rest = $this->createMoodleRestMockForRequestGetUserByField("email", $email, $expected);

        $app = new AppImpl($rest);
        $result = $app->getUserByEmail($email);

        $this->assertEquals($expected, $result);
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

    private function createMoodleRestMock() : MoodleRest {
        return $this->createMock(MoodleRest::class);
    }
}