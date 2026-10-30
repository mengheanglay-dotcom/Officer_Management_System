<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('officers', function (Blueprint $table) {
            $table->id('officer_id');                           // PK
            $table->string('officer_name');
            $table->string('email')->unique();
            $table->enum('gender', ['Male', 'Female']);
            $table->date('dob')->nullable();
            $table->string('phone', 30)->nullable();
            $table->string('position');
            $table->date('hire_date');
            $table->text('description')->nullable();
            $table->foreignId('dept_id')                         // FK -> departments.dept_id
                  ->constrained('departments', 'dept_id')
                  ->restrictOnDelete();
            $table->timestamps();
        });

        // Department manager is an officer (added after officers exists)
        Schema::table('departments', function (Blueprint $table) {
            $table->foreign('manager_id')->references('officer_id')->on('officers')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('departments', fn (Blueprint $t) => $t->dropForeign(['manager_id']));
        Schema::dropIfExists('officers');
    }
};
