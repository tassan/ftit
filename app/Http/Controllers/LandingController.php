<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class LandingController extends Controller
{
    public function __invoke(): View
    {
        $page = [
            'title' => 'Sites e Automação para Pequenas Empresas',
            'description' => 'Seu site e seus processos trabalhando por você. Desenvolvimento web e automação com 10+ anos de experiência.',
            'url' => config('ftit.base_url'),
        ];

        return view('landing', [
            'page' => $page,
            'whatsapp' => config('ftit.whatsapp'),
            'emailTo' => config('ftit.email_to'),
        ]);
    }
}

