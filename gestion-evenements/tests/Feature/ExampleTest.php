<?php

namespace Tests\Feature;

use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * Test de redirection de la page d'accueil vers les événements.
     */
    public function test_the_application_redirects_home_to_events(): void
    {
        $response = $this->get('/');

        $response->assertRedirect('/events');
    }
}
