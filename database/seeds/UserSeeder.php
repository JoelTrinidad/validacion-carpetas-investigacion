<?php

use App\User;
use App\Role;
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
        $administrador_role = Role::where('name', 'administrador')->first();

        $sistemas_user = User::create([
            'dependencia' => 'c5',
            'curp' => 'MAHJ280603MSPRRV09',
            'email' => 'admin@yopmail.com',
            'oficio_alta' => 'oficio de alta',
            'password' => Hash::make('admin1234'),
        ]);
        $regular_user = User::create([
            'dependencia' => 'ssc',
            'curp' => 'ROVI490617HSPDSS05',
            'email' => 'user@yopmail.com',
            'oficio_alta' => 'oficio de alta',
            'password' => Hash::make('user1234'),
        ]);
        $sistemas_user->roles()->attach($administrador_role);
    }
}
