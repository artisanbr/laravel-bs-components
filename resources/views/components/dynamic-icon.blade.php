@props([
    'icon' => null,
    'component' => config('bootstrap-components.icon_component'),
])


@if($icon)

    @if(is_array($icon))
        @php

            if($icon['p'] ?? false){
                $icon['paths'] = $icon['p'];
            }

            if($icon['s'] ?? false){
                    $icon['size'] = $icon['s'];
            }

            if($icon['c'] ?? false){
                    $icon['color'] = $icon['c'];
            }

            $attributes = $attributes->merge($icon);

        @endphp

        <x-dynamic-component
                :component="$icon['component'] ?? $icon['c'] ?? $component" {{ $attributes }}/>

    @elseif(is_string($icon))
        <x-dynamic-component
                :component="$component" :name="$icon" :icon="$icon" {{ $attributes }}/>
    @else
        {{ $icon }}
    @endif

@endif

