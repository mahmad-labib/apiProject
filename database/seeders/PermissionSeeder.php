<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('perrmissions')->insert(
            [
                'name' => 'edit-users'
            ],
            [
                'name' => 'edit-sections'
            ],
            [
                'name' => 'edit-roles'
            ],
            [
                'name' => 'edit-articles'
            ],
            [
                'name' => 'admin'
            ],
            [
                'name' => 'publisher'
            ],
            [
                'name' => 'writer'
            ],
            [
                'name' => 'approve'
            ],
            [
                'name' => 'show-users'
            ],
            [
                'name' => 'show-sections'
            ],
            [
                'name' => 'show-roles'
            ],
        );
    }
}
