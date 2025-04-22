<?php

use App\Models\Course;
use App\Models\Score;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Course::class);
            $table->foreignIdFor(Score::class)->nullable();
            $table->integer('order');
            $table->string('title');
            $table->string('description');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('assignments');
    }
};
