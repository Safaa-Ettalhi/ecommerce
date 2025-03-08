
<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-bold text-2xl bg-gradient-to-r from-purple-600 to-pink-500 bg-clip-text text-transparent">
                {{ __('Accueil') }}
            </h2>
            <nav class="flex items-center space-x-1">
                <span class="text-sm text-gray-500">Vous êtes ici:</span>
                <a href="{{ route('home') }}" class="text-sm font-medium text-purple-600 hover:text-purple-500 transition-colors">
                    Accueil
                </a>
            </nav>
        </div>
    </x-slot>

    <!-- Hero Section -->
    <div x-data="{ loaded: false }" x-init="setTimeout(() => loaded = true, 300)"
         class="relative bg-gradient-to-br from-gray-50 to-white overflow-hidden">
        <div class="absolute inset-0 bg-[url('/img/grid.svg')] opacity-10"></div>
        <div class="absolute -top-40 -right-40 w-80 h-80 bg-purple-300 rounded-full mix-blend-multiply filter blur-2xl opacity-50 animate-blob"></div>
        <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-pink-300 rounded-full mix-blend-multiply filter blur-2xl opacity-50 animate-blob animation-delay-2000"></div>
        <div class="absolute top-40 left-20 w-80 h-80 bg-blue-300 rounded-full mix-blend-multiply filter blur-2xl opacity-50 animate-blob animation-delay-4000"></div>

        <div class="max-w-7xl mx-auto relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                <div class="py-12 px-6 sm:px-8 lg:py-24 lg:pr-0 flex flex-col justify-center"
                     :class="loaded ? 'animate-fade-in-left' : 'opacity-0'">
                    <h1 class="text-4xl sm:text-5xl md:text-6xl font-extrabold tracking-tight">
                        <span class="block mb-2">Découvrez Notre</span>
                        <span class="bg-gradient-to-r from-purple-600 to-pink-500 bg-clip-text text-transparent">Boutique en Ligne</span>
                    </h1>
                    <p class="mt-6 text-lg text-gray-600 leading-relaxed max-w-xl">
                        Des produits exceptionnels à des prix compétitifs. Profitez d'une livraison rapide et d'un service client d'excellence.
                    </p>
                    <div class="mt-8 flex flex-col sm:flex-row gap-4">
                        <a href="{{ route('products.index') }}"
                           class="relative group inline-flex items-center justify-center overflow-hidden rounded-full p-0.5">
                            <span class="absolute h-full w-full bg-gradient-to-br from-purple-600 to-pink-500 group-hover:from-purple-500 group-hover:to-pink-400 transition-all duration-500"></span>
                            <span class="relative flex items-center gap-2 px-8 py-4 transition-all duration-300 bg-white dark:bg-gray-900 rounded-full group-hover:bg-opacity-0">
                                <span class="text-base font-semibold text-gray-900 group-hover:text-white">Acheter maintenant</span>
                                <svg class="w-5 h-5 text-gray-900 group-hover:text-white transition-transform duration-300 group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                </svg>
                            </span>
                        </a>
                        <a href="#featured"
                           class="inline-flex items-center justify-center px-8 py-4 text-base font-semibold text-purple-600 bg-white border border-purple-200 rounded-full hover:bg-purple-50 hover:border-purple-300 transition-all duration-300">
                            En savoir plus
                        </a>
                    </div>
                </div>
                <div class="relative lg:h-auto" :class="loaded ? 'animate-fade-in-right' : 'opacity-0'">
                    <div class="absolute inset-0 bg-gradient-to-br from-purple-400/20 to-pink-400/20 rounded-3xl transform rotate-3 scale-105"></div>
                    <div class="absolute inset-0 bg-gradient-to-br from-purple-400/20 to-pink-400/20 rounded-3xl transform -rotate-3 scale-105"></div>
                    <div class="relative overflow-hidden rounded-3xl shadow-2xl">
                        <img class="w-full h-full object-cover transform hover:scale-105 transition-transform duration-700"
                             src="https://inmedia.ma/wp-content/uploads/2024/09/pc-gamer-ultime-ryzen-5-5600x-front.webp"
                             alt="Boutique en ligne">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>
                        <div class="absolute bottom-0 left-0 p-6">
                            <div class="flex items-center space-x-2">
                                <div class="w-3 h-3 bg-green-500 rounded-full animate-pulse"></div>
                                <span class="text-white font-medium">Nouveautés disponibles</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Section avec animation de comptage -->
    <div class="bg-gradient-to-br from-purple-900 to-indigo-800 text-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-2xl mx-auto text-center mb-12">
                <h2 class="text-3xl font-bold">Pourquoi nous choisir?</h2>
                <p class="mt-4 text-purple-200">Des milliers de clients nous font confiance pour la qualité de nos produits et services</p>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
                <div x-data="{ shown: false, count: 0, target: 5000 }"
                     x-intersect="shown = true; $nextTick(() => { if (shown) { const interval = setInterval(() => { count = count + Math.ceil(target / 50); if (count >= target) { count = target; clearInterval(interval); } }, 30); } })"
                     class="text-center">
                    <div class="text-4xl font-bold mb-2">
                        <span x-text="count.toLocaleString()"></span>+
                    </div>
                    <div class="text-purple-200">Clients satisfaits</div>
                </div>
                <div x-data="{ shown: false, count: 0, target: 200 }"
                     x-intersect="shown = true; $nextTick(() => { if (shown) { const interval = setInterval(() => { count = count + Math.ceil(target / 50); if (count >= target) { count = target; clearInterval(interval); } }, 30); } })"
                     class="text-center">
                    <div class="text-4xl font-bold mb-2">
                        <span x-text="count.toLocaleString()">0</span>+
                    </div>
                    <div class="text-purple-200">Produits</div>
                </div>
                <div x-data="{ shown: false, count: 0, target: 98 }"
                     x-intersect="shown = true; $nextTick(() => { if (shown) { const interval = setInterval(() => { count = count + Math.ceil(target / 50); if (count >= target) { count = target; clearInterval(interval); } }, 30); } })"
                     class="text-center">
                    <div class="text-4xl font-bold mb-2">
                        <span x-text="count.toLocaleString()">0</span>%
                    </div>
                    <div class="text-purple-200">Taux de satisfaction</div>
                </div>
                <div x-data="{ shown: false, count: 0, target: 24 }"
                     x-intersect="shown = true; $nextTick(() => { if (shown) { const interval = setInterval(() => { count = count + Math.ceil(target / 50); if (count >= target) { count = target; clearInterval(interval); } }, 30); } })"
                     class="text-center">
                    <div class="text-4xl font-bold mb-2">
                        <span x-text="count.toLocaleString()">0</span>h
                    </div>
                    <div class="text-purple-200">Service client</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Featured Products avec scroll progressif -->
    <section id="featured"
             x-data="featuredProductsSection()"
             class="relative py-16 bg-gradient-to-br from-gray-50 to-white overflow-hidden">

        {{-- Effets visuels dynamiques --}}
        <div class="absolute inset-0 opacity-10 bg-[url('/img/subtle-pattern.svg')]"></div>
        <div class="absolute -top-20 -right-20 w-96 h-96 bg-purple-200 rounded-full mix-blend-multiply blur-3xl opacity-30 animate-blob"></div>
        <div class="absolute -bottom-20 -left-20 w-96 h-96 bg-pink-200 rounded-full mix-blend-multiply blur-3xl opacity-30 animate-blob animation-delay-2000"></div>

        <div class="max-w-7xl mx-auto px-4 relative z-10">
            {{-- Titre dynamique avec animation --}}
            <div class="flex items-center justify-between mb-8">
                <h2 class="text-3xl font-bold bg-gradient-to-r from-purple-600 to-pink-500 bg-clip-text text-transparent">
                    Produits en Vedette
                </h2>
                <a href="{{ route('products.index') }}" class="flex items-center text-purple-600 hover:text-purple-500 transition-colors font-medium">
                    Tous les produits
                    <svg class="ml-2 w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                    </svg>
                </a>
            </div>

            {{-- Grille de produits avec interactions --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                @foreach($featuredProducts as $product)
                    <div
                        x-data="productCard({{ $product->id }})"
                        @mousemove.self="handleMouseMove($event)"
                        class="bg-white group overflow-hidden rounded-2xl shadow-lg hover:shadow-2xl
                           transform transition-all duration-500 hover:-translate-y-2
                           relative cursor-pointer">

                        {{-- Effet de brillance --}}
                        <div
                            x-ref="shine"
                            class="absolute top-0 left-0 w-full h-full pointer-events-none opacity-0
                               group-hover:opacity-30 transition-opacity duration-500"
                            :style="shineStyle">
                        </div>

                        {{-- Image du produit --}}
                        <div class="relative overflow-hidden">
                            <img
                                src="{{ asset('storage/' .$product->image) }}"
                                alt="{{ $product->name }}"
                                class="w-full h-64 object-cover transition-transform duration-500
                                   group-hover:scale-110">

                            {{-- Badge de mise en vedette --}}
                            <div class="absolute top-4 right-4 bg-purple-600 text-white
                                    px-3 py-1 rounded-full text-sm font-semibold">
                                Coup de cœur
                            </div>
                        </div>

                        {{-- Informations du produit --}}
                        <div class="p-6">
                            <h3 class="text-xl font-bold mb-2 text-gray-800">
                                {{ $product->name }}
                            </h3>
                            <p class="text-gray-600 mb-4">
                                {{ Str::limit($product->description, 80) }}
                            </p>

                            <div class="flex justify-between items-center">
                            <span class="text-2xl font-extrabold text-purple-600">
                                {{ number_format($product->price, 2) }} €
                            </span>
                                <a
                                    href="{{ route('products.show', $product->slug) }}"
                                    class="bg-gradient-to-r from-purple-600 to-pink-500
                                       text-white px-4 py-2 rounded-full
                                       hover:from-purple-700 hover:to-pink-600
                                       transition-all duration-300">
                                    Découvrir
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('featuredProductsSection', () => ({
                animateTitle: false
            }));

            Alpine.data('productCard', (productId) => ({
                shineStyle: {},
                handleMouseMove(e) {
                    const rect = e.target.getBoundingClientRect();
                    const x = e.clientX - rect.left;
                    const y = e.clientY - rect.top;

                    this.shineStyle = {
                        background: `radial-gradient(circle at ${x}px ${y}px, rgba(255,255,255,0.3), transparent 50%)`
                    };
                }
            }));
        });
    </script>

    <!-- Newsletter Section -->
    <div class="bg-gradient-to-br from-gray-50 to-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="relative rounded-3xl overflow-hidden">
                <div class="absolute inset-0">
                    <div class="absolute inset-0 bg-gradient-to-r from-purple-600 to-pink-500 mix-blend-multiply"></div>
                    <img src="https://images.unsplash.com/photo-1559056199-641a0ac8b55e?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1470&q=80"
                         alt="Newsletter background"
                         class="w-full h-full object-cover">
                </div>
                <div class="relative py-16 px-6 sm:py-24 sm:px-12 lg:px-16 flex flex-col items-center text-center">
                    <h2 class="text-3xl sm:text-4xl font-bold text-white mb-6">Restez informé de nos nouveautés</h2>
                    <p class="text-lg text-purple-100 mb-8 max-w-2xl">
                        Inscrivez-vous à notre newsletter et soyez le premier à découvrir nos nouvelles collections, offres exclusives et conseils d'experts.
                    </p>
                    <form class="w-full max-w-md">
                        <div class="flex flex-col sm:flex-row w-full gap-4">
                            <input type="email"
                                   placeholder="Votre adresse email"
                                   class="flex-1 px-5 py-3 rounded-full text-gray-900 focus:outline-none focus:ring-2 focus:ring-purple-500">
                            <button type="submit"
                                    class="px-8 py-3 rounded-full bg-gradient-to-r from-purple-600 to-pink-500 text-white font-medium hover:shadow-lg transform hover:scale-105 transition-all duration-300">
                                S'abonner
                            </button>
                        </div>
                    </form>
                    <p class="text-purple-200 text-sm mt-4">
                        Nous respectons votre vie privée. Désabonnement possible à tout moment.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Ajoutez ces styles dans votre CSS -->
    <style>
        .animate-blob {
            animation: blob 7s infinite;
        }

        .animation-delay-2000 {
            animation-delay: 2s;
        }

        .animation-delay-4000 {
            animation-delay: 4s;
        }

        @keyframes blob {
            0% {
                transform: translate(0px, 0px) scale(1);
            }
            33% {
                transform: translate(30px, -50px) scale(1.1);
            }
            66% {
                transform: translate(-20px, 20px) scale(0.9);
            }
            100% {
                transform: translate(0px, 0px) scale(1);
            }
        }

        .animate-fade-in-left {
            animation: fadeInLeft 1s ease-out forwards;
        }

        .animate-fade-in-right {
            animation: fadeInRight 1s ease-out forwards;
        }

        @keyframes fadeInLeft {
            from {
                opacity: 0;
                transform: translateX(-30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes fadeInRight {
            from {
                opacity: 0;
                transform: translateX(30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
    </style>
</x-app-layout>
