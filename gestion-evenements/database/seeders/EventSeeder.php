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
                'description'      => 'La plus grande conférence technologique d\'Afrique de l\'Ouest. Rejoignez des centaines de développeurs, entrepreneurs et investisseurs pour deux jours d\'ateliers, de talks et de networking autour de l\'innovation numérique et de l\'intelligence artificielle.',
                'event_date'       => Carbon::now()->addDays(15)->setTime(9, 0),
                'location'         => 'Palais des Congrès, Cotonou',
                'category'         => 'Conférence',
                'total_places'     => 500,
                'available_places' => 497,
                'image'            => 'https://images.unsplash.com/photo-1540575467063-178a50c2df87?w=800&auto=format&fit=crop&q=60',
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
            [
                'title'            => 'Festival des Rythmes du Bénin',
                'description'      => 'Un festival musical célébrant la richesse culturelle du Bénin. Artistes locaux et internationaux se retrouvent pour une nuit inoubliable de musique afrobeat, jazz et percussions traditionnelles.',
                'event_date'       => Carbon::now()->addDays(8)->setTime(18, 30),
                'location'         => 'Stade de l\'Amitié GMK, Cotonou',
                'category'         => 'Concert',
                'total_places'     => 2000,
                'available_places' => 1998,
                'image'            => 'https://images.unsplash.com/photo-1470225620780-dba8ba36b745?w=800&auto=format&fit=crop&q=60',
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
            [
                'title'            => 'Atelier Flutter & Dart : De Zéro à Héros',
                'description'      => 'Apprenez les bases solides du développement mobile avec Flutter et Dart. Cet atelier pratique vous guidera pas à pas dans la création d\'une application mobile multiplateforme fonctionnelle.',
                'event_date'       => Carbon::now()->addDays(3)->setTime(10, 0),
                'location'         => 'IFRI, Université d\'Abomey-Calavi',
                'category'         => 'Atelier',
                'total_places'     => 35,
                'available_places' => 32,
                'image'            => 'https://images.unsplash.com/photo-1517694712202-14dd9538aa97?w=800&auto=format&fit=crop&q=60',
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
            [
                'title'            => 'Tournoi de Football Inter-Quartiers',
                'description'      => 'La compétition annuelle de football réunissant les équipes des différents quartiers de la métropole. Venez encourager votre équipe favorite dans une ambiance festive et sportive.',
                'event_date'       => Carbon::now()->addDays(20)->setTime(8, 0),
                'location'         => 'Terrain Omnisports de Godomey',
                'category'         => 'Sport',
                'total_places'     => 300,
                'available_places' => 300,
                'image'            => 'https://images.unsplash.com/photo-1508098682722-e99c43a406b2?w=800&auto=format&fit=crop&q=60',
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
            [
                'title'            => 'Exposition d\'Art Contemporain Africain',
                'description'      => 'Une exposition exceptionnelle réunissant les œuvres de 25 artistes contemporains béninois et africains. Peintures, sculptures et installations numériques se côtoient pour vous offrir un voyage visuel unique.',
                'event_date'       => Carbon::now()->addDays(5)->setTime(14, 0),
                'location'         => 'Institut Français du Bénin, Cotonou',
                'category'         => 'Exposition',
                'total_places'     => 150,
                'available_places' => 148,
                'image'            => 'https://images.unsplash.com/photo-1579783902614-a3fb3927b675?w=800&auto=format&fit=crop&q=60',
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
            [
                'title'            => 'Hackathon IA & Santé Communautaire',
                'description'      => 'Un marathon de 48 heures pour concevoir des solutions innovantes basées sur l\'intelligence artificielle pour améliorer l\'accès aux soins de santé en Afrique. Prix et mentorat pour les vainqueurs.',
                'event_date'       => Carbon::now()->addDays(30)->setTime(8, 0),
                'location'         => 'Sèmè City Open Park, Cotonou',
                'category'         => 'Conférence',
                'total_places'     => 80,
                'available_places' => 80,
                'image'            => 'https://images.unsplash.com/photo-1531482615713-2afd69097998?w=800&auto=format&fit=crop&q=60',
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
            [
                'title'            => 'Masterclass UI/UX Design & Figma',
                'description'      => 'Perfectionnez vos compétences en conception d\'interfaces web et mobiles. Maîtrisez les Design Systems, les composants réutilisables et le prototypage avancé sur Figma.',
                'event_date'       => Carbon::now()->addDays(12)->setTime(14, 30),
                'location'         => 'Epitech Bénin, Cotonou',
                'category'         => 'Atelier',
                'total_places'     => 25,
                'available_places' => 25,
                'image'            => 'https://images.unsplash.com/photo-1581291518857-4e27b48ff24e?w=800&auto=format&fit=crop&q=60',
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
            [
                'title'            => 'Séminaire Leadership & Entrepreneuriat Féminin',
                'description'      => 'Une journée dédiée à l\'autonomisation et au leadership des femmes entrepreneures. Témoignages inspirants, panels de discussion et ateliers pratiques de financement.',
                'event_date'       => Carbon::now()->addDays(7)->setTime(9, 0),
                'location'         => 'Golden Tulip Le Diplomate, Cotonou',
                'category'         => 'Conférence',
                'total_places'     => 120,
                'available_places' => 120,
                'image'            => 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=800&auto=format&fit=crop&q=60',
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
        ];

        DB::table('events')->insert($events);
    }
}
