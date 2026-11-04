<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('departments', function (Blueprint $table): void {
            $table->dropForeign(['manager_id']);
        });

        Schema::table('departments', function (Blueprint $table): void {
            $table->renameColumn('location', 'floor');
            $table->renameColumn('manager_id', 'hod_id');
        });

        Schema::table('departments', function (Blueprint $table): void {
            $table->foreign('hod_id')->references('officer_id')->on('officers')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('departments', function (Blueprint $table): void {
            $table->dropForeign(['hod_id']);
        });

        Schema::table('departments', function (Blueprint $table): void {
            $table->renameColumn('floor', 'location');
            $table->renameColumn('hod_id', 'manager_id');
        });

        Schema::table('departments', function (Blueprint $table): void {
            $table->foreign('manager_id')->references('officer_id')->on('officers')->nullOnDelete();
        });
    }
};
