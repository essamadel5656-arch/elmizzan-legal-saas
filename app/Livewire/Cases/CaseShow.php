<?php

namespace App\Livewire\Cases;

use Livewire\Component;
use App\Models\LegalCase;

class CaseShow extends Component
{
    public LegalCase $case;
    public string $activeTab = 'tasks';
    public bool $confirmingDelete = false;

    // Media Viewer State
    public bool $isMediaViewerOpen = false;
    public array $mediaGallery = [];
    public int $currentMediaIndex = 0;

    public function mount(LegalCase $case): void
    {
        $this->case = $case->load([
            'clients',
            'court.jurisdiction',
            'courtLevel',
            'lawyers',
            'appointments',
            'documents',
        ]);

        $this->authorize('view', $this->case);
    }

    public function setTab(string $tab): void
    {
        if (in_array($tab, ['tasks', 'documents', 'expenses'])) {
            $this->activeTab = $tab;
        }
    }

    public function updateStatus(string $newStatus): void
    {
        $this->authorize('update', $this->case);

        $allowed = ['مفتوحة', 'متداولة', 'مؤجلة', 'محجوزة للحكم', 'منتهية', 'مستأنفة', 'محفوظة', 'معلقة'];
        if (in_array($newStatus, $allowed)) {
            $this->case->status = $newStatus;
            $this->case->save();
            session()->flash('success', 'تم تحديث حالة القضية إلى: ' . $newStatus);
        }
    }

    public function confirmDelete(): void
    {
        $this->confirmingDelete = true;
    }

    public function cancelDelete(): void
    {
        $this->confirmingDelete = false;
    }

    public function deleteCase(): mixed
    {
        $this->authorize('delete', $this->case);
        $this->case->delete();
        session()->flash('success', __('تم حذف القضية بنجاح'));
        return $this->redirect(route('cases.index'), navigate: true);
    }

    public function loadMediaGallery(): void
    {
        $this->mediaGallery = [];

        if ($this->case->case_file && \Illuminate\Support\Facades\Storage::disk('public')->exists($this->case->case_file)) {
            $this->mediaGallery[] = [
                'id' => 'main_file',
                'url' => asset('storage/' . $this->case->case_file),
                'path' => $this->case->case_file,
                'type' => $this->getFileType($this->case->case_file),
                'title' => __('ملف القضية الرئيسي'),
                'is_main_file' => true,
            ];
        }

        if ($this->case->relationLoaded('documents')) {
            foreach ($this->case->documents as $doc) {
                if ($doc->file_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($doc->file_path)) {
                    $this->mediaGallery[] = [
                        'id' => $doc->id,
                        'url' => asset('storage/' . $doc->file_path),
                        'path' => $doc->file_path,
                        'type' => $this->getFileType($doc->file_path),
                        'title' => $doc->title ?? __('مستند إضافي'),
                        'is_main_file' => false,
                    ];
                }
            }
        }
    }

    private function getFileType($path): string
    {
        $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'svg', 'webp'])) {
            return 'image';
        }
        if ($ext === 'pdf') {
            return 'pdf';
        }
        if (in_array($ext, ['doc', 'docx'])) {
            return 'word';
        }
        return 'other';
    }

    public function openMediaViewer($index = 0): void
    {
        $this->loadMediaGallery();
        if (count($this->mediaGallery) > 0) {
            $this->currentMediaIndex = max(0, min($index, count($this->mediaGallery) - 1));
            $this->isMediaViewerOpen = true;
        }
    }

    public function nextMedia(): void
    {
        if ($this->currentMediaIndex < count($this->mediaGallery) - 1) {
            $this->currentMediaIndex++;
        }
    }

    public function prevMedia(): void
    {
        if ($this->currentMediaIndex > 0) {
            $this->currentMediaIndex--;
        }
    }

    public function closeMediaViewer(): void
    {
        $this->isMediaViewerOpen = false;
    }

    public function render()
    {
        return view('livewire.cases.case-show', [
            'case' => $this->case,
        ])->title('تفاصيل القضية - ' . $this->case->case_number . ' | ' . firm_name());
    }
}
