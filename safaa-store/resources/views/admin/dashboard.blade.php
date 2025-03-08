
<x-app-layout>


    <div class="py-10 mt-8 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            {{-- Analytics Cards --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
                <div class="bg-white shadow-lg rounded-2xl p-6 border-l-4 border-blue-500 hover:shadow-xl transition-all duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-md font-semibold text-gray-600 mb-2">Total des commandes</h3>
                            <p class="text-4xl font-bold text-blue-600">{{ $totalOrders }}</p>
                        </div>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-blue-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                    </div>
                </div>
                <div class="bg-white shadow-lg rounded-2xl p-6 border-l-4 border-green-500 hover:shadow-xl transition-all duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-md font-semibold text-gray-600 mb-2">Total des produits</h3>
                            <p class="text-4xl font-bold text-green-600">{{ $totalProducts }}</p>
                        </div>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-green-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                    </div>
                </div>
                <div class="bg-white shadow-lg rounded-2xl p-6 border-l-4 border-purple-500 hover:shadow-xl transition-all duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-md font-semibold text-gray-600 mb-2">Total des utilisateurs</h3>
                            <p class="text-4xl font-bold text-purple-600">{{ $totalUsers }}</p>
                        </div>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-purple-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Recent Orders Table --}}
            <div class="bg-white shadow-lg rounded-2xl overflow-hidden mb-10">
                <div class="px-6 py-5 bg-gray-50 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800">Commandes récentes</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-gray-100 text-gray-600 uppercase text-sm leading-normal">
                                <th class="py-3 px-6 text-left">ID</th>
                                <th class="py-3 px-6 text-left">Utilisateur</th>
                                <th class="py-3 px-6 text-left">Total</th>
                                <th class="py-3 px-6 text-left">Statut</th>
                                <th class="py-3 px-6 text-left">Date</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-600 text-sm font-light">
                            @foreach($recentOrders as $order)
                                <tr class="border-b border-gray-200 hover:bg-gray-100 transition-colors">
                                    <td class="py-3 px-6 whitespace-nowrap">{{ $order->id }}</td>
                                    <td class="py-3 px-6 whitespace-nowrap">{{ $order->user->name }}</td>
                                    <td class="py-3 px-6 whitespace-nowrap">{{ number_format($order->total, 2) }} €</td>
                                    <td class="py-3 px-6 whitespace-nowrap">
                                        <span class="
                                            px-3 py-1 rounded-full text-xs font-medium
                                            {{ $order->status === 'completed' ? 'bg-green-200 text-green-800' :
                                               ($order->status === 'pending' ? 'bg-yellow-200 text-yellow-800' : 'bg-red-200 text-red-800') }}
                                        ">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-6 whitespace-nowrap">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Quick Management Section --}}
            <div>
                <h3 class="text-xl font-bold text-gray-800 mb-6">Gestion rapide</h3>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    @php
                        $quickActions = [
                            ['route' => 'admin.products.index', 'color' => 'indigo', 'icon' => 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4', 'label' => 'Gérer les produits'],
                            ['route' => 'admin.categories.index', 'color' => 'purple', 'icon' => 'M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z', 'label' => 'Gérer les catégories'],
                            ['route' => 'admin.orders.index', 'color' => 'blue', 'icon' => 'M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z', 'label' => 'Gérer les commandes'],
                            ['route' => 'admin.users.index', 'color' => 'green', 'icon' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z', 'label' => 'Gérer les utilisateurs']
                        ];
                    @endphp

                    @foreach($quickActions as $action)
                        <a href="{{ route($action['route']) }}" class="
                            bg-white shadow-md rounded-2xl p-6
                            flex flex-col items-center justify-center
                            hover:shadow-xl transform hover:-translate-y-2
                            transition-all duration-300
                            border-b-4 border-{{ $action['color'] }}-500
                        ">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-{{ $action['color'] }}-600 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $action['icon'] }}" />
                            </svg>
                            <span class="font-semibold text-gray-700">{{ $action['label'] }}</span>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
