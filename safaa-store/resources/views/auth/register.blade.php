<x-app-layout>
    <div class="min-h-screen relative flex items-center justify-center overflow-hidden py-12 px-4 sm:px-6 lg:px-8">
        <!-- Éléments d'arrière-plan animés -->
        <div class="absolute inset-0">
            <div class="absolute top-0 -left-4 w-72 h-72 bg-purple-300 rounded-full mix-blend-multiply filter blur-xl opacity-20 animate-blob"></div>
            <div class="absolute top-0 -right-4 w-72 h-72 bg-yellow-300 rounded-full mix-blend-multiply filter blur-xl opacity-20 animate-blob animation-delay-2000"></div>
            <div class="absolute -bottom-8 left-20 w-72 h-72 bg-pink-300 rounded-full mix-blend-multiply filter blur-xl opacity-20 animate-blob animation-delay-4000"></div>
        </div>

        <div class="max-w-xl w-full space-y-8 relative z-10">
            <!-- En-tête animé -->
            <div class="text-center animate-fade-in-down">
                <h2 class="mt-6 text-5xl font-black text-transparent bg-clip-text bg-gradient-to-r from-purple-600 via-pink-600 to-blue-600 animate-text-gradient">
                    {{ __('Créez votre compte') }}
                </h2>
                <p class="mt-4 text-lg text-gray-600 animate-fade-in-up delay-200">
                    {{ __('Rejoignez notre communauté dès aujourd hui') }}
                </p>
            </div>

            <!-- Formulaire avec effet glassmorphism -->
            <div class="backdrop-blur-xl bg-white/70 p-8 rounded-3xl shadow-[0_8px_32px_0_rgba(31,38,135,0.37)] border border-white/20 hover:shadow-[0_8px_32px_0_rgba(31,38,135,0.47)] transition-all duration-500">
                <form method="POST" action="{{ route('register') }}" class="space-y-6">
                    @csrf

                    <!-- Name -->
                    <div class="relative group animate-fade-in-up delay-300">
                        <div class="absolute -inset-0.5 bg-gradient-to-r from-pink-500 to-purple-500 rounded-xl blur opacity-0 group-hover:opacity-20 transition duration-700"></div>
                        <div class="relative">
                            <x-input-label for="name" :value="__('Nom')" class="text-sm font-bold text-gray-700 mb-2 block" />
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-500">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </span>
                                <x-text-input id="name" class="pl-10 w-full px-4 py-3 bg-white/60 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-2 focus:ring-purple-200 focus:ring-opacity-50 transition-all duration-300 hover:border-purple-400" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="Votre nom complet" />
                            </div>
                            <x-input-error :messages="$errors->get('name')" class="mt-2 text-sm text-red-500" />
                        </div>
                    </div>

                    <!-- Email Address -->
                    <div class="relative group animate-fade-in-up delay-400">
                        <div class="absolute -inset-0.5 bg-gradient-to-r from-blue-500 to-purple-500 rounded-xl blur opacity-0 group-hover:opacity-20 transition duration-700"></div>
                        <div class="relative">
                            <x-input-label for="email" :value="__('Email')" class="text-sm font-bold text-gray-700 mb-2 block" />
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-500">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                </span>
                                <x-text-input id="email" class="pl-10 w-full px-4 py-3 bg-white/60 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-2 focus:ring-purple-200 focus:ring-opacity-50 transition-all duration-300 hover:border-purple-400" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="votre@email.com" />
                            </div>
                            <x-input-error :messages="$errors->get('email')" class="mt-2 text-sm text-red-500" />
                        </div>
                    </div>

                    <!-- Password -->
                    <div class="relative group animate-fade-in-up delay-500">
                        <div class="absolute -inset-0.5 bg-gradient-to-r from-purple-500 to-pink-500 rounded-xl blur opacity-0 group-hover:opacity-20 transition duration-700"></div>
                        <div class="relative">
                            <x-input-label for="password" :value="__('Mot de passe')" class="text-sm font-bold text-gray-700 mb-2 block" />
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-500">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                    </svg>
                                </span>
                                <x-text-input id="password" class="pl-10 w-full px-4 py-3 bg-white/60 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-2 focus:ring-purple-200 focus:ring-opacity-50 transition-all duration-300 hover:border-purple-400" type="password" name="password" required autocomplete="new-password" placeholder="••••••••" />
                            </div>
                            <x-input-error :messages="$errors->get('password')" class="mt-2 text-sm text-red-500" />
                        </div>
                    </div>

                    <!-- Confirm Password -->
                    <div class="relative group animate-fade-in-up delay-600">
                        <div class="absolute -inset-0.5 bg-gradient-to-r from-pink-500 to-blue-500 rounded-xl blur opacity-0 group-hover:opacity-20 transition duration-700"></div>
                        <div class="relative">
                            <x-input-label for="password_confirmation" :value="__('Confirmez le mot de passe')" class="text-sm font-bold text-gray-700 mb-2 block" />
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-500">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                    </svg>
                                </span>
                                <x-text-input id="password_confirmation" class="pl-10 w-full px-4 py-3 bg-white/60 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-2 focus:ring-purple-200 focus:ring-opacity-50 transition-all duration-300 hover:border-purple-400" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="••••••••" />
                            </div>
                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-sm text-red-500" />
                        </div>
                    </div>

                    <!-- Boutons d'action -->
                    <div class="flex items-center justify-between mt-8 animate-fade-in-up delay-700">
                        <a href="{{ route('login') }}" class="group relative text-sm font-medium">
                            <span class="text-gray-600 hover:text-purple-600 transition-colors duration-300">
                                {{ __('Déjà inscrit?') }}
                            </span>
                            <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-gradient-to-r from-purple-600 to-pink-600 group-hover:w-full transition-all duration-300"></span>
                        </a>

                        <button type="submit" class="relative group">
                            <div class="absolute -inset-0.5 bg-gradient-to-r from-purple-600 to-pink-600 rounded-xl blur opacity-75 group-hover:opacity-100 transition duration-700 animate-gradient-xy"></div>
                            <div class="relative px-6 py-3 bg-black rounded-xl leading-none flex items-center">
                                <span class="text-white font-semibold transition duration-200 group-hover:text-gray-100 flex items-center">
                                    {{ __('S\'inscrire') }}
                                    <svg class="w-5 h-5 ml-2 transform group-hover:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                    </svg>
                                </span>
                            </div>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</x-app-layout>
