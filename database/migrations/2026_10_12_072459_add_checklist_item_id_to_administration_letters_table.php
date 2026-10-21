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
        Schema::table('administration_letters', function (Blueprint $table): void {
            $table->foreignId('checklist_item_id')
                ->nullable()
                ->after('hospital_id')
                ->constrained('hospital_checklist_items')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('administration_letters', function (Blueprint $table): void {
            $table->dropConstrainedForeignId('checklist_item_id');
        });
    }
};
