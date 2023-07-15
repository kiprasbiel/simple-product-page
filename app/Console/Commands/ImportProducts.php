<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

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
        // Checking if files for importing are present
        $files = Storage::disk('product_import')->files();

        // Checking if in the designated dir files with json extension exist
        $fileExist = in_array('json', array_map(function ($file){
            return pathinfo($file)['extension'];
        }, $files));

        if(!$fileExist) {
            $this->warn('No files to import found.');
            return;
        }

        \App\Jobs\ImportProducts::dispatch()->onQueue('import');

        $this->info('Product import job added to \'import\' queue');
    }
}
