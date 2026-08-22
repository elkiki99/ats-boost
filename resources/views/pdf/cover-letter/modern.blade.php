{{--
    Carta de presentación en PDF.

    Comparte encabezado y tipografía con el currículum a propósito: el
    reclutador recibe los dos archivos juntos y tienen que leerse como un
    mismo documento.

    @var \App\Data\CoverLetterData $letter
    @var array $metrics
    @var string $font
    @var string $locale
    @var string $date
--}}
<!DOCTYPE html>
<html lang="{{ $locale }}">
<head>
    <meta charset="utf-8">
    <title>{{ $letter->displayName() }}</title>

    <style>
        @page {
            margin: {{ $metrics['margin'] }};
        }

        body {
            font-family: {{ $font }}, sans-serif;
            font-size: {{ $metrics['base'] + 0.5 }}pt;
            line-height: 1.55;
            color: #1f2430;
            margin: 0;
        }

        .name {
            font-size: {{ $metrics['name'] }}pt;
            font-weight: normal;
            text-align: center;
            letter-spacing: -0.2pt;
            margin: 0 0 3pt;
            color: #11151f;
        }

        .contact {
            font-size: {{ $metrics['base'] - 0.7 }}pt;
            text-align: center;
            color: #4a5162;
            margin: 0;
        }

        .rule {
            border-bottom: 0.6pt solid #c9cedb;
            margin: {{ $metrics['section_gap'] }}pt 0;
        }

        .date {
            font-size: {{ $metrics['base'] - 0.5 }}pt;
            color: #5b6274;
            text-align: right;
            margin: 0 0 12pt;
        }

        .greeting {
            margin: 0 0 10pt;
        }

        p.body-text {
            margin: 0 0 9pt;
            text-align: justify;
        }

        .closing {
            margin: 14pt 0 0;
        }

        .signature {
            margin: 2pt 0 0;
            font-weight: bold;
            color: #11151f;
        }
    </style>
</head>
<body>
    <h1 class="name">{{ $letter->displayName() }}</h1>

    @unless ($letter->contact->isEmpty())
        <p class="contact">{{ $letter->contact->line() }}</p>
    @endunless

    <div class="rule"></div>

    <p class="date">{{ $date }}</p>

    @if ($letter->greeting)
        <p class="greeting">{{ $letter->greeting }}</p>
    @endif

    @foreach ($letter->paragraphs as $paragraph)
        <p class="body-text">{{ $paragraph }}</p>
    @endforeach

    @if ($letter->closing)
        <p class="closing">{{ $letter->closing }}</p>
    @endif

    <p class="signature">{{ $letter->displayName() }}</p>
</body>
</html>
