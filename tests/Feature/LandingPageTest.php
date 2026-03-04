<?php

namespace Tests\Feature;

use Tests\TestCase;

class LandingPageTest extends TestCase
{
    public function test_landing_page_loads(): void
    {
        $response = $this->get('/');

        $response
            ->assertOk()
            ->assertSee('f(t) it')
            ->assertSee('hero.headline', false); // data-i18n key present
    }
}

