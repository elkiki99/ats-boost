{{--
    Cuerpo del currículum.

    @var \App\Data\ResumeData $resume
    @var string $locale
--}}
@php
    $section = fn (string $key): string => __("resume.sections.{$key}", [], $locale);
@endphp

<h1 class="name">{{ $resume->displayName() }}</h1>

@if ($resume->headline)
    <p class="headline">{{ $resume->headline }}</p>
@endif

<p class="contact">{{ $resume->displayContactLine() }}</p>

@if ($resume->summary)
    <div class="section">
        <h2 class="section-title">{{ $section('summary') }}</h2>
        <p style="margin: 0;">{{ $resume->summary }}</p>
    </div>
@endif

@if ($resume->experience)
    <div class="section">
        <h2 class="section-title">{{ $section('experience') }}</h2>

        @foreach ($resume->experience as $entry)
            <div class="entry">
                {{-- El bloque de fechas se emite antes del título: DomPDF ancla
                     el float a la línea en curso, no a la anterior. --}}
                @if ($entry->dates)
                    <span class="entry-dates">{{ $entry->dates }}</span>
                @endif

                {{-- La coma queda fuera del <span> a propósito: DomPDF cierra
                     un fragmento de texto en cada cambio de estilo, y al
                     extraer el PDF eso inserta un espacio. Con la coma
                     adentro, un ATS lee "Desarrollador , Empresa". --}}
                <p class="entry-title">
                    {{ $entry->role }}@if ($entry->company),<span class="org"> {{ $entry->company }}</span>@endif
                </p>

                @if ($entry->location)
                    <p class="entry-meta">{{ $entry->location }}</p>
                @endif

                @if ($entry->bullets)
                    <ul>
                        @foreach ($entry->bullets as $bullet)
                            <li>{{ $bullet }}</li>
                        @endforeach
                    </ul>
                @endif
            </div>
        @endforeach
    </div>
@endif

@if ($resume->education)
    <div class="section">
        <h2 class="section-title">{{ $section('education') }}</h2>

        @foreach ($resume->education as $entry)
            <div class="entry">
                @if ($entry->dates)
                    <span class="entry-dates">{{ $entry->dates }}</span>
                @endif

                <p class="entry-title">
                    {{ $entry->degree }}@if ($entry->institution),<span class="org"> {{ $entry->institution }}</span>@endif
                </p>

                @if ($entry->location)
                    <p class="entry-meta">{{ $entry->location }}</p>
                @endif

                @if ($entry->description)
                    <p class="entry-note">{{ $entry->description }}</p>
                @endif
            </div>
        @endforeach
    </div>
@endif

@if ($resume->projects)
    <div class="section">
        <h2 class="section-title">{{ $section('projects') }}</h2>

        <ul>
            @foreach ($resume->projects as $project)
                <li>
                    <span class="inline-name">{{ $project->name }}</span>@if ($project->description) — {{ $project->description }}@endif
                    @if ($project->meta)<span class="muted">{{ $project->meta }}</span>@endif
                    @if ($project->link)<span class="muted">{{ $project->link }}</span>@endif
                </li>
            @endforeach
        </ul>
    </div>
@endif

@if ($resume->skills)
    <div class="section">
        <h2 class="section-title">{{ $section('skills') }}</h2>

        <table class="skills">
            @foreach ($resume->skills as $group)
                <tr>
                    <td class="label">{{ $group->label }}</td>
                    <td>{{ $group->value }}</td>
                </tr>
            @endforeach
        </table>
    </div>
@endif

@if ($resume->certifications)
    <div class="section">
        <h2 class="section-title">{{ $section('certifications') }}</h2>

        <ul>
            @foreach ($resume->certifications as $certification)
                <li>{{ $certification->line() }}@if ($certification->year)<span class="muted">, {{ $certification->year }}</span>@endif</li>
            @endforeach
        </ul>
    </div>
@endif
