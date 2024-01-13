@props([
    'select2Id' => 'select2_'.Random::alphanumericString(6),
    'label' => null,
    'placeholder' => null,
    'options' => [],
    'icon' => null,
    'prepend' => null,
    'append' => null,
    'size' => null,
    'help' => null,
    'model' => null,
    'value' => null,
    'lazy' => false,
    'vanilla' => false, //disable select2
    'dataUrl' => false,
    'disallowClear' => false,
])

@php
    if ($lazy) $bind = 'lazy';
    else $bind = 'defer';

    $wireModel = $attributes->whereStartsWith('wire:model')->first();
    $key = $attributes->get('name', $model ?? $wireModel);
    $id = $attributes->get('id', $model ?? $wireModel);
    $prefix = config('bootstrap-components.use_with_model_trait') ? 'model.' : null;
    $options = Arr::isAssoc($options) ? $options : array_combine($options, $options);
    $valueJSON = json_encode($value, JSON_OBJECT_AS_ARRAY);
    $optionsJSON = json_encode((array) $options, JSON_OBJECT_AS_ARRAY);

    $attributes = $attributes->class([
        'form-select',
        'form-select-' . $size => $size,
        $select2Id => !$vanilla,
        'rounded-end' => !$append,
        'is-invalid' => $errors->has($key),
    ])->merge([
        'id' => $id,
        //'data-pharaonic' => !$vanilla ? 'select2' : null,
        'data-component-id' => !$vanilla ? $select2Id : null,
        'wire:model.' . $bind => $model ? $prefix . $model : null,
    ]);
@endphp
@if(!$vanilla)
    @once
        @push('js')
            @script
            <script>
                function optionsToSelect2Data(options) {
                    return $.map(options, function (item, i) {
                        return {
                            id: i,
                            text: item,
                            select: i == {!! $valueJSON !!}
                        };
                    });
                }

                function select2Config(config) {

                    config = {
                        allowClear: {{ $disallowClear ? 'false' : 'true' }},
                        placeholder: '{{ $placeholder ?? 'Selecione...' }}',
                        escapeMarkup: function (markup) {
                            return markup;
                        },
                        data: optionsToSelect2Data({!! $optionsJSON !!}),
                        ...config,
                    }

                    @if($attributes['data-tags'] ?? false)

                        config = {
                        tags: true,
                        insertTag: function (data, tag) {
                            // Insert the tag at the end of the results
                            if (empty(data.length)) {
                                data.push(tag);
                            }

                        },
                        /*createTag: function (params, data) {
                            let term = $.trim(params.term);
                            let selected_values = $(".{{ $select2Id }}").val() ?? [];

                            if (term === '' || selected_values.map(v => v.toLowerCase()).includes(term.toLowerCase())) {
                                return null;
                            }
                            return {
                                id: term,
                                text: term,
                                selected: false,
                            }
                        },*/
                        createSearchChoice: function (term) {
                            return {id: term, text: term};
                        },
                        ...config,
                    };

                    @endif

                        @if($attributes['data-dropdown-parent'] ?? false)

                        config = {
                        dropdownParent: $('{{$attributes['data-dropdown-parent']}}'),
                        ...config,
                    };
                    @elseif(($dropdownParent ?? true))

                        config = {
                        dropdownParent: $(".{{ $select2Id }}").parent().parent(),
                        ...config,
                    };
                    @endif
                        @if($dataUrl)
                        config = {
                        ajax: {
                            url: '{{ $dataUrl }}',
                            data: function (params) {
                                var sendData = {
                                    //_token: $('meta[name="csrf-token"]').attr('content'),
                                    //selected_value: {!! json_encode($value) !!},
                                    search: params.term,
                                    selected_value: $(this).val(),
                                };
                                return sendData;
                            },
                            processResults: function (data) {
                                return {
                                    results: data
                                };
                            },
                            delay: 250,
                            dataType: 'json',
                            cache: {{ $attributes["data-cache"] ?? "true" }},
                            method: '{{ $dataMethod }}'
                        },
                        ...config,
                    };
                    @endif

                        return config;
                }
            </script>
            @endscript
        @endpush
    @endonce

    @push('js')
        @script
        <script>
            var Pharaonic;
            void 0 === Pharaonic && (Pharaonic = {});
            var PharaonicSelect2 = {
                Select2: {
                    init: function () {
                        $('.{{ $select2Id }}:not("[data-select2-id]")').each(function () {
                            Pharaonic.Select2.load($(this), $(this).is("[data-component-id]") ? $(this).data("component-id") : null)
                        })
                    }, load: function (a, e) {
                        var t, i, n, l, d;
                        e && (t = select2Config({
                            multiple: $(a).is("[multiple]"),
                            tags: $(a).is("[data-tags]"),
                            placeholder: $(a).is("[data-placeholder]") ? $(a).data("placeholder") : "Select an option",
                            language: $(a).is("[data-language]") ? $(a).data("language") : "en",
                            dir: $(a).is("[data-dir]") ? $(a).data("dir") : "ltr",
                            dropdownParent: $(a).is("[data-parent]") ? $($(a).data("parent")) : null,
                            minimumResultsForSearch: $(a).is("[data-search-off]") ? -1 : 0,
                            allowClear: $(a).is("[data-clear]")
                        }), n = (i = a[0].attributes).getNamedItem("wire:model") || i.getNamedItem("wire:model.defer") || null, i.getNamedItem("class") || $(a).attr("class", "form-select"), delete i, (a = $(a).select2(t)) && (l = n.value, d = 0 < n.name.search("defer"), a.on("change", function () {
                            @this.set(l, $(a).val(), d);
                        })))
                    }
                }
            };
            Object.assign(Pharaonic, PharaonicSelect2), $(document).ready(function () {
                Pharaonic.Select2.init()
            }), window.addEventListener("pharaonic.select2.init", () => {
                Pharaonic.Select2.init()
            }), window.addEventListener("pharaonic.select2.load", a => {
                Pharaonic.Select2.load($(a.detail.target), a.detail.component)
            });
        </script>

        <script>
            document.addEventListener('DOMContentLoaded', (event) => {

                /*$('.{{ $select2Id }}').on('change', function (e) {
                    var data = $('#select2').select2("val");
                    @this.set('selected', data);
                });*/

                //$(".{{ $select2Id }}").val({!! $valueJSON !!}).change();
            });
        </script>
        @endscript
    @endpush
@endif

<div wire:ignore>
    <x-bs::label :for="$id" :label="$label"/>

    <div class="input-group">
        <x-bs::input-addon :icon="$icon" :label="$prepend"/>

        <select {{ $attributes }}>
            <option value="">{{ $placeholder }}</option>

            @foreach($options as $optionValue => $optionLabel)
                <option value="{{ $optionValue }}">{{ $optionLabel }}</option>
            @endforeach
        </select>

        <x-bs::input-addon :label="$append" class="rounded-end"/>

        <x-bs::error :key="$key"/>
    </div>

    <x-bs::help :label="$help"/>
</div>
