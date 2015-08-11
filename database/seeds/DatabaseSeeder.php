<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();


        // $this->call(UserTableSeeder::class);

        $this->call('UserTableSeeder');
        $this->command->info('User Table Seeded!');


        Model::reguard();

    }
}

class UserTableSeeder extends Seeder {
    public function run()
    {
        DB::table('users')->delete();
        DB::table('users')->insert([
            'name' => 'admin',
            'email' => 'micverygood555@gmail.com',
            'password' => Hash::make('password'),
            'created_at' => date('Y-m-d H:i:s')
            ]);
    }
}
