@props([
    'target',
    'heading' => 'Trabajando…',
    'steps' => [],
])

{{--
    Cortina de espera sobre toda la pantalla mientras corre la generación.

    Reemplaza al modal de progreso que se abría con un evento aparte: aquel
    necesitaba dos viajes al servidor y quedaba abierto para siempre si el
    segundo fallaba. Con wire:loading el estado lo maneja Livewire y se cierra
    solo, incluso cuando la petición termina en error.
--}}
<div wire:loading.flex wire:target="{{ $target }}"
    class="fixed inset-0 z-50 hidden items-center justify-center bg-white/80 backdrop-blur-sm dark:bg-zinc-900/80">
    <div class="flex w-full max-w-sm flex-col items-center gap-5 px-6 text-center">
        <flux:icon.loading class="size-8 text-accent" />

        <div>
            <flux:heading size="lg">{{ $heading }}</flux:heading>
            <flux:subheading>Suele tardar entre 15 y 40 segundos. No cierres la pestaña.</flux:subheading>
        </div>

        @if ($steps)
            <ul class="w-full space-y-1.5 text-start text-sm text-zinc-500 dark:text-zinc-400">
                @foreach ($steps as $step)
                    <li class="flex items-start gap-2">
                        <flux:icon.check-circle variant="micro" class="mt-0.5 shrink-0 text-zinc-400" />
                        <span>{{ $step }}</span>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
</div>
