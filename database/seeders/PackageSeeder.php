<?php

namespace Database\Seeders;

use App\Models\Package;
use Illuminate\Database\Seeder;

class PackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Package::create([
                'name' => 'Package 1',
                'max_file_upload' => 3,
                'max_file_size'        => 1024,
            ]);

        Package::create([
            'name' => 'Package 2',
            'max_file_upload' => 5,
            'max_file_size'        => 2048,
            ]);

        Package::create([
            'name' => 'Package 3',
            'max_file_upload' => 7,
            'max_file_size'        => 4096,
            ]);
    }
}
