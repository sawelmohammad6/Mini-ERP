<x-app-layout>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0" style="color: #1a1a2e;">Orders</h4>
        <a href="{{ route('orders.create') }}" class="btn btn-primary btn-sm">+ New Order</a>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="panel bg-white shadow-sm p-4">
        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>Order ID</th>
                        <th>Customer</th>
                        <th>Total</th>
                        <th>Discount</th>
                        <th>Final Price</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($orders as $order)
                        <tr>
                            <td class="fw-medium" style="color: #1a1a2e;">#{{ $order->id }}</td>
                            <td style="color: #5a6270;">{{ $order->customer->name }}</td>
                            <td style="color: #5a6270;">${{ number_format($order->total, 2) }}</td>
                            <td style="color: #5a6270;">${{ number_format($order->discount, 2) }}</td>
                            <td style="color: #1a1a2e; font-weight: 600;">${{ number_format($order->final_price, 2) }}</td>
                            <td style="color: #a4b0c2;">{{ $order->created_at->format('M d, Y') }}</td>
                            <td>
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
                            <td colspan="7" class="text-center text-muted py-4">No orders found.</td>
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
