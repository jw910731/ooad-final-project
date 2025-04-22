<?php

namespace Database\Factories;

use App\Models\Course;
use App\Models\Info;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class InfoFactory extends Factory
{
    protected $model = Info::class;

    public function definition()
    {
        return [
            'order' => $this->faker->randomNumber(),
            'title' => $this->faker->word(),
            'description' => $this->faker->text(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),

            'course_id' => Course::factory(),
        ];
    }
}
