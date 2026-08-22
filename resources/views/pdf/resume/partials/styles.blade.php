{{--
    Hoja de estilos del currículum en PDF.

    Escrita contra DomPDF, no contra un navegador. Tres restricciones marcan
    todas las decisiones de acá abajo:

    1. La familia tipográfica tiene que ser una de las que DomPDF trae
       incrustadas. Helvetica no cubre –, · ni las tildes, así que se usa
       DejaVu Sans: es la única con cobertura Unicode completa de fábrica.
    2. No hay flexbox ni grid. La fecha alineada a la derecha se resuelve con
       float, que es lo único que DomPDF posiciona de forma predecible.
    3. Todo se mide en puntos. Los píxeles se reinterpretan según el DPI y
       hacen que el interlineado cambie entre entornos.
--}}
<style>
    @page {
        margin: {{ $metrics['margin'] }};
    }

    body {
        font-family: {{ $font }}, sans-serif;
        font-size: {{ $metrics['base'] }}pt;
        line-height: {{ $metrics['line_height'] }};
        color: #1f2430;
        margin: 0;
    }

    /* ---------- Encabezado ---------- */

    .name {
        font-size: {{ $metrics['name'] }}pt;
        font-weight: normal;
        text-align: center;
        letter-spacing: -0.2pt;
        margin: 0 0 3pt;
        color: #11151f;
    }

    .headline {
        font-size: {{ $metrics['base'] + 0.5 }}pt;
        text-align: center;
        color: #4a5162;
        margin: 0 0 2pt;
    }

    .contact {
        font-size: {{ $metrics['base'] - 0.7 }}pt;
        text-align: center;
        color: #4a5162;
        margin: 0;
    }

    /* ---------- Secciones ---------- */

    .section {
        margin-top: {{ $metrics['section_gap'] }}pt;
    }

    .section-title {
        font-size: {{ $metrics['section'] }}pt;
        font-weight: bold;
        color: #11151f;
        margin: 0 0 5pt;
        padding-bottom: 2.5pt;
        border-bottom: 0.6pt solid #c9cedb;
    }

    /* ---------- Entradas ---------- */

    .entry {
        margin-bottom: {{ $metrics['entry_gap'] }}pt;
        /* Evita que el título de un puesto quede solo al pie de una página. */
        page-break-inside: avoid;
    }

    .entry:last-child {
        margin-bottom: 0;
    }

    /* El float va primero en el HTML porque DomPDF resuelve el flotante
       contra la línea que ya empezó, no contra la anterior. */
    .entry-dates {
        float: right;
        font-size: {{ $metrics['base'] - 0.5 }}pt;
        color: #5b6274;
        padding-left: 8pt;
    }

    .entry-title {
        font-size: {{ $metrics['base'] + 0.5 }}pt;
        font-weight: bold;
        color: #11151f;
        margin: 0;
    }

    .entry-title .org {
        font-weight: normal;
    }

    .entry-meta {
        clear: both;
        font-size: {{ $metrics['base'] - 0.5 }}pt;
        color: #5b6274;
        margin: 0.5pt 0 0;
    }

    .entry-note {
        margin: 2.5pt 0 0;
    }

    /* ---------- Viñetas ---------- */

    ul {
        margin: 3pt 0 0;
        padding-left: 12pt;
    }

    li {
        margin-bottom: 1.5pt;
        padding-left: 1pt;
    }

    li:last-child {
        margin-bottom: 0;
    }

    /* ---------- Habilidades ---------- */

    /* Única tabla del documento. Un párrafo con indentación colgante sería
       más limpio para un ATS, pero DomPDF no mantiene la sangría cuando el
       valor ocupa dos renglones y las habilidades terminan pisando la
       etiqueta. Una tabla de dos celdas sin bordes se extrae como texto
       corrido ("Frameworks Laravel, Livewire, …"), así que el costo real
       para el parser es nulo. */
    .skills {
        width: 100%;
        border-collapse: collapse;
    }

    .skills td {
        vertical-align: top;
        padding: 0 0 2.5pt;
    }

    .skills .label {
        /* Ancho calibrado contra la etiqueta más larga que el modelo suele
           devolver ("Bases de datos"): más angosto y parte en dos renglones. */
        width: 88pt;
        font-weight: bold;
        padding-right: 8pt;
        color: #11151f;
    }

    /* ---------- Proyectos y certificaciones ---------- */

    .inline-name {
        font-weight: bold;
        color: #11151f;
    }

    .muted {
        color: #5b6274;
    }
</style>
