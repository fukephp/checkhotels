<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'email' => 'test@user.com',
            'username' => 'testusername',
            'username' => 'testusername',
            'password' => '12341234',
        ]);
    }
}
