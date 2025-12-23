<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentCourses extends Model
{
    protected $fillable = [
        'student_id',
        'course_id',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Get the course associated with the student course record.
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
