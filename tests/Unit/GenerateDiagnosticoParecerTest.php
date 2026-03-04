<?php

namespace Tests\Unit;

use App\Application\Diagnostico\GenerateDiagnosticoParecer;
use App\Domain\Diagnostico\DiagnosticoInput;
use App\Infrastructure\AI\OpenAiClient;
use PHPUnit\Framework\MockObject\MockObject;
use Tests\TestCase;

class GenerateDiagnosticoParecerTest extends TestCase
{
    public function test_it_parses_valid_ai_response(): void
    {
        /** @var OpenAiClient&MockObject $client */
        $client = $this->createMock(OpenAiClient::class);

        $client->method('chat')->willReturn(['choices' => []]);

        $client->method('extractJsonContent')->willReturn(json_encode([
            'parecer' => [
                'titulo' => 'Título teste',
                'situacao_atual' => 'Situação atual...',
                'gaps' => [
                    ['problema' => 'Gap 1', 'impacto' => 'Impacto 1'],
                ],
                'potencial' => 'Potencial...',
                'proximos_passos' => 'Próximos passos...',
                'cta_texto' => 'CTA...',
                'urgencia' => 'alta',
            ],
        ], JSON_THROW_ON_ERROR));

        $service = new GenerateDiagnosticoParecer($client);

        $input = new DiagnosticoInput(
            nome: 'Ana',
            negocio: 'Clínica X',
            email: 'ana@example.com',
            telefone: '123',
            cidade: 'SP',
            segmento: 'Clínica estética',
            faturamento: 'Até R$10k',
            funcionarios: '2–5',
            temSite: 'Não',
            googleMeuNegocio: 'Não',
            instagram: 'Tenho, mas não posto',
            comoAcham: 'Indicação de amigos',
            agendamento: 'WhatsApp manual',
            followup: 'Não faço',
            horasAdmin: 'Mais de 10h',
            problema: 'Poucos clientes',
            objetivo: 'Crescer',
        );

        $parecer = $service->handle($input);

        $this->assertSame('Título teste', $parecer->titulo);
        $this->assertSame('alta', $parecer->urgencia);
        $this->assertCount(1, $parecer->gaps);
    }
}

