@props([
    'name',
    'label',
    'required' => false,
    'placeholder' => '',
    'value' => '',
    'rows' => 4,
    'minLength' => null,
    'maxLength' => null,
    'help' => null
])

<div class="mb-3">
    <label for="{{ $name }}" class="form-label">
        {{ $label }}
        @if($required)
            <span class="text-danger">*</span>
        @endif
    </label>
    
    <textarea 
        class="form-control @error($name) is-invalid @enderror" 
        id="{{ $name }}" 
        name="{{ $name }}" 
        rows="{{ $rows }}"
        placeholder="{{ $placeholder }}"
        @if($required) required @endif
        @if($minLength) data-min-length="{{ $minLength }}" @endif
        @if($maxLength) data-max-length="{{ $maxLength }}" @endif
        {{ $attributes }}
    >{{ old($name, $value) }}</textarea>
    
    @error($name)
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
    
    @if($help)
        <div class="form-text">{{ $help }}</div>
    @endif
</div>
