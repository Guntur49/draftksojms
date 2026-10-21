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
        Schema::create('hospital_checklist_items', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('hospital_id')->constrained()->cascadeOnDelete();
            $table->string('document_name', 500);
            $table->boolean('is_available')->default(false);
            $table->text('notes')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->unique(['hospital_id', 'document_name']);
        });

        $now = now();
        $starterDocuments = [
            'Legalitas NMS - Akta perubahan terbaru',
            'Surat Penunjukan NMS kepada Legal Officer',
            'Surat Penunjukan NMS kepada JMS',
            'Legalitas JMS - Akta perubahan terbaru',
            'Surat Penunjukan JMS kepada Marketing',
            'Surat Penawaran',
            'Purchase Order (PO)',
            'Feasibility Study (FS) - data waiting list pasien, data dari Dinas Kesehatan dan/atau BPJS, dan data pendukung lain',
            'Layout penempatan/perencanaan yang telah ditandatangani atau disetujui oleh pihak rumah sakit',
            'Perjanjian KSO asli yang telah ditandatangani para pihak, berikut seluruh lampiran',
            'Daftar nama perawat/tenaga terkait KSO',
            'Legalitas Rumah Sakit',
        ];

        DB::table('hospitals')->orderBy('id')->chunk(100, function ($hospitals) use ($now, $starterDocuments): void {
            foreach ($hospitals as $hospital) {
                $items = [];

                foreach ($starterDocuments as $sortOrder => $documentName) {
                    $items[] = [
                        'hospital_id' => $hospital->id,
                        'document_name' => $documentName,
                        'is_available' => false,
                        'sort_order' => $sortOrder + 1,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }

                DB::table('hospital_checklist_items')->insert($items);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hospital_checklist_items');
    }
};
