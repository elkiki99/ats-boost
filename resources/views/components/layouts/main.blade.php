<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    @include('partials.head')
</head>

<body class="min-h-screen bg-white dark:bg-zinc-800">
    <livewire:main.navbar />

    <flux:main container>
        {{ $slot }}
    </flux:main>

    @include('components.footer')

    @fluxScripts

    @persist('toast')
        <flux:toast />
    @endpersist
</body>

<div aria-hidden="true" class="pointer-events-none fixed inset-0 -z-10">

    <!-- Blob 1 -->
    <div
        class="absolute -top-40 -left-32
               w-[38rem] h-[38rem]
               bg-sky-200/40 dark:bg-sky-900/30
               rounded-full blur-3xl">
    </div>

    <!-- Blob 2 -->
    <div
        class="absolute top-1/3 -right-40
               w-[42rem] h-[42rem]
               bg-indigo-200/30 dark:bg-indigo-900/25
               rounded-full blur-3xl">
    </div>

    <!-- Blob 3 (sutil, central) -->
    <div
        class="absolute bottom-[-12rem] left-1/4
               w-[32rem] h-[32rem]
               bg-blue-100/40 dark:bg-blue-950/40
               rounded-full blur-3xl">
    </div>
</div>

</html>
