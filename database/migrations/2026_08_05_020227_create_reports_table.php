<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->string('employee_id')->nullable();
            $table->foreign('employee_id')->references('id')->on('employees')->nullOnDelete()->cascadeOnUpdate();
            $table->foreignId('biometric_device_id')->constrained('biometric_devices')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('report_type_id')->constrained('report_types')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('check_status_id')->constrained('check_statuses')->cascadeOnDelete()->cascadeOnUpdate();

            $table->string('browser_id');
            $table->string('ip_address')->nullable();
            $table->string('os')->nullable();
            $table->longText('description');
            $table->longText('action_taken')->nullable();
            $table->unsignedTinyInteger('rephrase_count')->default(0);

            $table->timestamps(); // incident_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
