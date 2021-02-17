<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class RoleSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Role::insert([
            [
                'id' => Str::uuid(),
                'role_name' => 'admin',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
        	],
            [
                'id' => Str::uuid(),
                'role_name' => 'user',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
        	],
        ]);
    }
}
