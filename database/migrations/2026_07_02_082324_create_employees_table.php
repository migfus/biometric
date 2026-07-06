<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->string('id')->primary()->comment('employee_no');

            $table->string('full_name')->nullable(); // from form if no data found from pre-existing data

            $table->string('last_name')->nullable(); // if found data
            $table->string('first_name')->nullable();
            $table->string('middle_name')->nullable();

            $table->unsignedBigInteger('college_id')->nullable();
            $table->foreign('college_id')->references('id')->on('colleges')->onDelete('set null')->onUpdate('cascade');

            $table->unsignedBigInteger('department_id'); // from form if no data found from pre-existing data
            $table->foreign('department_id')->references('id')->on('departments')->onDelete('restrict')->onUpdate('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
