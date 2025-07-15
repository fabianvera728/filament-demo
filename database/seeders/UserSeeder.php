<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin principal
        User::create([
            'name' => 'Administrador Principal',
            'email' => 'admin@domisoft.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'role' => 'admin',
            'phone' => '+57 300 123 4567',
            'address' => 'Calle 123 #45-67, Bogotá',
            'is_active' => true,
            'document_number' => '12345678',
            'birth_date' => '1985-05-15',
        ]);

        // Usuario de oficina
        User::create([
            'name' => 'María González',
            'email' => 'maria.gonzalez@domisoft.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'role' => 'office',
            'phone' => '+57 301 234 5678',
            'address' => 'Carrera 45 #12-34, Medellín',
            'is_active' => true,
            'document_number' => '23456789',
            'birth_date' => '1990-08-22',
        ]);

        // Socio/Partner
        User::create([
            'name' => 'Carlos Rodríguez',
            'email' => 'carlos.rodriguez@domisoft.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'role' => 'partner',
            'phone' => '+57 302 345 6789',
            'address' => 'Avenida 80 #23-45, Cali',
            'is_active' => true,
            'document_number' => '34567890',
            'birth_date' => '1982-12-10',
        ]);

        // Repartidores
        $deliveryPersons = [
            [
                'name' => 'Juan Pérez',
                'email' => 'juan.perez@domisoft.com',
                'phone' => '+57 303 456 7890',
                'address' => 'Calle 50 #30-20, Bogotá',
                'document_number' => '45678901',
                'birth_date' => '1995-03-18',
            ],
            [
                'name' => 'Ana López',
                'email' => 'ana.lopez@domisoft.com',
                'phone' => '+57 304 567 8901',
                'address' => 'Carrera 70 #40-30, Medellín',
                'document_number' => '56789012',
                'birth_date' => '1993-07-25',
            ],
            [
                'name' => 'Luis Martínez',
                'email' => 'luis.martinez@domisoft.com',
                'phone' => '+57 305 678 9012',
                'address' => 'Calle 15 #25-35, Cali',
                'document_number' => '67890123',
                'birth_date' => '1988-11-08',
            ],
        ];

        foreach ($deliveryPersons as $person) {
            User::create([
                'name' => $person['name'],
                'email' => $person['email'],
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'role' => 'delivery',
                'phone' => $person['phone'],
                'address' => $person['address'],
                'is_active' => true,
                'document_number' => $person['document_number'],
                'birth_date' => $person['birth_date'],
            ]);
        }

        // Clientes
        $clients = [
            [
                'name' => 'Andrea Sánchez',
                'email' => 'andrea.sanchez@gmail.com',
                'phone' => '+57 310 123 4567',
                'address' => 'Calle 85 #15-25, Bogotá',
                'document_number' => '78901234',
                'birth_date' => '1992-04-12',
            ],
            [
                'name' => 'Roberto García',
                'email' => 'roberto.garcia@hotmail.com',
                'phone' => '+57 311 234 5678',
                'address' => 'Carrera 43A #20-15, Medellín',
                'document_number' => '89012345',
                'birth_date' => '1987-09-30',
            ],
            [
                'name' => 'Claudia Torres',
                'email' => 'claudia.torres@yahoo.com',
                'phone' => '+57 312 345 6789',
                'address' => 'Avenida 6N #28-45, Cali',
                'document_number' => '90123456',
                'birth_date' => '1991-01-20',
            ],
            [
                'name' => 'Miguel Herrera',
                'email' => 'miguel.herrera@outlook.com',
                'phone' => '+57 313 456 7890',
                'address' => 'Calle 72 #11-30, Bogotá',
                'document_number' => '01234567',
                'birth_date' => '1989-06-14',
            ],
            [
                'name' => 'Patricia Morales',
                'email' => 'patricia.morales@gmail.com',
                'phone' => '+57 314 567 8901',
                'address' => 'Carrera 65 #45-20, Medellín',
                'document_number' => '12345670',
                'birth_date' => '1994-10-05',
            ],
        ];

        foreach ($clients as $client) {
            User::create([
                'name' => $client['name'],
                'email' => $client['email'],
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'role' => 'client',
                'phone' => $client['phone'],
                'address' => $client['address'],
                'is_active' => true,
                'document_number' => $client['document_number'],
                'birth_date' => $client['birth_date'],
            ]);
        }
    }
}
