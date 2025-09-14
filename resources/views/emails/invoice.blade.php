<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Invoice {{ $invoice->invoice_number }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .header {
            border-bottom: 2px solid #3498db;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }

        .invoice-title {
            color: #3498db;
            font-size: 28px;
            margin: 0;
        }

        .invoice-details {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }

        .customer-info, .invoice-info {
            flex: 1;
        }

        .invoice-info {
            text-align: right;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f8f9fa;
            font-weight: bold;
            color: #2c3e50;
        }

        .amount {
            text-align: right;
        }

        .total-row {
            background-color: #e8f4f8;
            font-weight: bold;
        }

        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            color: #666;
            font-size: 14px;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <h1 class="invoice-title">Payment Invoice</h1>
    </div>
    <div class="invoice-details">
        <div class="customer-info">
            <h3>Bill To:</h3>
            <p>
                <strong>{{ $invoice->customer_name }}</strong><br>
                {{ $invoice->customer_email }}
            </p>
        </div>
        <div class="invoice-info">
            <h3>Invoice Details:</h3>
            <p>
                <strong>Invoice #:</strong> {{ $invoice->invoice_number }}<br>
                <strong>Date:</strong> {{ $invoice->created_at->format('M d, Y') }}<br>
                <strong>Total Amount:</strong> ${{ number_format($invoice->amount, 2) }}
            </p>
        </div>
    </div>
    <table>
        <thead>
        <tr>
            <th>Payment Date</th>
            <th>Reference #</th>
            <th>Original Currency</th>
            <th class="amount">Original Amount</th>
            <th class="amount">USD Amount</th>
        </tr>
        </thead>
        <tbody>
        @foreach($payments as $payment)
            <tr>
                <td>{{ $payment->payment_date->format('M d, Y') }}</td>
                <td>{{ $payment->reference_no }}</td>
                <td>{{ $payment->currency }}</td>
                <td class="amount">{{ number_format($payment->amount, 2) }} {{ $payment->currency }}</td>
                <td class="amount">${{ number_format($payment->usd_amount, 2) }}</td>
            </tr>
        @endforeach
        <tr class="total-row">
            <td colspan="4"><strong>Total Amount (USD)</strong></td>
            <td class="amount"><strong>${{ number_format($invoice->amount, 2) }}</strong></td>
        </tr>
        </tbody>
    </table>
    <div class="footer">
        <p>Thank you for your business!</p>
    </div>
</div>
</body>
</html>
