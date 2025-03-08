<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Paiement par carte bancaire') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="text-center">
                        <h3 class="text-lg font-semibold mb-4">Finaliser votre paiement</h3>
                        <p class="mb-4">Vous allez être redirigé vers Stripe pour effectuer votre paiement en toute sécurité.</p>
                        <div id="payment-loading" class="flex justify-center mb-4">
                            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span>Chargement du formulaire de paiement...</span>
                        </div>
                        <div id="payment-button" class="hidden">
                           
                            <button id="checkout-button" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Payer maintenant
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

   
    <script src="https://js.stripe.com/v3/"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const stripe = Stripe('{{ $stripeKey }}');
            const checkoutButton = document.getElementById('checkout-button');
            const paymentLoading = document.getElementById('payment-loading');
            const paymentButton = document.getElementById('payment-button');
        
            // Afficher le bouton après un court délai
            setTimeout(function() {
                paymentLoading.classList.add('hidden');
                paymentButton.classList.remove('hidden');
            }, 1500);
            
            checkoutButton.addEventListener('click', function() {
                // Rediriger vers Checkout
                stripe.redirectToCheckout({
                    sessionId: '{{ $sessionId }}'
                }).then(function (result) {
                    if (result.error) {
                        alert(result.error.message);
                    }
                });
            });
            
            // Redirection automatique après 3 secondes
            setTimeout(function() {
                checkoutButton.click();
            }, 3000);
        });
    </script>
   
</x-app-layout>

