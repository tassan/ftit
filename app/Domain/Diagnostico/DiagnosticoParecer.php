<?php

namespace App\Domain\Diagnostico;

class DiagnosticoParecer
{
    /**
     * @param  Gap[]  $gaps
     */
    public function __construct(
        public readonly string $titulo,
        public readonly string $situacaoAtual,
        public readonly array $gaps,
        public readonly string $potencial,
        public readonly string $proximosPassos,
        public readonly string $ctaTexto,
        public readonly string $urgencia,
    ) {
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        $clean = static fn (?string $value): string => trim((string) $value);

        $gaps = [];
        foreach (($data['gaps'] ?? []) as $gapData) {
            if (is_array($gapData)) {
                $gaps[] = Gap::fromArray($gapData);
            }
        }

        $urgencia = $clean($data['urgencia'] ?? '');
        if (! in_array($urgencia, ['alta', 'media', 'baixa'], true)) {
            $urgencia = '';
        }

        return new self(
            titulo: $clean($data['titulo'] ?? ''),
            situacaoAtual: $clean($data['situacao_atual'] ?? ''),
            gaps: $gaps,
            potencial: $clean($data['potencial'] ?? ''),
            proximosPassos: $clean($data['proximos_passos'] ?? ''),
            ctaTexto: $clean($data['cta_texto'] ?? ''),
            urgencia: $urgencia,
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'titulo' => $this->titulo,
            'situacao_atual' => $this->situacaoAtual,
            'gaps' => array_map(
                static fn (Gap $gap): array => $gap->toArray(),
                $this->gaps,
            ),
            'potencial' => $this->potencial,
            'proximos_passos' => $this->proximosPassos,
            'cta_texto' => $this->ctaTexto,
            'urgencia' => $this->urgencia,
        ];
    }
}

