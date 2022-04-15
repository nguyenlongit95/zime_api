<?php


namespace App\Repositories\Users;

interface UserRepositoryInterface
{
    public function checkPackage($user);

    public function trimEmail($email);

    public function checkTypePackage();

    public function countTotalFiles();

    public  function countTotalFilesByOtherUser($user);

    public function lastFileUpload($user);
}
