@props([
    'icon' => null,
    'label' => null,
    'items' => null,
    'color' => 'primary',
    'activeColor' => null,
    'hoverTextColor' => null,
    'textColor' => null,
    'showTextMobile' => false,
    'size' => null,
])

@php

    if(!$showTextMobile){
            $label = "<span class=\"d-none d-md-inline\">{$label}</span>";
        }
        $attributes = $attributes->class([
            'btn btn-' . $color,
            'btn-' . $size => $size,
            'dropdown-toggle',
            'border-0 p-0' => (string) $color === 'link',
            'btn-active-' . $activeColor => $activeColor,
            'text-hover-' . $hoverTextColor => $hoverTextColor,
            'btn-text-' . $textColor => $textColor,
        ])->merge([
            'role' => 'button',
            'data-bs-toggle' => 'dropdown',
        ]);
@endphp

<div class="dropdown d-inline-block">
    <a {{ $attributes }}>
        {{--<x-bs::icon :name="$icon"/>--}}
        @if($icon)
            <x-bs::dynamic-icon :icon="['size' => 2, ...(is_array($icon) ? $icon : compact('icon'))]" @class([
    'pe-0 pe-md-1' => !$showTextMobile
    ]) />
        @endif

        {!! $label !!}
    </a>

    <div class="dropdown-menu">
        {{ $items ?? $slot }}
    </div>
</div>
