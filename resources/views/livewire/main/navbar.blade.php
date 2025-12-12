<flux:header sticky container class="bg-white dark:bg-zinc-800 border-b border-zinc-200 dark:border-zinc-600">
    <flux:sidebar.toggle class="lg:hidden" icon="bars-2" />
    <flux:navbar class="max-lg:hidden -mb-px flex flex-row ml-auto mr-auto">
        <flux:navbar.item href="/" wire:navigate>Home</flux:navbar.item>
        <flux:navbar.item href="/features" wire:navigate>Features</flux:navbar.item>
        <flux:navbar.item href="/customers" wire:navigate>Customers</flux:navbar.item>
        <flux:navbar.item href="/pricing" wire:navigate>Pricing</flux:navbar.item>
        
    </flux:navbar>

    <flux:spacer />

        @if (Auth::user())
            <flux:navbar.item href="/dashboard" wire:navigate>Dashboard</flux:navbar.item>
        @else
            <flux:navbar.item href="/login" wire:navigate>Log in</flux:navbar.item>
            <flux:navbar.item href="/register" wire:navigate>Register</flux:navbar.item>
        @endif
        <flux:radio.group x-data variant="segmented" x-model="$flux.appearance">
            <flux:radio value="light" icon="sun">{{ __('Light') }}</flux:radio>
            <flux:radio value="dark" icon="moon">{{ __('Dark') }}</flux:radio>
            <flux:radio value="system" icon="computer-desktop">{{ __('System') }}</flux:radio>
        </flux:radio.group>
</flux:header>
