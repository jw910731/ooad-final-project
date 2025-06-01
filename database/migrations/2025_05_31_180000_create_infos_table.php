<?php

use App\Models\Course;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('infos', function (Blueprint $table) {
            $table->id();
            $table->integer('order');
            $table->string('title');
            $table->string('description');
            $table->foreignUuid('file_set_id')->nullable();
            $table->foreignIdFor(Course::class);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('infos');
    }
};
