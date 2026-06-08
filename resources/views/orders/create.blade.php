<x-app-layout>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0" style="color: #1a1a2e;">New Order</h4>
    </div>

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="panel bg-white shadow-sm p-4">
        <form action="{{ route('orders.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label for="customer_id" class="form-label">Customer <span class="text-danger">*</span></label>
                <select class="form-select @error('customer_id') is-invalid @enderror"
                    id="customer_id" name="customer_id" required>
                    <option value="">-- Select Customer --</option>
                    @foreach ($customers as $customer)
                        <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                            {{ $customer->name }} ({{ $customer->phone }})
                        </option>
                    @endforeach
                </select>
                @error('customer_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="product_id" class="form-label">Product <span class="text-danger">*</span></label>
                <select class="form-select @error('product_id') is-invalid @enderror"
                    id="product_id" name="product_id" required>
                    <option value="">-- Select Product --</option>
                    @foreach ($products as $product)
                        <option value="{{ $product->id }}"
                            data-price="{{ $product->price }}"
                            data-image="{{ $product->image ? Storage::url($product->image) : '' }}"
                            data-stock="{{ $product->stock_quantity }}"
                            {{ old('product_id') == $product->id ? 'selected' : '' }}>
                            {{ $product->name }} — ${{ number_format($product->price, 2) }}
                        </option>
                    @endforeach
                </select>
                @error('product_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="row mb-3">
                <div class="col-md-2">
                    <div id="productImagePreview"
                        class="d-flex align-items-center justify-content-center bg-light"
                        style="width: 100px; height: 100px; border-radius: 6px;">
                        <i class="bi bi-image text-muted" style="font-size: 2rem;"></i>
                    </div>
                </div>
                <div class="col-md-10">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="price" class="form-label">Price</label>
                                <input type="text" class="form-control" id="price" readonly
                                    value="{{ old('product_id') ? '$' . number_format($products->firstWhere('id', old('product_id'))?->price ?? 0, 2) : '' }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="stockDisplay" class="form-label">Available Stock</label>
                                <input type="text" class="form-control" id="stockDisplay" readonly
                                    value="{{ old('product_id') ? $products->firstWhere('id', old('product_id'))?->stock_quantity ?? '' : '' }}">
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="quantity" class="form-label">Quantity <span class="text-danger">*</span></label>
                        <input type="number" class="form-control @error('quantity') is-invalid @enderror"
                            id="quantity" name="quantity" value="{{ old('quantity', 1) }}" min="1" required>
                        @error('quantity')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="discount" class="form-label">Discount ($)</label>
                        <input type="number" class="form-control @error('discount') is-invalid @enderror"
                            id="discount" name="discount" value="{{ old('discount', 0) }}" min="0" step="0.01">
                        @error('discount')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="subtotal" class="form-label">Subtotal</label>
                        <input type="text" class="form-control" id="subtotal" readonly value="$0.00">
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-between">
                <a href="{{ route('orders.index') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">Save Order</button>
            </div>
        </form>
    </div>
</x-app-layout>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const productSelect = document.getElementById('product_id');
        const priceInput = document.getElementById('price');
        const stockDisplay = document.getElementById('stockDisplay');
        const qtyInput = document.getElementById('quantity');
        const discountInput = document.getElementById('discount');
        const subtotalInput = document.getElementById('subtotal');
        const imagePreview = document.getElementById('productImagePreview');

        function getSelected() {
            return productSelect.options[productSelect.selectedIndex];
        }

        function getAttr(name) {
            const selected = getSelected();
            return selected ? selected.getAttribute(name) || '' : '';
        }

        function getPrice() {
            const val = getAttr('data-price');
            return val ? parseFloat(val) : 0;
        }

        function getStock() {
            const val = getAttr('data-stock');
            return val ? parseInt(val) : 0;
        }

        function updateImage() {
            const src = getAttr('data-image');
            if (src) {
                imagePreview.innerHTML = '<img src="' + src + '" alt="Product" style="width:100px;height:100px;object-fit:cover;border-radius:6px;">';
            } else {
                imagePreview.innerHTML = '<i class="bi bi-image text-muted" style="font-size: 2rem;"></i>';
            }
        }

        function recalc() {
            const price = getPrice();
            const stock = getStock();
            const qty = parseInt(qtyInput.value) || 0;
            const discount = parseFloat(discountInput.value) || 0;

            priceInput.value = price ? '$' + price.toFixed(2) : '';
            stockDisplay.value = stock || '';

            const subtotal = price * qty;
            subtotalInput.value = '$' + subtotal.toFixed(2);

            updateImage();
        }

        productSelect.addEventListener('change', recalc);
        qtyInput.addEventListener('input', recalc);
        discountInput.addEventListener('input', recalc);

        recalc();
    });
</script>
@endpush
