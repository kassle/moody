<?php declare(strict_types=1);

namespace Loncat\Moody;

class Config {
    private $moodleBaseUrl;
    private $moodleToken;

    public function __construct(string $moodleBaseUrl, string $moodleToken) {
        $this->moodleBaseUrl = $moodleBaseUrl;
        $this->moodleToken = $moodleToken;
    }

    public function getMoodleBaseUrl() : string {
        return $this->moodleBaseUrl;
    }

    public function getMoodleToken() : string {
        return $this->moodleToken;
    }
}