<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('administration_letters', function (Blueprint $table): void {
            $table->foreignId('master_letter_id')->nullable()->change();
            $table->json('variables')->nullable()->change();
            $table->longText('content_snapshot')->nullable()->change();
            $table->string('pdf_path')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::table('administration_letters')->whereNull('master_letter_id')->exists()) {
            throw new RuntimeException('Cannot roll back PDF uploads while uploaded-only letters exist.');
        }

        Schema::table('administration_letters', function (Blueprint $table): void {
            $table->dropColumn('pdf_path');
            $table->foreignId('master_letter_id')->nullable(false)->change();
            $table->json('variables')->nullable(false)->change();
            $table->longText('content_snapshot')->nullable(false)->change();
        });
    }
};
