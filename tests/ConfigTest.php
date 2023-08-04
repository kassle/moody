<?php declare(strict_types=1);

use Loncat\Moody\Config;
use PHPUnit\Framework\TestCase;

class ConfigTest extends TestCase {
    public function testGetMoodleBaseUrlShouldReturnAssignedValue() {
        $baseUrl = "http://test1.moodle.org";
        $token = "random.token";

        $config = new Config($baseUrl, $token);

        $this->assertEquals($baseUrl, $config->getMoodleBaseUrl());
    }

    public function testGetMoodleTokenShouldReturnAssignedValue() {
        $baseUrl = "random.base.url";
        $token = "abcd.1234.zyxw";

        $config = new Config($baseUrl, $token);

        $this->assertEquals($token, $config->getMoodleToken());
    }
}