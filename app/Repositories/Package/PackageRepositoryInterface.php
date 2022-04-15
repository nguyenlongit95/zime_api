<?php

namespace App\Repositories\Package;

interface PackageRepositoryInterface
{
    public function checkPackageUsed($id);

    public function totalPackagesUsed();

    public function totalPackageUsed($id);
}
