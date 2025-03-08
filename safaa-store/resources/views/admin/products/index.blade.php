<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-md mb-4 shadow-md" role="alert">
                    <p class="font-semibold">Succès !</p>
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            <div class="bg-white shadow-lg rounded-lg p-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-2xl font-semibold text-gray-700">Gestion des Produits</h2>
                    <a href="{{ route('admin.products.create') }}" class="bg-indigo-600 text-white py-2 px-4 rounded-lg shadow hover:bg-indigo-700 transition">
                        + Ajouter un produit
                    </a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full table-auto border-collapse bg-white shadow-md rounded-lg overflow-hidden">
                        <thead class="bg-gray-100 text-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-sm font-semibold">Image</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold">Nom</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold">Catégorie</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold">Prix</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold">Stock</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold">Statut</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold">Actions</th>
                        </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                        @foreach ($products as $product)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <div class="h-12 w-12 overflow-hidden rounded-lg border border-gray-300">
                                        @if ($product->image)
                                            <img class="h-full w-full object-cover" src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}">
                                        @else
                                            <div class="flex items-center justify-center h-full bg-gray-200 text-gray-500 text-xs">No img</div>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-gray-700">
                                    <p class="font-medium">{{ $product->name }}</p>
                                    <p class="text-sm text-gray-500">{{ Str::limit($product->description, 50) }}</p>
                                </td>
                                <td class="px-6 py-4 text-gray-700">{{ $product->category->name }}</td>
                                <td class="px-6 py-4 text-gray-700 font-semibold">{{ number_format($product->price, 2) }} €</td>
                                <td class="px-6 py-4 text-gray-700">{{ $product->quantity }}</td>
                                <td class="px-6 py-4">
                                        <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $product->is_active ? 'bg-green-200 text-green-800' : 'bg-red-200 text-red-800' }}">
                                            {{ $product->is_active ? 'Actif' : 'Inactif' }}
                                        </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex space-x-4">
                                        <a href="{{ route('admin.products.edit', $product) }}" class="text-blue-600 hover:underline">Modifier</a>
                                        <form action="{{ route('admin.products.destroy', $product) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce produit?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:underline">Supprimer</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-6">
                    {{ $products->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
