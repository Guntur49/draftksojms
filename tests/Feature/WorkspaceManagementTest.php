<?php

namespace Tests\Feature;

use App\Models\AdministrationLetter;
use App\Models\Hospital;
use App\Models\InternalLetter;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class WorkspaceManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_create_and_delete_a_hospital_without_documents(): void
    {
        $this->actingAs(User::factory()->create());

        $this->post(route('hospitals.store'), [
            'name' => 'RS Pusat JMS',
            'city' => 'Jakarta',
            'email' => 'kerjasama@rspusat.id',
        ])->assertRedirect(route('workspace.index', ['section' => 'hospitals']));

        $hospital = Hospital::firstOrFail();
        $this->assertSame('Jakarta', $hospital->city);

        $this->delete(route('hospitals.destroy', $hospital))
            ->assertRedirect(route('workspace.index', ['section' => 'hospitals']));

        $this->assertDatabaseMissing('hospitals', ['id' => $hospital->id]);
    }

    public function test_hospital_with_letters_cannot_be_deleted(): void
    {
        $user = User::factory()->create();
        $hospital = Hospital::factory()->create();
        $letter = AdministrationLetter::factory()->create(['hospital_id' => $hospital->id]);

        $this->actingAs($user)
            ->delete(route('hospitals.destroy', $hospital))
            ->assertSessionHas('error');

        $this->assertModelExists($hospital);
        $this->assertModelExists($letter);
    }

    public function test_user_can_upload_a_pdf_and_download_it_after_creation(): void
    {
        Storage::fake('local');
        $user = User::factory()->create();
        $hospital = Hospital::factory()->create();

        $this->actingAs($user)->post(route('administration.store'), [
            'hospital_id' => $hospital->id,
            'checklist_item_id' => $hospital->checklistItems()->create([
                'document_name' => 'Surat Perjanjian Kerja Sama',
                'sort_order' => 1,
            ])->id,
            'document_number' => 'JMS/PKS/2026/0001',
            'status' => 'review',
            'pdf_file' => UploadedFile::fake()->create('perjanjian.pdf', 32, 'application/pdf'),
        ])->assertRedirect(route('workspace.index', ['section' => 'administration']));

        $letter = AdministrationLetter::firstOrFail();
        $this->assertSame('Surat Perjanjian Kerja Sama', $letter->document_name);
        $this->assertDatabaseHas('hospital_checklist_items', [
            'id' => $letter->checklist_item_id,
            'is_available' => true,
        ]);
        $this->assertNull($letter->master_letter_id);
        $this->assertNotNull($letter->pdf_path);
        Storage::disk('local')->assertExists($letter->pdf_path);

        $this->get(route('administration.pdf', $letter))
            ->assertDownload('document-'.$letter->id.'.pdf');

        $this->get(route('workspace.index', [
            'section' => 'completeness',
            'hospital_id' => $hospital->id,
        ]))
            ->assertSee('Ada')
            ->assertSee('Lihat surat')
            ->assertSee(route('administration.view', $letter));
    }

    public function test_administration_letter_requires_a_pdf_upload(): void
    {
        Storage::fake('local');
        $this->actingAs(User::factory()->create());
        $hospital = Hospital::factory()->create();
        $checklistItem = $hospital->checklistItems()->create([
            'document_name' => 'Surat Uji Coba',
            'sort_order' => 1,
        ]);

        $this->from(route('workspace.index', ['section' => 'administration']))
            ->post(route('administration.store'), [
                'hospital_id' => $hospital->id,
                'checklist_item_id' => $checklistItem->id,
                'document_number' => 'JMS/PKS/2026/0002',
                'status' => 'draft',
                'pdf_file' => UploadedFile::fake()->create('surat.txt', 2, 'text/plain'),
            ])
            ->assertSessionHasErrors('pdf_file');

        $this->assertDatabaseCount('administration_letters', 0);
        Storage::disk('local')->assertDirectoryEmpty('administration-letters');
    }

    public function test_user_can_view_replace_and_delete_a_letter_pdf(): void
    {
        Storage::fake('local');
        $user = User::factory()->create();
        $letter = AdministrationLetter::factory()->create([
            'document_name' => 'Surat Awal',
            'document_number' => 'JMS/PKS/2026/0100',
            'pdf_path' => 'administration-letters/original.pdf',
        ]);
        Storage::disk('local')->put($letter->pdf_path, '%PDF-1.4');

        $this->actingAs($user)
            ->get(route('administration.view', $letter))
            ->assertOk();

        $this->actingAs($user)
            ->put(route('administration.update', $letter), [
                'document_name' => 'Surat Baru',
                'document_number' => 'JMS/PKS/2026/0101',
                'status' => 'review',
                'pdf_file' => UploadedFile::fake()->create('replacement.pdf', 12, 'application/pdf'),
            ])
            ->assertRedirect(route('workspace.index', ['section' => 'administration']));

        $letter->refresh();
        $this->assertSame('Surat Baru', $letter->document_name);
        $this->assertSame('JMS/PKS/2026/0101', $letter->document_number);
        Storage::disk('local')->assertExists($letter->pdf_path);

        $this->actingAs($user)
            ->delete(route('administration.destroy', $letter))
            ->assertRedirect(route('workspace.index', ['section' => 'administration']));

        $this->assertDatabaseMissing('administration_letters', ['id' => $letter->id]);
    }

    public function test_pdf_download_requires_authentication(): void
    {
        Storage::fake('local');
        $letter = AdministrationLetter::factory()->create([
            'pdf_path' => 'administration-letters/private.pdf',
        ]);
        Storage::disk('local')->put($letter->pdf_path, '%PDF-1.4');

        $this->get(route('administration.pdf', $letter))
            ->assertRedirect(route('login'));
    }

    public function test_workspace_sections_display_hospitals_and_letters(): void
    {
        $this->actingAs(User::factory()->create());
        $hospital = Hospital::factory()->create(['name' => 'RS Contoh']);
        AdministrationLetter::factory()->create([
            'hospital_id' => $hospital->id,
            'pdf_path' => 'administration-letters/letter.pdf',
        ]);

        $this->get(route('workspace.index', ['section' => 'hospitals']))->assertSee('RS Contoh');
        $this->get(route('workspace.index', ['section' => 'administration']))
            ->assertSee('JMS/PKS/2026/')
            ->assertSee('Unggah PDF')
            ->assertSee(route('administration.pdf', AdministrationLetter::firstOrFail()));
    }

    public function test_updated_at_is_displayed_in_jakarta_timezone_for_both_letter_sections(): void
    {
        $user = User::factory()->create();
        $hospital = Hospital::factory()->create();
        $administrationLetter = AdministrationLetter::factory()->create([
            'hospital_id' => $hospital->id,
        ]);
        $internalLetter = InternalLetter::create([
            'document_name' => 'Memo Internal',
            'document_number' => 'JMS/INT/2026/0001',
            'status' => 'draft',
        ]);

        DB::table('administration_letters')
            ->where('id', $administrationLetter->id)
            ->update(['updated_at' => '2026-10-12 06:58:00']);
        DB::table('internal_letters')
            ->where('id', $internalLetter->id)
            ->update(['updated_at' => '2026-10-12 06:58:00']);

        $this->actingAs($user)
            ->get(route('workspace.index', ['section' => 'administration']))
            ->assertSee('Terakhir diperbarui: 12 Oct 2026 · 13:58');

        $this->get(route('workspace.index', ['section' => 'internal']))
            ->assertSee('Terakhir diperbarui: 12 Oct 2026 · 13:58');
    }

    public function test_new_hospital_receives_the_starter_checklist(): void
    {
        $this->actingAs(User::factory()->create())
            ->post(route('hospitals.store'), [
                'name' => 'RS Baru',
                'city' => 'Jakarta',
            ])
            ->assertRedirect(route('workspace.index', ['section' => 'hospitals']));

        $hospital = Hospital::query()->where('name', 'RS Baru')->firstOrFail();

        $this->assertSame(12, $hospital->checklistItems()->count());
        $this->assertDatabaseHas('hospital_checklist_items', [
            'hospital_id' => $hospital->id,
            'document_name' => 'Legalitas Rumah Sakit',
        ]);
    }

    public function test_checklist_is_separate_per_hospital_and_items_can_be_added_updated_and_deleted(): void
    {
        $user = User::factory()->create();
        $hospital = Hospital::factory()->create(['name' => 'RS A']);
        $otherHospital = Hospital::factory()->create(['name' => 'RS B']);

        $this->actingAs($user)
            ->post(route('checklist-items.store', $hospital), [
                'document_name' => 'Dokumen Tambahan',
            ])
            ->assertRedirect(route('workspace.index', ['section' => 'completeness', 'hospital_id' => $hospital->id]));

        $item = $hospital->checklistItems()->where('document_name', 'Dokumen Tambahan')->firstOrFail();
        $this->assertDatabaseMissing('hospital_checklist_items', [
            'hospital_id' => $otherHospital->id,
            'document_name' => 'Dokumen Tambahan',
        ]);

        $this->get(route('workspace.index', [
            'section' => 'completeness',
            'hospital_id' => $hospital->id,
        ]))
            ->assertSee('Kelengkapan Surat')
            ->assertSee('Dokumen Tambahan')
            ->assertSee('KETERANGAN');

        $this->put(route('checklist-items.update', $item), [
            'is_available' => '1',
            'notes' => 'Diterima tanggal 12 Oktober',
        ])->assertRedirect(route('workspace.index', ['section' => 'completeness', 'hospital_id' => $hospital->id]));

        $this->assertDatabaseHas('hospital_checklist_items', [
            'id' => $item->id,
            'is_available' => false,
            'notes' => 'Diterima tanggal 12 Oktober',
        ]);

        $this->get(route('workspace.index', [
            'section' => 'completeness',
            'hospital_id' => $hospital->id,
        ]))
            ->assertSee('checklist-indicator')
            ->assertDontSee('type="radio"');

        $this->delete(route('checklist-items.destroy', $item))
            ->assertRedirect(route('workspace.index', ['section' => 'completeness', 'hospital_id' => $hospital->id]));

        $this->assertDatabaseMissing('hospital_checklist_items', ['id' => $item->id]);
    }

    public function test_administration_upload_requires_a_checklist_item_from_the_selected_hospital(): void
    {
        Storage::fake('local');
        $user = User::factory()->create();
        $hospital = Hospital::factory()->create();
        $otherHospital = Hospital::factory()->create();
        $otherHospitalItem = $otherHospital->checklistItems()->create([
            'document_name' => 'Surat Rumah Sakit Lain',
            'sort_order' => 1,
        ]);

        $this->actingAs($user)
            ->from(route('workspace.index', ['section' => 'administration']))
            ->post(route('administration.store'), [
                'hospital_id' => $hospital->id,
                'checklist_item_id' => $otherHospitalItem->id,
                'document_number' => 'JMS/PKS/2026/0999',
                'status' => 'draft',
                'pdf_file' => UploadedFile::fake()->create('surat.pdf', 32, 'application/pdf'),
            ])
            ->assertSessionHasErrors('checklist_item_id');

        $this->assertDatabaseCount('administration_letters', 0);
        Storage::disk('local')->assertDirectoryEmpty('administration-letters');
    }

    public function test_uploaded_checklist_documents_are_hidden_from_upload_and_cannot_be_uploaded_again(): void
    {
        Storage::fake('local');
        $user = User::factory()->create();
        $hospital = Hospital::factory()->create();
        $uploadedItem = $hospital->checklistItems()->create([
            'document_name' => 'Surat Sudah Diunggah',
            'sort_order' => 1,
        ]);
        $availableItem = $hospital->checklistItems()->create([
            'document_name' => 'Surat Belum Diunggah',
            'sort_order' => 2,
        ]);
        AdministrationLetter::factory()->create([
            'hospital_id' => $hospital->id,
            'checklist_item_id' => $uploadedItem->id,
            'document_name' => $uploadedItem->document_name,
            'pdf_path' => 'administration-letters/uploaded.pdf',
        ]);

        $response = $this->actingAs($user)->get(route('workspace.index', ['section' => 'administration']));

        $this->assertSame(
            [$availableItem->id],
            $response->viewData('checklistItems')->pluck('id')->all(),
        );
        $response->assertSee('id="administration-checklist-select" required disabled', false)
            ->assertSee('Pilih rumah sakit terlebih dahulu');

        $this->from(route('workspace.index', ['section' => 'administration']))
            ->post(route('administration.store'), [
                'hospital_id' => $hospital->id,
                'checklist_item_id' => $uploadedItem->id,
                'document_number' => 'JMS/PKS/2026/0998',
                'status' => 'draft',
                'pdf_file' => UploadedFile::fake()->create('surat-duplikat.pdf', 32, 'application/pdf'),
            ])
            ->assertSessionHasErrors('checklist_item_id');

        $this->assertDatabaseCount('administration_letters', 1);
        Storage::disk('local')->assertDirectoryEmpty('administration-letters');
    }
}
