<div>
    <x-resume.page-header title="{{ __('Mis documentos') }}"
        description="{{ __('Todo lo que generaste. Podés reeditarlo y volver a descargarlo sin gastar una nueva generación.') }}" />

    <div class="mb-6 flex flex-wrap items-center gap-3">
        <flux:input wire:model.live.debounce.300ms="search" icon="magnifying-glass"
            :placeholder="__('Buscar por puesto o empresa')" class="max-w-xs" />

        <flux:select wire:model.live="type" class="max-w-48">
            @foreach ($this->typeOptions() as $value => $label)
                <flux:select.option value="{{ $value }}">{{ $label }}</flux:select.option>
            @endforeach
        </flux:select>

        <flux:spacer />

        <flux:button :href="route('resume.tailor')" icon="sparkles" variant="primary" size="sm" wire:navigate>
            {{ __('Adaptar un CV') }}
        </flux:button>
    </div>

    @if ($documents->isEmpty())
        <flux:card class="flex flex-col items-center justify-center gap-4 py-16 text-center">
            <flux:icon.document-text class="size-10 text-zinc-300 dark:text-zinc-600" />

            <div>
                <flux:heading>
                    {{ $search !== '' || $type !== '' ? __('No encontramos documentos con ese filtro') : __('Todavía no generaste ningún documento') }}
                </flux:heading>
                <flux:subheading>
                    {{ $search !== '' || $type !== '' ? __('Probá con otro término o quitá el filtro.') : __('Empezá adaptando tu currículum a una oferta concreta.') }}
                </flux:subheading>
            </div>

            @if ($search === '' && $type === '')
                <flux:button :href="route('resume.tailor')" icon="sparkles" variant="primary" wire:navigate>
                    {{ __('Adaptar mi CV') }}
                </flux:button>
            @endif
        </flux:card>
    @else
        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
            @foreach ($documents as $document)
                <flux:card class="flex flex-col justify-between gap-4" wire:key="doc-{{ $document->id }}">
                    <div>
                        <div class="mb-3 flex items-start justify-between gap-3">
                            <flux:badge size="sm" color="zinc" :icon="$document->type->icon()">
                                {{ $document->type->label() }}
                            </flux:badge>

                            @if ($document->ats_score !== null)
                                <flux:badge size="sm" :color="match (true) {
                                    $document->ats_score >= 80 => 'green',
                                    $document->ats_score >= 60 => 'amber',
                                    default => 'red',
                                }">
                                    {{ $document->ats_score }}/100
                                </flux:badge>
                            @endif
                        </div>

                        <flux:heading size="sm" class="line-clamp-2">{{ $document->title }}</flux:heading>

                        <flux:text size="sm" class="mt-1 text-zinc-500">
                            {{ $document->created_at->isoFormat('D MMM YYYY') }}
                            @if ($document->source_filename)
                                · {{ Str::limit($document->source_filename, 28) }}
                            @endif
                        </flux:text>
                    </div>

                    <div class="flex items-center gap-2">
                        <flux:button :href="route('documents.edit', $document)" icon="pencil-square" size="sm"
                            variant="filled" class="flex-1" wire:navigate>
                            {{ __('Editar') }}
                        </flux:button>

                        <flux:button :href="route('documents.download', $document)" icon="arrow-down-tray"
                            size="sm" variant="ghost" :tooltip="__('Descargar PDF')" />

                        <flux:button wire:click="delete({{ $document->id }})"
                            wire:confirm="{{ __('¿Eliminar este documento? No se puede deshacer.') }}" icon="trash"
                            size="sm" variant="ghost" :tooltip="__('Eliminar')" />
                    </div>
                </flux:card>
            @endforeach
        </div>

        <flux:pagination :paginator="$documents" class="mt-6" />
    @endif
</div>
