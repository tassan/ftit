<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class PrivacyController extends Controller
{
    public function show(): View
    {
        $page = [
            'title' => 'Política de Privacidade',
            'description' => 'Política de privacidade da FTIT — f(t) it. Como tratamos seus dados pessoais em conformidade com a LGPD.',
            'url' => rtrim(config('ftit.base_url'), '/').'/privacidade',
        ];

        return view('privacidade', [
            'page' => $page,
        ]);
    }
}

