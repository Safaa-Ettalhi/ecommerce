<x-app-layout>
    <div class="min-h-screen bg-gradient-to-br from-violet-50 via-white to-rose-50 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white shadow-xl rounded-3xl overflow-hidden transform transition-all duration-500 hover:scale-[1.01] border border-gray-100">
                <div class="grid md:grid-cols-2 gap-10 p-8 lg:p-12">
                    {{-- Dynamic Product Gallery --}}
                    <div class="space-y-8">
                        <div
                            x-data="{
                                currentImage: '{{ asset('storage/' . $product->image) }}',
                                images: {{ json_encode($product->images->pluck('image')->toArray()) }},
                                activeIndex: 0
                            }"
                            class="relative group"
                        >
                            {{-- Main Image with Animation --}}
                            <div class="aspect-w-1 aspect-h-1 bg-gray-50 rounded-3xl overflow-hidden shadow-lg">
                                <img
                                    :src="currentImage"
                                    alt="{{ $product->name }}"
                                    class="w-full h-full object-cover transition-all duration-700 group-hover:scale-105"
                                >
                                {{-- Availability Badge --}}
                                <div class="absolute top-5 right-5 z-10">
                                    <span
                                        class="px-4 py-1.5 rounded-full text-xs font-bold shadow-md transition-all {{ $product->quantity > 0 ? 'bg-gradient-to-r from-emerald-500 to-teal-500 text-white' : 'bg-gradient-to-r from-rose-500 to-red-500 text-white' }}"
                                    >
                                        {{ $product->quantity > 0 ? 'En Stock' : 'Rupture' }}
                                    </span>
                                </div>
                            </div>

                            {{-- Thumbnails --}}
                            <div class="mt-6 flex items-center justify-center space-x-3">
                                @foreach($product->images as $index => $image)
                                    <button
                                        @click="currentImage = '{{ asset('storage/' . $image->image) }}'; activeIndex = {{ $index }}"
                                        class="w-20 h-20 rounded-xl overflow-hidden border-2 transition-all hover:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:ring-offset-2"
                                        :class="activeIndex === {{ $index }} ? 'border-violet-500 ring-2 ring-violet-500 ring-offset-2' : 'border-gray-200'"
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

                    {{-- Ultra Elegant Product Details --}}
                    <div class="space-y-8">
                        <div>
                            <h1 class="text-4xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-violet-600 to-fuchsia-600 mb-4">
                                {{ $product->name }}
                            </h1>

                            <div class="flex flex-wrap items-center gap-4 mb-6">
                                <a
                                    href="{{ route('products.index', ['category' => $product->category->id]) }}"
                                    class="px-4 py-1.5 bg-violet-50 text-violet-700 rounded-full text-sm font-medium hover:bg-violet-100 transition-all"
                                >
                                    {{ $product->category->name }}
                                </a>

                                {{-- Product Rating --}}
                                <div class="flex items-center text-amber-500">
                                    @for($i = 1; $i <= 5; $i++)
                                        <svg
                                            class="w-5 h-5 {{ $i <= round($product->reviews->avg('rating') ?? 0) ? 'text-amber-400' : 'text-gray-200' }}"
                                            fill="currentColor"
                                            viewBox="0 0 20 20"
                                        >
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                    @endfor
                                    <span class="ml-2 text-sm font-medium text-gray-700">
                                        ({{ $product->reviews->count() }} avis)
                                    </span>
                                </div>
                            </div>
                        </div>

                        {{-- Price and Actions --}}
                        <div class="space-y-6">
                            <div class="flex items-center gap-4">
                                <div class="text-5xl font-black text-gray-900">
                                    {{ number_format($product->price, 2) }} €
                                </div>
                                @if($product->old_price)
                                    <div class="text-2xl font-medium text-gray-400 line-through">
                                        {{ number_format($product->old_price, 2) }} €
                                    </div>
                                @endif
                            </div>

                            <form
                                action="{{ route('cart.add') }}"
                                method="POST"
                                x-data="{ quantity: 1 }"
                                class="space-y-6"
                            >
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">

                                <div class="flex items-center gap-4">
                                    {{-- Quantity Selector --}}
                                    <div class="flex items-center border-2 border-gray-200 rounded-full bg-white shadow-sm">
                                        <button
                                            type="button"
                                            @click="quantity = Math.max(1, quantity - 1)"
                                            class="px-4 py-2.5 text-gray-600 hover:bg-gray-50 rounded-l-full focus:outline-none"
                                        >
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                        <input
                                            type="number"
                                            x-model="quantity"
                                            name="quantity"
                                            min="1"
                                            max="{{ $product->quantity }}"
                                            class="w-16 text-center border-none focus:ring-0 font-medium"
                                        >
                                        <button
                                            type="button"
                                            @click="quantity = Math.min({{ $product->quantity }}, quantity + 1)"
                                            class="px-4 py-2.5 text-gray-600 hover:bg-gray-50 rounded-r-full focus:outline-none"
                                        >
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                    </div>

                                    {{-- Add to Cart Button --}}
                                    <button
                                        type="submit"
                                        class="flex-1 px-8 py-3.5 bg-gradient-to-r from-violet-600 to-fuchsia-600 text-white rounded-full font-medium hover:from-violet-700 hover:to-fuchsia-700 transition-all transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:ring-offset-2 shadow-lg"
                                    >
                                        <div class="flex items-center justify-center gap-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z" />
                                            </svg>
                                            <span>Ajouter au Panier</span>
                                        </div>
                                    </button>
                                </div>
                            </form>
                        </div>

                        {{-- Detailed Description --}}
                        <div class="prose max-w-none text-gray-700 bg-gray-50 p-6 rounded-2xl">
                            <h3 class="text-xl font-semibold text-gray-900 mb-4">Description</h3>
                            <div class="text-gray-600">
                                {{ $product->description }}
                            </div>
                        </div>
                    </div>
                </div>
                
                {{-- Reviews Section --}}
                <div class="border-t border-gray-100 mt-10 pt-10 px-8 lg:px-12 pb-12">
                    <h2 class="text-2xl font-bold text-gray-900 mb-8">Avis des clients</h2>
                    
                    <!-- Approved Reviews List -->
                    @if($product->reviews->where('is_approved', true)->count() > 0)
                        <div class="grid sm:grid-cols-2 gap-6 mb-12">
                            @foreach($product->reviews->where('is_approved', true) as $review)
                                <div class="bg-gray-50 p-6 rounded-2xl shadow-sm hover:shadow-md transition-shadow">
                                    <div class="flex items-center mb-3">
                                        <div class="flex text-amber-400">
                                            @for($i = 1; $i <= 5; $i++)
                                                @if($i <= $review->rating)
                                                    <svg class="h-5 w-5 fill-current" viewBox="0 0 24 24">
                                                        <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"></path>
                                                    </svg>
                                                @else
                                                    <svg class="h-5 w-5 fill-current text-gray-300" viewBox="0 0 24 24">
                                                        <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"></path>
                                                    </svg>
                                                @endif
                                            @endfor
                                        </div>
                                        <span class="ml-2 font-medium text-gray-900">{{ $review->user->name }}</span>
                                        <span class="ml-auto text-sm text-gray-500">{{ $review->created_at->format('d/m/Y') }}</span>
                                    </div>
                                    <p class="text-gray-700">{{ $review->comment }}</p>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="bg-gray-50 p-8 rounded-2xl text-center mb-12">
                            <p class="text-gray-500">Aucun avis pour ce produit.</p>
                            <p class="text-gray-500 mt-2">Soyez le premier à donner votre avis !</p>
                        </div>
                    @endif
                    
                    <!-- Review Form -->
                    @auth
                        <div class="bg-gradient-to-br from-violet-50 to-fuchsia-50 p-8 rounded-2xl shadow-sm">
                            <h3 class="text-2xl font-semibold text-gray-900 mb-6">Donnez votre avis</h3>
                            
                            <form action="{{ route('products.reviews.store', $product) }}" method="POST">
                                @csrf
                                
                                <div class="mb-6">
                                    <label for="rating" class="block text-sm font-medium text-gray-700 mb-3">Note</label>
                                    <div class="flex flex-wrap gap-4">
                                        <div class="flex items-center">
                                            <input type="radio" id="rating-5" name="rating" value="5" class="mr-2 h-4 w-4 text-violet-600 focus:ring-violet-500">
                                            <label for="rating-5" class="text-gray-800">5 (Excellent)</label>
                                        </div>
                                        <div class="flex items-center">
                                            <input type="radio" id="rating-4" name="rating" value="4" class="mr-2 h-4 w-4 text-violet-600 focus:ring-violet-500">
                                            <label for="rating-4" class="text-gray-800">4 (Très bien)</label>
                                        </div>
                                        <div class="flex items-center">
                                            <input type="radio" id="rating-3" name="rating" value="3" class="mr-2 h-4 w-4 text-violet-600 focus:ring-violet-500" checked>
                                            <label for="rating-3" class="text-gray-800">3 (Bien)</label>
                                        </div>
                                        <div class="flex items-center">
                                            <input type="radio" id="rating-2" name="rating" value="2" class="mr-2 h-4 w-4 text-violet-600 focus:ring-violet-500">
                                            <label for="rating-2" class="text-gray-800">2 (Moyen)</label>
                                        </div>
                                        <div class="flex items-center">
                                            <input type="radio" id="rating-1" name="rating" value="1" class="mr-2 h-4 w-4 text-violet-600 focus:ring-violet-500">
                                            <label for="rating-1" class="text-gray-800">1 (Mauvais)</label>
                                        </div>
                                    </div>
                                    @error('rating')
                                        <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <div class="mb-6">
                                    <label for="comment" class="block text-sm font-medium text-gray-700 mb-3">Commentaire</label>
                                    <textarea 
                                        id="comment" 
                                        name="comment" 
                                        rows="4" 
                                        class="shadow-sm focus:ring-violet-500 focus:border-violet-500 block w-full sm:text-sm border-gray-300 rounded-xl" 
                                        placeholder="Partagez votre expérience avec ce produit..."
                                    ></textarea>
                                    @error('comment')
                                        <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <button 
                                    type="submit" 
                                    class="inline-flex items-center px-6 py-3 border border-transparent rounded-xl shadow-sm text-sm font-medium text-white bg-gradient-to-r from-violet-600 to-fuchsia-600 hover:from-violet-700 hover:to-fuchsia-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-violet-500 transition-all transform hover:scale-105"
                                >
                                    Soumettre l'avis
                                </button>
                            </form>
                        </div>
                    @else
                        <div class="bg-gray-50 p-8 rounded-2xl text-center">
                            <p class="text-gray-700 mb-4">Veuillez vous connecter pour laisser un avis.</p>
                            <a 
                                href="{{ route('login') }}" 
                                class="inline-flex items-center px-6 py-3 border border-transparent rounded-xl shadow-sm text-sm font-medium text-white bg-gradient-to-r from-violet-600 to-fuchsia-600 hover:from-violet-700 hover:to-fuchsia-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-violet-500 transition-all"
                            >
                                Se connecter
                            </a>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
