<?php declare(strict_types=1);

namespace Loncat\Moody;

interface App {
    public function getUserById(string $id) : array;
    public function getUserByUsername(string $username) : array;
    public function getUserByEmail(string $email) : array;

    /* blocked
    "Access to the function core_user_update_users() is not allowed.
    There could be multiple reasons for this:
    1. The service linked to the user token does not contain the function.
    2. The service is user-restricted and the user is not listed.
    3. The service is IP-restricted and the user IP is not listed.
    4. The service is time-restricted and the time has expired.
    5. The token is time-restricted and the time has expired.
    6. The service requires a specific capability which the user does not have.
    7. The function is called with username/password (no user token is sent) and none of the services has the function to allow the user.
    These settings can be found in Administration > Site administration
    > Server > Web services > External services and Manage tokens."
    */
    // public function updateUser(string $id, string $city) : mixed;
    // public function enrolUserToCourse(string $courseId, string $userId) : mixed;
    // public function unEnrolUserToCourse(string $courseId, string $userId) : mixed;

    public function getEnroledUsersByCourseId(string $courseId) : array;
}