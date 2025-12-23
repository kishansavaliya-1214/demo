<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Jobs\CreateCoursesJob;
use Illuminate\Database\Seeder;

class CoursesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CreateCoursesJob::dispatch(100);
    }
}
