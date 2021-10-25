<?php

namespace Infrastructure\Console\Commands\Support;

use Illuminate\Console\Command;
use DB;

class Demo extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'demo';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Demo';

    public function __construct(
    ) {
        parent::__construct();
    }

    public function handle()
    {
        return;
    }
}
