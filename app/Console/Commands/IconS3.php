<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;

class IconS3 extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'guild:uploads3';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $files = scandir(public_path('guild'));
        foreach (array_diff($files, ['.', '..']) as $file) {
            Storage::disk('launcher')
                ->putFileAs('launcher_api', new File(public_path('guild/'.$file)), $file, 'public');
        }
    }
}
