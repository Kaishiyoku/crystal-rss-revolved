@props(['for'])

@error($for)
    <p {{ $attributes->merge(['class' => 'text-sm text-pink-600']) }}>{{ $message }}</p>
@enderror
