<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserTableSeeder extends Seeder
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
              'name' => 'USER TEST',
              'email' => 'usertest@mail.com',
              'password' => app('hash')->make('123'),
            ],
        );

        foreach ($dataToSeed as $user) {
            User::create($user);
        }
    }
}
