<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Order Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-semibold">Order #{{ $order->order_number }}</h3>
                        <div>
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                @if ($order->status == 'pending') bg-yellow-100 text-yellow-800
                                @elseif ($order->status == 'processing') bg-blue-100 text-blue-800
                                @elseif ($order->status == 'shipped') bg-purple-100 text-purple-800
                                @elseif ($order->status == 'delivered') bg-green-100 text-green-800
                                @elseif ($order->status == 'cancelled') bg-red-100 text-red-800
                                @endif">
                                {{ ucfirst($order->status) }}
                            </span>
                            <span class="ml-2 px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                @if ($order->payment_status == 'pending') bg-yellow-100 text-yellow-800
                                @elseif ($order->payment_status == 'paid') bg-green-100 text-green-800
                                @elseif ($order->payment_status == 'failed') bg-red-100 text-red-800
                                @elseif ($order->payment_status == 'refunded') bg-gray-100 text-gray-800
                                @endif">
                                Payment: {{ ucfirst($order->payment_status) }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h4 class="font-semibold mb-2">Order Information</h4>
                            <p class="text-sm text-gray-600">Date: {{ $order->created_at->format('M d, Y H:i') }}</p>
                            <p class="text-sm text-gray-600">Payment Method: {{ ucfirst($order->payment_method) }}</p>
                            @if ($order->payment_id)
                                <p class="text-sm text-gray-600">Payment ID: {{ $order->payment_id }}</p>
                            @endif
                        </div>
                        
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h4 class="font-semibold mb-2">Customer Information</h4>
                            <p class="text-sm text-gray-600">Name: {{ $order->user->name }}</p>
                            <p class="text-sm text-gray-600">Email: {{ $order->user->email }}</p>
                            @if ($order->user->phone)
                                <p class="text-sm text-gray-600">Phone: {{ $order->user->phone }}</p>
                            @endif
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h4 class="font-semibold mb-2">Shipping Address</h4>
                            <p class="text-sm text-gray-600">{{ $order->shipping_address }}</p>
                            @if ($order->shipping_method)
                                <p class="text-sm text-gray-600 mt-2">Shipping Method: {{ $order->shipping_method }}</p>
                            @endif
                        </div>
                        
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h4 class="font-semibold mb-2">Billing Address</h4>
                            <p class="text-sm text-gray-600">{{ $order->billing_address }}</p>
                        </div>
                    </div>
                    
                    <div class="mb-6">
                        <h4 class="font-semibold mb-4">Order Items</h4>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Product
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Price
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Quantity
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Total
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($order->items as $item)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="h-10 w-10 flex-shrink-0">
                                                        @if ($item->product && $item->product->image)
                                                            <img src="{{ asset('storage/' . $item->product->image) }}" alt="{{ $item->product->name }}" class="h-10 w-10 object-cover rounded-full">
                                                        @else
                                                            <img src="https://via.placeholder.com/40" alt="Product" class="h-10 w-10 object-cover rounded-full">
                                                        @endif
                                                    </div>
                                                    <div class="ml-4">
                                                        <div class="text-sm font-medium text-gray-900">
                                                            {{ $item->product ? $item->product->name : 'Product no longer available' }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">${{ $item->price }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">{{ $item->quantity }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">${{ $item->total }}</div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <div class="flex justify-end">
                        <div class="w-full md:w-1/3">
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <div class="flex justify-between mb-2">
                                    <span class="text-sm text-gray-600">Subtotal</span>
                                    <span class="text-sm text-gray-900">${{ $order->total_amount - $order->shipping_cost - $order->tax }}</span>
                                </div>
                                <div class="flex justify-between mb-2">
                                    <span class="text-sm text-gray-600">Shipping</span>
                                    <span class="text-sm text-gray-900">${{ $order->shipping_cost }}</span>
                                </div>
                                <div class="flex justify-between mb-2">
                                    <span class="text-sm text-gray-600">Tax</span>
                                    <span class="text-sm text-gray-900">${{ $order->tax }}</span>
                                </div>
                                <div class="flex justify-between font-semibold">
                                    <span>Total</span>
                                    <span>${{ $order->total_amount }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-6 flex justify-between">
                        <a href="{{ route('orders.index') }}" class="text-indigo-600 hover:text-indigo-900">
                            &larr; Back to Orders
                        </a>
                        <a href="{{ route('orders.invoice', $order->id) }}" class="bg-indigo-600 text-white py-2 px-4 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                            Download Invoice
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>