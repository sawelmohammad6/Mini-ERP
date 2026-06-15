<x-app-layout>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0" style="color: #1a1a2e;">Order Details — #{{ $order->id }}</h4>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-secondary btn-sm" onclick="window.print()">
                <i class="bi bi-printer me-1"></i>Print Invoice
            </button>
            <a href="{{ route('orders.edit', $order) }}" class="btn btn-outline-primary btn-sm">
                <i class="bi bi-pencil me-1"></i>Edit
            </a>
        </div>
    </div>

    @php $item = $order->items->first(); @endphp

    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="stat-card bg-white shadow-sm">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon" style="background: #e8f5e9; color: #2e7d32;">
                        <i class="bi bi-currency-dollar"></i>
                    </div>
                    <div>
                        <div class="stat-title">Final Price</div>
                        <div class="stat-value" style="font-size: 1.3rem;">{{ format_currency($order->final_price) }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card bg-white shadow-sm">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon" style="background: #fef2f2; color: #f46a6a;">
                        <i class="bi bi-tag-fill"></i>
                    </div>
                    <div>
                        <div class="stat-title">Discount</div>
                        <div class="stat-value" style="font-size: 1.3rem;">{{ format_currency($order->discount) }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card bg-white shadow-sm">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon" style="background: #f0fdf4; color: #34c38f;">
                        <i class="bi bi-check-circle-fill"></i>
                    </div>
                    <div>
                        <div class="stat-title">Status</div>
                        <div>
                            @php $status = $order->status->value ?? $order->status; @endphp
                            @if ($status == 'completed' || $status == 'delivered')
                                <span class="badge bg-success rounded-pill fs-6">{{ ucfirst($status) }}</span>
                            @elseif ($status == 'pending')
                                <span class="badge bg-warning text-dark rounded-pill fs-6">{{ ucfirst($status) }}</span>
                            @elseif ($status == 'cancelled')
                                <span class="badge bg-danger rounded-pill fs-6">{{ ucfirst($status) }}</span>
                            @else
                                <span class="badge bg-secondary rounded-pill fs-6">{{ ucfirst($status) }}</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card bg-white shadow-sm">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon" style="background: #fefce8; color: #f1b44c;">
                        <i class="bi bi-calendar-event-fill"></i>
                    </div>
                    <div>
                        <div class="stat-title">Date</div>
                        <div class="stat-value" style="font-size: 1rem; font-weight: 600;">{{ $order->created_at->format('M d, Y') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="panel bg-white shadow-sm p-4 mb-4" id="invoice">
        <div class="d-flex justify-content-between align-items-start mb-4">
            <div>
                @if ($setting && $setting->logo)
                    <img src="{{ Storage::url($setting->logo) }}" alt="Logo" style="max-height: 50px; margin-bottom: 8px;">
                @endif
                <h5 class="fw-bold mb-1" style="color: #1a1a2e;">{{ $setting->business_name ?? 'Mini ERP' }}</h5>
                <p class="text-muted mb-0" style="font-size: 0.85rem;">Business Management Dashboard</p>
            </div>
            <div class="text-end">
                <h6 class="fw-semibold mb-1" style="color: #556ee6;">INVOICE</h6>
                <p class="mb-0" style="color: #5a6270; font-size: 0.85rem;">#{{ $order->id }}</p>
                <p class="mb-0" style="color: #a4b0c2; font-size: 0.8rem;">{{ $order->created_at->format('M d, Y') }}</p>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-6">
                <p class="fw-semibold mb-1" style="font-size: 0.85rem; color: #1a1a2e;">Bill To:</p>
                <p class="mb-0" style="color: #5a6270; font-size: 0.85rem;">{{ $order->customer->name }}</p>
                <p class="mb-0" style="color: #5a6270; font-size: 0.85rem;">{{ $order->customer->phone }}</p>
                @if ($order->customer->email)
                    <p class="mb-0" style="color: #5a6270; font-size: 0.85rem;">{{ $order->customer->email }}</p>
                @endif
            </div>
        </div>

        <table class="table table-bordered align-middle">
            <thead class="table-dark">
                <tr>
                    <th>Product</th>
                    <th class="text-center" style="width: 80px;">Qty</th>
                    <th class="text-end" style="width: 120px;">Price</th>
                    <th class="text-end" style="width: 120px;">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($order->items as $item)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                @if ($item->product && $item->product->image)
                                    <img src="{{ Storage::url($item->product->image) }}"
                                        alt="{{ $item->product->name }}"
                                        style="width: 40px; height: 40px; object-fit: cover; border-radius: 4px;">
                                @endif
                                <span style="color: #1a1a2e;">{{ $item->product->name ?? 'Product' }}</span>
                            </div>
                        </td>
                        <td class="text-center">{{ $item->quantity }}</td>
                        <td class="text-end">{{ format_currency($item->price) }}</td>
                        <td class="text-end fw-medium">{{ format_currency($item->subtotal) }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" class="text-end fw-medium">Total</td>
                    <td class="text-end fw-bold" style="color: #1a1a2e;">{{ format_currency($order->total) }}</td>
                </tr>
                <tr>
                    <td colspan="3" class="text-end fw-medium">Discount</td>
                    <td class="text-end" style="color: #dc3545;">-{{ format_currency($order->discount) }}</td>
                </tr>
                <tr class="table-active">
                    <td colspan="3" class="text-end fw-semibold">Final Price</td>
                    <td class="text-end fw-bold" style="color: #198754; font-size: 1.1rem;">{{ format_currency($order->final_price) }}</td>
                </tr>
            </tfoot>
        </table>

        <div class="text-muted mt-3" style="font-size: 0.8rem; text-align: center;">
            Thank you for your business!
        </div>
    </div>

    <div class="d-flex gap-2">
        <a href="{{ route('orders.index') }}" class="btn btn-secondary">Back to Orders</a>
    </div>
</x-app-layout>

<style media="print">
    .sidebar, .topbar, .stat-card, .btn, .d-flex.gap-2 { display: none !important; }
    .panel { box-shadow: none !important; border: none !important; }
    body { background: #fff !important; }
    #invoice { padding: 0 !important; }
</style>
