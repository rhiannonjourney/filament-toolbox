<?php

namespace UnexpectedJourney\FilamentToolbox\Tables\Columns;

use Closure;
use Filament\Forms\Concerns\HasColumns;
use Filament\Tables\Columns\Column;
use Filament\Tables\Columns\IconColumn\IconColumnSize;
use UnexpectedJourney\FilamentToolbox\Tables\Columns\FlagColumn\Flag;

class FlagColumn extends Column
{
    use HasColumns;

    protected string $view = 'toolbox::tables.columns.flag-column';

    protected string | Closure | null $inactiveIcon = null;

    protected string | Closure | null $activeIcon = null;

    protected string | array | Closure | null $inactiveColor = null;

    protected string | array | Closure | null $activeColor = null;

    protected IconColumnSize | string | Closure | null $size = null;

    protected bool | Closure $showTooltips = false;

    protected array | Closure | null $flags = null;

    public function configure(): static
    {
        parent::configure();

        $this->columns(['default' => 3]);

        return $this;
    }

    public function activeIcon(string | Closure | null $icon): static
    {
        $this->activeIcon = $icon;

        return $this;
    }

    public function getActiveIcon(): string
    {
        return $this->evaluate($this->activeIcon) ?? '';
    }

    public function inactiveIcon(string | Closure | null $icon): static
    {
        $this->inactiveIcon = $icon;

        return $this;
    }

    public function getInactiveIcon(): string
    {
        return $this->evaluate($this->inactiveIcon) ?? '';
    }

    public function activeColor(string | array | Closure | null $color): static
    {
        $this->activeColor = $color;

        return $this;
    }

    public function getActiveColor(): string | array
    {
        return $this->evaluate($this->activeColor) ?? 'primary';
    }

    public function inactiveColor(string | array | Closure | null $color): static
    {
        $this->inactiveColor = $color;

        return $this;
    }

    public function getInactiveColor(): string | array
    {
        return $this->evaluate($this->inactiveColor) ?? 'gray';
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

    public function flags(array | Closure $flags): static
    {
        $this->flags = $flags;

        return $this;
    }

    public function getFlags(mixed $state): array
    {
        $indicators = $this->evaluate($this->flags) ?? [];

        return collect($indicators)
            ->filter(function (Flag $flag) use ($state): bool {
                return $this->evaluate($flag->shouldShowWhenInactive()) || data_get($state, $flag->getName(), false);
            })
            ->all();
    }

    public function showTooltips(bool | Closure $condition = true): static
    {
        $this->showTooltips = $condition;

        return $this;
    }

    public function shouldShowTooltip(): bool
    {
        return $this->evaluate($this->showTooltips);
    }

    public function getIcon(Flag $flag, bool $active): string
    {
        $icon = $this->evaluate($flag->getIcon(), [
            'active' => $active,
        ]);

        if ($icon) {
            return $icon;
        }

        return $active
            ? $this->getActiveIcon()
            : $this->getInactiveIcon();
    }

    public function getColor(Flag $flag, bool $active): array | string | null
    {
        $color = $active
            ? $this->evaluate($flag->getActiveColor())
            : $this->evaluate($flag->getInactiveColor());

        if ($color) {
            return $color;
        }

        return $active
            ? $this->evaluate($this->getActiveColor())
            : $this->evaluate($this->getInactiveColor());
    }

    public function getIndicatorTooltip(Flag $flag, bool $active): ?string
    {
        $tooltip = $this->evaluate($flag->getTooltip(), [
            'active' => $active,
        ]);

        if ($tooltip) {
            return $tooltip;
        }

        if ($this->evaluate($this->showTooltips)) {
            return str($flag->getName())
                ->snake(' ')
                ->replace('_', ' ')
                ->headline();
        }

        return null;
    }

    public function getFlagUrl(Flag $flag, bool $active): ?string
    {
        return $this->evaluate($flag->getUrl(), [
            'active' => $active,
        ]);
    }

    public function shouldOpenFlagUrlInNewTab(Flag $flag, bool $active): ?string
    {
        return $this->evaluate($flag->shouldOpenUrlInNewTab(), [
            'active' => $active,
        ]);
    }
}
