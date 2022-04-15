<?php

namespace App\Repositories\Package;

interface PackageRepositoryInterface
{
    public function checkPackageUsed($id);
}
