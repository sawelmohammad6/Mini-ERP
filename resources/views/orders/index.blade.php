<x-app-layout>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0" style="color: #1a1a2e;">Orders</h4>
        <a href="{{ route('orders.create') }}" class="btn btn-primary btn-sm">+ New Order</a>
    </div>

    {{-- Search --}}
    <div class="panel bg-white shadow-sm p-3 mb-4">
        <form method="GET" action="{{ route('orders.index') }}" class="row g-2 align-items-end">
            <div class="col-md-8">
                <input type="text" class="form-control" name="search" placeholder="Search by order ID or customer name..."
                    value="{{ request('search') }}">
            </div>
            <div class="col-md-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary flex-fill">Search</button>
                <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary flex-fill">Reset</a>
            </div>
        </form>
    </div>

    <div class="panel bg-white shadow-sm p-4">
        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>Order ID</th>
                        <th>Product</th>
                        <th>Customer</th>
                        <th>Total</th>
                        <th>Discount</th>
                        <th>Final Price</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($orders as $order)
                        @php $item = $order->items->first(); @endphp
                        <tr>
                            <td class="fw-medium" style="color: #1a1a2e;">#{{ $order->id }}</td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    @if ($item && $item->product && $item->product->image)
                                        <img src="{{ Storage::url($item->product->image) }}"
                                            alt="{{ $item->product->name }}"
                                            style="width: 40px; height: 40px; object-fit: cover; border-radius: 4px;">
                                    @else
                                        <div class="d-flex align-items-center justify-content-center bg-light"
                                            style="width: 40px; height: 40px; border-radius: 4px;">
                                            <i class="bi bi-image text-muted" style="font-size: 1rem;"></i>
                                        </div>
                                    @endif
                                    <span style="color: #1a1a2e;">{{ $item->product->name ?? '—' }}</span>
                                </div>
                            </td>
                            <td style="color: #5a6270;">{{ $order->customer->name }}</td>
                            <td style="color: #5a6270;">{{ format_currency($order->total) }}</td>
                            <td style="color: #5a6270;">{{ format_currency($order->discount) }}</td>
                            <td style="color: #1a1a2e; font-weight: 600;">{{ format_currency($order->final_price) }}</td>
                            <td>
                                @php $status = $order->status->value ?? $order->status; @endphp
                                @if ($status == 'completed' || $status == 'delivered')
                                    <span class="badge bg-success rounded-pill">{{ ucfirst($status) }}</span>
                                @elseif ($status == 'pending')
                                    <span class="badge bg-warning text-dark rounded-pill">{{ ucfirst($status) }}</span>
                                @elseif ($status == 'cancelled')
                                    <span class="badge bg-danger rounded-pill">{{ ucfirst($status) }}</span>
                                @else
                                    <span class="badge bg-secondary rounded-pill">{{ ucfirst($status) }}</span>
                                @endif
                            </td>
                            <td style="color: #a4b0c2;">{{ $order->created_at->format('M d, Y') }}</td>
                            <td>
                                <a href="{{ route('orders.show', $order) }}" class="btn btn-sm btn-info me-1 text-white">View</a>
                                <a href="{{ route('orders.edit', $order) }}" class="btn btn-sm btn-warning me-1">Edit</a>
                                <form action="{{ route('orders.destroy', $order) }}" method="POST" class="d-inline"
                                    onsubmit="return confirm('Delete order #{{ $order->id }}? This cannot be undone.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted py-4">No orders found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $orders->links() }}
        </div>
    </div>
</x-app-layout>
