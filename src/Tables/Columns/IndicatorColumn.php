<?php

namespace UnexpectedJourney\FilamentToolbox\Tables\Columns;

use Closure;
use Filament\Forms\Concerns\HasColumns;
use Filament\Tables\Columns\Column;
use Filament\Tables\Columns\IconColumn\IconColumnSize;

class IndicatorColumn extends Column
{
    use HasColumns;

    protected string $view = 'toolbox::tables.columns.indicator-column';

    protected array | Closure | null $colors = null;

    protected array | Closure | null $icons = null;

    protected string | array | Closure | null $falseColor = null;

    protected string | array | Closure | null $trueColor = null;

    protected IconColumnSize | string | Closure | null $size = null;

    protected array | Closure | null $order = null;

    protected array | bool | Closure | string | null $showInactive = null;

    protected array | Closure | null $tooltips = null;

    protected bool | Closure $showTooltips = false;

    protected array | bool | null $cachedTooltips = null;

    protected array | Closure | null $urls = null;

    protected array | bool | Closure | null $openUrlsInNewTab = null;

    protected array | bool | null $cachedUrls = null;

    public function configure(): static
    {
        parent::configure();

        $this->columns(['default' => 3]);

        return $this;
    }

    public function getState(): mixed
    {
        $state = parent::getState();

        $order = $this->getOrder($state);

        return collect($state)
            ->filter(fn ($active, $indicator): bool => $active || $this->shouldShowInactive($indicator))
            ->keys()
            ->when($order, fn ($collection) => $collection->sortBy(
                fn ($indicator) => array_search($indicator, $order) || 1000
            ))
            ->toArray();
    }

    public function falseColor(string | array | Closure | null $color): static
    {
        $this->falseColor = $color;

        return $this;
    }

    public function getFalseColor(string $indicator): string | array
    {
        return $this->evaluate($this->falseColor, [
            'indicator' => $indicator,
        ]) ?? 'gray';
    }

    public function trueColor(string | array | Closure | null $color): static
    {
        $this->trueColor = $color;

        return $this;
    }

    public function getTrueColor(string $indicator): string | array
    {
        return $this->evaluate($this->trueColor, [
            'indicator' => $indicator,
        ]) ?? 'primary';
    }

    public function size(IconColumnSize | string | Closure | null $size): static
    {
        $this->size = $size;

        return $this;
    }

    public function getSize(mixed $state): IconColumnSize | string | null
    {
        return $this->evaluate($this->size, [
            'state' => $state,
        ]);
    }

    public function icons(array | Closure $icons): static
    {
        $this->icons = $icons;

        return $this;
    }

    public function getIcon(string $indicator): string
    {
        $state = parent::getState();

        $icons = $this->evaluate($this->icons, [
            'state' => $state,
        ]);

        return data_get($icons, $indicator, 'heroicon-s-information-circle');
    }

    public function colors(array | Closure $colors): static
    {
        $this->colors = $colors;

        return $this;
    }

    public function getColor(string $indicator)
    {
        $state = parent::getState();

        if (! data_get($state, $indicator)) {
            return $this->getFalseColor($indicator);
        }

        $colors = $this->evaluate($this->colors, [
            'state' => $state,
        ]);

        return data_get($colors, $indicator, $this->getTrueColor($indicator));
    }

    public function order(array | Closure $order): static
    {
        $this->order = $order;
    }

    protected function getOrder($state): ?array
    {
        $order = $this->evaluate($this->order, [
            'state' => $state,
        ]);

        if ($order !== null) {
            return $order;
        }

        // return a default order of the order the icons were passed in
        return collect(
            $this->evaluate($this->icons, [
                'state' => parent::getState(),
            ])
        )->keys()->toArray();
    }

    public function showInactive(array | bool | Closure | string | null $condition = true): static
    {
        $this->showInactive = $condition;

        return $this;
    }

    public function shouldShowInactive(string $indicator): ?bool
    {
        $shouldShow = $this->evaluate($this->showInactive, [
            'indicator' => $indicator,
        ]);

        if (is_string($shouldShow)) {
            $shouldShow = [$shouldShow];
        }

        return match (true) {
            is_bool($shouldShow) => $shouldShow,
            is_array($shouldShow) => in_array($indicator, $shouldShow),
            default => false
        };
    }

    public function showTooltips(bool | Closure $condition = true): static
    {
        $this->showTooltips = $condition;

        return $this;
    }

    public function tooltips(array | Closure $indicators = null): static
    {
        $this->tooltips = $indicators;
        $this->showTooltips = true;

        return $this;
    }

    public function getIndicatorTooltip(string $indicator): ?string
    {
        if ($this->evaluate($this->showTooltips) === false) {
            return null;
        }

        $cachedTooltips = $this->getCachedTooltips();
        $componentState = parent::getState();

        $tooltip = array_key_exists($indicator, $cachedTooltips)
            ? $this->evaluate($cachedTooltips[$indicator], [
                'indicator' => $indicator,
                'active' => $componentState[$indicator],
            ])
            : $indicator;

        return str($tooltip)
            ->snake(' ')
            ->replace('_', ' ')
            ->title();
    }

    protected function getCachedTooltips(): array
    {
        if ($this->cachedTooltips !== null) {
            return $this->cachedTooltips;
        }

        return $this->cachedTooltips = $this->evaluate($this->tooltips) ?? [];
    }

    public function openUrlsInNewTab(array | bool | Closure $condition = true): static
    {
        $this->openUrlsInNewTab = $condition;

        return $this;
    }

    public function shouldOpenIndicatorUrlInNewTab(string $indicator): ?bool
    {
        $state = parent::getState();

        return $this->evaluate($this->openUrlsInNewTab, [
            'indicator' => $indicator,
            'active' => data_get($state, $indicator, false),
        ]);
    }

    public function urls(array | Closure $urls, bool | Closure $openUrlsInNewTab = null): static
    {
        $this->urls = $urls;

        if ($openUrlsInNewTab !== null) {
            $this->openUrlsInNewTab($openUrlsInNewTab);
        }

        return $this;
    }

    public function getIndicatorUrl(string $indicator): ?string
    {
        $cachedUrls = $this->getCachedUrls();
        $componentState = parent::getState();

        return array_key_exists($indicator, $cachedUrls)
            ? $this->evaluate($cachedUrls[$indicator], [
                'indicator' => $indicator,
                'active' => $componentState[$indicator],
            ])
            : null;
    }

    protected function getCachedUrls(): array
    {
        if ($this->cachedUrls !== null) {
            return $this->cachedUrls;
        }

        return $this->cachedUrls = $this->evaluate($this->urls) ?? [];
    }
}
