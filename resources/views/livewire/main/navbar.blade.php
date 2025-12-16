<flux:header sticky container class="relative bg-white dark:bg-zinc-800 border-b border-zinc-200 dark:border-zinc-600">
    <div class="flex items-center gap-3">
        <a href="{{ route('home') }}" class="flex items-center gap-2 font-medium" wire:navigate>
            <span class="flex h-7 w-7 items-center justify-center rounded-md">
                <x-app-logo-icon class="size-7 text-blue-600/90 dark:text-white" />
            </span>
            <flux:text size="lg" class="text-blue-600/90 dark:text-white">
                ATS Boost
            </flux:text>
        </a>
    </div>

    <div class="absolute left-1/2 -translate-x-1/2 max-md:hidden">
        <flux:navbar class="-mb-px flex gap-2">
            <flux:navbar.item href="/customers" wire:navigate>Customers</flux:navbar.item>
            <flux:navbar.item href="/features" wire:navigate>Features</flux:navbar.item>
            <flux:navbar.item href="/pricing" wire:navigate>Pricing</flux:navbar.item>
        </flux:navbar>
    </div>

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

        <flux:sidebar.toggle class="md:hidden" icon="bars-2" />

        <flux:sidebar sticky collapsible="mobile"
            class="bg-zinc-50 dark:bg-zinc-900 border-r border-zinc-200 dark:border-zinc-700 max-sm:w-full md:hidden">
            <flux:sidebar.header>
                <a href="{{ route('home') }}" class="flex items-center gap-2 font-medium" wire:navigate>
                    <span class="flex h-7 w-7 items-center justify-center rounded-md">
                        <x-app-logo-icon class="size-7 text-blue-600/90 dark:text-white" />
                    </span>
                    <flux:text size="lg" class="text-blue-600/90 dark:text-white">
                        ATS Boost
                    </flux:text>
                </a>
                {{-- <flux:sidebar.collapse class="lg:hidden" /> --}}
            <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />

            </flux:sidebar.header>

            <flux:sidebar.nav>
                <flux:sidebar.item icon="users" wire:navigate href="/customers">Customers</flux:sidebar.item>
                <flux:sidebar.item icon="sparkles" wire:navigate href="/features">Features</flux:sidebar.item>
                <flux:sidebar.item icon="credit-card" wire:navigate href="/pricing">Pricing</flux:sidebar.item>
            </flux:sidebar.nav>

            <flux:sidebar.spacer />

            @if (Auth::user())
                <flux:button variant="primary" icon="squares-plus" as="link" href="/dashboard" wire:navigate>
                    Dashboard
                </flux:button>
            @else
                <div class="flex flex-col w-full space-y-2">
                    <flux:sidebar.item href="/login" wire:navigate class="!text-center items-center">
                        Log in
                    </flux:sidebar.item>

                    <flux:button variant="primary" as="link" href="/register" wire:navigate>
                        Register
                    </flux:button>
                </div>
            @endif
        </flux:sidebar>
    </div>
</flux:header>
