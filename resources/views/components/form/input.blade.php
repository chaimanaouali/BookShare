@props([
    'name',
    'label',
    'type' => 'text',
    'required' => false,
    'placeholder' => '',
    'value' => '',
    'minLength' => null,
    'maxLength' => null,
    'pattern' => null,
    'help' => null,
    'icon' => null
])

<div class="mb-3">
    <label for="{{ $name }}" class="form-label">
        {{ $label }}
        @if($required)
            <span class="text-danger">*</span>
        @endif
    </label>
    
    <div class="input-group">
        @if($icon)
            <span class="input-group-text">
                <i class="{{ $icon }}"></i>
            </span>
        @endif
        
        <input 
            type="{{ $type }}" 
            class="form-control @error($name) is-invalid @enderror" 
            id="{{ $name }}" 
            name="{{ $name }}" 
            value="{{ old($name, $value) }}" 
            placeholder="{{ $placeholder }}"
            @if($required) required @endif
            @if($minLength) data-min-length="{{ $minLength }}" @endif
            @if($maxLength) data-max-length="{{ $maxLength }}" @endif
            @if($pattern) pattern="{{ $pattern }}" @endif
            {{ $attributes }}
        >
        
        @error($name)
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    
    @if($help)
        <div class="form-text">{{ $help }}</div>
    @endif
</div>
