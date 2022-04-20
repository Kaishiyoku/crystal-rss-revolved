@props(['for'])

@error($for)
    <p {{ $attributes->merge(['class' => 'text-sm text-warning-600']) }}>{{ $message }}</p>
@enderror
