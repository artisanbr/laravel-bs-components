@props([
    'icon' => null,
    'label' => null,
    'color' => config('bootstrap-components.defaults_colors.button'),
    'activeColor' => null,
    'hoverTextColor' => null,
    'textColor' => null,
    'size' => null,
    'type' => 'a',
    'route' => null,
    'url' => null,
    'href' => null,
    'dismiss' => null,
    'toggle' => null,
    'target' => null,
    'title' => null,
    'trigger' => null,
    'click' => null,
    'confirm' => false,
    'showTextMobile' => false,
    'indicator' => false,
])

@php
    if ($route) $href = route($route);
    else if ($url) $href = url($url);

    $noText = empty($label) && (!$slot || empty((string) $slot)) ;

    $content = $label ?? (string) $slot ?? '';

    if(!$showTextMobile){
        $content = "<span class=\"d-none d-md-inline\">{$content}</span>";
    }

    $attributes = $attributes->class([
        'btn',
        'btn-' . $color => $color,
        'btn-active-' . $activeColor => $activeColor,
        'text-hover-' . $hoverTextColor => $hoverTextColor,
        'btn-text-' . $textColor => $textColor,
        'btn-' . $size => $size,
        'btn-icon' => $icon && $noText,
    ])->merge([
        'type' => !$href && $type !== 'a' ? $type : null,
        'href' => $href,
        'role' => !$href && $type === 'a' ? 'button' : null,
        'data-bs-dismiss' => $dismiss,
        'data-bs-toggle' => $toggle,
        'data-bs-trigger' => $trigger,
        'data-bs-target' => !$href ? $target : null,
        'target' => $href ? $target : null,
        'title' => !$toggle ? $title : null,
        'wire:click' => $click,
        'onclick' => $confirm ? 'confirm(\'' . __('Tem certeza?') . '\') || event.stopImmediatePropagation()' : null,
    ]);
@endphp
@if($title && $toggle)
    <div title="{{ $title }}">
        @endif

        <{{ $type === 'a' ? 'a' : 'button' }} {{ $attributes }}>

        @if($icon)
            <x-bs::dynamic-icon :icon="['size' => 2, ...(is_array($icon) ? $icon : compact('icon'))]" @class([
    'pe-0 pe-md-1' => !$showTextMobile && !$noText
    ]) />
        @endif

        @if($indicator)
            <span class="indicator-label">{!! $content !!}</span>
            <span class="indicator-progress">
            {{ is_string($indicator) ? $indicator : __('Aguarde...') }}
            <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
        </span>
        @else
            {!! $content !!}
        @endif
    </{{ $type === 'a' ? 'a' : 'button' }}>

    @if($title && $toggle)
        </div>
@endif