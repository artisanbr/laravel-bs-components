@props([
    'icon' => null,
    'label' => null,
    'route' => null,
    'url' => null,
    'href' => null,
    'click' => null,
    'type' => 'a',
    'dismiss' => null,
    'toggle' => null,
    'target' => null,
    'title' => null,
    'trigger' => null,
])

@php
    if ($route) $href = route($route);
    else if ($url) $href = url($url);

    $attributes = $attributes->class([
        'dropdown-item',
        'active' => $href == Request::url(),
    ])->merge([
        'type' => !$href && $type !== 'a' ? $type : null,
        'href' => $href,
        'role' => !$href && $type === 'a' ? 'button' : null,
        'data-bs-dismiss' => $dismiss,
        'data-bs-toggle' => $toggle,
        'data-bs-trigger' => $trigger,
        'data-bs-target' => $target,
        'title' => !$toggle ? $title : null,
        'wire:click' => $click,
    ]);
@endphp

<{{ $type === 'a' ? 'a' : 'button' }} {{ $attributes }}>
{{--<x-bs::icon :name="$icon"/>--}}

@if($icon)
    <x-bs::dynamic-icon :icon="['size' => 2, ...(is_array($icon) ? $icon : compact('icon'))]" @class([
    'pe-0 pe-md-1' => !$showTextMobile
    ]) />
@endif

{!! $label ?? $slot !!}
</{{ $type === 'a' ? 'a' : 'button' }}>
