{{--
    Plantilla «Compacta».

    Misma estructura y jerarquía que la moderna, con cuerpo e interlineado
    reducidos (ver ResumeTemplate::metrics()). Pensada para perfiles cuyo
    contenido no entra en una carilla.

    @var \App\Data\ResumeData $resume
    @var array $metrics
    @var string $font
    @var string $locale
--}}
<!DOCTYPE html>
<html lang="{{ $locale }}">
<head>
    <meta charset="utf-8">
    <title>{{ $resume->displayName() }}</title>
    @include('pdf.resume.partials.styles')

    <style>
        /* En una carilla apretada la línea de sección compite con el texto:
           se aliviana el trazo y se recorta el aire por debajo. */
        .section-title {
            border-bottom-color: #d7dbe4;
            margin-bottom: 3.5pt;
        }

        ul {
            margin-top: 2pt;
        }
    </style>
</head>
<body>
    @include('pdf.resume.partials.body')
</body>
</html>
