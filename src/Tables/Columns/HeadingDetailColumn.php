<?php

namespace UnexpectedJourney\FilamentToolbox\Tables\Columns;

use Closure;
use Filament\Support\Contracts\HasLabel as LabelInterface;
use Filament\Tables\Columns\Column;
use Illuminate\Support\Str;

class HeadingDetailColumn extends Column
{
    use CanHideColumnHeader;

    protected string $view = 'toolbox::tables.columns.heading-detail-column';

    protected string | Closure | null $heading = null;

    protected string | Closure | null $detail = null;

    protected int | Closure | null $headingCharacterLimit = null;

    protected string | Closure | null $headingCharacterLimitEnd = null;

    protected int | Closure | null $headingWordLimit = null;

    protected string | Closure | null $headingWordLimitEnd = null;

    protected bool | Closure $isHeadingCopyable = false;

    protected string | Closure | null $headingCopyableState = null;

    protected string | Closure | null $headingCopyMessage = null;

    protected int | Closure | null $headingCopyMessageDuration = null;

    protected int | Closure | null $detailCharacterLimit = null;

    protected string | Closure | null $detailCharacterLimitEnd = null;

    protected int | Closure | null $detailWordLimit = null;

    protected string | Closure | null $detailWordLimitEnd = null;

    protected bool | Closure $showHeadingTooltipWhenLimited = true;

    protected bool | Closure $showDetailTooltipWhenLimited = true;

    protected ?Closure $formatHeadingStateUsing = null;

    protected ?Closure $formatDetailStateUsing = null;

    protected bool | Closure $isDetailCopyable = false;

    protected string | Closure | null $detailCopyableState = null;

    protected string | Closure | null $detailCopyMessage = null;

    protected int | Closure | null $detailCopyMessageDuration = null;

    public function shouldShowHeadingTooltip(mixed $state): bool
    {
        if ($this->evaluate($this->showHeadingTooltipWhenLimited) === false) {
            return false;
        }

        return $this->isLimited($state, $this->getHeadingCharacterLimit(), $this->getHeadingWordLimit());
    }

    protected function isLimited(mixed $state, ?int $characterLimit, ?int $wordLimit): bool
    {
        if ($characterLimit) {
            return Str::length($state) > $characterLimit;
        }

        if ($wordLimit) {
            return Str::wordCount($state) > $wordLimit;
        }

        return false;
    }

    public function getHeadingCharacterLimit(): ?int
    {
        return $this->evaluate($this->headingCharacterLimit);
    }

    public function getHeadingWordLimit(): ?int
    {
        return $this->evaluate($this->headingWordLimit);
    }

    public function shouldShowDetailTooltip(mixed $state): bool
    {
        if ($this->evaluate($this->showDetailTooltipWhenLimited) === false) {
            return false;
        }

        return $this->isLimited($state, $this->getDetailCharacterLimit(), $this->getDetailWordLimit());
    }

    public function getDetailCharacterLimit(): ?int
    {
        return $this->evaluate($this->detailCharacterLimit);
    }

    public function getDetailWordLimit(): ?int
    {
        return $this->evaluate($this->detailWordLimit);
    }

    public function shouldShowDetailTooltipWhenLimited(): bool
    {
        return $this->evaluate($this->showDetailTooltipWhenLimited);
    }

    public function showTooltipWhenLimited(bool | Closure $condition = true): static
    {
        $this->showDetailTooltipWhenLimited($condition);
        $this->showHeadingTooltipWhenLimited($condition);

        return $this;
    }

    public function showDetailTooltipWhenLimited(bool | Closure $condition = true): static
    {
        $this->showDetailTooltipWhenLimited = $condition;

        return $this;
    }

    public function showHeadingTooltipWhenLimited(bool | Closure $condition = true): static
    {
        $this->showHeadingTooltipWhenLimited = $condition;

        return $this;
    }

    public function heading(string | Closure | null $heading): static
    {
        $this->heading = $heading;

        return $this;
    }

    public function detail(string | Closure | null $detail): static
    {
        $this->detail = $detail;

        return $this;
    }

    public function formatStateUsing(?Closure $callback): static
    {
        $this->formatHeadingStateUsing($callback);
        $this->formatDetailStateUsing($callback);

        return $this;
    }

    public function formatHeadingStateUsing(?Closure $callback): static
    {
        $this->formatHeadingStateUsing = $callback;

        return $this;
    }

    public function formatDetailStateUsing(?Closure $callback): static
    {
        $this->formatHeadingStateUsing = $callback;

        return $this;
    }

    public function getHeading(): ?string
    {
        if ($this->heading === null) {
            return $this->getState();
        }

        return $this->evaluate($this->heading);
    }

    public function getDetail(): ?string
    {
        return $this->evaluate($this->detail);
    }

    public function formatHeadingState(mixed $state): mixed
    {
        return $this->formatState(
            $state,
            $this->formatHeadingStateUsing,
            $this->getHeadingCharacterLimit(),
            $this->getHeadingCharacterLimitEnd(),
            $this->getHeadingWordLimit(),
            $this->getHeadingWordLimitEnd(),
        );
    }

    protected function formatState(
        mixed $state,
        ?Closure $formatStateUsing,
        ?int $characterLimit,
        ?string $characterLimitEnd,
        ?int $wordLimit,
        ?string $wordLimitEnd
    ): mixed {
        if ($state instanceof LabelInterface) {
            $state = $state->getLabel();
        }

        $state = $this->evaluate($formatStateUsing ?? $state, [
            'state' => $state,
        ]);

        if ($characterLimit) {
            $state = Str::limit($state, $characterLimit, $characterLimitEnd);
        }

        if ($wordLimit) {
            $state = Str::words($state, $wordLimit, $wordLimitEnd);
        }

        return $state;
    }

    public function limit(int | Closure | null $length = 100, string | Closure | null $end = '...'): static
    {
        $this->limitHeading($length, $end);
        $this->limitDetail($length, $end);

        return $this;
    }

    public function limitHeading(int | Closure | null $length = 100, string | Closure | null $end = '...'): static
    {
        $this->headingCharacterLimit = $length;
        $this->headingCharacterLimitEnd = $end;

        return $this;
    }

    public function limitDetail(int | Closure | null $length = 100, string | Closure | null $end = '...'): static
    {
        $this->detailCharacterLimit = $length;
        $this->detailCharacterLimitEnd = $end;

        return $this;
    }

    public function words(int $words = 100, string $end = '...'): static
    {
        $this->wordsHeading($words, $end);
        $this->wordsDetail($words, $end);

        return $this;
    }

    public function wordsHeading(int $words = 100, string $end = '...'): static
    {
        $this->headingWordLimit = $words;
        $this->headingWordLimitEnd = $end;

        return $this;
    }

    public function wordsDetail(int $words = 100, string $end = '...'): static
    {
        $this->detailWordLimit = $words;
        $this->detailWordLimitEnd = $end;

        return $this;
    }

    public function getHeadingCharacterLimitEnd(): ?string
    {
        return $this->evaluate($this->headingCharacterLimitEnd);
    }

    public function getHeadingWordLimitEnd(): ?string
    {
        return $this->evaluate($this->headingWordLimitEnd);
    }

    public function formatDetailState(mixed $state): mixed
    {
        return $this->formatState(
            $state,
            $this->formatDetailStateUsing,
            $this->getDetailCharacterLimit(),
            $this->getDetailCharacterLimitEnd(),
            $this->getDetailWordLimit(),
            $this->getDetailWordLimitEnd(),
        );
    }

    public function getDetailCharacterLimitEnd(): ?string
    {
        return $this->evaluate($this->detailCharacterLimitEnd);
    }

    public function getDetailWordLimitEnd(): ?string
    {
        return $this->evaluate($this->detailWordLimitEnd);
    }

    public function headingCopyable(bool | Closure $condition = true): static
    {
        $this->isHeadingCopyable = $condition;

        return $this;
    }

    public function headingCopyableState(string | Closure | null $state): static
    {
        $this->headingCopyableState = $state;

        return $this;
    }

    public function headingCopyMessage(string | Closure | null $message): static
    {
        $this->headingCopyMessage = $message;

        return $this;
    }

    public function headingCopyMessageDuration(int | Closure | null $duration): static
    {
        $this->headingCopyMessageDuration = $duration;

        return $this;
    }

    public function isHeadingCopyable(mixed $state): bool
    {
        return (bool) $this->evaluate($this->isHeadingCopyable, [
            'state' => $state,
        ]);
    }

    public function getHeadingCopyableState(mixed $state): ?string
    {
        return $this->evaluate($this->headingCopyableState, [
            'state' => $state,
        ]);
    }

    public function getHeadingCopyMessage(mixed $state): string
    {
        return $this->evaluate($this->headingCopyMessage, [
            'state' => $state,
        ]) ?? __('filament::components/copyable.messages.copied');
    }

    public function getHeadingCopyMessageDuration(mixed $state): int
    {
        return $this->evaluate($this->headingCopyMessageDuration, [
            'state' => $state,
        ]) ?? 2000;
    }

    public function detailCopyable(bool | Closure $condition = true): static
    {
        $this->isDetailCopyable = $condition;

        return $this;
    }

    public function detailCopyableState(string | Closure | null $state): static
    {
        $this->detailCopyableState = $state;

        return $this;
    }

    public function detailCopyMessage(string | Closure | null $message): static
    {
        $this->detailCopyMessage = $message;

        return $this;
    }

    public function detailCopyMessageDuration(int | Closure | null $duration): static
    {
        $this->detailCopyMessageDuration = $duration;

        return $this;
    }

    public function isDetailCopyable(mixed $state): bool
    {
        return (bool) $this->evaluate($this->isDetailCopyable, [
            'state' => $state,
        ]);
    }

    public function getDetailCopyableState(mixed $state): ?string
    {
        return $this->evaluate($this->detailCopyableState, [
            'state' => $state,
        ]);
    }

    public function getDetailCopyMessage(mixed $state): string
    {
        return $this->evaluate($this->detailCopyMessage, [
            'state' => $state,
        ]) ?? __('filament::components/copyable.messages.copied');
    }

    public function getDetailCopyMessageDuration(mixed $state): int
    {
        return $this->evaluate($this->detailCopyMessageDuration, [
            'state' => $state,
        ]) ?? 2000;
    }

    public function copyable(bool | Closure $condition = true): static
    {
        $this->isHeadingCopyable = $condition;
        $this->isDetailCopyable = $condition;

        return $this;
    }
}
