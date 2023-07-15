<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ImportProducts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:import-products';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Imports Products from JSON files from Product Import directory';

    /**
     * Execute the console command.
     */
    public function handle(): void {
        \App\Jobs\ImportProducts::dispatch()->onQueue('import');

        $this->info('Product import job added to \'import\' queue');
    }
}
