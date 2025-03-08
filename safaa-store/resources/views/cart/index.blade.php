<x-app-layout>
    <div class="py-12 bg-gradient-to-br from-gray-50 to-gray-100">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-2xl rounded-2xl border border-gray-100">
                <div class="p-8">
                    @if (count($cart) > 0)
                        <div class="flex flex-col lg:flex-row gap-10">
                            <!-- Articles du panier -->
                            <div class="lg:w-2/3">
                                <div class="bg-white">
                                    <div class="mb-8">
                                        <h2 class="text-3xl font-bold bg-gradient-to-r from-indigo-600 to-violet-700 bg-clip-text text-transparent">Votre Panier</h2>
                                        <p class="text-gray-500 mt-2">{{ count($cart) }} article(s) dans votre panier</p>
                                    </div>
                                    <div class="overflow-x-auto rounded-xl shadow-sm border border-gray-100">
                                        <table class="w-full">
                                            <thead class="bg-gray-50">
                                            <tr>
                                                <th class="text-left py-4 px-6 font-semibold text-gray-600">Produit</th>
                                                <th class="text-center py-4 px-4 font-semibold text-gray-600">Quantité</th>
                                                <th class="text-right py-4 px-4 font-semibold text-gray-600">Prix</th>
                                                <th class="text-right py-4 px-4 font-semibold text-gray-600">Total</th>
                                                <th class="text-right py-4 px-6 font-semibold text-gray-600">Actions</th>
                                            </tr>
                                            </thead>
                                            <tbody class="divide-y divide-gray-100">
                                            @foreach ($cart as $item)
                                                <tr class="hover:bg-gray-50 transition-colors duration-200 group">
                                                    <td class="py-6 px-6">
                                                        <div class="flex items-center gap-4">
                                                            <div class="h-24 w-24 flex-shrink-0 overflow-hidden rounded-xl border border-gray-200 group-hover:border-indigo-300 transition-all duration-300 shadow-sm group-hover:shadow-md">
                                                                @if ($item['image'])
                                                                    <img src="{{ asset('storage/' . $item['image']) }}"
                                                                         alt="{{ $item['name'] }}"
                                                                         class="h-full w-full object-cover object-center transform group-hover:scale-105 transition-transform duration-500">
                                                                @else
                                                                    <img src="https://via.placeholder.com/150"
                                                                         alt="{{ $item['name'] }}"
                                                                         class="h-full w-full object-cover object-center transform group-hover:scale-105 transition-transform duration-500">
                                                                @endif
                                                            </div>
                                                            <div>
                                                                <a href="{{ route('products.show', $item['id']) }}"
                                                                   class="text-lg font-medium text-gray-900 hover:text-indigo-600 transition-colors duration-200">
                                                                    {{ $item['name'] }}
                                                                </a>
                                                                <p class="text-sm text-gray-500 mt-1">Réf: PROD-{{ $item['id'] }}</p>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="py-6 px-4">
                                                        <form action="{{ route('cart.update') }}" method="POST" class="flex justify-center items-center">
                                                            @csrf
                                                            <input type="hidden" name="product_id" value="{{ $item['id'] }}">
                                                            <div class="flex items-center border border-gray-300 rounded-lg overflow-hidden shadow-sm group-hover:shadow-md group-hover:border-indigo-300 transition-all duration-200">
                                                                <button type="button" onclick="decrementQuantity(this)" class="bg-gray-50 px-3 py-2 text-gray-600 hover:bg-indigo-50 hover:text-indigo-700 transition-colors duration-200">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                                                                    </svg>
                                                                </button>
                                                                <input type="number"
                                                                       name="quantity"
                                                                       value="{{ $item['quantity'] }}"
                                                                       min="1"
                                                                       class="w-14 py-2 px-2 text-center text-gray-700 focus:outline-none focus:ring-0 border-0"
                                                                       readonly>
                                                                <button type="button" onclick="incrementQuantity(this)" class="bg-gray-50 px-3 py-2 text-gray-600 hover:bg-indigo-50 hover:text-indigo-700 transition-colors duration-200">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                                                    </svg>
                                                                </button>
                                                            </div>
                                                            <button type="submit"
                                                                    class="ml-3 text-sm font-medium text-indigo-600 hover:text-indigo-500 transition-colors duration-200 opacity-0 group-hover:opacity-100 transform translate-y-1 group-hover:translate-y-0 transition-all duration-300">
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                                                </svg>
                                                            </button>
                                                        </form>
                                                    </td>
                                                    <td class="py-6 px-4 text-right">
                                                            <span class="text-base font-medium text-gray-900">
                                                                {{ number_format($item['price'], 2, ',', ' ') }} €
                                                            </span>
                                                    </td>
                                                    <td class="py-6 px-4 text-right">
                                                            <span class="text-base font-medium text-gray-900">
                                                                {{ number_format($item['price'] * $item['quantity'], 2, ',', ' ') }} €
                                                            </span>
                                                    </td>
                                                    <td class="py-6 px-6 text-right">
                                                        <form action="{{ route('cart.remove', $item['id']) }}" method="Get" class="inline">
                                                            @csrf
                                                            <button type="submit"
                                                                    class="inline-flex items-center text-sm font-medium text-red-600 hover:text-red-700 transition-colors duration-200 bg-red-50 hover:bg-red-100 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                                </svg>
                                                                Supprimer
                                                            </button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <!-- Résumé de la commande -->
                            <div class="lg:w-1/3">
                                <div class="bg-gradient-to-br from-white to-gray-50 rounded-2xl p-8 sticky top-6 shadow-xl border border-gray-200">
                                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Résumé de la commande</h2>

                                    <div class="space-y-4 divide-y divide-dashed divide-gray-200">
                                        <div class="pb-4 space-y-3">
                                            <div class="flex justify-between text-base">
                                                <span class="text-gray-600">Sous-total</span>
                                                <span class="font-medium text-gray-900">
                                                    {{ number_format($total, 2, ',', ' ') }} €
                                                </span>
                                            </div>

                                            <div class="flex justify-between text-base">
                                                <span class="text-gray-600">Frais de livraison</span>
                                                <span class="font-medium text-gray-900">
                                                    {{ number_format($shipping, 2, ',', ' ') }} €
                                                </span>
                                            </div>

                                            <div class="flex justify-between text-base">
                                                <span class="text-gray-600">TVA (20%)</span>
                                                <span class="font-medium text-gray-900">
                                                    {{ number_format(($total + $shipping) * 0.2, 2, ',', ' ') }} €
                                                </span>
                                            </div>
                                        </div>

                                        <div class="pt-4">
                                            <div class="flex justify-between text-xl font-bold">
                                                <span class="text-gray-900">Total TTC</span>
                                                <span class="bg-gradient-to-r from-indigo-600 to-violet-700 bg-clip-text text-transparent">
                                                    {{ number_format($total + $shipping, 2, ',', ' ') }} €
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mt-8 space-y-4">
                                        <a href="{{ route('checkout.index') }}"
                                           class="w-full flex justify-center items-center px-6 py-4 text-base font-medium text-white bg-gradient-to-r from-indigo-600 to-violet-700 rounded-xl shadow-lg shadow-indigo-500/20 hover:shadow-indigo-600/30 transform hover:translate-y-[-2px] transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z" />
                                            </svg>
                                            Passer à la caisse
                                        </a>

                                        <a href="{{ route('products.index') }}"
                                           class="w-full flex justify-center items-center px-6 py-4 text-base font-medium text-indigo-700 bg-white border border-indigo-200 rounded-xl hover:bg-indigo-50 hover:border-indigo-300 shadow-sm hover:shadow-md transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                            </svg>
                                            Continuer les achats
                                        </a>
                                    </div>

                                    <!-- Informations supplémentaires -->
                                    <div class="mt-8 pt-6 border-t border-gray-200 space-y-4">
                                        <div class="flex items-center text-sm text-gray-500">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                            </svg>
                                            Paiement 100% sécurisé
                                        </div>
                                        <div class="flex items-center text-sm text-gray-500">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                            </svg>
                                            Livraison rapide (24-48h)
                                        </div>
                                        <div class="flex items-center text-sm text-gray-500">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                            </svg>
                                            Satisfait ou remboursé 30 jours
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="min-h-[500px] flex items-center justify-center">
                            <div class="max-w-md w-full p-8 transform hover:scale-105 transition-all duration-300">
                                <!-- Animation SVG améliorée -->
                                <div class="mb-8 relative">
                                    <div class="absolute inset-0 bg-indigo-100 rounded-full opacity-30 animate-pulse"></div>
                                    <svg class="mx-auto h-32 w-32 text-indigo-600 animate-float" fill="none" viewBox="0 0 24 24"
                                         stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                              d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z">
                                        </path>
                                        <!-- Ajout d'éléments décoratifs -->
                                        <circle cx="12" cy="8" r="7" class="text-indigo-100" stroke-width="1" stroke="currentColor" fill="none" opacity="0.5"/>
                                    </svg>
                                    <!-- Effet de brillance -->
                                    <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/50 to-transparent blur-sm animate-shine"></div>
                                </div>

                                <!-- Contenu textuel amélioré -->
                                <div class="space-y-6 text-center">
                                    <h2 class="text-4xl font-bold bg-gradient-to-r from-indigo-600 to-violet-700 bg-clip-text text-transparent">
                                        Votre panier est vide
                                    </h2>
                                    <p class="text-gray-600 text-lg leading-relaxed">
                                        Découvrez notre sélection exclusive de produits et commencez votre expérience shopping
                                    </p>

                                    <!-- Bouton modernisé -->
                                    <div class="mt-10">
                                        <a href="{{ route('products.index') }}"
                                           class="group relative inline-flex items-center justify-center px-8 py-4 text-lg font-medium overflow-hidden rounded-xl bg-gradient-to-r from-indigo-500 to-violet-600 text-white shadow-lg shadow-indigo-500/30 transition-all duration-300 hover:shadow-indigo-600/40 hover:scale-[1.02] focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                            <span class="absolute inset-0 w-full h-full bg-gradient-to-r from-indigo-400 to-violet-500 opacity-0 group-hover:opacity-20 transition-opacity"></span>
                                            <span class="relative z-10 flex items-center">
                                                <!-- Icône de magasin -->
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                                                </svg>
                                                Découvrir nos produits
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 ml-2 group-hover:translate-x-2 transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                                </svg>
                                            </span>
                                        </a>
                                    </div>


                                </div>
                            </div>
                        </div>

                        <style>
                            @keyframes float {
                                0%, 100% { transform: translateY(0); }
                                50% { transform: translateY(-15px); }
                            }

                            @keyframes shine {
                                from { transform: translateX(-100%); }
                                to { transform: translateX(100%); }
                            }

                            @keyframes pulse {
                                0%, 100% { transform: scale(1); opacity: 0.3; }
                                50% { transform: scale(1.05); opacity: 0.5; }
                            }

                            .animate-float {
                                animation: float 3s ease-in-out infinite;
                            }

                            .animate-shine {
                                animation: shine 3s infinite;
                            }

                            .animate-pulse {
                                animation: pulse 2s ease-in-out infinite;
                            }
                        </style>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        function incrementQuantity(button) {
            const input = button.parentNode.querySelector('input[name="quantity"]');
            input.value = parseInt(input.value) + 1;
            button.closest('form').submit();
        }

        function decrementQuantity(button) {
            const input = button.parentNode.querySelector('input[name="quantity"]');
            if (parseInt(input.value) > 1) {
                input.value = parseInt(input.value) - 1;
                button.closest('form').submit();
            }
        }
    </script>
</x-app-layout>
