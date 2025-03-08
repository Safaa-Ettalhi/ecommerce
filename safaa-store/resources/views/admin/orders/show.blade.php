<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white shadow overflow-hidden rounded-md mb-6">
                <div class="p-6">
                    <h3 class="text-2xl font-semibold text-gray-900 mb-5">Détails de la Commande</h3>

                    <div class="md:grid md:grid-cols-2 md:gap-6">
                        <div class="mt-5 md:mt-0 md:col-span-1">
                            <div class="shadow rounded-md">
                                <div class="px-4 py-5 bg-white sm:p-6">
                                    <h4 class="text-lg font-medium text-gray-700 mb-3">Informations Générales</h4>
                                    <div class="grid grid-cols-1 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-500">Numéro de commande</label>
                                            <p class="mt-1 text-sm text-gray-900">{{ $order->order_number }}</p>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-500">Date de commande</label>
                                            <p class="mt-1 text-sm text-gray-900">{{ $order->created_at->format('d/m/Y H:i') }}</p>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-500">Statut</label>
                                            <p class="mt-1">
                                                <span class="px-2 py-1 text-xs font-medium rounded-full
                                                    @if($order->status == 'pending') bg-yellow-50 text-yellow-700
                                                    @elseif($order->status == 'processing') bg-blue-50 text-blue-700
                                                    @elseif($order->status == 'shipped') bg-indigo-50 text-indigo-700
                                                    @elseif($order->status == 'delivered') bg-green-50 text-green-700
                                                    @elseif($order->status == 'cancelled') bg-red-50 text-red-700
                                                    @endif">
                                                    {{ ucfirst($order->status) }}
                                                </span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-5 md:mt-0 md:col-span-1">
                            <div class="shadow rounded-md">
                                <div class="px-4 py-5 bg-white sm:p-6">
                                    <h4 class="text-lg font-medium text-gray-700 mb-3">Informations de Paiement et Livraison</h4>
                                    <div class="grid grid-cols-1 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-500">Statut du paiement</label>
                                            <p class="mt-1">
                                                <span class="px-2 py-1 text-xs font-medium rounded-full
                                                    @if($order->payment_status == 'pending') bg-yellow-50 text-yellow-700
                                                    @elseif($order->payment_status == 'paid') bg-green-50 text-green-700
                                                    @elseif($order->payment_status == 'failed') bg-red-50 text-red-700
                                                    @elseif($order->payment_status == 'refunded') bg-purple-50 text-purple-700
                                                    @endif">
                                                    {{ ucfirst($order->payment_status) }}
                                                </span>
                                            </p>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-500">Méthode de paiement</label>
                                            <p class="mt-1 text-sm text-gray-900">{{ $order->payment_method ?? 'Non spécifié' }}</p>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-500">Méthode d'expédition</label>
                                            <p class="mt-1 text-sm text-gray-900">{{ $order->shipping_method ?? 'Non spécifié' }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div class="bg-white overflow-hidden shadow rounded-md">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Adresse de livraison</h3>
                        <p class="text-sm text-gray-700 whitespace-pre-line">{{ $order->shipping_address }}</p>
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow rounded-md">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Adresse de facturation</h3>
                        <p class="text-sm text-gray-700 whitespace-pre-line">{{ $order->billing_address }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow rounded-md mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Articles commandés</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Produit</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Prix unitaire</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantité</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                            </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($order->items as $item)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                @if ($item->product && $item->product->image)
                                                    <img class="h-10 w-10 rounded-full object-cover" src="{{ asset('storage/' . $item->product->image) }}" alt="{{ $item->product->name }}">
                                                @else
                                                    <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center">
                                                        <span class="text-gray-500 text-xs">No img</span>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">
                                                    @if ($item->product)
                                                        {{ $item->product->name }}
                                                    @else
                                                        Produit supprimé
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ number_format($item->price, 2) }} €</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $item->quantity }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ number_format($item->total, 2) }} €</div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                            <tfoot class="bg-gray-50">
                            <tr>
                                <td colspan="3" class="px-6 py-4 text-right text-sm font-medium text-gray-500">Sous-total</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ number_format($order->total_amount - $order->shipping_cost - $order->tax, 2) }} €</td>
                            </tr>
                            <tr>
                                <td colspan="3" class="px-6 py-4 text-right text-sm font-medium text-gray-500">Frais de livraison</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ number_format($order->shipping_cost, 2) }} €</td>
                            </tr>
                            <tr>
                                <td colspan="3" class="px-6 py-4 text-right text-sm font-medium text-gray-500">TVA</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ number_format($order->tax, 2) }} €</td>
                            </tr>
                            <tr>
                                <td colspan="3" class="px-6 py-4 text-right text-sm font-medium text-gray-900">Total</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">{{ number_format($order->total_amount, 2) }} €</td>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow rounded-md">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Mettre à jour la commande</h3>
                    <form action="{{ route('admin.orders.update', $order) }}" method="POST">
                        @csrf
                        @method('PATCH')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700">Statut de la commande</label>
                                <select name="status" id="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>En attente</option>
                                    <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>En traitement</option>
                                    <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>Expédiée</option>
                                    <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>Livrée</option>
                                    <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Annulée</option>
                                </select>
                            </div>
                            <div>
                                <label for="payment_status" class="block text-sm font-medium text-gray-700">Statut du paiement</label>
                                <select name="payment_status" id="payment_status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    <option value="pending" {{ $order->payment_status == 'pending' ? 'selected' : '' }}>En attente</option>
                                    <option value="paid" {{ $order->payment_status == 'paid' ? 'selected' : '' }}>Payée</option>
                                    <option value="failed" {{ $order->payment_status == 'failed' ? 'selected' : '' }}>Échouée</option>
                                    <option value="refunded" {{ $order->payment_status == 'refunded' ? 'selected' : '' }}>Remboursée</option>
                                </select>
                            </div>
                        </div>

                        <div class="flex justify-end mt-6">
                            <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Mettre à jour
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
