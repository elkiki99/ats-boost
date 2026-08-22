@props(['resume'])

{{--
    Vista del currículum en HTML, con la misma jerarquía que la plantilla del
    PDF (resources/views/pdf/resume/).

    Se usa donde todavía no hay un documento guardado que sirva por URL —hoy,
    la prueba pública de la landing—. El editor del panel muestra el PDF real
    en un iframe, porque ahí sí importa que lo que se ve sea exactamente lo
    que se descarga.

    @var \App\Data\ResumeData $resume
--}}
@php
    $locale = $resume->language->value;
    $section = fn (string $key): string => __("resume.sections.{$key}", [], $locale);
@endphp

<div {{ $attributes->merge(['class' => 'rounded-xl bg-white p-8 text-[13px] leading-relaxed text-zinc-800 shadow-sm ring-1 ring-zinc-200 sm:p-10']) }}>
    <header class="text-center">
        <h1 class="text-2xl tracking-tight text-zinc-900">{{ $resume->displayName() }}</h1>

        @if ($resume->headline)
            <p class="mt-1 text-zinc-600">{{ $resume->headline }}</p>
        @endif

        <p class="mt-0.5 text-xs text-zinc-600">{{ $resume->displayContactLine() }}</p>
    </header>

    @if ($resume->summary)
        <section class="mt-6">
            <h2 class="border-b border-zinc-300 pb-1 text-sm font-bold text-zinc-900">{{ $section('summary') }}</h2>
            <p class="mt-2">{{ $resume->summary }}</p>
        </section>
    @endif

    @if ($resume->experience)
        <section class="mt-6">
            <h2 class="border-b border-zinc-300 pb-1 text-sm font-bold text-zinc-900">{{ $section('experience') }}</h2>

            <div class="mt-3 space-y-4">
                @foreach ($resume->experience as $entry)
                    <article>
                        <div class="flex items-baseline justify-between gap-4">
                            <p class="font-bold text-zinc-900">
                                {{ $entry->role }}@if ($entry->company)<span class="font-normal">, {{ $entry->company }}</span>@endif
                            </p>
                            @if ($entry->dates)
                                <p class="shrink-0 text-xs text-zinc-600">{{ $entry->dates }}</p>
                            @endif
                        </div>

                        @if ($entry->location)
                            <p class="text-xs text-zinc-600">{{ $entry->location }}</p>
                        @endif

                        @if ($entry->bullets)
                            <ul class="mt-1.5 list-disc space-y-1 ps-5">
                                @foreach ($entry->bullets as $bullet)
                                    <li>{{ $bullet }}</li>
                                @endforeach
                            </ul>
                        @endif
                    </article>
                @endforeach
            </div>
        </section>
    @endif

    @if ($resume->education)
        <section class="mt-6">
            <h2 class="border-b border-zinc-300 pb-1 text-sm font-bold text-zinc-900">{{ $section('education') }}</h2>

            <div class="mt-3 space-y-3">
                @foreach ($resume->education as $entry)
                    <article>
                        <div class="flex items-baseline justify-between gap-4">
                            <p class="font-bold text-zinc-900">
                                {{ $entry->degree }}@if ($entry->institution)<span class="font-normal">, {{ $entry->institution }}</span>@endif
                            </p>
                            @if ($entry->dates)
                                <p class="shrink-0 text-xs text-zinc-600">{{ $entry->dates }}</p>
                            @endif
                        </div>

                        @if ($entry->location)
                            <p class="text-xs text-zinc-600">{{ $entry->location }}</p>
                        @endif

                        @if ($entry->description)
                            <p class="mt-1">{{ $entry->description }}</p>
                        @endif
                    </article>
                @endforeach
            </div>
        </section>
    @endif

    @if ($resume->projects)
        <section class="mt-6">
            <h2 class="border-b border-zinc-300 pb-1 text-sm font-bold text-zinc-900">{{ $section('projects') }}</h2>

            <ul class="mt-3 list-disc space-y-1.5 ps-5">
                @foreach ($resume->projects as $project)
                    <li>
                        <span class="font-bold text-zinc-900">{{ $project->name }}</span>@if ($project->description) — {{ $project->description }}@endif
                        @if ($project->meta)<span class="text-zinc-600">{{ $project->meta }}</span>@endif
                        @if ($project->link)<span class="text-zinc-600">{{ $project->link }}</span>@endif
                    </li>
                @endforeach
            </ul>
        </section>
    @endif

    @if ($resume->skills)
        <section class="mt-6">
            <h2 class="border-b border-zinc-300 pb-1 text-sm font-bold text-zinc-900">{{ $section('skills') }}</h2>

            <dl class="mt-3 space-y-1.5">
                @foreach ($resume->skills as $group)
                    <div class="flex gap-3">
                        <dt class="w-28 shrink-0 font-bold text-zinc-900">{{ $group->label }}</dt>
                        <dd>{{ $group->value }}</dd>
                    </div>
                @endforeach
            </dl>
        </section>
    @endif

    @if ($resume->certifications)
        <section class="mt-6">
            <h2 class="border-b border-zinc-300 pb-1 text-sm font-bold text-zinc-900">{{ $section('certifications') }}
            </h2>

            <ul class="mt-3 list-disc space-y-1 ps-5">
                @foreach ($resume->certifications as $certification)
                    <li>{{ $certification->line() }}@if ($certification->year)<span class="text-zinc-600">, {{ $certification->year }}</span>@endif</li>
                @endforeach
            </ul>
        </section>
    @endif
</div>
