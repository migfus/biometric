<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void {
        Schema::create('attachments', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('check_id');
            $table->foreign('check_id')->references('id')->on('checks')->onDelete('cascade')->onUpdate('restrict');

            $table->string('file_location');
            $table->unsignedInteger('file_size');
            $table->string('preview_location');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void {
        Schema::dropIfExists('attachments');
    }
};
