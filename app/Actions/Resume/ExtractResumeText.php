<?php

namespace App\Actions\Resume;

use App\Exceptions\ResumeGenerationException;
use Illuminate\Http\UploadedFile;
use Smalot\PdfParser\Parser;
use Throwable;

/**
 * Convierte el archivo subido en texto plano utilizable.
 *
 * Esta lógica estaba copiada, con tres variantes ligeramente distintas, en
 * CvTailorService, AnalyzeResumeService y CoverLetterService.
 */
class ExtractResumeText
{
    public function __construct(private readonly Parser $parser) {}

    public function handle(UploadedFile|string $file): string
    {
        $path = $file instanceof UploadedFile ? $file->getRealPath() : $file;

        if (! is_string($path) || ! is_file($path)) {
            throw ResumeGenerationException::unreadableFile();
        }

        $text = $this->isPdf($file, $path)
            ? $this->fromPdf($path)
            : (string) file_get_contents($path);

        $text = $this->normalize($text);

        // Un PDF escaneado parsea sin error pero devuelve casi nada. Cortar acá
        // evita gastar una llamada al modelo para recibir un CV vacío.
        if (mb_strlen($text) < 120) {
            throw ResumeGenerationException::emptyResume();
        }

        return mb_substr($text, 0, (int) config('resume.limits.cv_characters', 24000));
    }

    private function isPdf(UploadedFile|string $file, string $path): bool
    {
        $extension = $file instanceof UploadedFile
            ? $file->getClientOriginalExtension()
            : pathinfo($path, PATHINFO_EXTENSION);

        return mb_strtolower($extension) === 'pdf';
    }

    private function fromPdf(string $path): string
    {
        try {
            return $this->parser->parseFile($path)->getText();
        } catch (Throwable $e) {
            throw ResumeGenerationException::unreadableFile($e->getMessage());
        }
    }

    /**
     * Limpia los artefactos típicos de la extracción de PDF antes de que el
     * texto llegue al modelo: cada uno de estos cuesta tokens y confunde al
     * parser.
     */
    private function normalize(string $text): string
    {
        $text = str_replace(["\r\n", "\r", "\xC2\xA0"], ["\n", "\n", ' '], $text);

        // Ligaduras que el parser deja como caracteres compuestos.
        $text = strtr($text, ['ﬁ' => 'fi', 'ﬂ' => 'fl', 'ﬀ' => 'ff']);

        // Palabras cortadas con guion al final de renglón.
        $text = preg_replace('/(\p{Ll})-\n(\p{Ll})/u', '$1$2', $text) ?? $text;

        $text = preg_replace('/[ \t]+/', ' ', $text) ?? $text;
        $text = preg_replace('/\n{3,}/', "\n\n", $text) ?? $text;

        return trim($text);
    }
}
