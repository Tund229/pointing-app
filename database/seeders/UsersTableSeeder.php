<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            'name' => "Dénis Coly",
            'email' => "deniscoly19@gmail.com",
            'phone' => "+221 78 473 76 71",
            'role' => "admin",
            'password' => Hash::make('123456789'),
        ]);


        DB::table('users')->insert([
            'name' => "Recuperation",
            'email' => "recuperation@gmail.com",
            'phone' => "+221 78 473 76 71",
            'role' => "admin",
            'password' => Hash::make('1234567890'),
        ]);

        //  // Insérer 20 utilisateurs avec le rôle "teacher"
        //  for ($i = 1; $i <= 20; $i++) {
        //     DB::table('users')->insert([
        //         'name' => "Teacher User $i",
        //         'email' => "teacher$i@example.com",
        //         'phone' => "+123 456 789 0$i",
        //         'role' => "teacher",
        //         'password' => Hash::make('password'), // Vous pouvez utiliser le mot de passe de votre choix
        //     ]);
        // }

        // // Insérer 5 utilisateurs avec le rôle "admin"
        // for ($i = 1; $i <= 4; $i++) {
        //     DB::table('users')->insert([
        //         'name' => "Admin User $i",
        //         'email' => "admin$i@example.com",
        //         'phone' => "+123 456 789 1$i",
        //         'role' => "admin",
        //         'password' => Hash::make('password'), // Vous pouvez utiliser le mot de passe de votre choix
        //     ]);
        // }
    }
}
