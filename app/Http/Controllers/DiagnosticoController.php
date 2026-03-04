<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class DiagnosticoController extends Controller
{
    public function showForm(): View
    {
        $page = [
            'title' => 'Diagnóstico Digital Gratuito',
            'description' => 'Descubra em 2 minutos os principais gaps digitais do seu negócio e receba um parecer personalizado com IA.',
            'url' => rtrim(config('ftit.base_url'), '/').'/diagnostico',
        ];

        return view('diagnostico', [
            'page' => $page,
            'whatsapp' => config('ftit.whatsapp'),
        ]);
    }
}

