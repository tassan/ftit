<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DiagnosisRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nome'               => ['required', 'string', 'max:255'],
            'email'              => ['required', 'email', 'max:255'],
            'telefone'           => ['required', 'string', 'max:50'],
            'cidade'             => ['nullable', 'string', 'max:255'],
            'segmento'           => ['required', 'string', 'max:255'],
            'segmento_outro'     => ['nullable', 'string', 'max:255'],
            'faturamento'        => ['nullable', 'string', 'max:255'],
            'funcionarios'       => ['nullable', 'string', 'max:255'],
            'tem_site'           => ['nullable', 'string', 'max:50'],
            'google_meu_negocio' => ['nullable', 'string', 'max:50'],
            'instagram'          => ['nullable', 'string', 'max:50'],
            'como_acham'         => ['nullable', 'string', 'max:255'],
            'agendamento'        => ['nullable', 'string', 'max:255'],
            'followup'           => ['nullable', 'string', 'max:255'],
            'horas_admin'        => ['nullable', 'string', 'max:255'],
            'problema'           => ['required', 'string'],
            'objetivo'           => ['required', 'string'],
        ];
    }

    /**
     * Normalize the validated data into the structure expected by the diagnosis service.
     */
    public function validatedPayload(): array
    {
        $data = $this->validated();

        $segmento = $data['segmento'] ?? '';
        if ($segmento === 'outro') {
            $segmento = $data['segmento_outro'] ?? 'Outro';
        }

        return [
            'segmento'           => $segmento,
            'nome'               => $data['nome']               ?? '',
            'email'              => $data['email']              ?? '',
            'telefone'           => $data['telefone']           ?? '',
            'cidade'             => $data['cidade']             ?? '',
            'faturamento'        => $data['faturamento']        ?? '',
            'funcionarios'       => $data['funcionarios']       ?? '',
            'tem_site'           => $data['tem_site']           ?? '',
            'google_meu_negocio' => $data['google_meu_negocio'] ?? '',
            'instagram'          => $data['instagram']          ?? '',
            'como_acham'         => $data['como_acham']         ?? '',
            'agendamento'        => $data['agendamento']        ?? '',
            'followup'           => $data['followup']           ?? '',
            'horas_admin'        => $data['horas_admin']        ?? '',
            'problema'           => $data['problema']           ?? '',
            'objetivo'           => $data['objetivo']           ?? '',
        ];
    }
}

