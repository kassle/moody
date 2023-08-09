<?php declare(strict_types=1);

namespace Loncat\Moody;

use DateTime;

interface App {
    public function getUserById(string $id) : array;
    public function getUserByUsername(string $username) : array;
    public function getUserByEmail(string $email) : array;
    public function updateUser(string $id, string $password, string $email, string $firstname, string $lastname, string $city, string $country) : array;

    public function createCourse(string $shortname, string $fullname, int $categoryId, string $summary, DateTime $startDate, DateTime $endDate) : array;
    public function updateCourse(string $courseId, string $shortname, string $fullname, int $categoryId, string $summary, DateTime $startDate, DateTime $endDate) : array;
    public function deleteCourse(string $courseId) : array;

    public function getEnroledUsersByCourseId(string $courseId) : array;
    public function enrolUserToCourse(string $courseId, string $userId, string $roleId = Contract::ROLE_ID_STUDENT) : array;
    public function unEnrolUserFromCourse(string $courseId, string $userId) : array;

}