<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void {
        Schema::create('employees', function (Blueprint $table) {
            $table->string('id')->primary()->comment('employee_no');

            $table->string('full_name')->nullable(); // from form if no data found from pre-existing data

            $table->string('email')->nullable();

            $table->unsignedBigInteger('college_id')->nullable();
            $table->foreign('college_id')->references('id')->on('colleges')->onDelete('set null')->onUpdate('cascade');

            $table->unsignedBigInteger('office_id')->nullable(); // from form if no data found from pre-existing data
            $table->foreign('office_id')->references('id')->on('offices')->onDelete('set null')->onUpdate('cascade');

            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('employees');
    }
};
