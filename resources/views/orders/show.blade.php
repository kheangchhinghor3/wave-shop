@extends('layouts.app')

@section('title', 'Invoice ' . $order->order_number . ' | WAVE SHOP')

@section('content')
    <div class="invoice">

        {{-- Header --}}
        <!-- <div class="invoice-head">
            {{-- Replace this line with your image logo later --}}
            <div class="invoice-logo">WAVE <span>SHOP</span></div>

            <h1 class="invoice-title">Invoice</h1>
            <p class="invoice-number">{{ $order->order_number }}</p>
        </div> -->
        @include('partials.logo-img', ['class' => 'invoice-logo'])

        <hr class="invoice-line">

        {{-- Date and status --}}
        <div class="invoice-meta">
            <div class="invoice-field">
                <span class="invoice-label">Date</span>
                <span>{{ $order->created_at->format('d M Y, H:i') }}</span>
            </div>
            <div class="invoice-field">
                <span class="invoice-label">Status</span>
                <span class="status status-{{ $order->status }}">{{ ucfirst($order->status) }}</span>
            </div>
        </div>

        {{-- Delivery details --}}
        <h2 class="invoice-section">Delivery details</h2>

        <div class="invoice-info">
            <div class="invoice-field">
                <span class="invoice-label">Name</span>
                <span>{{ $order->shipping_name }}</span>
            </div>
            <div class="invoice-field">
                <span class="invoice-label">Phone</span>
                <span>{{ $order->shipping_phone }}</span>
            </div>
            <div class="invoice-field">
                <span class="invoice-label">Address</span>
                <span>{{ $order->shipping_address }}</span>
            </div>
            <div class="invoice-field">
                <span class="invoice-label">City</span>
                <span>{{ $order->shipping_city }}</span>
            </div>

            @if ($order->notes)
                <div class="invoice-field">
                    <span class="invoice-label">Notes</span>
                    <span>{{ $order->notes }}</span>
                </div>
            @endif
        </div>

        {{-- Items --}}
        <h2 class="invoice-section">Items</h2>

        @foreach ($order->items as $item)
            <div class="invoice-item">
                <div>
                    <div class="invoice-item-name">{{ $item->product_name }}</div>
                    <div class="invoice-item-sub">${{ number_format($item->price, 2) }} × {{ $item->quantity }}</div>
                </div>
                <div class="invoice-item-price">${{ number_format($item->price * $item->quantity, 2) }}</div>
            </div>
        @endforeach

        <div class="invoice-total">
            <span>Total</span>
            <span>${{ number_format($order->total, 2) }}</span>
        </div>

        <p class="invoice-note">Payment: pay on delivery. Thank you for riding the wave!</p>
    </div>

    <div class="invoice-actions">
        <a href="{{ route('products.index') }}" class="btn">Continue shopping</a>
        <button type="button" class="btn btn-blue" onclick="saveInvoice()">Save as PDF</button>
    </div>
    <p class="invoice-hint">In the window that opens, choose "Save as PDF" as the destination, then click Save.</p>

    <script>
        function saveInvoice() {
            const original = document.title;

            // The browser uses the page title as the default file name
            document.title = 'Invoice {{ $order->order_number }}';

            window.addEventListener('afterprint', function () {
                document.title = original;
            }, { once: true });

            window.print();
        }
    </script>
@endsection