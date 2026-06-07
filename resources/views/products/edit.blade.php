<x-app-layout>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0" style="color: #1a1a2e;">Edit Product: {{ $product->name }}</h4>
    </div>

    <div class="panel bg-white shadow-sm p-4">
        <form action="{{ route('products.update', $product) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="name" class="form-label">Product Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('name') is-invalid @enderror"
                    id="name" name="name" value="{{ old('name', $product->name) }}" required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="price" class="form-label">Price <span class="text-danger">*</span></label>
                <input type="number" step="0.01" min="0" class="form-control @error('price') is-invalid @enderror"
                    id="price" name="price" value="{{ old('price', $product->price) }}" required>
                @error('price')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Current Image</label>
                <div>
                    @if ($product->image)
                        <img src="{{ Storage::url($product->image) }}"
                            alt="{{ $product->name }}" style="width: 120px; height: 120px; object-fit: cover; border-radius: 6px;">
                    @else
                        <div class="d-flex align-items-center justify-content-center bg-light"
                            style="width: 120px; height: 120px; border-radius: 6px;">
                            <i class="bi bi-image text-muted" style="font-size: 2rem;"></i>
                        </div>
                    @endif
                </div>
            </div>

            <div class="mb-3">
                <label for="image" class="form-label">Replace Image</label>
                <input type="file" class="form-control @error('image') is-invalid @enderror"
                    id="image" name="image" accept="image/jpg,image/jpeg,image/png,image/webp">
                @error('image')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control @error('description') is-invalid @enderror"
                    id="description" name="description" rows="4">{{ old('description', $product->description) }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex justify-content-between">
                <a href="{{ route('products.index') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">Update Product</button>
            </div>
        </form>
    </div>
</x-app-layout>
