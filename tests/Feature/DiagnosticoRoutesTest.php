<?php

namespace Tests\Feature;

use Tests\TestCase;

class DiagnosticoRoutesTest extends TestCase
{
    public function test_diagnostico_page_loads(): void
    {
        $response = $this->get('/diagnostico');

        $response
            ->assertOk()
            ->assertSee('Diagnóstico', false)
            ->assertSee('Etapa 1 de 6');
    }

    public function test_privacidade_page_loads(): void
    {
        $response = $this->get('/privacidade');

        $response
            ->assertOk()
            ->assertSee('Política de Privacidade', false);
    }

    public function test_obrigado_page_loads(): void
    {
        $response = $this->get('/obrigado');

        $response
            ->assertOk()
            ->assertSee('Diagnóstico enviado!', false);
    }
}

