<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DiagnosticoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'nome' => ['required', 'string', 'max:120'],
            'negocio' => ['required', 'string', 'max:160'],
            'email' => ['required', 'string', 'email:rfc,dns', 'max:160'],
            'telefone' => ['required', 'string', 'max:40'],
            'cidade' => ['required', 'string', 'max:160'],

            'segmento' => ['required', 'string', 'max:160'],
            'segmento_outro' => ['nullable', 'string', 'max:160'],

            'faturamento' => ['required', 'string', 'max:80'],
            'funcionarios' => ['required', 'string', 'max:80'],

            'tem_site' => ['required', 'string', 'max:120'],
            'google_meu_negocio' => ['required', 'string', 'max:120'],
            'instagram' => ['required', 'string', 'max:120'],
            'como_acham' => ['required', 'string', 'max:120'],

            'agendamento' => ['required', 'string', 'max:120'],
            'followup' => ['required', 'string', 'max:120'],
            'horas_admin' => ['required', 'string', 'max:120'],

            'problema' => ['nullable', 'string', 'max:2000'],
            'objetivo' => ['nullable', 'string', 'max:2000'],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function validationData(): array
    {
        // Ensure we validate the raw JSON body
        return $this->json()->all() ?: parent::validationData();
    }
}

