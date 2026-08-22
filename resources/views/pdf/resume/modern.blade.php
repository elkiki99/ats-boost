{{--
    Plantilla «Moderna».

    Encabezado centrado, secciones separadas por una línea fina y fechas
    alineadas a la derecha de cada entrada. Es la plantilla por defecto.

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
</head>
<body>
    @include('pdf.resume.partials.body')
</body>
</html>
