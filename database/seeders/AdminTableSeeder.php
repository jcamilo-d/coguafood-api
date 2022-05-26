<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Admin;

class AdminTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $dataToSeed = array(
            [
              'name' => 'ADMIN TEST',
              'email' => 'admintest@mail.com',
              'password' => app('hash')->make('123'),
            ],
        );

        foreach ($dataToSeed as $admin) {
            Admin::create($admin);
        }
    }
}
