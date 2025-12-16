<flux:header sticky container class="relative bg-white dark:bg-zinc-800 border-b border-zinc-200 dark:border-zinc-600">
    <!-- Left -->
    <div class="flex items-center gap-3">
        <flux:sidebar.toggle class="lg:hidden" icon="bars-2" />

        <a href="{{ route('home') }}" class="flex items-center gap-2 font-medium" wire:navigate>
            <span class="flex h-7 w-7 items-center justify-center rounded-md">
                <x-app-logo-icon class="size-7 text-blue-600/90 dark:text-white" />
            </span>
            <flux:text size="lg" class="text-blue-600/90 dark:text-white">
                ATS Boost
            </flux:text>
        </a>
    </div>

    <!-- Center (TRUE CENTER) -->
    <div class="absolute left-1/2 -translate-x-1/2 max-lg:hidden">
        <flux:navbar class="-mb-px flex gap-2">
            <flux:navbar.item href="/" wire:navigate>Home</flux:navbar.item>
            <flux:navbar.item href="/features" wire:navigate>Features</flux:navbar.item>
            <flux:navbar.item href="/customers" wire:navigate>Customers</flux:navbar.item>
            <flux:navbar.item href="/pricing" wire:navigate>Pricing</flux:navbar.item>
        </flux:navbar>
    </div>

    <!-- Right -->
    <div class="ml-auto flex items-center gap-3">
        @if (Auth::user())
            <flux:navbar.item href="/dashboard" wire:navigate>
                Dashboard
            </flux:navbar.item>
        @else
            <flux:navbar.item href="/login" wire:navigate>
                Log in
            </flux:navbar.item>

            <flux:button variant="primary" as="link" href="/register" wire:navigate>
                Register
            </flux:button>
        @endif
    </div>
</flux:header>
