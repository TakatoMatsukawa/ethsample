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
            'password' => Hash::make('9605labs'),
            'remember_token' => '',
        ]);

        User::create([
            'name' => 'ushijima',
            'email' => 'saygo.ushijima@lm-labs.com',
            'password' => Hash::make('9605labs'),
            'remember_token' => '',
        ]);
    }
}
