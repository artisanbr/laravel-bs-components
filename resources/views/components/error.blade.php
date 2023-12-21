@props([
    'key' => null,
])

@php
    $attributes = $attributes->class([
        'invalid-feedback',
    ])->merge([
        //
    ]);
@endphp

@error($key)
    <div {{ $attributes }}>
        {{ __($message) }}
    </div>
@enderror
