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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->foreignId('departement_id')
                  ->nullable()
                  ->constrained('departements')
                  ->nullOnDelete();
            $table->foreignId('office_location_id')
                  ->nullable()
                  ->constrained('office_locations')
                  ->nullOnDelete();
            $table->foreignId('work_schedule_id')
                  ->nullable()
                  ->constrained('work_schedules')
                  ->nullOnDelete();
            $table->string('phone_number')->nullable();
            $table->text('address')->nullable();
            $table->string('position')->nullable(); // jabatan
            $table->string('employee_id')->unique()->nullable(); // nomor induk pegawai
            $table->string('photo')->nullable(); // Tambahkan kolom foto
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes(); // untuk fitur soft delete
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
