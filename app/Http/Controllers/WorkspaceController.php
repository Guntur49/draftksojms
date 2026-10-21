<?php

namespace App\Http\Controllers;

use App\Models\AdministrationLetter;
use App\Models\Hospital;
use App\Models\HospitalChecklistItem;
use App\Models\InternalLetter;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Throwable;

class WorkspaceController extends Controller
{
    public function index(Request $request): View
    {
        $section = $request->string('section')->toString();
        $section = in_array($section, ['overview', 'hospitals', 'administration', 'completeness', 'internal'], true) ? $section : 'overview';
        $search = $request->string('search')->trim()->toString();

        $hospitals = Hospital::query()
            ->withCount('administrationLetters')
            ->when($search !== '', fn ($query) => $query->where(function ($query) use ($search): void {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('city', 'like', "%{$search}%");
            }))
            ->orderBy('name')
            ->get();

        $letters = AdministrationLetter::query()
            ->with('hospital')
            ->when($request->filled('hospital_id'), fn ($query) => $query->where('hospital_id', $request->integer('hospital_id')))
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->string('status')->toString()))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $internalLetters = InternalLetter::query()
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $checklistHospital = $request->filled('hospital_id')
            ? Hospital::query()->find($request->integer('hospital_id'))
            : null;

        return view('workspace.dashboard', [
            'section' => $section,
            'search' => $search,
            'hospitals' => $hospitals,
            'letters' => $letters,
            'internalLetters' => $internalLetters,
            'checklistHospital' => $checklistHospital,
            'checklistDocuments' => $checklistHospital?->checklistItems()
                ->with('administrationLetters')
                ->get() ?? collect(),
            'checklistItems' => HospitalChecklistItem::query()
                ->whereDoesntHave('administrationLetters')
                ->orderBy('hospital_id')
                ->orderBy('sort_order')
                ->get(['id', 'hospital_id', 'document_name']),
            'recentLetters' => AdministrationLetter::query()->with('hospital')->latest()->take(5)->get(),
            'hospitalCount' => Hospital::count(),
            'draftCount' => AdministrationLetter::where('status', 'draft')->count(),
            'reviewCount' => AdministrationLetter::where('status', 'review')->count(),
            'pdfCount' => AdministrationLetter::whereNotNull('pdf_path')->count(),
        ]);
    }

    public function storeHospital(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:180'],
            'city' => ['nullable', 'string', 'max:120'],
            'contact_person' => ['nullable', 'string', 'max:120'],
            'email' => ['nullable', 'email', 'max:180'],
            'phone' => ['nullable', 'string', 'max:40'],
            'address' => ['nullable', 'string', 'max:1000'],
        ]);

        DB::transaction(function () use ($validated): void {
            $hospital = Hospital::create($validated);
            $hospital->checklistItems()->createMany(
                collect(HospitalChecklistItem::STARTER_DOCUMENTS)
                    ->values()
                    ->map(fn (string $documentName, int $index): array => [
                        'document_name' => $documentName,
                        'is_available' => false,
                        'sort_order' => $index + 1,
                    ])
                    ->all(),
            );
        });

        return redirect()->route('workspace.index', ['section' => 'hospitals'])->with('success', 'Rumah sakit berhasil ditambahkan.');
    }

    public function destroyHospital(Hospital $hospital): RedirectResponse
    {
        if ($hospital->administrationLetters()->exists()) {
            return back()->with('error', 'Rumah sakit masih memiliki surat administrasi. Hapus atau arsipkan suratnya terlebih dahulu.');
        }

        $hospital->delete();

        return redirect()->route('workspace.index', ['section' => 'hospitals'])->with('success', 'Rumah sakit berhasil dihapus.');
    }

    public function storeAdministrationLetter(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'hospital_id' => ['required', 'exists:hospitals,id'],
            'checklist_item_id' => [
                'required',
                Rule::exists('hospital_checklist_items', 'id')
                    ->where(function ($query) use ($request): void {
                        $query->where('hospital_id', $request->input('hospital_id'))
                            ->whereNotExists(function ($subquery): void {
                                $subquery->selectRaw('1')
                                    ->from('administration_letters')
                                    ->whereColumn('administration_letters.checklist_item_id', 'hospital_checklist_items.id')
                                    ->whereNotNull('pdf_path');
                            });
                    }),
            ],
            'document_number' => ['required', 'string', 'max:100', 'unique:administration_letters,document_number'],
            'status' => ['required', Rule::in(['draft', 'review', 'final'])],
            'pdf_file' => ['required', 'file', 'mimes:pdf', 'extensions:pdf', 'max:20480'],
        ], [
            'pdf_file.required' => 'Silakan unggah file PDF surat.',
            'pdf_file.mimes' => 'File surat harus berupa PDF.',
            'pdf_file.extensions' => 'File surat harus memiliki ekstensi .pdf.',
            'pdf_file.max' => 'Ukuran PDF maksimal 20 MB.',
        ]);

        $checklistItem = HospitalChecklistItem::query()
            ->where('hospital_id', $validated['hospital_id'])
            ->findOrFail($validated['checklist_item_id']);

        $pdfPath = $validated['pdf_file']->store('administration-letters', 'local');

        if ($pdfPath === false) {
            return back()->withInput()->with('error', 'PDF gagal disimpan. Silakan coba kembali.');
        }

        try {
            DB::transaction(function () use ($validated, $checklistItem, $pdfPath): void {
                AdministrationLetter::create([
                    'hospital_id' => $validated['hospital_id'],
                    'checklist_item_id' => $checklistItem->id,
                    'document_name' => $checklistItem->document_name,
                    'document_number' => $validated['document_number'],
                    'status' => $validated['status'],
                    'pdf_path' => $pdfPath,
                ]);

                $checklistItem->update(['is_available' => true]);
            });
        } catch (Throwable $exception) {
            Storage::disk('local')->delete($pdfPath);

            throw $exception;
        }

        return redirect()->route('workspace.index', ['section' => 'administration'])->with('success', 'PDF surat berhasil diunggah.');
    }

    public function downloadAdministrationLetterPdf(AdministrationLetter $administrationLetter): StreamedResponse
    {
        $disk = Storage::disk('local');

        abort_unless(
            $administrationLetter->pdf_path !== null
                && $disk instanceof FilesystemAdapter
                && $disk->exists($administrationLetter->pdf_path),
            404,
        );

        return $disk->download(
            $administrationLetter->pdf_path,
            'document-'.$administrationLetter->id.'.pdf',
            ['Content-Type' => 'application/pdf'],
        );
    }

    public function viewAdministrationLetterPdf(AdministrationLetter $administrationLetter)
    {
        $disk = Storage::disk('local');

        abort_unless(
            $administrationLetter->pdf_path !== null
                && $disk instanceof FilesystemAdapter
                && $disk->exists($administrationLetter->pdf_path),
            404,
        );

        return $disk->response(
            $administrationLetter->pdf_path,
            null,
            ['Content-Type' => 'application/pdf'],
        );
    }

    public function updateAdministrationLetter(Request $request, AdministrationLetter $administrationLetter): RedirectResponse
    {
        $validated = $request->validate([
            'document_name' => ['required', 'string', 'max:200'],
            'document_number' => ['required', 'string', 'max:100', Rule::unique('administration_letters', 'document_number')->ignore($administrationLetter->id)],
            'status' => ['required', Rule::in(['draft', 'review', 'final'])],
            'pdf_file' => ['nullable', 'file', 'mimes:pdf', 'extensions:pdf', 'max:20480'],
        ], [
            'pdf_file.mimes' => 'File surat harus berupa PDF.',
            'pdf_file.extensions' => 'File surat harus memiliki ekstensi .pdf.',
            'pdf_file.max' => 'Ukuran PDF maksimal 20 MB.',
        ]);

        $disk = Storage::disk('local');

        if ($request->hasFile('pdf_file')) {
            $newPdfPath = $request->file('pdf_file')->store('administration-letters', 'local');

            if ($newPdfPath === false) {
                return back()->with('error', 'PDF gagal diperbarui. Silakan coba kembali.');
            }

            $oldPdfPath = $administrationLetter->pdf_path;
            $administrationLetter->pdf_path = $newPdfPath;
        } else {
            $oldPdfPath = null;
        }

        $administrationLetter->document_name = $validated['document_name'];
        $administrationLetter->document_number = $validated['document_number'];
        $administrationLetter->status = $validated['status'];

        try {
            DB::transaction(function () use ($administrationLetter, $request): void {
                $administrationLetter->save();

                if ($request->hasFile('pdf_file')) {
                    $administrationLetter->checklistItem?->update(['is_available' => true]);
                }
            });
        } catch (Throwable $exception) {
            if (isset($newPdfPath) && $newPdfPath !== false) {
                $disk->delete($newPdfPath);
            }

            throw $exception;
        }

        if ($oldPdfPath !== null && $disk->exists($oldPdfPath)) {
            $disk->delete($oldPdfPath);
        }

        return redirect()->route('workspace.index', ['section' => 'administration'])->with('success', 'Surat berhasil diperbarui.');
    }

    public function destroyAdministrationLetter(AdministrationLetter $administrationLetter): RedirectResponse
    {
        $disk = Storage::disk('local');
        $checklistItem = $administrationLetter->checklistItem;

        if ($administrationLetter->pdf_path !== null && $disk->exists($administrationLetter->pdf_path)) {
            $disk->delete($administrationLetter->pdf_path);
        }

        $administrationLetter->delete();

        if ($checklistItem !== null && ! $checklistItem->hospital->administrationLetters()->where('checklist_item_id', $checklistItem->id)->whereNotNull('pdf_path')->exists()) {
            $checklistItem->update(['is_available' => false]);
        }

        return redirect()->route('workspace.index', ['section' => 'administration'])->with('success', 'Surat berhasil dihapus.');
    }

    public function storeChecklistItem(Request $request, Hospital $hospital): RedirectResponse
    {
        $validated = $request->validate([
            'document_name' => [
                'required',
                'string',
                'max:200',
                Rule::unique('hospital_checklist_items', 'document_name')->where('hospital_id', $hospital->id),
            ],
        ]);

        $hospital->checklistItems()->create([
            'document_name' => $validated['document_name'],
            'is_available' => false,
            'sort_order' => (int) $hospital->checklistItems()->max('sort_order') + 1,
        ]);

        return redirect()->route('workspace.index', ['section' => 'completeness', 'hospital_id' => $hospital->id])
            ->with('success', 'Jenis dokumen berhasil ditambahkan.');
    }

    public function updateChecklistItem(Request $request, HospitalChecklistItem $checklistItem): RedirectResponse
    {
        $validated = $request->validate([
            'notes' => ['nullable', 'string', 'max:2000'],
        ]);

        $checklistItem->update(['notes' => $validated['notes'] ?? null]);

        return redirect()->route('workspace.index', ['section' => 'completeness', 'hospital_id' => $checklistItem->hospital_id])
            ->with('success', 'Kelengkapan surat berhasil diperbarui.');
    }

    public function destroyChecklistItem(HospitalChecklistItem $checklistItem): RedirectResponse
    {
        $hospitalId = $checklistItem->hospital_id;
        $checklistItem->delete();

        return redirect()->route('workspace.index', ['section' => 'completeness', 'hospital_id' => $hospitalId])
            ->with('success', 'Jenis dokumen berhasil dihapus.');
    }

    public function storeInternalLetter(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'document_name' => ['required', 'string', 'max:200'],
            'document_number' => ['required', 'string', 'max:100', 'unique:internal_letters,document_number'],
            'status' => ['required', Rule::in(['draft', 'review', 'final'])],
            'pdf_file' => ['required', 'file', 'mimes:pdf', 'extensions:pdf', 'max:20480'],
        ], [
            'pdf_file.required' => 'Silakan unggah file PDF surat internal.',
            'pdf_file.mimes' => 'File surat internal harus berupa PDF.',
            'pdf_file.extensions' => 'File surat internal harus memiliki ekstensi .pdf.',
            'pdf_file.max' => 'Ukuran PDF maksimal 20 MB.',
        ]);

        $pdfPath = $validated['pdf_file']->store('internal-letters', 'local');

        if ($pdfPath === false) {
            return back()->withInput()->with('error', 'PDF gagal disimpan. Silakan coba kembali.');
        }

        try {
            InternalLetter::create([
                'document_name' => $validated['document_name'],
                'document_number' => $validated['document_number'],
                'status' => $validated['status'],
                'pdf_path' => $pdfPath,
            ]);
        } catch (Throwable $exception) {
            Storage::disk('local')->delete($pdfPath);
            throw $exception;
        }

        return redirect()->route('workspace.index', ['section' => 'internal'])->with('success', 'Surat internal berhasil diunggah.');
    }

    public function updateInternalLetter(Request $request, InternalLetter $internalLetter): RedirectResponse
    {
        $validated = $request->validate([
            'document_name' => ['required', 'string', 'max:200'],
            'document_number' => ['required', 'string', 'max:100', Rule::unique('internal_letters', 'document_number')->ignore($internalLetter->id)],
            'status' => ['required', Rule::in(['draft', 'review', 'final'])],
            'pdf_file' => ['nullable', 'file', 'mimes:pdf', 'extensions:pdf', 'max:20480'],
        ], [
            'pdf_file.mimes' => 'File surat internal harus berupa PDF.',
            'pdf_file.extensions' => 'File surat internal harus memiliki ekstensi .pdf.',
            'pdf_file.max' => 'Ukuran PDF maksimal 20 MB.',
        ]);

        $disk = Storage::disk('local');

        if ($request->hasFile('pdf_file')) {
            $oldPdfPath = $internalLetter->pdf_path;
            $newPdfPath = $request->file('pdf_file')->store('internal-letters', 'local');

            if ($newPdfPath === false) {
                return back()->with('error', 'PDF gagal diperbarui. Silakan coba kembali.');
            }

            $internalLetter->pdf_path = $newPdfPath;

            if ($oldPdfPath !== null && $disk->exists($oldPdfPath)) {
                $disk->delete($oldPdfPath);
            }
        }

        $internalLetter->document_name = $validated['document_name'];
        $internalLetter->document_number = $validated['document_number'];
        $internalLetter->status = $validated['status'];
        $internalLetter->save();

        return redirect()->route('workspace.index', ['section' => 'internal'])->with('success', 'Surat internal berhasil diperbarui.');
    }

    public function destroyInternalLetter(InternalLetter $internalLetter): RedirectResponse
    {
        $disk = Storage::disk('local');

        if ($internalLetter->pdf_path !== null && $disk->exists($internalLetter->pdf_path)) {
            $disk->delete($internalLetter->pdf_path);
        }

        $internalLetter->delete();

        return redirect()->route('workspace.index', ['section' => 'internal'])->with('success', 'Surat internal berhasil dihapus.');
    }

    public function downloadInternalLetterPdf(InternalLetter $internalLetter): StreamedResponse
    {
        $disk = Storage::disk('local');

        abort_unless(
            $internalLetter->pdf_path !== null
                && $disk instanceof FilesystemAdapter
                && $disk->exists($internalLetter->pdf_path),
            404,
        );

        return $disk->download(
            $internalLetter->pdf_path,
            'internal-document-'.$internalLetter->id.'.pdf',
            ['Content-Type' => 'application/pdf'],
        );
    }

    public function viewInternalLetterPdf(InternalLetter $internalLetter)
    {
        $disk = Storage::disk('local');

        abort_unless(
            $internalLetter->pdf_path !== null
                && $disk instanceof FilesystemAdapter
                && $disk->exists($internalLetter->pdf_path),
            404,
        );

        return $disk->response(
            $internalLetter->pdf_path,
            null,
            ['Content-Type' => 'application/pdf'],
        );
    }
}
