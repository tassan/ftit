<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubmitRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nome'     => ['required', 'string', 'max:255'],
            'negocio'  => ['nullable', 'string', 'max:255'],
            'email'    => ['required', 'email', 'max:255'],
            'telefone' => ['required', 'string', 'max:50'],
            'segmento' => ['nullable', 'string', 'max:255'],
            'temSite'  => ['nullable', 'string', 'max:50'],
            'dor'      => ['required', 'string'],
        ];
    }

    /**
     * Normalize payload to match legacy submit.php behavior.
     */
    public function payload(): array
    {
        $data = $this->validated();

        return [
            'nome'     => $data['nome']     ?? '',
            'negocio'  => $data['negocio']  ?? '',
            'email'    => $data['email']    ?? '',
            'telefone' => $data['telefone'] ?? '',
            'segmento' => $data['segmento'] ?? '',
            'temSite'  => $data['temSite']  ?? '',
            'dor'      => $data['dor']      ?? '',
        ];
    }
}

