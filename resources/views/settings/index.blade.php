<x-app-layout>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0" style="color: #1a1a2e;">Business Settings</h4>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="panel bg-white shadow-sm p-4">
        <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
                <label for="business_name" class="form-label">Business Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('business_name') is-invalid @enderror"
                    id="business_name" name="business_name"
                    value="{{ old('business_name', $setting->business_name) }}" required>
                @error('business_name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="currency" class="form-label">Currency <span class="text-danger">*</span></label>
                <select class="form-select @error('currency') is-invalid @enderror"
                    id="currency" name="currency" required>
                    @foreach ($currencies as $cur)
                        <option value="{{ $cur }}" {{ old('currency', $setting->currency) == $cur ? 'selected' : '' }}>{{ $cur }}</option>
                    @endforeach
                </select>
                @error('currency')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Current Logo</label>
                <div>
                    @if ($setting->logo)
                        <img src="{{ Storage::url($setting->logo) }}"
                            alt="Logo" style="max-height: 80px; border-radius: 8px;">
                    @else
                        <div class="d-flex align-items-center justify-content-center bg-light"
                            style="width: 80px; height: 80px; border-radius: 8px;">
                            <i class="bi bi-image text-muted" style="font-size: 2rem;"></i>
                        </div>
                    @endif
                </div>
            </div>

            <div class="mb-3">
                <label for="logo" class="form-label">Upload Logo</label>
                <input type="file" class="form-control @error('logo') is-invalid @enderror"
                    id="logo" name="logo" accept="image/jpg,image/jpeg,image/png,image/webp">
                <div class="form-text">Recommended size: 200x60px. Max 2MB.</div>
                @error('logo')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex justify-content-between">
                <a href="{{ route('dashboard') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">Save Settings</button>
            </div>
        </form>
    </div>
</x-app-layout>
