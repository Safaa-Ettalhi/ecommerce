<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold text-gray-800 leading-tight">
            {{ __('Confirmation de commande') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-8">
                    <div class="flex items-center mb-6">
                        <div class="bg-green-100 p-3 rounded-full mr-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-900">
                                {{ __('Merci pour votre commande !') }}
                            </h3>
                            <p class="text-gray-600">
                                {{ __('Votre commande a été enregistrée et est en cours de traitement.') }}
                            </p>
                        </div>
                    </div>

                    <div class="bg-gray-50 rounded-lg p-6 mb-8">
                        <h4 class="text-lg font-semibold text-gray-800 mb-4">{{ __('Détails de la commande') }}</h4>
                        <div class="space-y-4">
                            <div class="flex border-b border-gray-200 pb-3">
                                <div class="w-1/3 text-gray-600">{{ __('Numéro de commande') }}</div>
                                <div class="w-2/3 font-medium">{{ $order->order_number }}</div>
                            </div>

                            <div class="flex border-b border-gray-200 pb-3">
                                <div class="w-1/3 text-gray-600">{{ __('Date de commande') }}</div>
                                <div class="w-2/3 font-medium">{{ $order->created_at->format('d/m/Y à H:i') }}</div>
                            </div>

                            <div class="flex border-b border-gray-200 pb-3">
                                <div class="w-1/3 text-gray-600">{{ __('Montant total') }}</div>
                                <div class="w-2/3 font-medium">{{ number_format($order->total, 2) }} €</div>
                            </div>

                            <div class="flex">
                                <div class="w-1/3 text-gray-600">{{ __('Moyen de paiement') }}</div>
                                <div class="w-2/3 font-medium">
                                    @if($order->payment_method === 'stripe')
                                        <span class="flex items-center">
                                            <svg class="h-5 w-5 mr-2" viewBox="0 0 24 24" fill="currentColor">
                                                <path d="M14 9.5a2.5 2.5 0 0 0-4 0v5a2.5 2.5 0 0 0 4 0v-5z"/>
                                                <path d="M7 9.5a2.5 2.5 0 0 1 4 0v5a2.5 2.5 0 0 1-4 0v-5z"/>
                                            </svg>
                                            Carte bancaire
                                        </span>
                                    @elseif($order->payment_method === 'paypal')
                                        <span class="flex items-center text-blue-600">
                                            <svg class="h-5 w-5 mr-2" viewBox="0 0 24 24" fill="currentColor">
                                                <path d="M9.5 6.5c-1.5 0-2.5 1-2.5 2.5s1 2.5 2.5 2.5h5c1.5 0 2.5 1 2.5 2.5s-1 2.5-2.5 2.5h-10"/>
                                                <path d="M6.5 18.5v-12"/>
                                                <path d="M14.5 18.5v-12"/>
                                            </svg>
                                            PayPal
                                        </span>
                                    @elseif($order->payment_method === 'cod')
                                        <span class="flex items-center">
                                            <svg class="h-5 w-5 mr-2" viewBox="0 0 24 24" fill="currentColor">
                                                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8z"/>
                                                <path d="M12.5 7.5l-2 0v4.89l4.25 2.55 1-1.66-3.25-1.95v-3.83z"/>
                                            </svg>
                                            Paiement à la livraison
                                        </span>
                                    @else
                                        {{ $order->payment_method }}
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-4 sm:gap-6">
                        <a href="{{ route('home') }}" class="inline-flex justify-center items-center px-6 py-3 bg-gray-800 text-white font-medium rounded-lg hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition ease-in-out duration-150">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            {{ __('Continuer mes achats') }}
                        </a>

                        <a href="{{ route('orders.index') }}" class="inline-flex justify-center items-center px-6 py-3 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition ease-in-out duration-150">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            {{ __('Mes commandes') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
