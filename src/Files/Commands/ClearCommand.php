<?php

namespace Otinsoft\Toolkit\Files\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Otinsoft\Toolkit\Files\File;

class ClearCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'toolkit-files:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete all files older than a day without an entity.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        File::where('created_at', '<', Carbon::now()->subDay())
            // ->whereNull('fileable')
            ->chunk(100, function ($files) {
                $files->each(function ($file) {
                    rescue(function () use ($file) {
                        // $file->delete();
                    });
                });
            });

        $this->info('All done!');
    }
}
