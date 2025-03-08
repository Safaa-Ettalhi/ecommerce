<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                {{ __('Modifier le produit') }}: <span class="text-indigo-600">{{ $product->name }}</span>
            </h2>
            <a href="{{ route('admin.products.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Retour
            </a>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-md shadow-sm mb-6" role="alert">
                    <p class="font-medium">{{ session('error') }}</p>
                </div>
            @endif

            @if (session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-md shadow-sm mb-6" role="alert">
                    <p class="font-medium">{{ session('success') }}</p>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-md rounded-lg">
                <div class="px-6 py-5 border-b border-gray-200 bg-gray-50">
                    <h3 class="text-lg font-medium text-gray-900">Informations du produit</h3>
                    <p class="mt-1 text-sm text-gray-500">Modifiez les détails du produit ci-dessous.</p>
                </div>

                <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data" class="p-6">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                        <div class="space-y-6">
                            <!-- Nom du produit -->
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700">Nom du produit</label>
                                <input type="text" name="name" id="name"
                                       value="{{ old('name', $product->name) }}"
                                       class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                                @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Description -->
                            <div>
                                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                                <textarea name="description" id="description"
                                          rows="4"
                                          class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                          required>{{ old('description', $product->description) }}</textarea>
                                @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Prix et Quantité -->
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="price" class="block text-sm font-medium text-gray-700">Prix (€)</label>
                                    <div class="mt-1 relative rounded-md shadow-sm">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <span class="text-gray-500 sm:text-sm">€</span>
                                        </div>
                                        <input type="number" name="price" id="price"
                                               value="{{ old('price', $product->price) }}"
                                               step="0.01" min="0"
                                               class="block w-full pl-7 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                                    </div>
                                    @error('price')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="quantity" class="block text-sm font-medium text-gray-700">Quantité</label>
                                    <input type="number" name="quantity" id="quantity"
                                           value="{{ old('quantity', $product->quantity) }}"
                                           min="0"
                                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                                    @error('quantity')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Catégorie -->
                            <div>
                                <label for="category_id" class="block text-sm font-medium text-gray-700">Catégorie</label>
                                <select name="category_id" id="category_id"
                                        class="mt-1 block w-full bg-white border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}"
                                            {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Actif/Inactif -->
                            <div class="flex items-center">
                                <div class="flex items-center h-5">
                                    <input id="is_active" name="is_active" type="checkbox"
                                           {{ old('is_active', $product->is_active) ? 'checked' : '' }}
                                           class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                                </div>
                                <div class="ml-3 text-sm">
                                    <label for="is_active" class="font-medium text-gray-700">Produit actif</label>
                                    <p class="text-gray-500">Ce produit sera visible sur votre boutique.</p>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-6">
                            <!-- Image principale -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Image principale</label>
                                <div class="mt-1 flex flex-col items-center">
                                    <div id="main-image-preview" class="w-full h-48 bg-gray-100 rounded-lg overflow-hidden mb-3 flex items-center justify-center border-2 border-dashed border-gray-300 hover:border-indigo-500 transition-colors">
                                        @if($product->image)
                                            <img src="{{ Storage::url($product->image) }}" alt="Image actuelle" class="w-full h-full object-contain">
                                        @else
                                            <svg class="h-12 w-12 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        @endif
                                    </div>
                                    <div class="w-full flex items-center justify-center">
                                        <label for="image" class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none">
                                            <span class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                                <svg class="-ml-1 mr-2 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                                Changer l'image
                                            </span>
                                            <input id="image" name="image" type="file" class="sr-only" accept="image/*" onchange="previewMainImage(this)">
                                        </label>
                                    </div>
                                    @error('image')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Images supplémentaires -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Images supplémentaires</label>

                                <!-- Affichage des images existantes -->
                                <div id="existing-images" class="mb-4">
                                    <h4 class="text-sm font-medium text-gray-500 mb-2">Images existantes</h4>
                                    <div class="grid grid-cols-3 gap-3">
                                        @if(isset($product->images) && count($product->images) > 0)
                                            @foreach($product->images as $index => $image)
                                                <div class="relative group">
                                                    <div class="h-24 bg-gray-100 rounded-md overflow-hidden border border-gray-200">
                                                        <img src="{{ Storage::url($image->image) }}" alt="Image supplémentaire" class="w-full h-full object-cover">
                                                    </div>
                                                    <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 flex items-center justify-center transition-all duration-200 opacity-0 group-hover:opacity-100">
                                                        <button type="button" onclick="removeExistingImage(this, {{ $image->id }})" class="p-1 bg-red-500 rounded-full text-white hover:bg-red-600 focus:outline-none">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                            </svg>
                                                        </button>
                                                    </div>
                                                    <input type="hidden" name="existing_images[]" value="{{ $image->id }}">
                                                </div>
                                            @endforeach
                                        @else
                                            <div class="col-span-3 text-center py-4 text-sm text-gray-500 italic">
                                                Aucune image supplémentaire
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <!-- Nouvelles images à ajouter -->
                                <div>
                                    <h4 class="text-sm font-medium text-gray-500 mb-2">Ajouter de nouvelles images</h4>
                                    <div id="new-images-preview" class="grid grid-cols-3 gap-3 mb-3">
                                        <!-- Les prévisualisations des nouvelles images seront ajoutées ici par JavaScript -->
                                        <div class="h-24 bg-gray-100 rounded-md border-2 border-dashed border-gray-300 flex items-center justify-center text-gray-400 text-sm">
                                            Prévisualisation
                                        </div>
                                    </div>

                                    <div class="mt-3">
                                        <label for="additional_images" class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none">
                                            <span class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                                <svg class="-ml-1 mr-2 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                                </svg>
                                                Sélectionner des images
                                            </span>
                                            <input id="additional_images" name="additional_images[]" type="file" multiple class="sr-only" accept="image/*" onchange="previewAdditionalImages(this)">
                                        </label>
                                    </div>
                                    @error('additional_images')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                    @error('additional_images.*')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Images à supprimer (sera rempli par JavaScript) -->
                    <div id="images-to-delete-container"></div>

                    <div class="mt-8 pt-5 border-t border-gray-200">
                        <div class="flex justify-end">
                            <a href="{{ route('admin.products.index') }}" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Annuler
                            </a>
                            <button type="submit" class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Mettre à jour le produit
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- JavaScript pour la prévisualisation et la gestion des images -->
    <script>
        // Tableau pour stocker les IDs des images à supprimer
        let imagesToDelete = [];

        // Prévisualisation de l'image principale
        function previewMainImage(input) {
            const preview = document.getElementById('main-image-preview');
            preview.innerHTML = '';

            if (input.files && input.files[0]) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.className = 'w-full h-full object-contain';
                    img.alt = 'Prévisualisation';
                    preview.appendChild(img);
                }

                reader.readAsDataURL(input.files[0]);
            } else {
                // Si aucun fichier n'est sélectionné, afficher l'icône par défaut
                preview.innerHTML = `
                    <svg class="h-12 w-12 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                `;
            }
        }

        // Prévisualisation des images supplémentaires
        function previewAdditionalImages(input) {
            const preview = document.getElementById('new-images-preview');

            // Effacer le contenu par défaut
            preview.innerHTML = '';

            if (input.files && input.files.length > 0) {
                for (let i = 0; i < input.files.length; i++) {
                    const file = input.files[i];
                    const reader = new FileReader();

                    reader.onload = function(e) {
                        const container = document.createElement('div');
                        container.className = 'relative group';

                        const imageContainer = document.createElement('div');
                        imageContainer.className = 'h-24 bg-gray-100 rounded-md overflow-hidden border border-gray-200';

                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.className = 'w-full h-full object-cover';
                        img.alt = 'Nouvelle image';

                        const overlay = document.createElement('div');
                        overlay.className = 'absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 flex items-center justify-center transition-all duration-200 opacity-0 group-hover:opacity-100';

                        const removeButton = document.createElement('button');
                        removeButton.type = 'button';
                        removeButton.className = 'p-1 bg-red-500 rounded-full text-white hover:bg-red-600 focus:outline-none';
                        removeButton.innerHTML = `
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        `;
                        removeButton.onclick = function() {
                            container.remove();

                            // Si toutes les prévisualisations sont supprimées, afficher le message par défaut
                            if (preview.children.length === 0) {
                                preview.innerHTML = `
                                    <div class="h-24 bg-gray-100 rounded-md border-2 border-dashed border-gray-300 flex items-center justify-center text-gray-400 text-sm">
                                        Prévisualisation
                                    </div>
                                `;
                            }
                        };

                        overlay.appendChild(removeButton);
                        imageContainer.appendChild(img);
                        container.appendChild(imageContainer);
                        container.appendChild(overlay);
                        preview.appendChild(container);
                    }

                    reader.readAsDataURL(file);
                }
            } else {
                // Si aucun fichier n'est sélectionné, afficher le message par défaut
                preview.innerHTML = `
                    <div class="h-24 bg-gray-100 rounded-md border-2 border-dashed border-gray-300 flex items-center justify-center text-gray-400 text-sm">
                        Prévisualisation
                    </div>
                `;
            }
        }

        // Supprimer une image existante
        function removeExistingImage(button, imageId) {
            // Ajouter l'ID de l'image à la liste des images à supprimer
            imagesToDelete.push(imageId);

            // Créer un champ caché pour chaque image à supprimer
            const container = document.getElementById('images-to-delete-container');
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'delete_images[]';
            input.value = imageId;
            container.appendChild(input);

            // Supprimer visuellement l'élément
            button.closest('.relative.group').remove();

            // Si toutes les images existantes sont supprimées, afficher un message
            const existingImagesContainer = document.getElementById('existing-images').querySelector('.grid');
            if (existingImagesContainer.children.length === 0) {
                existingImagesContainer.innerHTML = `
                    <div class="col-span-3 text-center py-4 text-sm text-gray-500 italic">
                        Aucune image supplémentaire
                    </div>
                `;
            }
        }
    </script>
</x-app-layout>
