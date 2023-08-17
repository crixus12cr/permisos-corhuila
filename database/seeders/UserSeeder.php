<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
                'name' => 'unknow',
                'last_name' => 'no rastro',
                'email' => 'unknow@gmail.com',
                'password' => '12345678',
                'type_document_id' => 1,
                'document_number' => '1078965783',
                'position_id' => 1,
                'area_id' => 1,
                'rol_id' => 1
            ]
        ];


        foreach ($users as $user) {
            User::create([
                'name' => $user['name'],
                'last_name' => $user['last_name'],
                'email' => $user['email'],
                'password' => Hash::make($user['password']),
                'type_document_id' => $user['type_document_id'],
                'document_number' => $user['document_number'],
                'position_id' => $user['position_id'],
                'area_id' => $user['area_id'],
                'rol_id' => $user['rol_id']
            ]);

            /*
            registro

            'name'
            'lastName'
            'email'
            'password'
            'typeDocument_id'
            'documentNumber'
            'positionId'
            'areaId'
            'rolId'

            login
            user{
                id
                fullName
                email
                typeDocument
                documentNumber
                position
                area
                rol{
                    Id
                    Name
                }
            },
            token: sdfasdasdfasdf

            */
        }
    }
}
