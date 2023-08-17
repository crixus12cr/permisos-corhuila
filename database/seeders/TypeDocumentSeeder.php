<?php

namespace Database\Seeders;

use App\Models\TypeDocument;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TypeDocumentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $nombres = [
            'Cedula de Ciudadania',
            'Pasaporte Extranjero',
        ];
        foreach ($nombres as $nombre) {
            TypeDocument::create([
                'name' => $nombre
            ]);
        }
    }
}
