<?php

namespace App\Repositories\Files;

interface FileRepositoryInterface
{
    public function listFileOfUser();

    public function listFileOfOtherUser($user);

    public function totalFiles();
}
