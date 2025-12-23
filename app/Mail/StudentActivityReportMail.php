<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class StudentActivityReportMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public $reportPath;

    public $reportData;

    public $students;

    public function __construct($reportPath, $reportData, $students)
    {
        $this->reportPath = $reportPath;
        $this->students = $students;
        $this->reportData = $reportData;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Student Activity Report Mail',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.student_activity_report',
            with: [
                'students' => $this->students,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        // return [
        //     Attachment::fromPath($this->reportPath)
        //         ->as($this->reportData)
        //         ->withMime('application/pdf'),
        // ];
        Log::info('attachment pdf');
        Log::info(storage_path('app/public/student_activity_report_20251223_064111.pdf'));
        Log::info(file_exists(storage_path('app/public/student_activity_report_20251223_064111.pdf')));
        return [
            // Attachment::fromPath("/var/www/html/kishan/importantfiles/demo/storage/app/public/student_activity_report_20251223_064111.pdf")
            //     ->as("student_activity_report_20251223_064111.pdf")
            //     ->withMime('application/pdf'),

            Attachment::fromPath(storage_path('app/public/student_activity_report_20251223_064111.pdf'))
                ->as('student_activity_report_20251223_064111.pdf')
                ->withMime('application/pdf')
        ];
    }
}
