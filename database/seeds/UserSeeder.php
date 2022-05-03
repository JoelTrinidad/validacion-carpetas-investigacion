<?php

use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [
            [
                'dependencia' => 'c5',
                'curp' => 'MAHJ280603MSPRRV09',
                'email' => 'ejemplo@yopmail.com',
                'oficio_alta' => 'oficio de alta',
                'password' => '0123456789',
            ],
        ];
        foreach ($users as $user) {
            User::create([
                'dependencia' => $user['dependencia'],
                'curp' => $user['curp'],
                'email' => $user['email'],
                'oficio_alta' => $user['oficio_alta'],
                'password' => Hash::make($user['password']),
            ]);
        }
    }
}
