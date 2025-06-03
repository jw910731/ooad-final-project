<?php

namespace Database\Factories;

use App\Models\Assignment;
use App\Models\User;
use App\Models\UserAssignment;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class UserAssignmentFactory extends Factory
{
    protected $model = UserAssignment::class;

    public function definition()
    {
        return [
            'score_point' => $this->faker->randomNumber(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),

            'user_id' => User::factory(),
            'assignment_id' => Assignment::factory(),
        ];
    }
}
