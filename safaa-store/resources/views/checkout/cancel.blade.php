<x-app-layout>
    <div class="bg-gradient-to-b from-gray-50 to-white py-16">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
                <div class="p-8 md:p-10">
                    <!-- En-tête avec statut d'erreur -->
                    <div class="text-center">
                        <div class="inline-flex items-center justify-center h-20 w-20 bg-red-50 rounded-full mb-6">
                            <svg class="h-12 w-12 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </div>

                        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 tracking-tight">Paiement non finalisé</h1>

                        <div class="mt-6 space-y-4">
                            <div class="bg-gray-50 rounded-xl p-6">
                                <p class="text-gray-600">Votre transaction n'a pas pu être complétée. Aucun montant n'a été débité de votre compte.</p>

                                <div class="mt-4 bg-red-50 rounded-lg p-4 border border-red-100">
                                    <div class="flex">
                                        <div class="shrink-0">
                                            <svg class="h-5 w-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <h3 class="text-sm font-medium text-red-800">Le paiement a été annulé</h3>
                                            <div class="mt-2 text-sm text-red-700">
                                                <ul class="list-disc pl-5 space-y-1">
                                                    <li>Vous pourrez réessayer quand vous le souhaitez</li>
                                                    <li>Votre panier a été conservé</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Options -->
                            <div class="bg-gray-50 rounded-xl p-6">
                                <h3 class="text-base font-medium text-gray-900 mb-4">Que souhaitez-vous faire ?</h3>

                                <div class="flex flex-col sm:flex-row sm:space-x-4 space-y-3 sm:space-y-0">
                                    <a href="{{ route('checkout.index') }}" class="inline-flex items-center justify-center px-5 py-3 border border-transparent text-base font-medium rounded-lg shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-150 w-full sm:w-auto">
                                        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                        </svg>
                                        Réessayer le paiement
                                    </a>

                                    <a href="{{ route('cart.index') }}" class="inline-flex items-center justify-center px-5 py-3 border border-gray-300 text-base font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-150 w-full sm:w-auto">
                                        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                                        </svg>
                                        Modifier mon panier
                                    </a>
                                </div>
                            </div>
                        </div>


                    </div>
                </div>
            </div>

            <!-- Boutton de retour -->
            <div class="mt-6 text-center">
                <a href="{{ route('home') }}" class="inline-flex items-center text-sm text-gray-500 hover:text-gray-700">
                    <svg class="h-5 w-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Retour à la page d'accueil
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
