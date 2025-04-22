<?php

use App\Models\Course;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('course_user', function (Blueprint $table) {
            $table->foreignIdFor(Course::class);
            $table->foreignIdFor(User::class);
            $table->enum('role', ['teacher', 'student', 'teaching_assistant', 'helper']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('course_user');
    }
};
