<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ManuscriptSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $manuscripts = [
            [
                'name' => 'test_name1',
                'era' => 'test_era1',
                'writer' => 'test_writer1',
                'description' => 'test_description',
                'unique_id' => '11000001',
                'license' => '0',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'test_name2',
                'era' => 'test_era2',
                'writer' => 'test_writer2',
                'description' => 'test_description',
                'unique_id' => '11000002',
                'license' => '0',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'test_name3',
                'era' => 'test_era3',
                'writer' => 'test_writer3',
                'description' => 'test_description',
                'unique_id' => '11000003',
                'license' => '0',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        DB::table('manuscripts')->insert($manuscripts);
    }
}
