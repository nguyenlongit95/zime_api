<?php

namespace App\Repositories\Package;

use App\Models\Package;
use App\Repositories\Eloquent\EloquentRepository;
use Illuminate\Support\Facades\DB;

class PackageEloquentRepository extends EloquentRepository implements PackageRepositoryInterface
{
     /**
      * @return mixed
      */
     public function getModel()
     {
         return Package::class;
     }

    /**
     * Repository function check Package used
     *
     * @param int $id of package
     */
    public function checkPackageUsed($id)
    {
        return DB::table('users')->where('package_id', $id)->count();
    }

    /**
     * Repository count total packages were used
     *
     * @return int
     */
    public function totalPackagesUsed()
    {
        return DB::table('users')->where('package_id','<>', 'null')->count();
    }

    /**
     * Repository count total packages were used by package id
     *
     * @param int $id of package
     * @return int
     */
    public function totalPackageUsed($id)
    {
        return DB::table('users')->where('package_id', $id)->count();
    }
}
