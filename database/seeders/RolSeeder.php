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
            'Talento Humano',
            'Jefe de area',
            'Colaborador'
        ];

        foreach ($roles as $rol) {
            Rol::create([
                'name' => $rol
            ]);
        }
    }
}
