<?php

use Illuminate\Database\Seeder;
use App\Role;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::insert([[
            'id' => 0,
            'name' => 'Undefined'
        ], [
        	'id' => 1,
        	'name' => 'Member'
        ], [
            'id' => 2,
            'name' => 'Vip Member'
        ], [
            'id' => 3,
            'name' => 'Admin'
        ], [
            'id' => 4,
            'name' => 'Super Admin'
        ]]);
    }
}
