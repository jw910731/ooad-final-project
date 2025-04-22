<?php

namespace Database\Factories;

use App\Models\Course;
use App\Models\Score;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class ScoreFactory extends Factory
{
    protected $model = Score::class;

    public function definition()
    {
        return [
            'order' => $this->faker->randomNumber(),
            'title' => $this->faker->word(),
            'description' => $this->faker->text(),
            'max_point' => $this->faker->randomNumber(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),

            'course_id' => Course::factory(),
        ];
    }
}
