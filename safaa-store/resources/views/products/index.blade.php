<x-app-layout>
    <div class="min-h-screen bg-gradient-to-br from-gray-50 via-white to-gray-100 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            {{-- Titre Dynamique avec Animation --}}
            <div class="mb-10 text-center">
                <h1 class="text-4xl md:text-5xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-purple-600 to-pink-600 mb-4 animate-gradient-x">
                    Notre Catalogue
                </h1>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                    Découvrez une sélection soigneusement choisie de produits exceptionnels
                </p>
            </div>

            <div class="flex flex-col md:flex-row space-y-6 md:space-y-0 md:space-x-8">
                {{-- Sidebar Filtres Moderne --}}
                <div class="w-full md:w-1/4 space-y-6">
                    <div class="bg-white rounded-2xl shadow-2xl overflow-hidden transition-all duration-300 hover:shadow-3xl transform hover:-translate-y-2">
                        <div class="p-6 bg-gradient-to-r from-purple-50 to-pink-50 border-b border-gray-100">
                            <h3 class="text-xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-purple-600 to-pink-600 flex items-center">
                                <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path>
                                </svg>
                                Filtres Intelligents
                            </h3>
                        </div>

                        <form
                            x-data="filterForm()"
                            @submit.prevent="submitForm()"
                            action="{{ route('products.index') }}"
                            method="GET"
                            class="p-6 space-y-6"
                        >
                            {{-- Catégories avec Design Amélioré --}}
                            <div class="space-y-4">
                                <h4 class="font-semibold text-lg text-gray-800 mb-3">Catégories</h4>
                                <div class="grid grid-cols-2 gap-2">
                                    @foreach ($categories as $category)
                                        <label class="inline-flex items-center group cursor-pointer">
                                            <input
                                                type="radio"
                                                name="category"
                                                value="{{ $category->id }}"
                                                class="hidden peer"
                                                {{ request('category') == $category->id ? 'checked' : '' }}
                                            >
                                            <span class="px-3 py-1 text-sm rounded-full border-2 border-transparent peer-checked:border-pink-500 peer-checked:text-pink-600 text-gray-600 group-hover:bg-pink-50 transition-all duration-300">
                                                {{ $category->name }}
                                            </span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            {{-- Prix Dynamique avec Range Slider --}}
                            <div class="space-y-4">
                                <h4 class="font-semibold text-lg text-gray-800 mb-3">Prix</h4>
                                <div x-data="rangePriceSlider()" class="space-y-4">
                                    <div class="relative w-full h-2 bg-gray-200 rounded-full">
                                        <div
                                            x-ref="slider"
                                            class="absolute h-full bg-gradient-to-r from-purple-500 to-pink-500 rounded-full"
                                            :style="`width: ${(maxValue - minValue) / maxAllowedValue * 100}%; left: ${minValue / maxAllowedValue * 100}%`"
                                        ></div>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <input
                                            type="range"
                                            x-model="minValue"
                                            :max="maxAllowedValue"
                                            min="0"
                                            name="min_price"
                                            class="w-full h-2 bg-transparent appearance-none"
                                        >
                                        <input
                                            type="range"
                                            x-model="maxValue"
                                            :max="maxAllowedValue"
                                            min="0"
                                            name="max_price"
                                            class="w-full h-2 bg-transparent appearance-none"
                                        >
                                    </div>
                                    <div class="flex justify-between text-sm text-gray-600">
                                        <span x-text="`${minValue} €`"></span>
                                        <span x-text="`${maxValue} €`"></span>
                                    </div>
                                </div>
                            </div>

                            {{-- Tri Élégant --}}
                            <div class="space-y-4">
                                <h4 class="font-semibold text-lg text-gray-800 mb-3">Trier Par</h4>
                                <div class="relative">
                                    <select
                                        name="sort"
                                        class="w-full rounded-xl border-gray-300 shadow-md focus:border-pink-500 focus:ring-pink-500 transition-all duration-300"
                                    >
                                        <option value="newest">Les Plus Récents</option>
                                        <option value="price_asc">Prix : Croissant</option>
                                        <option value="price_desc">Prix : Décroissant</option>
                                        <option value="popularity">Plus Populaires</option>
                                    </select>
                                </div>
                            </div>

                            {{-- Boutons de Filtrage --}}
                            <div class="flex space-x-4">
                                <button
                                    type="submit"
                                    class="w-full bg-gradient-to-r from-purple-600 to-pink-600 text-white py-3 rounded-xl hover:from-purple-700 hover:to-pink-700 transition-all duration-300 transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-pink-500 focus:ring-opacity-50"
                                >
                                    Appliquer les Filtres
                                </button>
                                <button
                                    type="reset"
                                    class="w-full bg-gray-100 text-gray-800 py-3 rounded-xl hover:bg-gray-200 transition-all duration-300 transform hover:scale-105"
                                >
                                    Réinitialiser
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Liste des Produits --}}
                <div class="w-full md:w-3/4">
                    {{-- Grid de Produits Moderne --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($products as $product)
                            <div class="bg-white rounded-2xl shadow-xl overflow-hidden transform transition duration-500 hover:scale-105 hover:shadow-2xl group">
                                <a href="{{ route('products.show', $product->slug) }}">
                                <div class="relative">
                                    <img
                                        src="{{asset('storage/' . $product->image) }}"
                                        alt="{{ $product->name }}"
                                        class="w-full h-48 object-cover transition-transform duration-500 group-hover:scale-110"
                                    >
                                    @if($product->isNew())
                                        <span class="absolute top-4 right-4 bg-green-500 text-white text-xs font-bold px-3 py-1 rounded-full">
                                            Nouveau
                                        </span>
                                    @endif
                                </div></a>
                                <div class="p-5">
                                    <h3 class="text-lg font-bold text-gray-900 mb-2">{{ $product->name }}</h3>
                                    <p class="text-sm text-gray-600 mb-4">{{ Str::limit($product->description, 50) }}</p>
                                    <div class="flex justify-between items-center">
                                        <span class="text-xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-purple-600 to-pink-600">
                                            {{ number_format($product->price, 2) }} €
                                        </span>
                                        <form action="{{ route('cart.add') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                                            <input type="hidden" name="quantity" value="1">
                                        <button class="bg-gradient-to-r from-purple-500 to-pink-500 text-white px-4 py-2 rounded-full hover:from-purple-600 hover:to-pink-600 transition-all duration-300">
                                            Ajouter
                                        </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Pagination Élégante --}}
                    <div class="mt-10">
                        {{ $products->appends(request()->query())->links('vendor.pagination.tailwind') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function filterForm() {
                return {
                    submitForm() {
                        this.$el.submit();
                    }
                }
            }

            function rangePriceSlider() {
                return {
                    minValue: {{ request('min_price', 0) }},
                    maxValue: {{ request('max_price', 1000) }},
                    maxAllowedValue: 1000,
                }
            }
        </script>
    @endpush
</x-app-layout>
