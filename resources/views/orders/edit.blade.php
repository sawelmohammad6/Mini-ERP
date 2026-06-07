<x-app-layout>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0" style="color: #1a1a2e;">Edit Order #{{ $order->id }}</h4>
    </div>

    <div class="panel bg-white shadow-sm p-4">
        <form action="{{ route('orders.update', $order) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="customer_id" class="form-label">Customer <span class="text-danger">*</span></label>
                <select class="form-select @error('customer_id') is-invalid @enderror"
                    id="customer_id" name="customer_id" required>
                    <option value="">-- Select Customer --</option>
                    @foreach ($customers as $customer)
                        <option value="{{ $customer->id }}"
                            {{ old('customer_id', $order->customer_id) == $customer->id ? 'selected' : '' }}>
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
                        <option value="{{ $product->id }}" data-price="{{ $product->price }}"
                            {{ old('product_id', $order->items->first()->product_id ?? '') == $product->id ? 'selected' : '' }}>
                            {{ $product->name }} — ${{ number_format($product->price, 2) }}
                        </option>
                    @endforeach
                </select>
                @error('product_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="price" class="form-label">Price</label>
                <input type="text" class="form-control" id="price" readonly
                    value="${{ number_format($order->items->first()->price ?? 0, 2) }}">
            </div>

            <div class="mb-3">
                <label for="quantity" class="form-label">Quantity <span class="text-danger">*</span></label>
                <input type="number" class="form-control @error('quantity') is-invalid @enderror"
                    id="quantity" name="quantity"
                    value="{{ old('quantity', $order->items->first()->quantity ?? 1) }}" min="1" required>
                @error('quantity')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="discount" class="form-label">Discount ($)</label>
                <input type="number" class="form-control @error('discount') is-invalid @enderror"
                    id="discount" name="discount" value="{{ old('discount', $order->discount) }}" min="0" step="0.01">
                @error('discount')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="subtotal" class="form-label">Subtotal</label>
                <input type="text" class="form-control" id="subtotal" readonly
                    value="${{ number_format(($order->items->first()->price ?? 0) * ($order->items->first()->quantity ?? 0), 2) }}">
            </div>

            <div class="d-flex justify-content-between">
                <a href="{{ route('orders.index') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">Update Order</button>
            </div>
        </form>
    </div>
</x-app-layout>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const productSelect = document.getElementById('product_id');
        const priceInput = document.getElementById('price');
        const qtyInput = document.getElementById('quantity');
        const discountInput = document.getElementById('discount');
        const subtotalInput = document.getElementById('subtotal');

        function getPrice() {
            const selected = productSelect.options[productSelect.selectedIndex];
            const price = selected ? selected.getAttribute('data-price') : '';
            return price ? parseFloat(price) : 0;
        }

        function recalc() {
            const price = getPrice();
            const qty = parseInt(qtyInput.value) || 0;
            const discount = parseFloat(discountInput.value) || 0;

            priceInput.value = price ? '$' + price.toFixed(2) : '';

            const subtotal = price * qty;
            subtotalInput.value = '$' + subtotal.toFixed(2);
        }

        productSelect.addEventListener('change', recalc);
        qtyInput.addEventListener('input', recalc);
        discountInput.addEventListener('input', recalc);

        recalc();
    });
</script>
@endpush
