<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('buildings', function (Blueprint $table): void {
            $table->id();
            $table->string('name')->unique();
            $table->timestamps();
        });

        Schema::create('floors', function (Blueprint $table): void {
            $table->id();
            $table->string('name')->unique();
            $table->timestamps();
        });

        foreach (DB::table('departments')->select('building')->whereNotNull('building')->distinct()->pluck('building') as $name) {
            DB::table('buildings')->insert(['name' => $name, 'created_at' => now(), 'updated_at' => now()]);
        }
        foreach (DB::table('departments')->select('floor')->whereNotNull('floor')->distinct()->pluck('floor') as $name) {
            DB::table('floors')->insert(['name' => $name, 'created_at' => now(), 'updated_at' => now()]);
        }

        Schema::table('departments', function (Blueprint $table): void {
            $table->foreignId('building_id')->nullable()->after('dept_name')->constrained('buildings')->nullOnDelete();
            $table->foreignId('floor_id')->nullable()->after('building_id')->constrained('floors')->nullOnDelete();
        });

        foreach (DB::table('departments')->get(['dept_id', 'building', 'floor']) as $department) {
            DB::table('departments')->where('dept_id', $department->dept_id)->update([
                'building_id' => DB::table('buildings')->where('name', $department->building)->value('id'),
                'floor_id' => DB::table('floors')->where('name', $department->floor)->value('id'),
            ]);
        }
    }

    public function down(): void
    {
        Schema::table('departments', function (Blueprint $table): void {
            $table->dropForeign(['building_id']);
            $table->dropForeign(['floor_id']);
            $table->dropColumn(['building_id', 'floor_id']);
        });
        Schema::dropIfExists('floors');
        Schema::dropIfExists('buildings');
    }
};
