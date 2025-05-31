<?php

use App\Models\Score;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('user_scores', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class);
            $table->foreignIdFor(Score::class);
            $table->integer('score_point');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_scores');
    }
};
