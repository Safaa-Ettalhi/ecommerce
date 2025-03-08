<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg shadow mb-4 flex items-center" role="alert">
                    <svg class="w-6 h-6 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="min-w-full border border-gray-200 rounded-lg">
                            <thead class="bg-gray-100">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Numéro</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Client</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Total</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Statut</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Paiement</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Actions</th>
                            </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($orders as $order)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 text-sm text-gray-900 font-medium">{{ $order->order_number }}</td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $order->user->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $order->user->email }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900 font-semibold">{{ number_format($order->total_amount, 2) }} €</td>
                                    <td class="px-6 py-4">
                                            <span class="px-3 py-1 text-xs font-semibold rounded-full {{
                                                match($order->status) {
                                                    'pending' => 'bg-yellow-100 text-yellow-800',
                                                    'processing' => 'bg-blue-100 text-blue-800',
                                                    'shipped' => 'bg-indigo-100 text-indigo-800',
                                                    'delivered' => 'bg-green-100 text-green-800',
                                                    'cancelled' => 'bg-red-100 text-red-800',
                                                    default => ''
                                                }
                                            }}">
                                                {{ ucfirst($order->status) }}
                                            </span>
                                    </td>
                                    <td class="px-6 py-4">
                                            <span class="px-3 py-1 text-xs font-semibold rounded-full {{
                                                match($order->payment_status) {
                                                    'pending' => 'bg-yellow-100 text-yellow-800',
                                                    'paid' => 'bg-green-100 text-green-800',
                                                    'failed' => 'bg-red-100 text-red-800',
                                                    'refunded' => 'bg-purple-100 text-purple-800',
                                                    default => ''
                                                }
                                            }}">
                                                {{ ucfirst($order->payment_status) }}
                                            </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                                    <td class="px-6 py-4 text-sm">
                                        <div class="flex space-x-2">
                                            <a href="{{ route('admin.orders.show', $order) }}" class="text-indigo-600 hover:text-indigo-900 flex items-center">
                                                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.94-4.94a1.5 1.5 0 00-2.12-2.12L13 7.88V6a2 2 0 00-4 0v1.88L6.18 2.94a1.5 1.5 0 00-2.12 2.12L9 10m6 4l-4.94 4.94a1.5 1.5 0 01-2.12-2.12L11 16.12V18a2 2 0 004 0v-1.88l3.82 3.82a1.5 1.5 0 002.12-2.12L15 14"></path></svg>
                                                Voir
                                            </a>
                                            <a href="{{ route('admin.orders.invoice', $order) }}" class="text-green-600 hover:text-green-900 flex items-center">
                                                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-4m3 4v-6m3 6v-2m3-5V4a2 2 0 00-2-2H6a2 2 0 00-2 2v14a2 2 0 002 2h12a2 2 0 002-2v-5z"></path></svg>
                                                Facture
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $orders->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
