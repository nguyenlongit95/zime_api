<?php

namespace Database\Seeders;

use App\Models\File;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class FileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $userIds = DB::table('users')->select('id')->orderBy('id','asc')->pluck('id')->toArray();
        for ($i = 1; $i < 31; $i++) {
            for ($j = 1; $j = rand(0,20); $j++) {
                File::create([
                    'user_id' => $userIds[array_rand($userIds)],
                    'name' => Str::random(10),
                    'file_size' => rand(0,10000),
                    'created_at' => new Carbon('2022-04-' .$i),
                ]);
            }
        }
    }
}
