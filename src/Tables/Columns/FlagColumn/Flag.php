<?php

namespace UnexpectedJourney\FilamentToolbox\Tables\Columns\FlagColumn;

use Closure;
use Filament\Support\Components\Component;

class Flag extends Component
{
    protected string $name;

    protected string | Closure | null $icon = null;

    protected bool | Closure | null $showWhenInactive = null;

    protected string | Closure | null $tooltip = null;

    protected string | Closure | null $url = null;

    protected bool | Closure | null $openUrlInNewTab = null;

    protected string | array | Closure | null $activeColor = null;

    protected string | array | Closure | null $inactiveColor = null;

    final public function __construct(string $name, string | Closure | null $icon = null)
    {
        $this->name($name);
        $this->icon($icon);
    }

    public static function make(string $name, string | Closure | null $icon = null): static
    {
        $static = app(static::class, [
            'name' => $name,
            'icon' => $icon,
        ]);

        $static->configure();

        return $static;
    }

    public function name(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function icon(string | Closure | null $icon): static
    {
        $this->icon = $icon;

        return $this;
    }

    public function getIcon(): Closure | string | null
    {
        return $this->icon;
    }

    public function showWhenInactive(bool | Closure | null $condition = true): static
    {
        $this->showWhenInactive = $condition;

        return $this;
    }

    public function shouldShowWhenInactive(): bool | Closure | null
    {
        return $this->showWhenInactive;
    }

    public function tooltip(string | Closure | null $tooltip): static
    {
        $this->tooltip = $tooltip;

        return $this;
    }

    public function getTooltip(): Closure | string | null
    {
        return $this->tooltip;
    }

    public function url(string | Closure | null $url, bool | Closure | null $openInNewTab = null): static
    {
        $this->url = $url;

        if ($openInNewTab) {
            $this->openUrlInNewTab($openInNewTab);
        }

        return $this;
    }

    public function getUrl(): Closure | string | null
    {
        return $this->url;
    }

    public function openUrlInNewTab(bool | Closure | null $condition): static
    {
        $this->openUrlInNewTab = $condition;

        return $this;
    }

    public function shouldOpenUrlInNewTab(): bool | Closure | null
    {
        return $this->openUrlInNewTab;
    }

    public function activeColor(string | array | Closure | null $color): static
    {
        $this->activeColor = $color;

        return $this;
    }

    public function getActiveColor(): array | string | Closure | null
    {
        return $this->activeColor;
    }

    public function inactiveColor(string | array | Closure | null $color): static
    {
        $this->inactiveColor = $color;

        return $this;
    }

    public function getInactiveColor(): array | string | Closure | null
    {
        return $this->inactiveColor;
    }
}
