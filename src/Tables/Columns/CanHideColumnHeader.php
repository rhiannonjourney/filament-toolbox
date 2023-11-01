<?php

namespace UnexpectedJourney\FilamentToolbox\Tables\Columns;

trait CanHideColumnHeader
{
    public function hiddenColumnHeader(): static
    {
        $this->extraHeaderAttributes(['class' => 'filament-toolbox-hidden-column-header'], merge: true);

        return $this;
    }
}
