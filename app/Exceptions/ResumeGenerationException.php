<?php

namespace App\Exceptions;

use RuntimeException;
use Throwable;

/**
 * Cualquier fallo del pipeline de generación que el usuario tiene que ver.
 *
 * El mensaje siempre está redactado para mostrarse tal cual en un toast: no
 * filtra respuestas crudas del modelo ni rutas del servidor.
 */
class ResumeGenerationException extends RuntimeException
{
    public static function unreadableFile(string $reason = ''): self
    {
        return new self(
            'No pudimos leer el archivo. Verificá que sea un PDF con texto seleccionable y no un escaneo o una imagen.'
            .($reason !== '' ? " ({$reason})" : '')
        );
    }

    public static function emptyResume(): self
    {
        return new self(
            'El archivo no tiene texto suficiente para analizar. Si tu CV es un PDF escaneado, exportalo de nuevo desde el editor original.'
        );
    }

    public static function modelUnavailable(?Throwable $previous = null): self
    {
        return new self(
            'El servicio de IA no está respondiendo en este momento. Probá de nuevo en unos segundos.',
            previous: $previous,
        );
    }

    public static function invalidStructure(string $task): self
    {
        return new self(
            "No pudimos estructurar el resultado ({$task}). Probá de nuevo; si vuelve a pasar, revisá que el CV no esté vacío."
        );
    }

    public static function refused(): self
    {
        return new self(
            'El modelo rechazó procesar este contenido. Revisá que el CV y la oferta no incluyan datos sensibles de terceros.'
        );
    }
}
