<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class ObrigadoController extends Controller
{
    public function show(): View
    {
        $page = [
            'title' => 'Diagnóstico Enviado',
            'description' => 'Seu diagnóstico foi gerado. Em breve entraremos em contato.',
            'url' => rtrim(config('ftit.base_url'), '/').'/obrigado',
        ];

        return view('obrigado', [
            'page' => $page,
        ]);
    }
}

