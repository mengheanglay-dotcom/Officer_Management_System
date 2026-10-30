<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('officers', function (Blueprint $table) {
            $table->enum('gender', ['Male', 'Female'])->nullable()->change();
            $table->string('position')->nullable()->change();
            $table->date('hire_date')->nullable()->change();
            $table->foreignId('dept_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('officers', function (Blueprint $table) {
            $table->enum('gender', ['Male', 'Female'])->nullable(false)->change();
            $table->string('position')->nullable(false)->change();
            $table->date('hire_date')->nullable(false)->change();
            $table->foreignId('dept_id')->nullable(false)->change();
        });
    }
};
