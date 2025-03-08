<nav x-data="{ open: false, scrolled: false }"
     @scroll.window="scrolled = (window.pageYOffset > 20)"
     :class="{ 'bg-white/80 backdrop-blur-lg': scrolled, 'bg-white': !scrolled }"
     class="fixed w-full z-50 transition-all duration-300 shadow-sm">

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-20">
            <!-- Logo -->
            <a href="{{ route('home') }}" class="flex items-center space-x-3 group">
                <x-application-logo class="h-12 w-auto transform transition duration-500 group-hover:scale-110" />

            </a>

            <!-- Navigation Links - Desktop -->
            <div class="hidden md:flex items-center space-x-8">
                <x-nav-link :href="route('home')" :active="request()->routeIs('home')"
                            class="text-gray-700 hover:text-pink-500 font-medium transition-colors duration-300 relative after:absolute after:bottom-0 after:left-0 after:h-0.5 after:w-0 hover:after:w-full after:bg-pink-500 after:transition-all after:duration-300">
                    Accueil
                </x-nav-link>

                <x-nav-link :href="route('products.index')" :active="request()->routeIs('products.index')"
                            class="text-gray-700 hover:text-pink-500 font-medium transition-colors duration-300 relative after:absolute after:bottom-0 after:left-0 after:h-0.5 after:w-0 hover:after:w-full after:bg-pink-500 after:transition-all after:duration-300">
                    Produits
                </x-nav-link>

                @auth
                    @if(Auth::user()->role === 'admin')
                        <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')"
                                    class="text-gray-700 hover:text-pink-500 font-medium transition-colors duration-300 relative after:absolute after:bottom-0 after:left-0 after:h-0.5 after:w-0 hover:after:w-full after:bg-pink-500 after:transition-all after:duration-300">
                            Tableau de Bord Admin
                        </x-nav-link>
                    @endif
                @endauth
            </div>


            <!-- Actions - Desktop -->
            <div class="hidden md:flex items-center space-x-6">
                <!-- Cart -->
                <a href="{{ route('cart.index') }}"
                   class="relative group p-2 hover:bg-gray-100 rounded-full transition-colors duration-300">
                    <svg class="h-6 w-6 text-gray-700 group-hover:text-pink-500 transition-colors duration-300"
                         viewBox="0 0 24 24" stroke="currentColor" fill="none">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                    @if(session('cart') && count(session('cart')) > 0)
                        <span class="absolute -top-1 -right-1 bg-pink-500 text-white text-xs font-bold rounded-full h-5 w-5 flex items-center justify-center transform transition-transform duration-300 group-hover:scale-110">
                            {{ count(session('cart')) }}
                        </span>
                    @endif
                </a>

                @auth
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="flex items-center space-x-2 group">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-r from-pink-500 to-violet-500 p-0.5">
                                    <div class="w-full h-full rounded-full bg-white flex items-center justify-center">
                                        <span class="text-lg font-semibold text-pink-500">
                                            {{ substr(Auth::user()->name, 0, 1) }}
                                        </span>
                                    </div>
                                </div>
                                <span class="text-gray-700 group-hover:text-pink-500 font-medium transition-colors duration-300">
                                    {{ Auth::user()->name }}
                                </span>
                                <svg class="w-4 h-4 text-gray-700 group-hover:text-pink-500 transition-transform duration-300"
                                     :class="{'rotate-180': open}"
                                     viewBox="0 0 24 24" stroke="currentColor" fill="none">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <div class="py-2 bg-white rounded-lg shadow-xl border border-gray-100">
                                <x-dropdown-link :href="route('profile.edit')"
                                                 class="flex items-center px-4 py-2 hover:bg-gray-50 transition-colors duration-200">
                                    <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                    Profil
                                </x-dropdown-link>

                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                            class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors duration-200 flex items-center">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                        </svg>
                                        Déconnexion
                                    </button>
                                </form>
                            </div>
                        </x-slot>
                    </x-dropdown>
                @else
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('login') }}"
                           class="text-gray-700 hover:text-pink-500 font-medium transition-colors duration-300">
                            Connexion
                        </a>
                        <a href="{{ route('register') }}"
                           class="px-6 py-2 bg-gradient-to-r from-pink-500 to-violet-500 text-white font-medium rounded-full hover:shadow-lg hover:scale-105 transition-all duration-300">
                            Inscription
                        </a>
                    </div>
                @endauth
            </div>

            <!-- Mobile Menu Button -->
            <button @click="open = !open"
                    class="md:hidden p-2 rounded-lg hover:bg-gray-100 transition-colors duration-300 focus:outline-none">
                <svg class="w-6 h-6 text-gray-700" :class="{ 'hidden': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
                <svg class="w-6 h-6 text-gray-700" :class="{ 'hidden': !open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div x-show="open"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 -translate-y-4"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-4"
         class="md:hidden bg-white border-t border-gray-100">
        <div class="px-4 py-6 space-y-4">
            <a href="{{ route('home') }}"
               class="block py-2 text-gray-700 hover:text-pink-500 transition-colors duration-300">
                Accueil
            </a>
            <a href="{{ route('products.index') }}"
               class="block py-2 text-gray-700 hover:text-pink-500 transition-colors duration-300">
                Produits
            </a>
            <a href="{{ route('cart.index') }}"
               class="block py-2 text-gray-700 hover:text-pink-500 transition-colors duration-300">
                Panier
                @if(session('cart') && count(session('cart')) > 0)
                    <span class="ml-2 bg-pink-500 text-white text-xs font-bold rounded-full px-2 py-1">
                        {{ count(session('cart')) }}
                    </span>
                @endif
            </a>
        </div>
    </div>
</nav>
<div class="mt-18 ></div>
