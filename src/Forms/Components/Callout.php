<?php

namespace UnexpectedJourney\FilamentToolbox\Forms\Components;

use Closure;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\Concerns\CanBeCollapsed;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;

class Callout extends Component
{
    use CanBeCollapsed;

    protected string $view = 'toolbox::forms.components.callout';

    protected string | Htmlable | Closure | null $content = null;

    protected string | Closure | null $heading = null;

    protected bool $isMarkdown = false;

    protected bool $isHtml = false;

    protected string | Closure | null $icon = null;

    protected string | Closure | null $iconColor = null;

    final public function __construct(string | Closure | null $heading = null)
    {
        $this->heading($heading);
    }

    public static function make(string | Closure | null $heading = null): static
    {
        $static = app(static::class, ['heading' => $heading]);
        $static->configure();

        return $static;
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->columnSpan('full');
    }

    public function warning(): static
    {
        $this->iconColor('warning');
        $this->icon('heroicon-o-exclamation-triangle');

        return $this;
    }

    public function help(): static
    {
        $this->collapsed(true);
        $this->icon('heroicon-o-question-mark-circle');
        $this->iconColor('primary');

        return $this;
    }

    public function heading(string | Closure | null $heading): static
    {
        $this->heading = $heading;

        return $this;
    }

    public function content(string | Htmlable | Closure | null $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function getContent(): string | Htmlable | null
    {
        $content = $this->evaluate($this->content);

        if ($this->isHtml) {
            return $content instanceof Htmlable
                ? $content
                : new HtmlString($content);
        }

        if ($this->isMarkdown) {
            return new HtmlString(Str::markdown($content));
        }

        return $content;
    }

    public function getHeading(): ?string
    {
        return $this->evaluate($this->heading);
    }

    public function markdown(bool $markdown = true): static
    {
        $this->isMarkdown = $markdown;

        return $this;
    }

    public function html(bool $html = true): static
    {
        $this->isHtml = $html;

        return $this;
    }

    public function icon(string | Closure | null $icon): static
    {
        $this->icon = $icon;

        return $this;
    }

    public function getIcon(): ?string
    {
        return $this->evaluate($this->icon);
    }

    public function iconColor(string | Closure | null $iconColor): static
    {
        $this->iconColor = $iconColor;

        return $this;
    }

    public function getIconColor(): ?string
    {
        return $this->evaluate($this->iconColor);
    }
}
