<x-app-layout>
    <div class="min-h-screen bg-gradient-to-br from-gray-50 via-white to-gray-100 py-12">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white shadow-2xl rounded-3xl overflow-hidden transform transition-all duration-500 hover:scale-[1.01]">
                <div class="grid md:grid-cols-2 gap-8 p-8 lg:p-12">
                    {{-- Galerie Produit Dynamique --}}
                    <div class="space-y-6">
                        <div
                            x-data="{
                                currentImage: '{{ asset('storage/' . $product->image) }}',
                                images: {{ json_encode($product->images->pluck('image')->toArray()) }}
                            }"
                            class="relative group"
                        >
                            {{-- Image Principale avec Animation --}}
                            <div class="aspect-w-1 aspect-h-1 bg-gray-100 rounded-2xl overflow-hidden">
                                <img
                                    :src="currentImage"
                                    alt="{{ $product->name }}"
                                    class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
                                >
                                {{-- Badge de Disponibilité --}}
                                <div class="absolute top-4 right-4">
                                    <span
                                        class="px-3 py-1 rounded-full text-xs font-bold {{ $product->quantity > 0 ? 'bg-green-500 text-white' : 'bg-red-500 text-white' }}"
                                    >
                                        {{ $product->quantity > 0 ? 'En Stock' : 'Rupture' }}
                                    </span>
                                </div>
                            </div>

                            {{-- Miniatures --}}
                            <div class="mt-4 flex space-x-2">
                                @foreach($product->images as $image)
                                    <button
                                        @click="currentImage = '{{ asset('storage/' . $image->image) }}'"
                                        class="w-16 h-16 rounded-lg overflow-hidden border-2 transition-all hover:border-pink-500"
                                    >
                                        <img
                                            src="{{ asset('storage/' . $image->image) }}"
                                            class="w-full h-full object-cover"
                                        >
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    {{-- Détails Produit Ultra Élégants --}}
                    <div class="space-y-6">
                        <div>

                            <h1 class="text-4xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-purple-600 to-pink-600 mb-3">
                                {{ $product->name }}
                            </h1>

                            <div class="flex items-center space-x-4 mb-4">
                                <a
                                    href="{{ route('products.index', ['category' => $product->category->id]) }}"
                                    class="px-3 py-1 bg-purple-50 text-purple-600 rounded-full text-sm hover:bg-purple-100 transition"
                                >
                                    {{ $product->category->name }}
                                </a>

                                {{-- Note Produit --}}
                                <div class="flex items-center text-yellow-500">
                                    @for($i = 1; $i <= 5; $i++)
                                        <svg
                                            class="w-5 h-5 {{ $i <= round($product->reviews->avg('rating') ?? 0) ? 'text-yellow-500' : 'text-gray-300' }}"
                                            fill="currentColor"
                                            viewBox="0 0 20 20"
                                        >
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                    @endfor
                                    <span class="ml-2 text-sm text-gray-600">
                                        ({{ $product->reviews->count() }} avis)
                                    </span>
                                </div>
                            </div>
                        </div>

                        {{-- Prix et Actions --}}
                        <div class="space-y-4">
                            <div class="text-4xl font-extrabold text-gray-900">
                                {{ number_format($product->price, 2) }} €
                            </div>

                            <form
                                action="{{ route('cart.add') }}"
                                method="POST"
                                x-data="{ quantity: 1 }"
                                class="space-y-4"
                            >
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">

                                <div class="flex items-center space-x-4">
                                    {{-- Sélecteur de Quantité --}}
                                    <div class="flex items-center border rounded-full">
                                        <button
                                            type="button"
                                            @click="quantity = Math.max(1, quantity - 1)"
                                            class="px-3 py-2 text-gray-600 hover:bg-gray-100 rounded-l-full"
                                        >
                                            -
                                        </button>
                                        <input
                                            type="number"
                                            x-model="quantity"
                                            name="quantity"
                                            min="1"
                                            max="{{ $product->quantity }}"
                                            class="w-16 text-center border-none focus:ring-0"
                                        >
                                        <button
                                            type="button"
                                            @click="quantity = Math.min({{ $product->quantity }}, quantity + 1)"
                                            class="px-3 py-2 text-gray-600 hover:bg-gray-100 rounded-r-full"
                                        >
                                            +
                                        </button>
                                    </div>

                                    {{-- Bouton Ajouter au Panier --}}
                                    <button
                                        type="submit"
                                        class="flex-1 px-6 py-3 bg-gradient-to-r from-purple-600 to-pink-600 text-white rounded-full hover:from-purple-700 hover:to-pink-700 transition-all transform hover:scale-105 focus:outline-none"
                                    >
                                        Ajouter au Panier
                                    </button>
                                </div>
                            </form>
                        </div>

                        {{-- Description Détaillée --}}
                        <div class="prose max-w-none text-gray-700">
                            <h3 class="text-xl font-semibold mb-3">Description</h3>
                            {{ $product->description }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
