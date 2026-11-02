<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RegistrationSeeder extends Seeder
{
    public function run(): void
    {
        $registrations = [
            // Inscriptions pour l'événement 1 (Conférence Tech Africa)
            [
                'event_id'   => 1,
                'full_name'  => 'Koffi Mensah',
                'email'      => 'koffi.mensah@gmail.com',
                'phone'      => '+229 97 11 22 33',
                'status'     => 'active',
                'created_at' => now()->subDays(2),
                'updated_at' => now()->subDays(2),
            ],
            [
                'event_id'   => 1,
                'full_name'  => 'Aïcha Traoré',
                'email'      => 'aicha.traore@yahoo.fr',
                'phone'      => '+229 96 44 55 66',
                'status'     => 'active',
                'created_at' => now()->subDays(1),
                'updated_at' => now()->subDays(1),
            ],
            [
                'event_id'   => 1,
                'full_name'  => 'Rodrigue Dossou',
                'email'      => 'r.dossou@outlook.com',
                'phone'      => '+229 95 12 34 56',
                'status'     => 'cancelled',
                'created_at' => now()->subDays(3),
                'updated_at' => now()->subDays(1),
            ],

            // Inscriptions pour l'événement 2 (Festival des Rythmes du Bénin)
            [
                'event_id'   => 2,
                'full_name'  => 'Flore Akakpo',
                'email'      => 'flore.akakpo@gmail.com',
                'phone'      => '+229 95 77 88 99',
                'status'     => 'active',
                'created_at' => now()->subDays(4),
                'updated_at' => now()->subDays(4),
            ],
            [
                'event_id'   => 2,
                'full_name'  => 'Joël Hounwanou',
                'email'      => 'joel.h@gmail.com',
                'phone'      => '+229 94 12 34 56',
                'status'     => 'active',
                'created_at' => now()->subDays(2),
                'updated_at' => now()->subDays(2),
            ],

            // Inscriptions pour l'événement 3 (Atelier Flutter)
            [
                'event_id'   => 3,
                'full_name'  => 'Oswald Azonhoumon',
                'email'      => 'oswald.dev@gmail.com',
                'phone'      => '+229 97 00 11 22',
                'status'     => 'active',
                'created_at' => now()->subDays(2),
                'updated_at' => now()->subDays(2),
            ],
            [
                'event_id'   => 3,
                'full_name'  => 'Dieudonné Loko',
                'email'      => 'dieudonne.loko@ifri.bj',
                'phone'      => '+229 96 11 33 55',
                'status'     => 'active',
                'created_at' => now()->subDays(1),
                'updated_at' => now()->subDays(1),
            ],
            [
                'event_id'   => 3,
                'full_name'  => 'Roberto Gbaguidi',
                'email'      => 'roberto.g@ifri.bj',
                'phone'      => '+229 96 55 66 77',
                'status'     => 'active',
                'created_at' => now()->subHours(12),
                'updated_at' => now()->subHours(12),
            ],

            // Inscriptions pour l'événement 5 (Exposition Art)
            [
                'event_id'   => 5,
                'full_name'  => 'Céleste Houénou',
                'email'      => 'celeste.h@gmail.com',
                'phone'      => '+229 95 33 44 55',
                'status'     => 'active',
                'created_at' => now()->subDays(1),
                'updated_at' => now()->subDays(1),
            ],
            [
                'event_id'   => 5,
                'full_name'  => 'Marc Agbayissah',
                'email'      => 'marc.agba@yahoo.fr',
                'phone'      => null,
                'status'     => 'cancelled',
                'created_at' => now()->subDays(2),
                'updated_at' => now()->subHours(5),
            ],
        ];

        DB::table('registrations')->insert($registrations);
    }
}
