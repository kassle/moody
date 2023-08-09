<?php declare(strict_types=1);

namespace Loncat\Moody;

interface App {
    public function getUserById(string $id) : array;
    public function getUserByUsername(string $username) : array;
    public function getUserByEmail(string $email) : array;

    public function updateUser(string $id, string $password, string $email, string $firstname, string $lastname, string $city) : mixed;
    public function enrolUserToCourse(string $courseId, string $userId, string $roleId) : mixed;
    public function unEnrolUserFromCourse(string $courseId, string $userId) : mixed;

    public function getEnroledUsersByCourseId(string $courseId) : array;
}