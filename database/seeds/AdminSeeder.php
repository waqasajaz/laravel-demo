<?php

use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if(DB::table('admin_users')->get()->count() == 0){
            DB::table('admin_users')->insert([
                [
                    'name' => 'Loquare Admin',
                    'email' => 'admin@loquare.com',
                    'password' => bcrypt('loquare@password'),
                    'role_id' => 1
                ],
                [
                    'name' => 'Jordi Agent',
                    'email' => 'jordi@loquare.com',
                    'password' => bcrypt('loquare@password'),
                    'role_id' => 2
                ],
                [
                    'name' => 'Nacho Agent',
                    'email' => 'nacho@loquare.com',
                    'password' => bcrypt('loquare@password'),
                    'role_id' => 2
                ],
                [                
                    'name' => 'Ana Agent',
                    'email' => 'ana@loquare.com',
                    'password' => bcrypt('loquare@password'),
                    'role_id' => 2
                ],
                [                
                    'name' => 'Carlos Agent',
                    'email' => 'carlos@loquare.com',
                    'password' => bcrypt('loquare@password'),
                    'role_id' => 2
                ],
                [                
                    'name' => 'Paula Agent',
                    'email' => 'paula@loquare.com',
                    'password' => bcrypt('loquare@password'),
                    'role_id' => 2
                ],
                [                
                    'name' => 'Cristian Agent',
                    'email' => 'cristian@loquare.com',
                    'password' => bcrypt('loquare@password'),
                    'role_id' => 2
                ],
                [                
                    'name' => 'Eduardo Agent',
                    'email' => 'eduardo@loquare.com',
                    'password' => bcrypt('loquare@password'),
                    'role_id' => 2
                ],
                [                
                    'name' => 'David Agent',
                    'email' => 'david@loquare.com',
                    'password' => bcrypt('loquare@password'),
                    'role_id' => 2
                ],

            ]);
        }
    }
}