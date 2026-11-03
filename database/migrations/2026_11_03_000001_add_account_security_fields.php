<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->string('approval_status')->default('pending')->after('role');
            $table->timestamp('approved_at')->nullable()->after('approval_status');
            $table->foreignId('approved_by')->nullable()->after('approved_at')->constrained('users')->nullOnDelete();
            $table->text('rejection_reason')->nullable()->after('approved_by');
            $table->boolean('is_active')->default(true)->after('rejection_reason');
            $table->string('avatar_path')->nullable()->after('is_active');
        });

        DB::table('users')->update(['approval_status' => 'approved']);

        Schema::table('officers', function (Blueprint $table): void {
            $table->string('gender')->nullable()->change();
            $table->string('position')->nullable()->change();
            $table->date('hire_date')->nullable()->change();
            $table->foreignId('dept_id')->nullable()->change();
            $table->string('avatar_path')->nullable()->after('description');
        });
    }

    public function down(): void
    {
        Schema::table('officers', function (Blueprint $table): void {
            $table->dropColumn('avatar_path');
        });

        Schema::table('users', function (Blueprint $table): void {
            $table->dropConstrainedForeignId('approved_by');
            $table->dropColumn([
                'approval_status', 'approved_at', 'rejection_reason',
                'is_active', 'avatar_path',
            ]);
        });
    }
};
