@php
    $heading = $getHeading();
    $detail = $getDetail();

    $formattedHeading = $formatHeadingState($heading);
    $formattedDetail = $formatDetailState($detail);

    $showHeadingToolTip = $shouldShowHeadingToolTip($heading);
    $showDetailToolTip = $shouldShowDetailToolTip($detail);
@endphp

<div
    class="px-3 py-4 flex flex-col"
    @if($showHeadingToolTip || $showDetailToolTip)
        x-data="{}"
    @endif
>

    <span
        class="font-bold"
        @if($showHeadingToolTip)
            x-tooltip="{ content: @js($heading), theme: $store.theme }"
        @endif
    >
        {{ $formattedHeading }}
    </span>
    <span
        class="text-sm"
        @if($showDetailToolTip)
            x-tooltip="{ content: @js($detail), theme: $store.theme }"
        @endif
    >
        {{ $formattedDetail }}
    </span>
</div>
