@php
    $heading = $getHeading();
    $detail = $getDetail();

    $formattedHeading = $formatHeadingState($heading);
    $formattedDetail = $formatDetailState($detail);

    $showHeadingTooltip = $shouldShowHeadingTooltip($heading);
    $showDetailTooltip = $shouldShowDetailTooltip($detail);
@endphp

<div
    class="px-3 py-4 flex flex-col"
    @if($showHeadingTooltip || $showDetailTooltip)
        x-data="{}"
    @endif
>

    <span
        class="font-bold"
        @if($showHeadingTooltip)
            x-tooltip="{ content: @js($heading), theme: $store.theme }"
        @endif
    >
        {{ $formattedHeading }}
    </span>
    <span
        class="text-sm"
        @if($showDetailTooltip)
            x-tooltip="{ content: @js($detail), theme: $store.theme }"
        @endif
    >
        {{ $formattedDetail }}
    </span>
</div>
