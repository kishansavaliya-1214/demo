<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Course extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'name',
        'image',
        'description',
        'number_of_hours',
        'course_fee',
        'created_by',
        'created_at',
        'updated_at',
    ];

    public function students()
    {
        return $this->belongsToMany(Student::class, 'student_courses', 'course_id', 'student_id');
    }
}
