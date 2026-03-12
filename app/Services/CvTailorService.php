<?php

namespace App\Services;

use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use OpenAI\Laravel\Facades\OpenAI;
use RuntimeException;
use Smalot\PdfParser\Parser;

class CvTailorService
{
    /**
     * Detecta el idioma principal del texto (español, inglés, etc.)
     */
    private function detectLanguage(string $text): string
    {
        $response = OpenAI::chat()->create([
            'model' => 'gpt-4o-mini',
            'temperature' => 0,
            'messages' => [
                [
                    'role' => 'system',
                    'content' => 'Detect the primary language of the given text. Respond ONLY with "es" for Spanish or "en" for English. Do not add anything else. If there is no match, choose "en" by default'
                ],
                [
                    'role' => 'user',
                    'content' => $text
                ],
            ],
        ]);

        $lang = trim($response->choices[0]->message->content);

        return $lang === 'es' ? 'es' : 'en';
    }

    /**
     * Obtiene el prompt dinámico según el idioma
     */
    private function getPromptExtractRequirements(string $language, string $jobOffer): string
    {
        $currentDate = Carbon::now()->format('d/m/Y');

        if ($language === 'es') {
            return "
        Hoy es: {$currentDate}

        Extrae requisitos clave del trabajo.
        Responde SOLO con JSON válido.
        Devuelve un array. Si no se encuentran, devuelve [].

        --- DESCRIPCIÓN DEL TRABAJO ---
        {$jobOffer}
    ";
        }

        // English version
        return "
        Today is: {$currentDate}

        Extract key job requirements.
        Respond ONLY with valid JSON.
        Return an array. If none are found, return [].

        --- JOB DESCRIPTION ---
        {$jobOffer}
    ";
    }

    /**
     * Obtiene el prompt dinámico para extraer línea de contacto según idioma
     */
    private function getPromptExtractContactLine(string $language, string $cvText): string
    {
        $currentDate = Carbon::now()->format('d/m/Y');

        if ($language === 'es') {
            return "
            Hoy es: {$currentDate}

            Extrae ubicación, correo y teléfono.
            Devuelve UNA línea separada por •.

            --- CV ---
            {$cvText}
            ";
        }

        // English version
        return "
            Today is: {$currentDate}

            Extract location, email and phone.
            Return ONE line separated by •.

            --- CV ---
            {$cvText}
            ";
    }

    /**
     * Obtiene el prompt dinámico para extraer rol según idioma
     */
    private function getPromptExtractRole(string $language, string $jobOffer): string
    {
        $currentDate = Carbon::now()->format('d/m/Y');

        if ($language === 'es') {
            return "
            Hoy es: {$currentDate}

            Extrae SOLO el título del puesto.
            Devuelve \"Desconocido\" si no está claro.

            --- DESCRIPCIÓN DEL TRABAJO ---
            {$jobOffer}
        ";
        }

        // English version
        return "
            Today is: {$currentDate}

            Extract ONLY the job title.
            Return \"Unknown\" if unclear.

            --- JOB DESCRIPTION ---
            {$jobOffer}
        ";
    }

    /**
     * Obtiene el prompt dinámico para tailoring según idioma
     */
    private function getPromptTailor(string $language, string $cvText, string $jobOffer, array $req): string
    {
        $currentDate = Carbon::now()->format('d/m/Y');

        if ($language === 'es') {
            return '
            Eres un motor profesional de reescritura de CV.

            Hoy es: ' . $currentDate . '

            Reescribe el CV para alinearlo con los requisitos del trabajo.

            ====================
            REGLAS DE ESTRUCTURA GLOBAL
            ====================

            - Los títulos de sección SIEMPRE deben renderizarse como <h2> con texto en negrita.
            - Los títulos de sección NO deben ser viñetas.
            - Devuelve SOLO HTML limpio.
            - NO incluyas perfil o resumen.
            - NO incluyas nombre o datos de contacto.
            - Nunca inventes información.
            - Usa SOLO estas etiquetas HTML:
            h1, h2, p, ul, li, strong, em, u, span, br.

            ====================
            ALCANCE DE FORMATO ESTRICTO (MUY IMPORTANTE)
            ====================

            Las reglas de formato ESTRICTO que se describen a continuación aplican SOLO a:
            - Entradas de Experiencia Laboral
            - Entradas de Educación

            NO aplican a:
            - Habilidades
            - Habilidades Técnicas
            - Certificaciones
            - Proyectos Académicos
            - Idiomas
            - Herramientas
            - Habilidades Blandas
            - Cualquier otra sección no explícitamente listada arriba

            Todas las otras secciones DEBEN renderizarse en un formato de CV natural,
            usando párrafos y/o viñetas según corresponda.

            ====================
            FORMATO DE EXPERIENCIA LABORAL (ESTRICTO – SIN EXCEPCIONES)
            ====================

            Cada entrada de experiencia laboral DEBE seguir EXACTAMENTE esta estructura:

            1) PRIMER <p> — LÍNEA DE TÍTULO
            - Envuelto en <strong>
            - Contenido SOLO:
            Título del Trabajo – Empresa / Nombre de la Experiencia
            - NO DEBE incluir:
            Fechas, ubicación, descripciones, responsabilidades, habilidades o contexto extra

            2) SEGUNDO <p> — LÍNEA META
            - Texto plano (SIN <strong>)
            - Contenido SOLO:
            Ubicación — Rango de fechas
            - El formato de fecha DEBE ser:
            Mes YYYY – Mes YYYY
            O
            Mes YYYY – Presente

            3) DESPUÉS de estos dos <p>:
            - Responsabilidades / logros DEBEN ser viñetas (<ul><li>)
            - MÁXIMO 5 viñetas por rol

            REGLAS PARA REESCRITURA DE VIÑETAS (CRÍTICO):

            - Puedes reescribir y refinar las viñetas dentro de Experiencia Laboral.
            - Debes integrar tecnologías y palabras clave relevantes de la oferta,
              pero SOLO si ya existen en el CV original.
            - Nunca inventes nuevas habilidades o experiencias.
            - Puedes hacer las viñetas más específicas y orientadas a logros.
            - Si una habilidad (por ejemplo, PHP) existe en el CV y es relevante,
              debe incorporarse naturalmente en la viñeta más apropiada.
            - No te limites a agregar habilidades en la sección Skills sin adaptar la experiencia.
            - El objetivo es alineación semántica profesional, no relleno de palabras clave.

            ====================
            FORMATO DE EDUCACIÓN (ESTRICTO, DESCRIPCIÓN PERMITIDA)
            ====================

            Cada entrada de Educación DEBE seguir EXACTAMENTE esta estructura:

            1) PRIMER <p>
            - Envuelto en <strong>
            - Contenido:
            Título / Programa – Institución

            2) SEGUNDO <p>
            - Texto plano (SIN <strong>)
            - Contenido:
            Ubicación — Rango de fechas O graduación esperada

            3) TERCER <p> OPCIONAL (PERMITIDO Y ALENTADO):
            - Texto plano
            - Usado para describir:
            - Campo de estudio
            - Campus
            - Cursos relevantes
            - DEBE ser conciso
            - DEBE reflejar información presente en el CV original
            - NO DEBE incluir viñetas

            ====================
            TODAS LAS OTRAS SECCIONES (FORMATO NORMAL)
            ====================

            Para secciones como Habilidades, Proyectos Académicos, Certificaciones, Idiomas, etc.:

            - Usa un formato de CV natural
            - Las viñetas están permitidas y alentadas donde corresponda
            - No se requiere estructura de párrafo estricta
            - NO fuerces el formato Experiencia/Educación aquí

            ====================
            REQUISITOS PARA ADAPTAR
            ====================

            ' . json_encode($req, JSON_PRETTY_PRINT) . "

            --- CV ORIGINAL ---
            {$cvText}

            --- DESCRIPCIÓN DEL TRABAJO ---
            {$jobOffer}
        ";
        }

        // English version
        return '
            You are a professional CV rewriting engine.

            Today is: ' . $currentDate . '

            Rewrite the CV to align with the job requirements.

            ====================
            GLOBAL STRUCTURE RULES
            ====================

            - Section titles MUST ALWAYS be rendered as <h2> with bold text.
            - Section titles should NOT be bullet points.
            - Return ONLY clean HTML.
            - Do NOT include profile or summary.
            - Do NOT include name or contact information.
            - Never invent information.
            - Use ONLY these HTML tags:
            h1, h2, p, ul, li, strong, em, u, span, br.

            ====================
            STRICT FORMAT SCOPE (VERY IMPORTANT)
            ====================

            The strict formatting rules described below apply ONLY to:
            - Work Experience entries
            - Education entries

            They do NOT apply to:
            - Skills
            - Technical Skills
            - Certifications
            - Academic Projects
            - Languages
            - Tools
            - Soft Skills
            - Any other section not explicitly listed above

            All other sections MUST be rendered in a natural CV format,
            using paragraphs and/or bullet points as appropriate.

            ====================
            WORK EXPERIENCE FORMAT (STRICT – NO EXCEPTIONS)
            ====================

            Each work experience entry MUST follow EXACTLY this structure:

            1) FIRST <p> — TITLE LINE
            - Wrapped in <strong>
            - Content ONLY:
            Job Title – Company / Experience Name
            - Must NOT include:
            Dates, location, descriptions, responsibilities, skills or extra context

            2) SECOND <p> — META LINE
            - Plain text (NO <strong>)
            - Content ONLY:
            Location — Date Range
            - Date format MUST be:
            Month YYYY – Month YYYY
            OR
            Month YYYY – Present

            3) AFTER these two <p>:
            - Responsibilities / achievements MUST be bullet points (<ul><li>)
            - MAXIMUM 5 bullets per role

            BULLET REWRITING RULES (CRITICAL):

            - You ARE allowed to rewrite, refine, and adapt bullet points inside Work Experience.
            - You SHOULD integrate relevant technologies, tools, and keywords from the job description,
              but ONLY if they already exist somewhere in the original CV.
            - You MUST NOT invent new skills or experiences.
            - You MAY make bullets more specific and achievement-oriented.
            - If a skill (e.g., PHP) exists in the CV and is relevant to the job,
              it SHOULD be naturally incorporated into the most appropriate experience bullet.
            - Do NOT simply add skills to the Skills section without adapting experience.
            - The goal is semantic alignment, not keyword stuffing.

            ====================
            EDUCATION FORMAT (STRICT, DESCRIPTION ALLOWED)
            ====================

            Each Education entry MUST follow EXACTLY this structure:

            1) FIRST <p>
            - Wrapped in <strong>
            - Content:
            Degree / Program – Institution

            2) SECOND <p>
            - Plain text (NO <strong>)
            - Content:
            Location — Date Range OR expected graduation

            3) THIRD <p> OPTIONAL (ALLOWED AND ENCOURAGED):
            - Plain text
            - Used to describe:
            - Field of study
            - Campus
            - Relevant courses
            - MUST be concise
            - MUST reflect information present in original CV
            - Must NOT include bullet points

            ====================
            ALL OTHER SECTIONS (NORMAL FORMAT)
            ====================

            For sections like Skills, Academic Projects, Certifications, Languages, etc.:

            - Use a natural CV format
            - Bullet points are allowed and encouraged where appropriate
            - No strict paragraph structure required
            - Do NOT force Experience/Education format here

            ====================
            REQUIREMENTS TO ADAPT
            ====================

            ' . json_encode($req, JSON_PRETTY_PRINT) . "

            --- ORIGINAL CV ---
            {$cvText}

            --- JOB DESCRIPTION ---
            {$jobOffer}
        ";
    }

    /**
     * Flujo completo de adaptación de CV (HTML con encabezado)
     */
    public function tailorResume(string $resumePath, string $jobDescription): array
    {
        $cvText = $this->extractCvText($resumePath);
        $language = $this->detectLanguage($jobDescription);

        $name = $this->extractNameFromCv($cvText, $language);
        $contactLine = $this->extractContactLine($cvText, $language);

        $requirements = $this->extractRequirements($jobDescription, $language);

        $bodyHtml = $this->tailor(
            cvText: $cvText,
            jobOffer: $jobDescription,
            req: $requirements,
            language: $language
        );

        $placeholderName = $language === 'es' ? 'Insertar nombre aquí' : 'Insert name here';
        $placeholderContact = $language === 'es'
            ? 'Insertar ciudad, país • Insertar correo • Insertar teléfono'
            : 'Insert city, country • Insert email • Insert phone';

        return [
            'html' => $this->buildHeaderHtml(
                $name ?: $placeholderName,
                $contactLine ?: $placeholderContact
            ) . $bodyHtml,
            'cvText' => $cvText,
            'name' => $name,
        ];
    }

    /**
     * Descargar PDF final
     */
    public function downloadPdf(
        string $tailoredHtml,
        string $candidateName,
        string $jobDescription
    ) {
        $pdf = $this->generatePdf($tailoredHtml);
        $language = $this->detectLanguage($jobDescription);

        return response()->streamDownload(
            fn() => print($pdf),
            $this->buildFileName(
                $candidateName,
                $this->extractRole($jobDescription, $language)
            )
        );
    }

    /**
     * -----------------------------
     * Header
     * -----------------------------
     */
    private function buildHeaderHtml(string $name, string $contactLine): string
    {
        return <<<HTML
            <h1>{$name}</h1>
            <span>{$contactLine}</span>
        HTML;
    }

    /**
     * -----------------------------
     * File helpers
     * -----------------------------
     */
    private function buildFileName(?string $name, ?string $role): string
    {
        $name = ($name && $name !== 'Desconocido')
            ? $name
            : 'Nombre Desconocido';

        $role = ($role && $role !== 'Desconocido')
            ? $role
            : null;

        $file = $role
            ? "{$name} - {$role}.pdf"
            : "{$name}.pdf";

        return preg_replace('/[\/\\\\]/', '-', $file);
    }

    public function extractNameFromCv(string $cvText, string $language = 'en'): string
    {
        if ($language === 'es') {
            $prompt = "
            Extrae el nombre completo del candidato.
            Devuelve SOLO el nombre, si no puedes encontrar el nombre, devuelve \"Desconocido\".

            --- CV ---
            {$cvText}
            ";
        } else {
            $prompt = "
            Extract the full name of the candidate.
            Return ONLY the name, if you cannot find the name, return \"Unknown\".

            --- CV ---
            {$cvText}
            ";
        }

        return trim(
            OpenAI::chat()->create([
                'model' => 'gpt-4.1',
                'messages' => [['role' => 'user', 'content' => $prompt]],
            ])->choices[0]->message->content
        );
    }

    /**
     * -----------------------------
     * CV extraction
     * -----------------------------
     */
    private function extractCvText(string $path): string
    {
        if (! file_exists($path)) {
            throw new RuntimeException('Archivo de CV no encontrado.');
        }

        if (str_ends_with(strtolower($path), '.pdf')) {
            return $this->extractPdf($path);
        }

        return trim(file_get_contents($path));
    }

    private function extractPdf(string $path): string
    {
        try {
            return trim((new Parser)->parseFile($path)->getText());
        } catch (\Throwable) {
            throw new RuntimeException('No se puede leer el PDF del CV.');
        }
    }

    /**
     * -------- ----------------------
     * Generación de PDF
     * -----------------------------
     */
    private function generatePdf(string $html): string
    {
        return Pdf::loadHTML("
            <html>
                <head>
                    <style>
                        body {
                            font-family: 'Calibri', sans-serif;
                            font-size: 12px; /* normal text */
                            font-weight: normal;
                            line-height: 1.4;
                            margin: 30px;
                        }

                        h1 {
                            font-family: 'Calibri', sans-serif;
                            font-size: 14px; /* Calibri bold 14 */
                            font-weight: bold;
                            margin-bottom: 4px;
                            text-align: center;
                            border-bottom: 1px solid #000;
                            padding-bottom: 4px;
                        }

                        h2, h3, h4, h5, h6 {
                            font-family: 'Calibri', sans-serif;
                            font-size: 12px; /* headings 11 */
                            font-weight: bold; /* bold for all subheadings */
                            margin-top: 15px;
                            margin-bottom: 4px;
                            border-bottom: 1px solid #000;
                            padding-bottom: 4px;
                        }

                        span {
                            font-family: 'Calibri', sans-serif;
                            font-size: 12px;
                            display: block;
                            text-align: center;
                        }

                        p {
                            margin-top: 0px;
                            padding-top: 0px;
                            margin-bottom: 0px;
                            padding-bottom: 0px;
                        }

                        ul {
                            margin-top: 0px;
                            padding-top: 0px;
                            margin-bottom: 0px;
                            padding-bottom: 0px;
                        }

                        li {
                            margin-top: 0px;
                            padding-top: 0px;
                            margin-bottom: 0px;
                            padding-bottom: 0px;
                        }
                    </style>
                </head>
                <body>
                    $html
                </body>
            </html>
        ")->output();
    }

    /**
     * IA – Extracción
     */
    private function extractRequirements(string $jobOffer, string $language = 'en'): array
    {
        $prompt = $this->getPromptExtractRequirements($language, $jobOffer);

        $response = OpenAI::chat()->create([
            'model' => 'gpt-4.1',
            'messages' => [['role' => 'user', 'content' => $prompt]],
        ])->choices[0]->message->content ?? '';

        // Limpieza básica por si vienen fences ```json
        $response = trim($response);
        $response = preg_replace('/^```json|```$/i', '', $response);

        $decoded = json_decode($response, true);

        // 🔐 Fallback absoluto
        if (! is_array($decoded)) {
            return [];
        }

        return $decoded;
    }

    private function extractContactLine(string $cvText, string $language = 'en'): ?string
    {
        $prompt = $this->getPromptExtractContactLine($language, $cvText);

        return trim(
            OpenAI::chat()->create([
                'model' => 'gpt-4.1',
                'messages' => [['role' => 'user', 'content' => $prompt]],
            ])->choices[0]->message->content
        );
    }

    public function extractRole(string $jobOffer, string $language = 'en'): string
    {
        $prompt = $this->getPromptExtractRole($language, $jobOffer);

        return trim(
            OpenAI::chat()->create([
                'model' => 'gpt-4.1',
                'messages' => [['role' => 'user', 'content' => $prompt]],
            ])->choices[0]->message->content
        );
    }

    /**
     * AI – Tailoring
     */
    public function tailor(string $cvText, string $jobOffer, array $req, string $language = 'en'): string
    {
        $prompt = $this->getPromptTailor($language, $cvText, $jobOffer, $req);

        return trim(
            OpenAI::chat()->create([
                'model' => 'gpt-4.1',
                'messages' => [['role' => 'user', 'content' => $prompt]],
            ])->choices[0]->message->content
        );
    }
}
