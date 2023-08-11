<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;

class MoodleRestMock extends TestCase {
    function createMoodleRestMockForRequestThatThrowException() {
        $rest = $this->createMoodleRestMock();
        $rest->expects($this->once())
            ->method("request")
            ->will($this->throwException(new Exception()));

        return $rest;
    }

    function createMoodleRestMock() : MoodleRest {
        return $this->createMock(MoodleRest::class);
    }
}