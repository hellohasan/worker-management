<div class="form-group {{ $col }}">
    <label for="{{ $name }}">{{ $label }}:</label>
    <div class="input-group image-preview">
        <input type="text" class="form-control image-preview-filename" disabled="disabled"> <!-- don't give a name === doesn't send on POST/GET -->
        <div class="input-group-append input-group-btn">
            <button type="button" class="btn btn-default image-preview-clear" style="display:none;">
                <i class="fas fa-times"></i> Clear
            </button>
            <div class="btn btn-default image-preview-input">
                <i class="fas fa-folder-open"></i>
                <span class="image-preview-input-title">Browse</span>
                <input type="file" accept="image/*" id="{{ $name }}" name="{{ $name }}" /> <!-- rename it -->
            </div>
        </div>
        @error($name)
            <span class="invalid-feedback" role="alert">{{ $message }}</span>
        @enderror
    </div>
</div>


@pushOnce('scripts')
    <script type="text/javascript" src="{{ asset('backend/js/form-group-photo.js') }}"></script>
@endpushOnce

@pushOnce('styles')
    <link rel="stylesheet" href="{{ asset('backend/css/form-group-photo.css') }}">
@endpushOnce
