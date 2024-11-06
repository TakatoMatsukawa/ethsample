<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UsersTableDevSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'develop_user',
            'email' => 'test@test.com',
            'password' => Hash::make('9605testlabs'),
            'remember_token' => '',
        ]);
    }
}
