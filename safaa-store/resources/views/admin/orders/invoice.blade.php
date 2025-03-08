<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Facture {{ $order->order_number }}</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            font-size: 14px;
            line-height: 1.6;
            color: #374151; /* text-gray-700 */
            background-color: #f9fafb; /* bg-gray-50 */
        }
        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 30px;
            background-color: #fff; /* bg-white */
            border-radius: 0.5rem; /* rounded-md */
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06); /* shadow-md */
        }
        .header {
            text-align: center;
            margin-bottom: 2rem;
        }
        .header h1 {
            font-size: 2.25rem; /* text-3xl */
            font-weight: 600; /* font-semibold */
            color: #111827; /* text-gray-900 */
            margin-bottom: 0.5rem;
        }
        .header p {
            color: #6b7280; /* text-gray-500 */
            margin-bottom: 0.25rem;
        }
        .info-section {
            margin-bottom: 1.5rem;
        }
        .info-section h2 {
            font-size: 1.25rem; /* text-lg */
            font-weight: 500; /* font-medium */
            color: #1f2937; /* text-gray-800 */
            border-bottom: 2px solid #e5e7eb; /* border-gray-200 */
            padding-bottom: 0.75rem;
            margin-bottom: 1rem;
        }
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.25rem;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 1.5rem;
        }
        table th, table td {
            border: 1px solid #d1d5db; /* border-gray-300 */
            padding: 0.75rem;
            text-align: left;
            color: #4b5563; /* text-gray-600 */
        }
        table th {
            background-color: #f9fafb; /* bg-gray-50 */
            font-weight: 500; /* font-medium */
            color: #374151; /* text-gray-700 */
        }
        .text-right {
            text-align: right;
        }
        .totals {
            width: 300px;
            margin-left: auto;
        }
        .totals table {
            margin-bottom: 0;
        }
        .totals table td {
            padding: 0.5rem 0.75rem;
        }
        .totals table tr:last-child td {
            font-size: 1.125rem; /* text-lg */
            font-weight: 600; /* font-semibold */
            color: #111827; /* text-gray-900 */
        }
        .footer {
            margin-top: 3rem;
            text-align: center;
            font-size: 0.875rem; /* text-sm */
            color: #9ca3af; /* text-gray-400 */
        }
        .logo {
            max-height: 50px;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <img src="URL_DE_VOTRE_LOGO" alt="Logo de la boutique" class="logo">
        <h1>FACTURE</h1>
        <p>Numéro de facture: {{ $order->order_number }}</p>
        <p>Date: {{ $order->created_at->format('d/m/Y') }}</p>
    </div>

    <div class="info-grid">
        <div class="info-section">
            <h2>Informations du vendeur</h2>
            <p>Safaa Store</p>
            <p>38 Rue du Commerce</p>
            <p>4600 Safi, Maroc</p>
            <p>Téléphone: 06 23 45 67 89</p>
            <p>Email: contact@safaa.com</p>
            <p>SIRET: 123 456 789 00012</p>
        </div>

        <div class="info-section">
            <h2>Informations du client</h2>
            <p>{{ $order->user->name }}</p>
            <p>{{ $order->user->email }}</p>
            <p>{{ $order->user->phone ?? 'Non spécifié' }}</p>
            <p>Adresse de facturation:</p>
            <p>{{ $order->billing_address }}</p>
        </div>
    </div>

    <div class="info-section">
        <h2>Détails de la commande</h2>
        <table>
            <thead>
            <tr>
                <th>Produit</th>
                <th>Prix unitaire</th>
                <th>Quantité</th>
                <th>Total</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($order->items as $item)
                <tr>
                    <td>
                        @if ($item->product)
                            {{ $item->product->name }}
                        @else
                            Produit supprimé
                        @endif
                    </td>
                    <td>{{ number_format($item->price, 2) }} €</td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ number_format($item->total, 2) }} €</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <div class="totals">
        <table>
            <tr>
                <td>Sous-total:</td>
                <td class="text-right">{{ number_format($order->total_amount - $order->shipping_cost - $order->tax, 2) }} €</td>
            </tr>
            <tr>
                <td>Frais de livraison:</td>
                <td class="text-right">{{ number_format($order->shipping_cost, 2) }} €</td>
            </tr>
            <tr>
                <td>TVA (20%):</td>
                <td class="text-right">{{ number_format($order->tax, 2) }} €</td>
            </tr>
            <tr>
                <td><strong>Total:</strong></td>
                <td class="text-right"><strong>{{ number_format($order->total_amount, 2) }} €</strong></td>
            </tr>
        </table>
    </div>

    <div class="info-section">
        <h2>Informations de paiement</h2>
        <p>Méthode de paiement: {{ $order->payment_method ?? 'Non spécifié' }}</p>
        <p>Statut du paiement: {{ ucfirst($order->payment_status) }}</p>
        @if ($order->payment_id)
            <p>ID de transaction: {{ $order->payment_id }}</p>
        @endif
    </div>

    <div class="info-section">
        <h2>Informations de livraison</h2>
        <p>Méthode de livraison: {{ $order->shipping_method ?? 'Standard' }}</p>
        <p>Adresse de livraison:</p>
        <p>{{ $order->shipping_address }}</p>
    </div>

    <div class="footer">
        <p>Merci pour votre achat! Pour toute question concernant cette facture, veuillez nous contacter à support@votreboutique.com</p>
        <p>Cette facture a été générée automatiquement et ne nécessite pas de signature.</p>
    </div>
</div>
</body>
</html>
