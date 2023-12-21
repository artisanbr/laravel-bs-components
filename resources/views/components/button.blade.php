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
    'disabled' => false,
    'showTextMobile' => false,
    'indicator' => false,
    'indicatorEvent' => false,
    'submit' => false,
])

@php
    if ($route) $href = route($route);
    else if ($url) $href = url($url);

    $noText = empty($label) && (!$slot || empty((string) $slot)) ;

    $content = $label ?? (string) $slot ?? '';

    if(!$showTextMobile){
        $content = "<span class=\"d-none d-md-inline\">{$content}</span>";
    }

    if($dismiss && !is_string($dismiss)){
        $dismiss = 'modal';
    }

    $type = $submit ? 'submit' : $type;

    $attributes = $attributes->class([
        'btn',
        'btn-' . $color => $color,
        'btn-active-' . $activeColor => $activeColor,
        'text-hover-' . $hoverTextColor => $hoverTextColor,
        'btn-text-' . $textColor => $textColor,
        'btn-' . $size => $size,
        'btn-icon' => $icon && $noText,
        'disabled' => $disabled,
    ])->merge([
        'type' => !$href && $type !== 'a' ? $type : null,
        'href' => $href,
        'role' => !$href && $type === 'a' ? 'button' : null,
        'data-bs-dismiss' => $dismiss,
        'data-bs-toggle' => $toggle,
        'data-bs-trigger' => $trigger,
        'data-bs-target' => !$href ? $target : null,
        'target' => $href ? $target : null,
        'title' => $title,
        'wire:click' => $click,
        'onclick' => $confirm ? 'confirm(\'' . __('Tem certeza?') . '\') || event.stopImmediatePropagation()' : null,
    ]);

    $indicatorEvent = $indicatorEvent ? 'wire:target="'.$indicatorEvent.'"' : '';
@endphp
{{--@if($title && $toggle)
    <div title="{{ $title }}">
        @endif--}}

        <{{ $type === 'a' ? 'a' : 'button' }} {{ $attributes }}>

        @if($icon)
            <x-icon :icon="['size' => 2, ...(is_array($icon) ? $icon : compact('icon'))]" @class([
    'pe-0 pe-md-1' => !$showTextMobile && !$noText
    ]) />
        @endif

        @if($indicator)
            <span class="indicator-label" wire:loading.remove>{!! $content !!}</span>
            <span class="indicator-progress" wire:loading {{ $indicatorEvent }} >
                {{ is_string($indicator) ? $indicator : __('Aguarde...') }}
                <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
            </span>
        @else
            {!! $content !!}
        @endif
    </{{ $type === 'a' ? 'a' : 'button' }}>

{{--@if($title && $toggle)
        </div>
@endif--}}
