<div
    @if ($isCollapsible())
        x-data="{ isCollapsed: {{ $isCollapsed() ? 'true' : 'false' }} }"
    x-on:open-form-section.window="if ($event.detail.id == $el.id) isCollapsed = false"
    x-on:collapse-form-section.window="if ($event.detail.id == $el.id) isCollapsed = true"
    x-on:toggle-form-section.window="if ($event.detail.id == $el.id) isCollapsed = ! isCollapsed"
    x-on:expand-concealing-component.window="
            if ($event.detail.id === $el.id) {
                isCollapsed = false
                $el.scrollIntoView({ behavior: 'smooth', block: 'start' })
            }
        "
    @endif
    id="{{ $getId() }}"
    {{ $attributes->merge($getExtraAttributes())->class([
        'bg-gray-100 rounded-xl border-gray-300',
        'dark:bg-gray-900' => config('forms.dark_mode'),
    ]) }}
>
    <div
        @class([
            'flex items-center px-4 py-2 bg-gray-100 rtl:space-x-reverse overflow-hidden rounded-t-xl filament-forms-section-header-wrapper',
            'dark:bg-gray-900' => config('forms.dark_mode'),
        ])
        @if ($isCollapsible())
            x-bind:class="{ 'rounded-b-xl': isCollapsed }"
        @endif
    >
        @if($icon = $getIcon())
            @php
                $iconColor = match ($getIconColor()) {
                    'danger' => 'text-danger-500',
                    'primary' => 'text-primary-500',
                    'success' => 'text-success-500',
                    'warning' => 'text-warning-500',
                    default => 'text-gray-700',
                };
            @endphp
            <x-dynamic-component
                :component="$icon"
                :class="'w-6 h-6 mr-3' . ' ' . $iconColor"
            />
        @endif

        <div class="flex-1 filament-forms-section-header">
            <h3 class="text-xl font-bold tracking-tight">
                {{ $getHeading() }}
            </h3>
        </div>

        @if ($isCollapsible())
            <button x-on:click="isCollapsed = ! isCollapsed"
                    x-bind:class="{
                    '-rotate-180': !isCollapsed,
                }" type="button"
                @class([
                    'flex items-center justify-center w-8 h-8 transform rounded-full text-primary-500 hover:bg-gray-500/5 focus:bg-primary-500/10 focus:outline-none',
                    '-rotate-180' => ! $isCollapsed(),
                ])
            >
                <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                     stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>
        @endif
    </div>

    <div
        @if ($isCollapsible())
            x-bind:class="{ 'invisible h-0 !m-0 overflow-y-hidden': isCollapsed }"
        x-bind:aria-expanded="(! isCollapsed).toString()"
        @if ($isCollapsed()) x-cloak @endif
        @endif
        class=""
    >
        <div class="px-4 pb-2">
            <div @class([
                "prose max-w-none",
                "dark:text-gray-100 dark:prose-strong:text-gray-100 dark:prose-strong:font-bold" => config('forms.dark_mode'),
            ])>
                {{ $getContent() }}
            </div>
        </div>
    </div>
</div>
