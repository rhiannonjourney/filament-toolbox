@php
    use Filament\Tables\Columns\IconColumn\IconColumnSize;

    $state = $getState();
@endphp

<div
    {{
        $attributes
            ->merge($getExtraAttributes(), escape: false)
            ->class('flex flex-wrap w-full px-3 py-4')
    }}
>
    @if (count($flags = $getFlags($state)))
        <x-filament::grid
            :default="$getColumns('default')"
            :sm="$getColumns('sm')"
            :md="$getColumns('md')"
            :lg="$getColumns('lg')"
            :xl="$getColumns('xl')"
            :two-xl="$getColumns('2xl')"
            class="gap-2"
        >
            @foreach ($flags as $flag)
                @php
                    $isActive = data_get($state, $flag->getName(), false);
                @endphp

                @if ($icon = $getIcon($flag, $isActive))
                    @php
                        $color = $getColor($flag, $isActive) ?? 'gray';
                        $size = $getSize($flag, $isActive) ?? IconColumnSize::Medium;
                        $tooltip = $getIndicatorTooltip($flag, $isActive);
                        $url = $getFlagUrl($flag, $isActive);
                        $tag = $url ? 'a' : 'span';
                    @endphp

                    <{{ $tag }}
                        @if($url)
                            href="{{ $url }}"
                            {{ $shouldOpenFlagUrlInNewTab($flag, $isActive) ? 'target="_blank"' : null }}

                            @class([
                                match ($color) {
                                    'gray' => 'fi-color-gray text-gray-700 dark:text-gray-200',
                                    default => 'fi-color-custom text-custom-600 hover:text-custom-500 dark:text-custom-400 hover:dark:text-custom-300',
                                },
                            ])
                            @style([
                                \Filament\Support\get_color_css_variables(
                                    $color,
                                    shades: [300, 400, 500, 600],
                                ) => $color !== 'gray',
                            ])
                        @else
                            @class([
                                match ($color) {
                                    'gray' => 'fi-color-gray text-gray-400 dark:text-gray-500',
                                    default => 'fi-color-custom text-custom-500 dark:text-custom-400',
                                },
                            ])
                            @style([
                                \Filament\Support\get_color_css_variables(
                                    $color,
                                    shades: [400, 500],
                                ) => $color !== 'gray',
                            ])
                        @endif
                    >
                        <x-filament::icon
                            :icon="$icon"
                            :x-tooltip="$tooltip ? '{ content: ' . \Illuminate\Support\Js::from($tooltip) . ', theme: $store.theme }' : null"
                            @class([
                                match ($size) {
                                    IconColumnSize::ExtraSmall, 'xs' => 'h-3 w-3',
                                    IconColumnSize::Small, 'sm' => 'h-4 w-4',
                                    IconColumnSize::Medium, 'md' => 'h-5 w-5',
                                    IconColumnSize::Large, 'lg' => 'h-6 w-6',
                                    IconColumnSize::ExtraLarge, 'xl' => 'h-7 w-7',
                                    default => $size,
                                },
                            ])
                        />
                    </{{  $tag }}>
                @endif
            @endforeach
        </x-filament::grid>
    @elseif (($placeholder = $getPlaceholder()) !== null)
        <x-filament-tables::columns.placeholder>
            {{ $placeholder }}
        </x-filament-tables::columns.placeholder>
    @endif
</div>
