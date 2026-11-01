<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class EventSeeder extends Seeder
{
    public function run(): void
    {
        $events = [
            [
                'title'            => 'Conférence Tech Africa 2026',
                'description'      => 'La plus grande conférence technologique d\'Afrique de l\'Ouest. Rejoignez des centaines de développeurs, entrepreneurs et investisseurs pour deux jours d\'ateliers, de talks et de networking autour de l\'innovation numérique.',
                'event_date'       => Carbon::now()->addDays(15)->setTime(9, 0),
                'location'         => 'Palais des Congrès, Cotonou',
                'category'         => 'Conférence',
                'total_places'     => 500,
                'available_places' => 320,
                'image'            => null,
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
            [
                'title'            => 'Festival des Rythmes du Bénin',
                'description'      => 'Un festival musical célébrant la richesse culturelle du Bénin. Artistes locaux et internationaux se retrouvent pour une nuit inoubliable de musique afrobeat, jazz et musique traditionnelle.',
                'event_date'       => Carbon::now()->addDays(8)->setTime(18, 30),
                'location'         => 'Stade de l\'Amitié, Cotonou',
                'category'         => 'Concert',
                'total_places'     => 2000,
                'available_places' => 850,
                'image'            => null,
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
            [
                'title'            => 'Atelier Flutter & Dart — Niveau Débutant',
                'description'      => 'Apprenez les bases du développement mobile avec Flutter. Cet atelier pratique vous guidera pas à pas dans la création de votre première application mobile multiplateforme.',
                'event_date'       => Carbon::now()->addDays(3)->setTime(10, 0),
                'location'         => 'IFRI, Université d\'Abomey-Calavi',
                'category'         => 'Atelier',
                'total_places'     => 40,
                'available_places' => 12,
                'image'            => null,
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
            [
                'title'            => 'Tournoi de Football Inter-Quartiers',
                'description'      => 'La compétition annuelle de football réunissant les équipes des différents quartiers de la ville. Venez encourager votre équipe favorite dans une ambiance festive et conviviale.',
                'event_date'       => Carbon::now()->addDays(20)->setTime(8, 0),
                'location'         => 'Terrain Omnisports de Godomey',
                'category'         => 'Sport',
                'total_places'     => 300,
                'available_places' => 300,
                'image'            => null,
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
            [
                'title'            => 'Exposition d\'Art Contemporain Africain',
                'description'      => 'Une exposition réunissant les œuvres de 25 artistes contemporains africains. Peintures, sculptures et installations numériques se côtoient pour vous offrir un voyage visuel unique à travers la créativité du continent.',
                'event_date'       => Carbon::now()->addDays(5)->setTime(14, 0),
                'location'         => 'Centre Culturel Français, Cotonou',
                'category'         => 'Exposition',
                'total_places'     => 150,
                'available_places' => 75,
                'image'            => null,
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
            [
                'title'            => 'Hackathon IA & Santé',
                'description'      => 'Un hackathon de 48 heures pour concevoir des solutions d\'intelligence artificielle appliquées au secteur de la santé en Afrique. Prix à gagner et opportunités de financement pour les meilleures équipes.',
                'event_date'       => Carbon::now()->addDays(30)->setTime(8, 0),
                'location'         => 'Hub Innovation, Cotonou',
                'category'         => 'Conférence',
                'total_places'     => 100,
                'available_places' => 63,
                'image'            => null,
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
            [
                'title'            => 'Gala de Danse Classique',
                'description'      => 'Le gala annuel de l\'Académie de Danse de Cotonou. Élèves et professeurs vous présenteront des chorégraphies classiques et contemporaines dans une soirée élégante et artistique.',
                'event_date'       => Carbon::now()->addDays(12)->setTime(20, 0),
                'location'         => 'Théâtre de Cotonou',
                'category'         => 'Concert',
                'total_places'     => 250,
                'available_places' => 30,
                'image'            => null,
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
            [
                'title'            => 'Séminaire Entrepreneuriat Féminin',
                'description'      => 'Une journée dédiée aux femmes entrepreneures du Bénin. Témoignages, panels de discussion et sessions de mentorat pour vous aider à lancer et développer votre activité.',
                'event_date'       => Carbon::now()->addDays(7)->setTime(9, 0),
                'location'         => 'Hôtel du Lac, Cotonou',
                'category'         => 'Conférence',
                'total_places'     => 200,
                'available_places' => 88,
                'image'            => null,
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
        ];

        DB::table('events')->insert($events);
    }
}