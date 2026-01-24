<flux:header container class="border-b border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
    <flux:sidebar.toggle class="lg:hidden mr-2" icon="bars-2" inset="left" />

    <x-app-logo href="{{ route('home') }}" wire:navigate />

    <flux:spacer />

    @auth
    <x-desktop-user-menu />
    @else
    <flux:navbar>
        <flux:navbar.item icon="arrow-right-end-on-rectangle" :href="route('login')" wire:navigate>
            {{ __('Iniciar Sesión') }}
        </flux:navbar.item>
    </flux:navbar>
    @endauth
</flux:header>

<!-- Mobile Menu -->
<flux:sidebar collapsible="mobile" sticky class="lg:hidden border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
    <flux:sidebar.header>
        <x-app-logo :sidebar="true" href="{{ route('home') }}" wire:navigate />
        <flux:sidebar.collapse class="in-data-flux-sidebar-on-desktop:not-in-data-flux-sidebar-collapsed-desktop:-mr-2" />
    </flux:sidebar.header>

    <flux:sidebar.nav>
        <flux:sidebar.group :heading="__('Platform')">
            <flux:sidebar.item icon="home" :href="route('home')" :current="request()->routeIs('home')" wire:navigate>
                {{ __('Inicio') }}
            </flux:sidebar.item>
        </flux:sidebar.group>
    </flux:sidebar.nav>

    <flux:spacer />

    @auth
    <x-desktop-user-menu />
    @else
    <flux:sidebar.nav>
        <flux:sidebar.item icon="arrow-right-end-on-rectangle" :href="route('login')" wire:navigate>
            {{ __('Acceder') }}
        </flux:sidebar.item>
    </flux:sidebar.nav>
    @endauth
</flux:sidebar>

<flux:main container>
    {{ $slot }}
</flux:main>