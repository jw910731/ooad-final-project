<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('file_set', function (Blueprint $table) {
            $table->uuid()->index();
            $table->foreignId('file_id')->constrained('files');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('file_set');
    }
};
