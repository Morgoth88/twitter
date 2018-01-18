<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('role')->insert([
            'id' => 1,
            'name' => 'admin',
        ]);

        DB::table('role')->insert([
            'id' => 0,
            'name' => 'user',
        ]);

    }
}
