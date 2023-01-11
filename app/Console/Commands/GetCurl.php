<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\HistoryInout;
use App\Models\Timesheet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GetCurl extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'GetCurl:get';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get data curl ttlock';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function __construct()
    {
        parent::__construct();
    }
    public function handle(Request $request)
    {
            
    }
}
