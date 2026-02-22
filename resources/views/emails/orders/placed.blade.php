<!DOCTYPE html>
{{-- Set text direction and language based on the order's stored locale --}}
<html lang="{{ $order->locale }}" dir="{{ $order->locale == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <style>
        body { font-family: sans-serif; background-color: #f6f6f6; padding: 20px; }
        .container { background-color: #ffffff; max-width: 600px; margin: 0 auto; padding: 20px; border-radius: 8px; }
        .header { text-align: center; border-bottom: 2px solid #eee; padding-bottom: 10px; margin-bottom: 20px; }
        .items-table { width: 100%; border-collapse: collapse; }

        /* Dynamic text alignment based on locale */
        .items-table th, .items-table td {
            padding: 10px;
            border-bottom: 1px solid #eee;
            text-align: {{ $order->locale == 'ar' ? 'right' : 'left' }};
        }
        .total { font-size: 18px; font-weight: bold; margin-top: 10px; color: #2563eb; }
        .footer { text-align: center; font-size: 12px; color: #888; margin-top: 30px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            {{-- Localization via translation keys --}}
            <h2>{{ __('emails.welcome', ['name' => $order->customer_name]) }}</h2>
            <p>{{ __('emails.thank_you_msg') }}</p>
        </div>

        <div class="details" style="text-align: {{ $order->locale == 'ar' ? 'right' : 'left' }}">
            <p><strong>{{ __('emails.order_number') }}:</strong> {{ $order->number }}</p>
            <p><strong>{{ __('emails.order_date') }}:</strong> {{ $order->created_at->format('Y-m-d') }}</p>
            <p><strong>{{ __('emails.address') }}:</strong> {{ $order->customer_city }} - {{ $order->customer_address }}</p>
        </div>

        <h3 style="text-align: {{ $order->locale == 'ar' ? 'right' : 'left' }}">{{ __('emails.order_summary') }}:</h3>

        <table class="items-table">
            <thead>
                <tr>
                    <th>{{ __('emails.product') }}</th>
                    <th>{{ __('emails.quantity') }}</th>
                    <th>{{ __('emails.price') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                    <tr>
                        {{-- Note: Product name is pre-saved in order_items in the correct language at the time of purchase --}}
                        <td>{{ $item->product_name }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>${{ $item->price }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="total" style="text-align: {{ $order->locale == 'ar' ? 'right' : 'left' }}">
            {{ __('emails.total') }}: ${{ $order->total_price }}
        </div>

        <div class="footer">
            <p>{{ __('emails.footer_msg') }}</p>
        </div>
    </div>
</body>
</html>
