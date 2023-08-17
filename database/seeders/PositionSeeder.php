<?php

namespace Database\Seeders;

use App\Models\Position;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $nombres = [
            'Docente',
            'Contador',
            'Aseador',
            'Rector',
            'Cordinador'
        ];
        foreach ($nombres as $nombre) {
            Position::create([
                'name' => $nombre
            ]);
        }
    }
}
