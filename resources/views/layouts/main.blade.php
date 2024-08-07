<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>CineMagic</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts AND CSS Fileds -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100 dark:bg-gray-800">

        <!-- Navigation Menu -->
        <nav class="bg-white dark:bg-gray-900 border-b border-gray-100 dark:border-gray-800">
            <!-- Navigation Menu Full Container -->
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Logo + Menu Items + Hamburger -->
                <div class="relative flex flex-col sm:flex-row px-6 sm:px-0 grow justify-between">
                    <!-- Logo -->
                    <div class="shrink-0 -ms-4">
                        <a href="{{ route('home') }}">
                            <div
                                class="h-16 w-40 bg-cover bg-[url('../img/politecnico_h.svg')] dark:bg-[url('../img/politecnico_h_white.svg')]">
                            </div>
                        </a>
                    </div>

                    <!-- Menu Items -->
                    <div id="menu-container"
                        class="grow flex flex-col sm:flex-row items-stretch
                               invisible h-0 sm:visible sm:h-auto">


                        <!-- Menu Item: Movies -->
                        <x-menus.menu-item
                        content="Movies"
                        href="{{ route('movies.showmovies')}}"
                        selected="{{ Route::currentRouteName() == 'movies.showmovies' }}"
                        />

                        <!-- Menu Item: Screenigs -->
                        <x-menus.menu-item
                            content="Screenings"
                            href="{{ route('screenings.showscreenings')}}"
                            selected="{{ Route::currentRouteName() == 'screenings.showscreenings' }}"
                            />

                        @if (Auth::User()?->type === 'C')
                            <!-- Menu Item: Tickets -->
                            <x-menus.menu-item
                                content="My Tickets"
                                href="{{ route('tickets.index')}}"
                                selected="{{ Route::currentRouteName() == 'tickets.index' }}"
                                />

                            <!-- Menu Item: Purchases -->
                            <x-menus.menu-item
                                content="My Purchases"
                                href="{{ route('purchases.index')}}"
                                selected="{{ Route::currentRouteName() == 'purchases.index' }}"
                                />
                        @endif





                        <div class="grow"></div>

                        <!-- Menu Item: Cart -->
                        @if (session('cart'))
                                <x-menus.cart :href="route('cart.show')" selectable="1"
                                    selected="{{ Route::currentRouteName() == 'cart.show' }}" :total="session('cart')->count()" />
                        @endif

                        @auth
                            <x-menus.submenu selectable="0" uniqueName="submenu_user">
                                <x-slot:content>
                                    <div class="pe-1">
                                        <img src="{{ Auth::user()->photoFullUrl }}"
                                            class="w-11 h-11 min-w-11 min-h-11 rounded-full">
                                    </div>
                                    {{-- ATENÇÃO - ALTERAR FORMULA DE CALCULO DAS LARGURAS MÁXIMAS QUANDO O MENU FOR ALTERADO --}}
                                    <div
                                        class="ps-1 sm:max-w-[calc(100vw-39rem)] md:max-w-[calc(100vw-41rem)] lg:max-w-[calc(100vw-46rem)] xl:max-w-[34rem] truncate">
                                        {{ Auth::user()->name }}
                                    </div>
                                </x-slot>
                                @if(Auth::user()?->type === 'A')
                                    <x-menus.submenu-item content="Dashboard" selectable="0"
                                        href="{{ route('dashboard') }}" />
                                @endif

                                @auth
                                    <hr>
                                    @if(Auth::user()?->type === 'A' || Auth::user()?->type === 'C' )
                                        <x-menus.submenu-item content="Profile" selectable="0" :href="match (Auth::user()->type) {
                                            'A' => route('administratives.show', ['administrative' => Auth::user()]),
                                            'E' => '#',
                                            'C' => route('customers.show', ['customer' => Auth::user()])
                                        }" />
                                    @endif
                                    <x-menus.submenu-item content="Change Password" selectable="0"
                                        href="{{ route('profile.edit.password') }}" />
                                @endauth
                                <hr>
                                <form id="form_to_logout_from_menu" method="POST" action="{{ route('logout') }}"
                                    class="hidden">
                                    @csrf
                                </form>
                                <x-menus.submenu-item content="Log Out" selectable="0" form="form_to_logout_from_menu"/>

                            </x-menus.submenu>
                        @else
                            @if(Route::has('login'))
                            <!-- Menu Item: Login -->
                            <x-menus.menu-item content="Login" selectable="1" href="{{ route('login') }}"
                                selected="{{ Route::currentRouteName() == 'login' }}" />
                            @endif
                            @if(Route::has('register'))
                            <x-menus.menu-item content="Register" selectable="1" href="{{ route('register') }}"
                                selected="{{ Route::currentRouteName() == 'register' }}" />
                            @endif
                        @endauth
                </div>
                <!-- Hamburger -->
                <div class="absolute right-0 top-0 flex sm:hidden pt-3 pe-3 text-black dark:text-gray-50">
                    <button id="hamburger_btn">
                        <svg class="h-8 w-8" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path id="hamburger_btn_open" stroke-linecap="round" stroke-linejoin="round"
                                stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                            <path class="invisible" id="hamburger_btn_close" stroke-linecap="round"
                                stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <!-- Page Heading -->
    <header class="bg-white dark:bg-gray-900 shadow">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <h4 class="mb-1 text-base text-gray-500 dark:text-gray-400 leading-tight">
                CineMagic
            </h4>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                @yield('header-title')
            </h2>
        </div>
    </header>

    <main>
        <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
            @if (session('alert-msg'))
                <x-alert type="{{ session('alert-type') ?? 'info' }}">
                    {!! session('alert-msg') !!}
                </x-alert>
            @endif
            @if (!$errors->isEmpty())
                <x-alert type="warning" message="Operation failed because there are validation errors!" />
            @endif
            @yield('main')
        </div>
    </main>
</div>
</body>

</html>
