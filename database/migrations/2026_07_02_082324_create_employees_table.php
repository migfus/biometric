<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->string('id')->primary()->comment('employee_no');

            $table->foreignId('office_id')->nullable()->constrained('offices')->nullOnDelete()->cascadeOnUpdate();
            $table->foreignId('employment_type_id')->nullable()->constrained('employment_types')->nullOnDelete()->cascadeOnUpdate();

            $table->string('full_name')->nullable(); // from form if no data found from pre-existing data

            $table->string('email')->nullable();
            $table->string('phone')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
