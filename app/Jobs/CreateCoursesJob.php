<?php

namespace App\Jobs;

use App\Models\Course;
use Faker\Factory as Faker;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Http;

class CreateCoursesJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public $count;

    public function __construct($count = 100)
    {
        $this->count = $count;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $faker = Faker::create();
        $response = Http::get('https://dummyjson.com/products?limit=100');
        $data = $response->json();
        $productimages = [];

        if (isset($data['products']) && is_array($data['products'])) {
            foreach ($data['products'] as $product) {
                $productimages[] = $product['thumbnail'];
            }
        }

        // Create courses
        for ($i = 0; $i < $this->count; $i++) {
            Course::create([
                'name' => $faker->name,
                'description' => $faker->sentence,
                'image' => $productimages[array_rand($productimages)],
                'course_fee' => $faker->randomNumber(4),
                'created_by' => $faker->numberBetween(1, 10),
                'numberofhours' => $faker->numberBetween(10, 200),
            ]);
        }
    }

    public function maxAttempts()
    {
        return 3;
    }

    public function backoff()
    {
        return 60;
    }
}
