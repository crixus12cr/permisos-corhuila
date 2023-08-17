<?php

namespace Database\Seeders;

use App\Models\Rol;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = [
            'Super Usuario',
            'Administrador',
            'Usuario'
        ];

        foreach ($roles as $rol) {
            Rol::create([
                'name' => $rol
            ]);
        }
    }
}
