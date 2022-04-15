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
}
