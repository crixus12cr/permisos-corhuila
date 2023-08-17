<?php

namespace Database\Seeders;

use App\Models\Area;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AreaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $nombres = [
            'Informatica',
            'Quimica',
            'Fisica',
            'Sociales',
            'Musica'
        ];
        foreach ($nombres as $nombre) {
            Area::create([
                'name' => $nombre
            ]);
        }
    }
}
