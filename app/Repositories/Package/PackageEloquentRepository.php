<?php

namespace App\Repositories\Package;

use App\Models\Package;
use App\Repositories\Eloquent\EloquentRepository;

class PackageEloquentRepository extends EloquentRepository implements PackageRepositoryInterface
{
     /**
      * @return mixed
      */
     public function getModel()
     {
         return Package::class;
     }
}
