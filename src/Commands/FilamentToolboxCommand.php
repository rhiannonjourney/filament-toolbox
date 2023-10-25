<?php

namespace UnexpectedJourney\FilamentToolbox\Commands;

use Illuminate\Console\Command;

class FilamentToolboxCommand extends Command
{
    public $signature = 'filament-toolbox';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
