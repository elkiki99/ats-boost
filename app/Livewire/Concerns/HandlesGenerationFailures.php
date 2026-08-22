<?php

namespace App\Livewire\Concerns;

use App\Exceptions\ResumeGenerationException;
use Flux\Flux;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * Convierte cualquier fallo de generación en un toast legible.
 *
 * Antes las excepciones subían sin capturar: el usuario veía la pantalla de
 * error 500 de Laravel y perdía el archivo que acababa de subir.
 */
trait HandlesGenerationFailures
{
    /**
     * @template TReturn
     *
     * @param  callable(): TReturn  $callback
     * @return TReturn|null
     */
    protected function attempt(callable $callback): mixed
    {
        try {
            return $callback();
        } catch (ResumeGenerationException $e) {
            // Mensaje ya redactado para el usuario final.
            $this->failed($e->getMessage());
        } catch (Throwable $e) {
            Log::error('Fallo inesperado en la generación', [
                'component' => static::class,
                'exception' => $e,
            ]);

            $this->failed('Algo salió mal de nuestro lado. Ya quedó registrado; probá de nuevo en unos minutos.');
        }

        return null;
    }

    protected function failed(string $message): void
    {
        Flux::toast(heading: 'No pudimos completar la operación', text: $message, variant: 'danger');
    }

    protected function succeeded(string $heading, string $message): void
    {
        Flux::toast(heading: $heading, text: $message, variant: 'success');
    }
}
