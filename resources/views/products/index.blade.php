<x-app-layout>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0" style="color: #1a1a2e;">Product Management</h4>
        <a href="{{ route('products.create') }}" class="btn btn-primary btn-sm">+ Add Product</a>
    </div>

    {{-- Stat Cards --}}
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="stat-card bg-white shadow-sm">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon" style="background: #e8f5e9; color: #2e7d32;">
                        <i class="bi bi-box-seam-fill"></i>
                    </div>
                    <div>
                        <div class="stat-title">Total Products</div>
                        <div class="stat-value" style="font-size: 1.5rem;">{{ $totalProducts }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card bg-white shadow-sm">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon" style="background: #fef2f2; color: #ef4444;">
                        <i class="bi bi-exclamation-triangle-fill"></i>
                    </div>
                    <div>
                        <div class="stat-title">Low Stock</div>
                        <div class="stat-value" style="font-size: 1.5rem;">{{ $lowStockCount }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card bg-white shadow-sm">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon" style="background: #fce4ec; color: #c62828;">
                        <i class="bi bi-x-circle-fill"></i>
                    </div>
                    <div>
                        <div class="stat-title">Out of Stock</div>
                        <div class="stat-value" style="font-size: 1.5rem;">{{ $outOfStockCount }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Search --}}
    <div class="panel bg-white shadow-sm p-3 mb-4">
        <form method="GET" action="{{ route('products.index') }}" class="row g-2 align-items-end">
            <div class="col-md-8">
                <input type="text" class="form-control" name="search" placeholder="Search by name or price..."
                    value="{{ request('search') }}">
            </div>
            <div class="col-md-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary flex-fill">Search</button>
                <a href="{{ route('products.index') }}" class="btn btn-outline-secondary flex-fill">Reset</a>
            </div>
        </form>
    </div>

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
                        <tr class="{{ $product->stock_quantity <= $product->low_stock_alert ? 'table-danger' : '' }}">
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
                                @if($product->stock_quantity == 0)
                                    <span class="badge bg-danger ms-1">Out</span>
                                @elseif($product->stock_quantity <= $product->low_stock_alert)
                                    <span class="badge bg-warning text-dark ms-1">Low</span>
                                @endif
                            </td>
                            <td style="color: #5a6270; max-width: 200px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                {{ $product->description ?: '—' }}
                            </td>
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
