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
        if (!Schema::hasColumn('antrians', 'uuid')) {
            Schema::table('antrians', function (Blueprint $table) {
                $table->uuid('uuid')->nullable()->after('id');
            });
        }

        // Fill existing rows with UUIDs
        $antrians = \App\Models\Antrian::whereNull('uuid')->orWhere('uuid', '')->get();
        foreach ($antrians as $antrian) {
            $antrian->uuid = (string) \Illuminate\Support\Str::uuid();
            $antrian->save();
        }

        // Now add unique constraint
        Schema::table('antrians', function (Blueprint $table) {
            $table->string('uuid')->nullable(false)->unique()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('antrians', function (Blueprint $table) {
            $table->dropColumn('uuid');
        });
    }
};
