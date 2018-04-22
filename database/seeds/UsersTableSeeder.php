<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => '罗震宇',
            'student_id' => '15211121',
            'email' => 'lzy@luozy.cn',
            'password' => bcrypt('secret'),
        ]);
    }
}
