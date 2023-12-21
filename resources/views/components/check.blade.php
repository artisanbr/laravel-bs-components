@props([
    'label' => null,
    'title' => null,
    'help' => null,
    'switch' => false,
    'model' => null,
    'lazy' => false,
    'solid' => false,
    'color' => 'primary',
])

@php
    if ($lazy) $bind = 'lazy';
    else $bind = 'defer';

    $wireModel = $attributes->whereStartsWith('wire:model')->first();
    $key = $attributes->get('name', $model ?? $wireModel);
    $id = $attributes->get('id', $model ?? $wireModel);
    $prefix = config('bootstrap-components.use_with_model_trait') ? 'model.' : null;

    $attributes = $attributes->class([
        'form-check-input',
        'is-invalid' => $errors->has($key),
    ])->merge([
        'type' => 'checkbox',
        'id' => $id,
        'wire:model.' . $bind => $model ? $prefix . $model : null,
    ]);
@endphp

<div>
    <x-bs::label :label="$title" />

    <div @class([
        "form-check form-check-{$color}",
        'form-check-solid' => $solid,
        'form-switch' => $switch,
        'form-check-custom' => !$switch,
        ])>
        <input {{ $attributes }}>

        <x-bs::check-label :for="$id" :label="$label"/>

        <x-bs::error :key="$key"/>

        <x-bs::help :label="$help"/>
    </div>
</div>
