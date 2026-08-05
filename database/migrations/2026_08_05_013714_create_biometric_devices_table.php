<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('biometric_devices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('office_id')->nullable()->constrained('offices')->nullOnDelete()->cascadeOnUpdate();
            $table->foreignId('biometric_device_status_id')->nullable()->constrained('biometric_device_statuses')->nullOnDelete()->cascadeOnUpdate();

            $table->string('name')->unique();
            $table->string('serial')->unique();
            $table->string('model')->nullable();
            $table->unsignedSmallInteger('user_count')->default(0);
            $table->unsignedSmallInteger('fingerprint_count')->default(0);
            $table->ipAddress('ip_address')->unique();
            $table->unsignedSmallInteger('port')->default(4370);
            $table->dateTime('status_at')->default(now());

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('biometric_devices');
    }
};
