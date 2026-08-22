{{--
    Editor por campos del currículum.

    Cada control apunta a una ruta del array de ResumeEditorForm, así que lo
    que el usuario escribe entra directo al mismo documento estructurado que
    consume la plantilla del PDF. No hay HTML intermedio que se pueda romper.
--}}
<div class="space-y-4">
    <flux:card class="space-y-4">
        <flux:heading size="sm">{{ __('Encabezado') }}</flux:heading>

        <flux:input wire:model.blur="resumeForm.data.full_name" :label="__('Nombre y apellido')" />

        <flux:input wire:model.blur="resumeForm.data.headline" :label="__('Titular')"
            placeholder="Desarrollador de Software — PHP · Laravel · Vue.js" />

        <div class="grid gap-4 sm:grid-cols-2">
            <flux:input wire:model.blur="resumeForm.data.contact.location" :label="__('Ubicación')"
                placeholder="Montevideo, Uruguay" />
            <flux:input wire:model.blur="resumeForm.data.contact.phone" :label="__('Teléfono')" />
        </div>

        <flux:input wire:model.blur="resumeForm.data.contact.email" type="email" :label="__('Correo')" />

        <flux:field>
            <flux:label>{{ __('Enlaces') }}</flux:label>

            <div class="space-y-2">
                @foreach ($resumeForm->data['contact']['links'] ?? [] as $index => $link)
                    <div class="flex items-center gap-2" wire:key="link-{{ $index }}">
                        <flux:input wire:model.blur="resumeForm.data.contact.links.{{ $index }}"
                            placeholder="github.com/usuario" class="flex-1" />
                        <flux:button wire:click="removeLink({{ $index }})" icon="trash" variant="subtle"
                            size="sm" :tooltip="__('Quitar')" />
                    </div>
                @endforeach
            </div>

            <flux:button wire:click="addLink" icon="plus" size="sm" variant="ghost" class="mt-2">
                {{ __('Agregar enlace') }}
            </flux:button>

            <flux:description>{{ __('Sin https:// ni www. Por ejemplo: linkedin.com/in/usuario') }}</flux:description>
        </flux:field>

        <flux:textarea wire:model.blur="resumeForm.data.summary" :label="__('Perfil')" rows="3"
            :placeholder="__('Opcional. Dejalo vacío si preferís que el CV arranque por la experiencia.')" />
    </flux:card>

    {{-- Experiencia --}}
    <flux:card class="space-y-4">
        <div class="flex items-center justify-between">
            <flux:heading size="sm">
                {{ __('Experiencia') }}
                <flux:badge size="sm" color="zinc">{{ count($resumeForm->data['experience'] ?? []) }}</flux:badge>
            </flux:heading>

            <flux:button wire:click="addEntry('experience')" icon="plus" size="sm" variant="ghost">
                {{ __('Agregar') }}
            </flux:button>
        </div>

        @forelse ($resumeForm->data['experience'] ?? [] as $index => $entry)
            <div class="rounded-lg border border-zinc-200 p-4 dark:border-zinc-700" wire:key="exp-{{ $index }}">
                <div class="mb-3 flex items-center justify-end gap-1">
                    <flux:button wire:click="moveEntry('experience', {{ $index }}, -1)" icon="chevron-up"
                        size="xs" variant="subtle" :disabled="$index === 0" :tooltip="__('Subir')" />
                    <flux:button wire:click="moveEntry('experience', {{ $index }}, 1)" icon="chevron-down"
                        size="xs" variant="subtle"
                        :disabled="$index === count($resumeForm->data['experience'] ?? []) - 1" :tooltip="__('Bajar')" />
                    <flux:button wire:click="removeEntry('experience', {{ $index }})" icon="trash"
                        size="xs" variant="subtle" :tooltip="__('Eliminar puesto')" />
                </div>

                <div class="space-y-3">
                    <div class="grid gap-3 sm:grid-cols-2">
                        <flux:input wire:model.blur="resumeForm.data.experience.{{ $index }}.role"
                            :label="__('Cargo')" />
                        <flux:input wire:model.blur="resumeForm.data.experience.{{ $index }}.company"
                            :label="__('Empresa')" />
                    </div>

                    <div class="grid gap-3 sm:grid-cols-2">
                        <flux:input wire:model.blur="resumeForm.data.experience.{{ $index }}.location"
                            :label="__('Ubicación')" />
                        <flux:input wire:model.blur="resumeForm.data.experience.{{ $index }}.dates"
                            :label="__('Fechas')" placeholder="May 2026 – Presente" />
                    </div>

                    <flux:field>
                        <flux:label>{{ __('Logros') }}</flux:label>

                        <div class="space-y-2">
                            @foreach ($entry['bullets'] ?? [] as $bulletIndex => $bullet)
                                <div class="flex items-start gap-2" wire:key="exp-{{ $index }}-b-{{ $bulletIndex }}">
                                    <flux:textarea
                                        wire:model.blur="resumeForm.data.experience.{{ $index }}.bullets.{{ $bulletIndex }}"
                                        rows="2" class="flex-1" />
                                    <flux:button
                                        wire:click="removeBullet({{ $index }}, {{ $bulletIndex }})"
                                        icon="trash" variant="subtle" size="sm" :tooltip="__('Quitar')" />
                                </div>
                            @endforeach
                        </div>

                        <flux:button wire:click="addBullet({{ $index }})" icon="plus" size="sm"
                            variant="ghost" class="mt-2">
                            {{ __('Agregar logro') }}
                        </flux:button>

                        <flux:description>
                            {{ __('Empezá con un verbo de acción y terminá en un resultado. Máximo 5 por puesto.') }}
                        </flux:description>
                    </flux:field>
                </div>
            </div>
        @empty
            <flux:text class="text-zinc-500">{{ __('Todavía no hay puestos cargados.') }}</flux:text>
        @endforelse
    </flux:card>

    {{-- Educación --}}
    <flux:card class="space-y-4">
        <div class="flex items-center justify-between">
            <flux:heading size="sm">
                {{ __('Educación') }}
                <flux:badge size="sm" color="zinc">{{ count($resumeForm->data['education'] ?? []) }}</flux:badge>
            </flux:heading>

            <flux:button wire:click="addEntry('education')" icon="plus" size="sm" variant="ghost">
                {{ __('Agregar') }}
            </flux:button>
        </div>

        @foreach ($resumeForm->data['education'] ?? [] as $index => $entry)
            <div class="space-y-3 rounded-lg border border-zinc-200 p-4 dark:border-zinc-700"
                wire:key="edu-{{ $index }}">
                <div class="flex items-center justify-end gap-1">
                    <flux:button wire:click="moveEntry('education', {{ $index }}, -1)" icon="chevron-up"
                        size="xs" variant="subtle" :disabled="$index === 0" />
                    <flux:button wire:click="moveEntry('education', {{ $index }}, 1)" icon="chevron-down"
                        size="xs" variant="subtle"
                        :disabled="$index === count($resumeForm->data['education'] ?? []) - 1" />
                    <flux:button wire:click="removeEntry('education', {{ $index }})" icon="trash"
                        size="xs" variant="subtle" />
                </div>

                <div class="grid gap-3 sm:grid-cols-2">
                    <flux:input wire:model.blur="resumeForm.data.education.{{ $index }}.degree"
                        :label="__('Título')" />
                    <flux:input wire:model.blur="resumeForm.data.education.{{ $index }}.institution"
                        :label="__('Institución')" />
                </div>

                <div class="grid gap-3 sm:grid-cols-2">
                    <flux:input wire:model.blur="resumeForm.data.education.{{ $index }}.location"
                        :label="__('Ubicación')" />
                    <flux:input wire:model.blur="resumeForm.data.education.{{ $index }}.dates"
                        :label="__('Fechas')" placeholder="2024 – 2027" />
                </div>

                <flux:textarea wire:model.blur="resumeForm.data.education.{{ $index }}.description"
                    :label="__('Detalle')" rows="2"
                    :placeholder="__('Materias relevantes, campus, mención académica.')" />
            </div>
        @endforeach
    </flux:card>

    {{-- Proyectos --}}
    <flux:card class="space-y-4">
        <div class="flex items-center justify-between">
            <flux:heading size="sm">
                {{ __('Proyectos') }}
                <flux:badge size="sm" color="zinc">{{ count($resumeForm->data['projects'] ?? []) }}</flux:badge>
            </flux:heading>

            <flux:button wire:click="addEntry('projects')" icon="plus" size="sm" variant="ghost">
                {{ __('Agregar') }}
            </flux:button>
        </div>

        @foreach ($resumeForm->data['projects'] ?? [] as $index => $entry)
            <div class="space-y-3 rounded-lg border border-zinc-200 p-4 dark:border-zinc-700"
                wire:key="proj-{{ $index }}">
                <div class="flex items-center justify-end gap-1">
                    <flux:button wire:click="moveEntry('projects', {{ $index }}, -1)" icon="chevron-up"
                        size="xs" variant="subtle" :disabled="$index === 0" />
                    <flux:button wire:click="moveEntry('projects', {{ $index }}, 1)" icon="chevron-down"
                        size="xs" variant="subtle"
                        :disabled="$index === count($resumeForm->data['projects'] ?? []) - 1" />
                    <flux:button wire:click="removeEntry('projects', {{ $index }})" icon="trash"
                        size="xs" variant="subtle" />
                </div>

                <flux:input wire:model.blur="resumeForm.data.projects.{{ $index }}.name" :label="__('Nombre')" />

                <flux:textarea wire:model.blur="resumeForm.data.projects.{{ $index }}.description"
                    :label="__('Descripción')" rows="2" />

                <div class="grid gap-3 sm:grid-cols-2">
                    <flux:input wire:model.blur="resumeForm.data.projects.{{ $index }}.meta"
                        :label="__('Contexto')" placeholder="Estructuras de Datos y Algoritmos, 2025" />
                    <flux:input wire:model.blur="resumeForm.data.projects.{{ $index }}.link" :label="__('Enlace')"
                        placeholder="ats-boost.com" />
                </div>
            </div>
        @endforeach
    </flux:card>

    {{-- Habilidades --}}
    <flux:card class="space-y-4">
        <div class="flex items-center justify-between">
            <flux:heading size="sm">
                {{ __('Habilidades') }}
                <flux:badge size="sm" color="zinc">{{ count($resumeForm->data['skills'] ?? []) }}</flux:badge>
            </flux:heading>

            <flux:button wire:click="addEntry('skills')" icon="plus" size="sm" variant="ghost">
                {{ __('Agregar') }}
            </flux:button>
        </div>

        @foreach ($resumeForm->data['skills'] ?? [] as $index => $group)
            <div class="flex items-start gap-2" wire:key="skill-{{ $index }}">
                <flux:input wire:model.blur="resumeForm.data.skills.{{ $index }}.label" class="w-40 shrink-0"
                    placeholder="Frameworks" />
                <flux:input wire:model.blur="resumeForm.data.skills.{{ $index }}.value" class="flex-1"
                    placeholder="Laravel, Livewire, Vue.js, Tailwind CSS" />
                <flux:button wire:click="removeEntry('skills', {{ $index }})" icon="trash"
                    variant="subtle" size="sm" />
            </div>
        @endforeach

        <flux:description>
            {{ __('Poné arriba las categorías que la oferta menciona: el reclutador lee las dos primeras filas.') }}
        </flux:description>
    </flux:card>

    {{-- Certificaciones --}}
    <flux:card class="space-y-4">
        <div class="flex items-center justify-between">
            <flux:heading size="sm">
                {{ __('Certificaciones') }}
                <flux:badge size="sm" color="zinc">{{ count($resumeForm->data['certifications'] ?? []) }}</flux:badge>
            </flux:heading>

            <flux:button wire:click="addEntry('certifications')" icon="plus" size="sm" variant="ghost">
                {{ __('Agregar') }}
            </flux:button>
        </div>

        @foreach ($resumeForm->data['certifications'] ?? [] as $index => $certification)
            <div class="flex items-start gap-2" wire:key="cert-{{ $index }}">
                <flux:input wire:model.blur="resumeForm.data.certifications.{{ $index }}.name" class="flex-1"
                    placeholder="CS50's Introduction to Computer Science" />
                <flux:input wire:model.blur="resumeForm.data.certifications.{{ $index }}.issuer" class="w-48"
                    placeholder="Harvard Online" />
                <flux:input wire:model.blur="resumeForm.data.certifications.{{ $index }}.year" class="w-24"
                    placeholder="2025" />
                <flux:button wire:click="removeEntry('certifications', {{ $index }})" icon="trash"
                    variant="subtle" size="sm" />
            </div>
        @endforeach
    </flux:card>
</div>
