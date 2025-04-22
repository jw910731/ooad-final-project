<?php

namespace Database\Factories;

use App\Models\Assignment;
use App\Models\Course;
use App\Models\Score;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class AssignmentFactory extends Factory
{
    protected $model = Assignment::class;

    public function definition()
    {
        return [
            'order' => $this->faker->randomNumber(),
            'title' => $this->faker->word(),
            'description' => $this->faker->text(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),

            'course_id' => Course::factory(),
            'score_id' => Score::factory(),
        ];
    }
}
