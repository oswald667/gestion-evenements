<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\Registration;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EventManagementTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test de l'affichage de la liste des événements
     */
    public function test_can_view_events_list(): void
    {
        Event::create([
            'title'            => 'Séminaire Laravel',
            'description'      => 'Découverte des fonctionnalités modernes de Laravel.',
            'event_date'       => Carbon::now()->addDays(5),
            'location'         => 'IFRI Amphi Etisalat',
            'category'         => 'Atelier',
            'total_places'     => 50,
            'available_places' => 50,
        ]);

        $response = $this->get('/events');

        $response->assertStatus(200);
        $response->assertSee('Séminaire Laravel');
        $response->assertSee('IFRI Amphi Etisalat');
    }

    /**
     * Test de création d'un événement
     */
    public function test_can_create_event(): void
    {
        $response = $this->post('/events', [
            'title'        => 'Conférence IA & Futur',
            'description'  => 'Une grande conférence sur l\'intelligence artificielle générative.',
            'event_date'   => Carbon::now()->addDays(10)->format('Y-m-d H:i'),
            'location'     => 'Palais des Congrès',
            'category'     => 'Conférence',
            'total_places' => 100,
        ]);

        $this->assertDatabaseHas('events', [
            'title' => 'Conférence IA & Futur',
            'category' => 'Conférence',
        ]);

        $response->assertSessionHas('success');
    }

    /**
     * Test d'inscription à un événement
     */
    public function test_user_can_register_for_an_event(): void
    {
        $event = Event::create([
            'title'            => 'Concert Live Jazz',
            'description'      => 'Soirée de jazz en plein air.',
            'event_date'       => Carbon::now()->addDays(2),
            'location'         => 'Plage Fidjrossè',
            'category'         => 'Concert',
            'total_places'     => 10,
            'available_places' => 10,
        ]);

        $response = $this->post("/events/{$event->id}/register", [
            'full_name' => 'Jean Dupont',
            'email'     => 'jean.dupont@test.com',
            'phone'     => '+229 97 12 34 56',
        ]);

        $response->assertRedirect("/events/{$event->id}");
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('registrations', [
            'event_id'  => $event->id,
            'email'     => 'jean.dupont@test.com',
            'status'    => 'active',
        ]);
    }

    /**
     * Test d'annulation d'une inscription
     */
    public function test_can_cancel_registration(): void
    {
        $event = Event::create([
            'title'            => 'Tournoi E-Sport',
            'description'      => 'Tournoi annuel de jeux vidéo.',
            'event_date'       => Carbon::now()->addDays(4),
            'location'         => 'Cotonou Mall',
            'category'         => 'Sport',
            'total_places'     => 20,
            'available_places' => 19,
        ]);

        $registration = Registration::create([
            'event_id'  => $event->id,
            'full_name' => 'Alice Dossou',
            'email'     => 'alice@test.com',
            'status'    => 'active',
        ]);

        $response = $this->delete("/registrations/{$registration->id}");

        $response->assertSessionHas('success');

        $this->assertDatabaseHas('registrations', [
            'id'     => $registration->id,
            'status' => 'cancelled',
        ]);
    }
}
