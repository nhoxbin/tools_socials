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
        Role::insert([
        	'id' => 0,
        	'name' => 'Member'
        ]);
        Role::insert([
        	'id' => 1,
        	'name' => 'Vip Member'
        ]);
        Role::insert([
        	'id' => 2,
        	'name' => 'Admin'
        ]);
        Role::insert([
        	'id' => 3,
        	'name' => 'Super Admin'
        ]);
    }
}
