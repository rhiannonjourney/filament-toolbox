@php
    $heading = $getHeading();
    $detail = $getDetail();

    $formattedHeading = $formatHeadingState($heading);
    $formattedDetail = $formatDetailState($detail);

    $showHeadingTooltip = $shouldShowHeadingTooltip($heading);
    $showDetailTooltip = $shouldShowDetailTooltip($detail);

    $headingIsCopyable = $isHeadingCopyable($heading);
    $headingCopyableState = $getHeadingCopyableState($heading) ?? $heading;
    $headingCopyMessage = $getHeadingCopyMessage($heading);
    $headingCopyMessageDuration = $getHeadingCopyMessageDuration($heading);

    $detailIsCopyable = $isDetailCopyable($detail);
    $detailCopyableState = $getDetailCopyableState($detail) ?? $detail;
    $detailCopyMessage = $getDetailCopyMessage($detail);
    $detailCopyMessageDuration = $getDetailCopyMessageDuration($detail);
@endphp

<div
    class="px-3 py-4 flex flex-col"
    @if($showHeadingTooltip || $showDetailTooltip)
        x-data="{}"
    @endif
>

    <span
        class="flex items-center space-x-1 group"
        @if($headingIsCopyable)
            x-on:click="
                window.navigator.clipboard.writeText(@js($headingCopyableState))
                $tooltip(@js($headingCopyMessage), {
                    theme: $store.theme,
                    timeout: @js($headingCopyMessageDuration),
                })
            "
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

        @if($headingIsCopyable)
            <span class="h-4 w-4 hidden group-hover:block cursor-pointer">
                <x-filament::icon
                    icon="heroicon-o-clipboard"
                    size="sm"
                />
            </span>
        @endif
    </span>

    <span
        class="flex items-center space-x-1 group"
        @if($detailIsCopyable)
            x-on:click="
                window.navigator.clipboard.writeText(@js($detailCopyableState))
                $tooltip(@js($detailCopyMessage), {
                    theme: $store.theme,
                    timeout: @js($detailCopyMessageDuration),
                })
            "
        @endif
    >
        <span
            class="text-sm"
            @if($showDetailTooltip)
                x-tooltip="{ content: @js($detail), theme: $store.theme }"
            @endif
        >
            {{ $formattedDetail }}
        </span>

        @if($detailIsCopyable)
            <span class="h-4 w-4 hidden group-hover:block cursor-pointer">
                <x-filament::icon
                    icon="heroicon-o-clipboard"
                    size="sm"
                />
            </span>
        @endif
    </span>
</div>
