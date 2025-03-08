<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                    <button type="button" class="absolute top-0 right-0 px-4 py-3" aria-label="Fermer">
                        <span class="sr-only">Fermer</span>
                        <svg class="h-4 w-4 fill-current" role="button" viewBox="0 0 20 20"><path d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" fill-rule="evenodd"></path></svg>
                    </button>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6">Finaliser votre commande</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Récapitulatif de la commande -->
                        <div class="bg-gray-50 p-6 rounded-lg">
                            <h3 class="text-lg font-semibold mb-4 text-gray-800 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-indigo-600" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3z" />
                                </svg>
                                Récapitulatif de la commande
                            </h3>
                            <div class="overflow-x-auto bg-white rounded-lg shadow-sm">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-100">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Produit</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Prix</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantité</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                    </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($cartItems as $item)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="flex-shrink-0 h-12 w-12">
                                                        @if ($item['product']->image)
                                                            <img class="h-12 w-12 rounded object-cover" src="{{ asset('storage/' . $item['product']->image) }}" alt="{{ $item['product']->name }}">
                                                        @else
                                                            <div class="h-12 w-12 rounded bg-gray-200 flex items-center justify-center">
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                                </svg>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="ml-4">
                                                        <div class="text-sm font-medium text-gray-900">{{ $item['product']->name }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">{{ number_format($item['price'], 2) }} €</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">{{ $item['quantity'] }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">{{ number_format($item['total'], 2) }} €</div>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                    <tfoot class="bg-gray-50">
                                    <tr>
                                        <td colspan="3" class="px-6 py-3 text-right text-sm font-medium text-gray-500">Sous-total</td>
                                        <td class="px-6 py-3 whitespace-nowrap text-sm text-gray-900">{{ number_format($total, 2) }} €</td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" class="px-6 py-3 text-right text-sm font-medium text-gray-500">Frais de livraison</td>
                                        <td class="px-6 py-3 whitespace-nowrap text-sm text-gray-900">{{ number_format($shippingCost, 2) }} €</td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" class="px-6 py-3 text-right text-sm font-medium text-gray-500">TVA (20%)</td>
                                        <td class="px-6 py-3 whitespace-nowrap text-sm text-gray-900">{{ number_format($tax, 2) }} €</td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" class="px-6 py-3 text-right text-sm font-bold text-gray-900">Total</td>
                                        <td class="px-6 py-3 whitespace-nowrap text-sm font-bold text-indigo-600">{{ number_format($grandTotal, 2) }} €</td>
                                    </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>

                        <!-- Formulaire de paiement -->
                        <div class="bg-gray-50 p-6 rounded-lg">
                            <h3 class="text-lg font-semibold mb-4 text-gray-800 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-indigo-600" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 116 0z" clip-rule="evenodd" />
                                </svg>
                                Informations de livraison et paiement
                            </h3>
                            <form action="{{ route('checkout.process') }}" method="POST" class="space-y-5">
                                @csrf

                                <div class="bg-white p-4 rounded-md shadow-sm">
                                    <label for="shipping_address" class="block text-sm font-medium text-gray-700 mb-1">Adresse de livraison</label>
                                    <textarea name="shipping_address" id="shipping_address" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>{{ old('shipping_address', Auth::user()->address) }}</textarea>
                                    @error('shipping_address')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="bg-white p-4 rounded-md shadow-sm">
                                    <div class="flex items-center justify-between mb-1">
                                        <label for="billing_address" class="block text-sm font-medium text-gray-700">Adresse de facturation</label>
                                        <div class="flex items-center">
                                            <input type="checkbox" id="same_as_shipping" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                            <label for="same_as_shipping" class="ml-2 text-sm text-gray-600">Identique à l'adresse de livraison</label>
                                        </div>
                                    </div>
                                    <textarea name="billing_address" id="billing_address" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>{{ old('billing_address', Auth::user()->address) }}</textarea>
                                    @error('billing_address')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="bg-white p-4 rounded-md shadow-sm">
                                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Notes (optionnel)</label>
                                    <textarea name="notes" id="notes" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="Instructions spéciales pour la livraison...">{{ old('notes') }}</textarea>
                                </div>

                                <div class="bg-white p-4 rounded-md shadow-sm">
                                    <label class="block text-sm font-medium text-gray-700 mb-3">Méthode de paiement</label>
                                    <div class="space-y-3">
                                        <div class="flex items-center p-3 border rounded-md hover:bg-gray-50 transition-colors cursor-pointer">
                                            <input type="radio" name="payment_method" id="payment_method_stripe" value="stripe" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300" checked>
                                            <label for="payment_method_stripe" class="ml-3 flex flex-1 justify-between items-center">
                                                <span class="text-sm font-medium text-gray-700">Carte bancaire</span>
                                                <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/b/ba/Stripe_Logo%2C_revised_2016.svg/2560px-Stripe_Logo%2C_revised_2016.svg.png" alt="Stripe" class="h-6">
                                            </label>
                                        </div>
                                        <div class="flex items-center p-3 border rounded-md hover:bg-gray-50 transition-colors cursor-pointer">
                                            <input type="radio" name="payment_method" id="payment_method_paypal" value="paypal" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300">
                                            <label for="payment_method_paypal" class="ml-3 flex flex-1 justify-between items-center">
                                                <span class="text-sm font-medium text-gray-700">PayPal</span>
                                                <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/b/b5/PayPal.svg/1200px-PayPal.svg.png" alt="PayPal" class="h-6">
                                            </label>
                                        </div>
                                        <div class="flex items-center p-3 border rounded-md hover:bg-gray-50 transition-colors cursor-pointer">
                                            <input type="radio" name="payment_method" id="payment_method_cod" value="cod" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300">
                                            <label for="payment_method_cod" class="ml-3 flex flex-1 justify-between items-center">
                                                <span class="text-sm font-medium text-gray-700">Paiement à la livraison</span>
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2z" />
                                                </svg>
                                            </label>
                                        </div>
                                    </div>
                                    @error('payment_method')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="flex justify-end mt-6 space-x-3">
                                    <a href="{{ route('cart.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-gray-500" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M9.707 14.707a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 1.414L7.414 9H15a1 1 0 110 2H7.414l2.293 2.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                                        </svg>
                                        Retour au panier
                                    </a>
                                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                                        Procéder au paiement
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sameAsShippingCheckbox = document.getElementById('same_as_shipping');
            const shippingAddressField = document.getElementById('shipping_address');
            const billingAddressField = document.getElementById('billing_address');

            sameAsShippingCheckbox.addEventListener('change', function() {
                if (this.checked) {
                    billingAddressField.value = shippingAddressField.value;
                    billingAddressField.disabled = true;
                } else {
                    billingAddressField.disabled = false;
                }
            });

            shippingAddressField.addEventListener('input', function() {
                if (sameAsShippingCheckbox.checked) {
                    billingAddressField.value = this.value;
                }
            });

            // Rendre les options de paiement entièrement cliquables
            document.querySelectorAll('.flex.items-center.p-3.border').forEach(container => {
                const radio = container.querySelector('input[type="radio"]');
                container.addEventListener('click', () => {
                    radio.checked = true;
                });
            });
        });
    </script>
</x-app-layout>
