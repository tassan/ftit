<?php

namespace App\Domain\Diagnostico;

class Gap
{
    public function __construct(
        public readonly string $problema,
        public readonly string $impacto,
    ) {
    }

    /**
     * @param  array{problema?: string, impacto?: string}  $data
     */
    public static function fromArray(array $data): self
    {
        $clean = static fn (?string $value): string => trim((string) $value);

        return new self(
            problema: $clean($data['problema'] ?? ''),
            impacto: $clean($data['impacto'] ?? ''),
        );
    }

    /**
     * @return array{problema: string, impacto: string}
     */
    public function toArray(): array
    {
        return [
            'problema' => $this->problema,
            'impacto' => $this->impacto,
        ];
    }
}

