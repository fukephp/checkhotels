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
            'username' => 'Test',
            'name' => 'TestU',
            'password' => '12341234',
        ]);
    }
}
