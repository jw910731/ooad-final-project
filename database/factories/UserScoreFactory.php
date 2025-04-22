<?php

namespace Database\Factories;

use App\Models\Score;
use App\Models\User;
use App\Models\UserScore;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class UserScoreFactory extends Factory
{
    protected $model = UserScore::class;

    public function definition()
    {
        return [
            'score_point' => $this->faker->randomNumber(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),

            'user_id' => User::factory(),
            'score_id' => Score::factory(),
        ];
    }
}
