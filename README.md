# ATS Boost

Adapta un currículum a una oferta de trabajo concreta, lo puntúa contra filtros
ATS y redacta la carta de presentación. Laravel 12 · Livewire 3 · Flux Pro ·
OpenAI · Mercado Pago.

## Puesta en marcha

```bash
composer setup          # instala, genera la clave, migra y compila assets
composer dev            # servidor + cola + vite
php artisan test
```

Hace falta `OPENAI_API_KEY`. Sin ella no funciona ninguna de las tres
herramientas. Ver `.env.example` para el resto.

## Cómo está armado

El dominio se apoya en tres piezas: **datos estructurados**, **acciones** y
**plantillas Blade**. No hay ninguna capa que manipule HTML generado por el
modelo.

```
Archivo subido
  └─ ExtractResumeText      PDF/TXT → texto plano normalizado
       └─ ParseResume       texto   → ResumeData        (llamada 1)
  Oferta de trabajo
       └─ AnalyzeJobPosting texto   → JobPostingData    (llamada 2)
            └─ TailorResume ResumeData + JobPostingData → ResumeData (llamada 3)
                 └─ StoreDocument   → Document (payload JSON)
                      └─ RenderResumePdf → PDF vía resources/views/pdf/
```

### `app/Data` — el contrato

`ResumeData`, `CoverLetterData`, `JobPostingData` y `AtsReportData` son objetos
`readonly` que viajan entre acciones, se guardan como JSON y alimentan las
plantillas. `Data\Cast` normaliza lo que llega del modelo, de la base y del
editor: los tres mienten de formas distintas.

Que el currículum sea un dato estructurado y no HTML es lo que permite que el
PDF garantice el diseño — fechas alineadas a la derecha, tabla de habilidades,
encabezado centrado — y que el usuario lo edite campo por campo sin poder
romper el formato.

### `app/Actions` — un paso, una clase

Cada acción hace una cosa y se inyecta por constructor. Las que empiezan con
`Generate*` orquestan a las demás y son las únicas que llaman los componentes
Livewire.

### `app/Services/OpenAi` — salida estructurada

`StructuredCompletion` es el único punto que habla con OpenAI. Pide salida
validada contra un `JsonSchema` en modo `strict`, reintenta y traduce los
fallos a `ResumeGenerationException`, cuyos mensajes están escritos para
mostrarse tal cual al usuario. Ninguna capa de arriba parsea texto.

Los prompts viven en `app/Prompts` y son cortos: el formato lo impone el
esquema, así que en el prompt solo queda lo que un esquema no puede expresar
(honestidad sobre los datos, tono, idioma).

### `app/Livewire/Forms` — las requests dedicadas

En Livewire el equivalente a una `FormRequest` es un objeto `Form`. Ahí vive
toda la validación, incluida la del editor (`ResumeEditorForm`), con sus reglas
anidadas. Las `FormRequest` de verdad quedan para los endpoints HTTP
(`StartCheckoutRequest`).

### `resources/views/pdf` — el diseño

Una plantilla Blade por diseño (`modern`, `compact`), compartiendo
`partials/body` y `partials/styles`. `ResumeTemplate::metrics()` define la
escala tipográfica de cada una.

Está escrito contra DomPDF, no contra un navegador: no hay flex ni grid, todo
se mide en puntos y la fuente tiene que ser una de las que DomPDF trae
incrustadas. Los comentarios de `partials/styles.blade.php` explican cada
decisión.

Para agregar un diseño: sumar un caso a `ResumeTemplate`, sus métricas y la
vista. Los documentos ya generados pueden cambiarse de plantilla sin migrar
nada, porque el PDF se regenera en cada descarga a partir del JSON.

## Persistencia

`documents` guarda el documento estructurado, **nunca el PDF**. El binario se
compila en cada descarga desde `DocumentController`. Así no quedan archivos con
datos personales en el servidor y un ajuste de plantilla alcanza al historial
completo.

## Tests

```bash
php artisan test
php artisan test --filter=ResumePdf   # compila PDFs y les vuelve a extraer el texto
```

Los tests de PDF generan el archivo de verdad y verifican el texto resultante:
DomPDF no falla cuando un bloque no se renderiza, simplemente lo omite. Los
flujos de IA usan `OpenAI::fake()` mediante los helpers de `tests/Pest.php`.
