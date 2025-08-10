<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-800">
        <flux:header container class="border-b border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
            <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

            <flux:navbar class="-mb-px max-lg:hidden">
                <flux:navbar.item icon="layout-grid" :href="route('dashboard')" :current="request()->routeIs('dashboard')" wire:navigate>
                    {{ __('Dashboard') }}
                </flux:navbar.item>
                <flux:navbar.item 
                    icon="calendar" 
                    :href="route('dashboard')" 
                    :current="request()->routeIs('booking.*')" 
                    wire:navigate>
                    {{ __('Booking Kamar') }}
                </flux:navbar.item>

                <flux:navbar.item 
                    icon="users" 
                    :href="route('dashboard')" 
                    :current="request()->routeIs('penyewa.*')" 
                    wire:navigate>
                    {{ __('Data Penyewa') }}
                </flux:navbar.item>

                <flux:navbar.item 
                    icon="credit-card" 
                    :href="route('dashboard')" 
                    :current="request()->routeIs('pembayaran.*')" 
                    wire:navigate>
                    {{ __('Pembayaran & Bukti') }}
                </flux:navbar.item>

                <flux:navbar.item 
                    icon="chart-bar" 
                    :href="route('dashboard')" 
                    :current="request()->routeIs('laporan-keuangan.*')" 
                    wire:navigate>
                    {{ __('Laporan Keuangan') }}
                </flux:navbar.item>
            </flux:navbar>

            <flux:spacer />

            <flux:navbar class="me-1.5 space-x-0.5 rtl:space-x-reverse py-0!">
                <flux:tooltip :content="__('Search')" position="bottom">
                    <flux:navbar.item class="!h-10 [&>div>svg]:size-5" icon="magnifying-glass" href="#" :label="__('Search')" />
                </flux:tooltip>
            </flux:navbar>

            <!-- Desktop User Menu -->
            <flux:dropdown position="top" align="end">
                <flux:profile
                    class="cursor-pointer"
                    :initials="auth()->user()->initials()"
                />

                <flux:menu>
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                                <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                    <span
                                        class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white"
                                    >
                                        {{ auth()->user()->initials() }}
                                    </span>
                                </span>

                                <div class="grid flex-1 text-start text-sm leading-tight">
                                    <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                    <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <flux:menu.radio.group>
                        <flux:menu.item :href="route('settings.profile')" icon="cog" wire:navigate>{{ __('Settings') }}</flux:menu.item>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full">
                            {{ __('Log Out') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:header>

        <flux:sidebar sticky stashable class="border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
            <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />

            <a href="{{ route('dashboard') }}" class="me-5 flex items-center space-x-2 rtl:space-x-reverse" wire:navigate>
                
            </a>

            <flux:navlist class="w-64">

                <!-- Menu Utama -->
                <flux:navlist.item :href="route('dashboard')" icon="home" :current="request()->routeIs('dashboard')" wire:navigate>
                    Dashboard
                </flux:navlist.item>
                <flux:navlist.item :href="route('users.index')" icon="home" :current="request()->routeIs('users.*')" wire:navigate>
                    Manajemen Pengguna
                </flux:navlist.item>
                <flux:navlist.item :href="route('dashboard')" icon="calendar" :current="request()->routeIs('booking.*')" wire:navigate>
                    Booking Kamar
                </flux:navlist.item>

                <!-- Manajemen Data -->
                <flux:navlist.group heading="Manajemen Data" expandable>
                    <flux:navlist.item :href="route('dashboard')" :current="request()->routeIs('penyewa.*')" wire:navigate>
                        Data Penyewa
                    </flux:navlist.item>
                    <flux:navlist.item :href="route('dashboard')" :current="request()->routeIs('pembayaran.*')" wire:navigate>
                        Pembayaran & Bukti
                    </flux:navlist.item>
                    <flux:navlist.item :href="route('dashboard')" :current="request()->routeIs('jatuh-tempo.*')" wire:navigate>
                        Pengingat Jatuh Tempo
                    </flux:navlist.item>
                    <flux:navlist.item :href="route('dashboard')" :current="request()->routeIs('komplain.*')" wire:navigate>
                        Komplain & Penanganan
                    </flux:navlist.item>
                    <flux:navlist.item :href="route('dashboard')" :current="request()->routeIs('riwayat-kamar.*')" wire:navigate>
                        Riwayat Kamar
                    </flux:navlist.item>
                </flux:navlist.group>

                <!-- Hak Akses -->
                <flux:navlist.group heading="Pengguna & Hak Akses" expandable>
                    <flux:navlist.item :href="route('dashboard')" :current="request()->routeIs('users.*')" wire:navigate>
                        Manajemen Pengguna
                    </flux:navlist.item>
                    <flux:navlist.item :href="route('dashboard')" :current="request()->routeIs('roles.*')" wire:navigate>
                        Hak Akses & Role
                    </flux:navlist.item>
                    <flux:navlist.item :href="route('dashboard')" :current="request()->routeIs('activity-log.*')" wire:navigate>
                        Riwayat Aktivitas
                    </flux:navlist.item>
                </flux:navlist.group>

                <!-- Laporan & Ekspor -->
                <flux:navlist.group heading="Laporan" expandable>
                    <flux:navlist.item :href="route('dashboard')" :current="request()->routeIs('laporan-keuangan.*')" wire:navigate>
                        Laporan Keuangan Bulanan
                    </flux:navlist.item>
                    <flux:navlist.item :href="route('dashboard')" :current="request()->routeIs('histori-harga.*')" wire:navigate>
                        Histori Harga Sewa
                    </flux:navlist.item>
                    <flux:navlist.item :href="route('dashboard')" :current="request()->routeIs('komunikasi-penting.*')" wire:navigate>
                        Komunikasi Penting
                    </flux:navlist.item>
                    <flux:navlist.item :href="route('dashboard')" :current="request()->routeIs('export.*')" wire:navigate>
                        Export PDF / Excel
                    </flux:navlist.item>
                </flux:navlist.group>

            </flux:navlist>

            <flux:spacer />

            <!-- Desktop User Menu -->
            <flux:dropdown class="hidden lg:block" position="bottom" align="start">
                <flux:profile
                    :name="auth()->user()->name"
                    :initials="auth()->user()->initials()"
                    icon:trailing="chevrons-up-down"
                />

                <flux:menu class="w-[220px]">
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                                <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                    <span
                                        class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white"
                                    >
                                        {{ auth()->user()->initials() }}
                                    </span>
                                </span>

                                <div class="grid flex-1 text-start text-sm leading-tight">
                                    <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                    <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <flux:menu.radio.group>
                        <flux:menu.item :href="route('settings.profile')" icon="cog" wire:navigate>{{ __('Settings') }}</flux:menu.item>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full">
                            {{ __('Log Out') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:sidebar>

        <!-- Mobile User Menu -->
        <flux:header class="lg:hidden">
            <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

            <flux:spacer />

            <flux:dropdown position="top" align="end">
                <flux:profile
                    :initials="auth()->user()->initials()"
                    icon-trailing="chevron-down"
                />

                <flux:menu>
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                                <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                    <span
                                        class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white"
                                    >
                                        {{ auth()->user()->initials() }}
                                    </span>
                                </span>

                                <div class="grid flex-1 text-start text-sm leading-tight">
                                    <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                    <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <flux:menu.radio.group>
                        <flux:menu.item :href="route('settings.profile')" icon="cog" wire:navigate>{{ __('Settings') }}</flux:menu.item>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full">
                            {{ __('Log Out') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:header>

        {{ $slot }}

        @fluxScripts
    </body>
</html>
