<?php

use App\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::create([
            "name" => "administrador",
            "display_name" => "Administrador",
            "description" => "Perfil del administrador"
        ]);
    }
}
