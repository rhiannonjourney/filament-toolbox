<?php

namespace UnexpectedJourney\FilamentToolbox\Tables\Columns;

use Closure;
use Filament\Forms\Concerns\HasColumns;
use Filament\Tables\Columns\Column;
use Filament\Tables\Columns\IconColumn\IconColumnSize;
use UnexpectedJourney\FilamentToolbox\Tables\Columns\FlagColumn\Flag;

class FlagColumn extends Column
{
    use CanHideColumnHeader;
    use HasColumns;

    protected string $view = 'toolbox::tables.columns.flag-column';

    protected string | Closure | null $inactiveIcon = null;

    protected string | Closure | null $activeIcon = null;

    protected string | array | Closure | null $inactiveColor = null;

    protected string | array | Closure | null $activeColor = null;

    protected IconColumnSize | Closure | null $size = null;

    protected bool | Closure | null $showTooltips = null;

    protected array | Closure | null $flags = null;

    protected bool | Closure | null $showInactive = null;

    public function setUp(): void
    {
        parent::setUp();

        $this->columns(['default' => 3]);

        $this->extraHeaderAttributes(function () {
            $columns = collect([
                'default' => $this->getColumns('default'),
                'sm' => $this->getColumns('sm'),
                'md' => $this->getColumns('md'),
                'lg' => $this->getColumns('lg'),
                'xl' => $this->getColumns('xl'),
                '2xl' => $this->getColumns('2xl'),
            ])->filter();

            $sizeMultiplier = match ($this->getSize($this->getState())) {
                IconColumnSize::ExtraSmall, 'xs' => .75,
                IconColumnSize::Small, 'sm' => 1,
                IconColumnSize::Large, 'lg' => 1.5,
                IconColumnSize::ExtraLarge, 'xl' => 1.75,
                default => 1.25
            };

            return [
                'class' => $columns
                    ->map(fn (?int $columnCount, string $breakpoint): string => match ($breakpoint) {
                        'default' => 'min-w-[var(--ft-fc-w-default)] w-[var(--ft-fc-w-default)]',
                        'sm' => 'sm:w-[var(--ft-fc-w-sm)]',
                        'md' => 'md:w-[var(--ft-fc-w-md)]',
                        'lg' => 'lg:w-[var(--ft-fc-w-lg)]',
                        'xl' => 'xl:w-[var(--ft-fc-w-xl)]',
                        '2xl' => '2xl:w-[var(--ft-fc-w-2xl)]',
                    })
                    ->join(' '),
                'style' => $columns
                    ->map(function (?int $columnCount, string $breakpoint) use ($sizeMultiplier) {
                        $totalPotentialIconsWidth = $columnCount * $sizeMultiplier;
                        $gap = ($columnCount - 1) * .5;
                        $padding = 1.5;

                        return sprintf(
                            '--ft-fc-w-%s: %srem;',
                            $breakpoint,
                            $totalPotentialIconsWidth + $gap + $padding
                        );
                    })
                    ->join('; ', ';'),
            ];
        });
    }

    public function activeIcon(string | Closure | null $icon): static
    {
        $this->activeIcon = $icon;

        return $this;
    }

    public function getActiveIcon(): string
    {
        return $this->evaluate($this->activeIcon) ?? 'heroicon-o-check-circle';
    }

    public function inactiveIcon(string | Closure | null $icon): static
    {
        $this->inactiveIcon = $icon;

        return $this;
    }

    public function getInactiveIcon(): string
    {
        return $this->evaluate($this->inactiveIcon) ?? 'heroicon-o-x-circle';
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
        ]) ?? IconColumnSize::Medium;
    }

    public function flags(array | Closure $flags): static
    {
        $this->flags = $flags;

        return $this;
    }

    public function getFlags(mixed $state): array
    {
        $flags = $this->evaluate($this->flags) ?? [];
        $showInactive = $this->evaluate($this->showInactive) ?? false;

        return collect($flags)
            ->filter(function (Flag $flag) use ($state, $showInactive): bool {
                $active = data_get($state, $flag->getName(), false);
                $alwaysShowCurrentFlag = $this->evaluate($flag->shouldShowWhenInactive());

                if (is_bool($alwaysShowCurrentFlag)) {
                    return $alwaysShowCurrentFlag;
                }

                return $showInactive || $active;
            })
            ->all();
    }

    public function showInactive(bool | Closure $condition = true): static
    {
        $this->showInactive = $condition;

        return $this;
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
