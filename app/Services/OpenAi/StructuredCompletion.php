<?php

namespace App\Services\OpenAi;

use App\Exceptions\ResumeGenerationException;
use Illuminate\Support\Facades\Log;
use JsonException;
use OpenAI\Laravel\Facades\OpenAI;
use Throwable;

/**
 * Único punto de contacto con OpenAI.
 *
 * Antes cada servicio armaba su propia llamada, parseaba el texto a mano y
 * limpiaba fences ```json con expresiones regulares. Acá se pide salida
 * estructurada validada contra un esquema, así que lo que vuelve o cumple el
 * contrato o lanza excepción: ninguna capa de arriba parsea nada.
 */
class StructuredCompletion
{
    /**
     * Ejecuta una tarea y devuelve el JSON ya decodificado.
     *
     * @param  non-empty-string  $task  Identificador corto para logs y errores.
     * @return array<string, mixed>
     *
     * @throws ResumeGenerationException
     */
    public function run(
        string $task,
        string $model,
        string $systemPrompt,
        string $userPrompt,
        JsonSchema $schema,
        float $temperature = 0.2,
    ): array {
        $attempts = max(1, (int) config('resume.retries', 2) + 1);
        $lastError = null;

        for ($attempt = 1; $attempt <= $attempts; $attempt++) {
            try {
                return $this->attempt($model, $systemPrompt, $userPrompt, $schema, $temperature);
            } catch (ResumeGenerationException $e) {
                // Una negativa del modelo no cambia con reintentos.
                throw $e;
            } catch (Throwable $e) {
                $lastError = $e;

                Log::warning('Fallo la generación estructurada', [
                    'task' => $task,
                    'attempt' => $attempt,
                    'of' => $attempts,
                    'model' => $model,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        // Distinguir "el modelo devolvió basura" de "la API no contestó"
        // importa: el primer caso es culpa del prompt, el segundo es red.
        throw $lastError instanceof JsonException
            ? ResumeGenerationException::invalidStructure($task)
            : ResumeGenerationException::modelUnavailable($lastError);
    }

    /**
     * @return array<string, mixed>
     *
     * @throws JsonException|ResumeGenerationException|Throwable
     */
    protected function attempt(
        string $model,
        string $systemPrompt,
        string $userPrompt,
        JsonSchema $schema,
        float $temperature,
    ): array {
        $message = OpenAI::chat()->create([
            'model' => $model,
            'temperature' => $temperature,
            'response_format' => $schema->toResponseFormat(),
            'messages' => [
                ['role' => 'system', 'content' => $systemPrompt],
                ['role' => 'user', 'content' => $userPrompt],
            ],
        ])->choices[0]->message;

        if (filled($message->refusal ?? null)) {
            throw ResumeGenerationException::refused();
        }

        $decoded = json_decode((string) $message->content, true, flags: JSON_THROW_ON_ERROR);

        if (! is_array($decoded)) {
            throw new JsonException('La respuesta no es un objeto JSON.');
        }

        return $decoded;
    }
}
