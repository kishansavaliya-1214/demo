<?php

namespace App\Console\Commands;

use App\Jobs\SendStudentActivityReport;
use Illuminate\Console\Command;

class ReportGenerate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'report:student-activity {email?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates a student course activity report';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $authUserEmail = $this->argument('email');

        SendStudentActivityReport::dispatch($authUserEmail);

        $this->info('Student course activity report generation job dispatched to queue successfully.');
    }
}
