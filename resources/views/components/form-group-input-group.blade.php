<div class="form-group {{ $col }}">
    <label for="{{ $name }}">{{ $label }}:</label>
    <div class="input-group">
        @if (!$append)
            <div class="input-group-append">
                <span class="input-group-text font-weight-bold" id="basic-addon2">{!! $getGroupText !!}</span>
            </div>
        @endif
        <input id="{{ $name }}" name="{{ $name }}" value="{{ $value }}" type="{{ $getType }}" @if ($step) step="{{ $step }}" @endif class="form-control @error($name) is-invalid @enderror" @if ($required) required @endif @if ($readonly) readonly @endif placeholder="{{ $label }}">
        @if ($append)
            <div class="input-group-append">
                <span class="input-group-text font-weight-bold" id="basic-addon2">{!! $getGroupText !!}</span>
            </div>
        @endif
    </div>
    @error($name)
        <span class="invalid-feedback" role="alert">{{ $message }}</span>
    @enderror
</div>
