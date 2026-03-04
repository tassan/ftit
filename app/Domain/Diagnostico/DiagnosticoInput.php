<?php

namespace App\Domain\Diagnostico;

class DiagnosticoInput
{
    public function __construct(
        public readonly string $nome,
        public readonly string $negocio,
        public readonly string $email,
        public readonly string $telefone,
        public readonly string $cidade,
        public readonly string $segmento,
        public readonly string $faturamento,
        public readonly string $funcionarios,
        public readonly string $temSite,
        public readonly string $googleMeuNegocio,
        public readonly string $instagram,
        public readonly string $comoAcham,
        public readonly string $agendamento,
        public readonly string $followup,
        public readonly string $horasAdmin,
        public readonly string $problema,
        public readonly string $objetivo,
    ) {
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        $segmento = trim((string) ($data['segmento'] ?? ''));
        $segmentoOutro = trim((string) ($data['segmento_outro'] ?? ''));

        if ($segmento === 'outro' && $segmentoOutro !== '') {
            $segmento = $segmentoOutro;
        }

        $clean = static fn (?string $value): string => trim((string) strip_tags((string) $value));

        return new self(
            nome: $clean($data['nome'] ?? ''),
            negocio: $clean($data['negocio'] ?? ''),
            email: $clean($data['email'] ?? ''),
            telefone: $clean($data['telefone'] ?? ''),
            cidade: $clean($data['cidade'] ?? ''),
            segmento: $clean($segmento),
            faturamento: $clean($data['faturamento'] ?? ''),
            funcionarios: $clean($data['funcionarios'] ?? ''),
            temSite: $clean($data['tem_site'] ?? ''),
            googleMeuNegocio: $clean($data['google_meu_negocio'] ?? ''),
            instagram: $clean($data['instagram'] ?? ''),
            comoAcham: $clean($data['como_acham'] ?? ''),
            agendamento: $clean($data['agendamento'] ?? ''),
            followup: $clean($data['followup'] ?? ''),
            horasAdmin: $clean($data['horas_admin'] ?? ''),
            problema: $clean($data['problema'] ?? ''),
            objetivo: $clean($data['objetivo'] ?? ''),
        );
    }
}

