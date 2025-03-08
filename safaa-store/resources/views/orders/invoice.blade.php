<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #{{ $order->order_number }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            color: #333;
        }
        .container {
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .invoice-details {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }
        .invoice-details-left, .invoice-details-right {
            width: 48%;
        }
        .invoice-details h3 {
            margin-top: 0;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
        }
        .totals {
            width: 300px;
            margin-left: auto;
        }
        .totals table {
            margin-bottom: 0;
        }
        .totals th {
            text-align: right;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            color: #777;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>INVOICE</h1>
            <p>{{ config('app.name') }}</p>
        </div>
        
        <div class="invoice-details">
            <div class="invoice-details-left">
                <h3>Bill To</h3>
                <p>{{ $order->user->name }}<br>
                {{ $order->billing_address }}</p>
                <p>Email: {{ $order->user->email }}</p>
                @if ($order->user->phone)
                    <p>Phone: {{ $order->user->phone }}</p>
                @endif
            </div>
            <div class="invoice-details-right">
                <h3>Invoice Details</h3>
                <p><strong>Invoice Number:</strong> INV-{{ $order->order_number }}</p>
                <p><strong>Order Number:</strong> {{ $order->order_number }}</p>
                <p><strong>Date:</strong> {{ $order->created_at->format('M d, Y') }}</p>
                <p><strong>Payment Method:</strong> {{ ucfirst($order->payment_method) }}</p>
                <p><strong>Status:</strong> {{ ucfirst($order->payment_status) }}</p>
            </div>
        </div>
        
        <table>
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($order->items as $item)
                    <tr>
                        <td>{{ $item->product ? $item->product->name : 'Product no longer available' }}</td>
                        <td>${{ $item->price }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>${{ $item->total }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        
        <div class="totals">
            <table>
                <tr>
                    <th>Subtotal:</th>
                    <td>${{ $order->total_amount - $order->shipping_cost - $order->tax }}</td>
                </tr>
                <tr>
                    <th>Shipping:</th>
                    <td>${{ $order->shipping_cost }}</td>
                </tr>
                <tr>
                    <th>Tax:</th>
                    <td>${{ $order->tax }}</td>
                </tr>
                <tr>
                    <th>Total:</th>
                    <td><strong>${{ $order->total_amount }}</strong></td>
                </tr>
            </table>
        </div>
        
        <div class="footer">
            <p>Thank you for your business!</p>
            <p>{{ config('app.name') }} &copy; {{ date('Y') }}</p>
        </div>
    </div>
</body>
</html>