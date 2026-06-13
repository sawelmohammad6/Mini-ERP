<x-app-layout>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0" style="color: #1a1a2e;">Product Management</h4>
        <a href="{{ route('products.create') }}" class="btn btn-primary btn-sm">+ Add Product</a>
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
                        <th>Image</th>
                        <th>ID</th>
                        <th>Product Name</th>
                        <th>Price</th>
                        <th>Stock</th>
                        <th>Description</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($products as $product)
                        <tr>
                            <td>
                                @if ($product->image)
                                    <img src="{{ Storage::url($product->image) }}"
                                        alt="{{ $product->name }}"
                                        style="width: 60px; height: 60px; object-fit: cover; border-radius: 4px;">
                                @else
                                    <div class="d-flex align-items-center justify-content-center bg-light"
                                        style="width: 60px; height: 60px; border-radius: 4px;">
                                        <i class="bi bi-image text-muted" style="font-size: 1.5rem;"></i>
                                    </div>
                                @endif
                            </td>
                            <td>{{ $product->id }}</td>
                            <td class="fw-medium" style="color: #1a1a2e;">{{ $product->name }}</td>
                            <td style="color: #5a6270;">{{ format_currency($product->price) }}</td>
                            <td>
                                {{ $product->stock_quantity }}
                                @if($product->stock_quantity <= $product->low_stock_alert)
                                    <span class="badge bg-danger ms-1">Low Stock</span>
                                @endif
                            </td>
                            <td style="color: #5a6270;">{{ Str::limit($product->description, 50) ?? '—' }}</td>
                            <td>
                                <a href="{{ route('products.edit', $product) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                <form action="{{ route('products.destroy', $product) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger"
                                        onclick="return confirm('Delete this product?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">No products found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $products->links() }}
        </div>
    </div>
</x-app-layout>
