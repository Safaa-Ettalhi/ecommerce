<?php

use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\ProductImageController as AdminProductImageController;
use App\Http\Controllers\Admin\ReviewController as AdminReviewController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TestPayPalController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WebhookController;
use App\Http\Controllers\TestPayPalCertificateController;
use App\Http\Controllers\TestSSLController;
Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Product routes
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{slug}', [ProductController::class, 'show'])->name('products.show');
Route::post('/products/{product}/reviews', [ProductController::class, 'storeReview'])->name('products.reviews.store')->middleware('auth');

// Cart routes
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
Route::get('/cart/remove/{productId}', [CartController::class, 'remove'])->name('cart.remove');
Route::get('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');

/* Checkout routes
Route::middleware('auth')->group(function () {
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout/process', [CheckoutController::class, 'process'])->name('checkout.process');
    Route::get('/checkout/success/{order}', [CheckoutController::class, 'success'])->name('checkout.success');
    Route::get('/checkout/cancel/{order}', [CheckoutController::class, 'cancel'])->name('checkout.cancel');
});*/

// Order routes
Route::middleware('auth')->group(function () {
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::get('/orders/{order}/invoice', [OrderController::class, 'generateInvoice'])->name('orders.invoice');
});

// Profile routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin routes
Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Admin Product routes
    Route::resource('products', AdminProductController::class);
    Route::delete('/product-images/{productImage}', [AdminProductImageController::class, 'destroy'])->name('product-images.destroy');

    // Admin Category routes
    Route::resource('categories', AdminCategoryController::class);

    // Admin Order routes
    Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
    Route::patch('/orders/{order}', [AdminOrderController::class, 'update'])->name('orders.update');
    Route::get('/orders/{order}/invoice', [AdminOrderController::class, 'generateInvoice'])->name('orders.invoice');

    // Admin User routes
    Route::resource('users', AdminUserController::class);

    // Admin Review routes
    Route::get('/reviews', [AdminReviewController::class, 'index'])->name('reviews.index');
    Route::get('/reviews/{review}', [AdminReviewController::class, 'show'])->name('reviews.show');
    Route::patch('/reviews/{review}/approve', [AdminReviewController::class, 'approve'])->name('reviews.approve');
    Route::delete('/reviews/{review}', [AdminReviewController::class, 'destroy'])->name('reviews.destroy');
});

// Webhook routes
Route::middleware(['auth'])->group(function () {
  // Routes existantes
  Route::get('/test-paypal', [TestPayPalController::class, 'testConnection']);

  // Routes de checkout
  Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
  Route::post('/checkout/process', [CheckoutController::class, 'process'])->name('checkout.process');
  Route::get('/checkout/success/{order}', [CheckoutController::class, 'success'])->name('checkout.success');
  Route::get('/checkout/cancel/{order}', [CheckoutController::class, 'cancel'])->name('checkout.cancel');

  // Nouvelle route pour le paiement à la livraison
  Route::get('/checkout/cod-success/{order}', [CheckoutController::class, 'codSuccess'])->name('checkout.cod-success');

  // Route pour tester les certificats SSL
  Route::get('/test-paypal-certificate', [TestPayPalCertificateController::class, 'testCertificate']);

  // Nouvelle route pour tester la connexion SSL à PayPal
  Route::get('/test-paypal-ssl', [TestSSLController::class, 'testPayPalSSL']);
});

// Route pour les webhooks Stripe (sans middleware CSRF)
Route::post('/webhooks/stripe', [WebhookController::class, 'handleStripeWebhook'])->name('webhooks.stripe');
require __DIR__.'/auth.php';

