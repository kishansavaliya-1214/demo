<?php

namespace App\Jobs;

use App\Mail\StudentActivityReportMail;
use App\Models\Student;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class SendStudentActivityReport implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public $authuseremail;

    public function __construct($authuseremail)
    {
        $this->authuseremail = $authuseremail;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $students = Student::with(['courses', 'user'])
            ->withCount(['courses as active_courses_count'])
            ->get();
        $pdf = Pdf::loadView('emails.student_activity_report', compact('students'));
        $reportFileName = 'student_activity_report_'.now()->format('Ymd_His').'.pdf';
        Storage::disk('public')->put('/'.$reportFileName, $pdf->output());
        $reportPath = storage_path('app/public/'.$reportFileName);
        Log::info('email sending'.$reportPath);
        try {
            Log::info('email sending,......');
            Mail::to($this->authuseremail)->send(new StudentActivityReportMail($reportPath, $reportFileName, $students));
        } catch (\Exception $e) {
            Log::info('email sending failed,......'.$e->getMessage());

            $this->error('Failed to send report: '.$e->getMessage());
        } finally {
            Log::info('email sending done and remove existing pdf');

            // if (file_exists($reportPath)) {
            //     unlink($reportPath);
            // }
        }
    }

    public function maxAttempts()
    {
        return 3;
    }
}
