<?php declare(strict_types=1);

require_once "include/MoodleRestMock.php";

use Loncat\Moody\AppImpl;

class AppImplCourseTest extends MoodleRestMock {
    function testCreateCourseShouldReturnSuccess() {
        $id = "9182";
        $course = array(
            "shortname" => "batman",
            "fullname" => "batman arkham knight",
            "categoryid" => 88,
            "summary" => "batman the darknight appears in arkham",
            "startdate" => new DateTime("now"),
            "enddate" => new DateTime("tomorrow")
        );

        $rest = $this->createMoodleRestMock();
        $rest->expects($this->once())
            ->method("request")
            ->with("core_course_create_courses", array("courses" => array(array(
                "shortname" => $course["shortname"],
                "fullname" => $course["fullname"],
                "categoryid" => $course["categoryid"],
                "summary" => $course["summary"],
                "startdate" => $course["startdate"]->getTimestamp(),
                "enddate" => $course["enddate"]->getTimestamp()
            ))), MoodleRest::METHOD_POST)
            ->willReturn(array(array("id" => $id)));

        $app = new AppImpl($rest);
        $result = $app->createCourse(
            $course["shortname"],
            $course["fullname"],
            $course["categoryid"],
            $course["summary"],
            $course["startdate"],
            $course["enddate"]);
        
        $this->assertEquals(0, sizeof($result["error"]));
        $this->assertEquals(200, $result["data"]["code"]);
        $this->assertEquals($id, $result["data"]["courseid"]);
    }

    public function testCreateCourseShouldReturnError400WhenException() {
        $rest = $this->createMoodleRestMockForRequestThatThrowException();
        
        $app = new AppImpl($rest);
        $result = $app->createCourse("any", "", 0, "", new DateTime(), new DateTime());

        $this->assertEquals(0, sizeof($result["data"]));
        $this->assertEquals(400, $result["error"]["code"]);
    }

    public function testCreateCourseShouldReturnError500WhenResponseError() {
        $response = array(
            "errorcode" => "exception",
            "message" => "access denied"
        );
        $rest = $this->createMoodleRestMock();
        $rest->expects($this->once())
            ->method("request")
            ->willReturn($response);
        
        $app = new AppImpl($rest);
        $result = $app->createCourse("any", "", 0, "", new DateTime(), new DateTime());

        $this->assertEquals(0, sizeof($result["data"]));
        $this->assertEquals(500, $result["error"]["code"]);
        $this->assertEquals($response["message"], $result["error"]["message"]);
    }

    function testUpdateCourseShouldReturnSuccess() {
        $id = "9182";
        $course = array(
            "shortname" => "batman",
            "enddate" => new DateTime("tomorrow")
        );

        $rest = $this->createMoodleRestMock();
        $rest->expects($this->once())
            ->method("request")
            ->with("core_course_update_courses", array("courses" => array(array(
                "shortname" => $course["shortname"],
                "enddate" => $course["enddate"]->getTimestamp(),
                "id" => $id
            ))), MoodleRest::METHOD_POST)
            ->willReturn(array());

        $app = new AppImpl($rest);
        $result = $app->updateCourse(
            $id,
            $course["shortname"],
            "", -1, "", 
            new DateTime(date(DATE_RFC2822, 0)),
            $course["enddate"]);
        
        $this->assertEquals(0, sizeof($result["error"]));
        $this->assertEquals(200, $result["data"]["code"]);
    }

    public function testUpdateCourseShouldReturnError400WhenException() {
        $rest = $this->createMoodleRestMockForRequestThatThrowException();
        
        $app = new AppImpl($rest);
        $result = $app->updateCourse("8129", "any", "", 0, "", new DateTime(), new DateTime());

        $this->assertEquals(0, sizeof($result["data"]));
        $this->assertEquals(400, $result["error"]["code"]);
    }

    public function testUpdateCourseShouldReturnError500WhenResponseError() {
        $response = array(
            "errorcode" => "exception",
            "message" => "access denied"
        );
        $rest = $this->createMoodleRestMock();
        $rest->expects($this->once())
            ->method("request")
            ->willReturn($response);
        
        $app = new AppImpl($rest);
        $result = $app->updateCourse("8192", "any", "", 0, "", new DateTime(), new DateTime());

        $this->assertEquals(0, sizeof($result["data"]));
        $this->assertEquals(500, $result["error"]["code"]);
        $this->assertEquals($response["message"], $result["error"]["message"]);
    }

    function testDeleteCourseShouldReturnSuccess() {
        $id = "9182";

        $rest = $this->createMoodleRestMock();
        $rest->expects($this->once())
            ->method("request")
            ->with("core_course_delete_courses", array("courseids" => array($id)), MoodleRest::METHOD_POST)
            ->willReturn(array());

        $app = new AppImpl($rest);
        $result = $app->deleteCourse($id);
        
        $this->assertEquals(0, sizeof($result["error"]));
        $this->assertEquals(200, $result["data"]["code"]);
    }

    public function testDeleteCourseShouldReturnError400WhenException() {
        $rest = $this->createMoodleRestMockForRequestThatThrowException();
        
        $app = new AppImpl($rest);
        $result = $app->deleteCourse("8129");

        $this->assertEquals(0, sizeof($result["data"]));
        $this->assertEquals(400, $result["error"]["code"]);
    }

    public function testDeleteCourseShouldReturnError500WhenResponseError() {
        $response = array(
            "errorcode" => "exception",
            "message" => "access denied"
        );
        $rest = $this->createMoodleRestMock();
        $rest->expects($this->once())
            ->method("request")
            ->willReturn($response);
        
        $app = new AppImpl($rest);
        $result = $app->deleteCourse("8192");

        $this->assertEquals(0, sizeof($result["data"]));
        $this->assertEquals(500, $result["error"]["code"]);
        $this->assertEquals($response["message"], $result["error"]["message"]);
    }
}