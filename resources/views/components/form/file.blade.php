@props([
    'name',
    'label',
    'required' => false,
    'accept' => null,
    'maxSize' => null,
    'allowedTypes' => null,
    'help' => null
])

<div class="mb-3">
    <label for="{{ $name }}" class="form-label">
        {{ $label }}
        @if($required)
            <span class="text-danger">*</span>
        @endif
    </label>
    
    <input 
        type="file" 
        class="form-control @error($name) is-invalid @enderror" 
        id="{{ $name }}" 
        name="{{ $name }}"
        @if($required) required @endif
        @if($accept) accept="{{ $accept }}" @endif
        @if($maxSize) data-max-size="{{ $maxSize }}" @endif
        @if($allowedTypes) data-allowed-types="{{ $allowedTypes }}" @endif
        {{ $attributes }}
    >
    
    @error($name)
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
    
    @if($help)
        <div class="form-text">{{ $help }}</div>
    @endif
</div>
