<?php

namespace App\Livewire\Documents;

use Livewire\Component;
use App\Models\Document;
use Illuminate\Support\Facades\Storage;

class DocumentIndex extends Component
{
    public string $search = '';

    public function delete(int $id): void
    {
        $user = auth()->user();
        $query = Document::query();
        if ($user && $user->role === 'lawyer') {
            $query->whereHas('case', function ($q) use ($user) {
                $q->where('lawyer_id', $user->lawyer_id);
            });
        }
        $document = $query->findOrFail($id);

        if ($document->file_path && Storage::disk('public')->exists($document->file_path)) {
            Storage::disk('public')->delete($document->file_path);
        }

        $document->delete();
        session()->flash('success', 'تم حذف المستند بنجاح!');
    }

    public function render()
    {
        $user = auth()->user();

        $documents = Document::with(['case'])
            ->when($user && $user->role === 'lawyer', function ($query) use ($user) {
                $query->whereHas('case', function ($q) use ($user) {
                    $q->where('lawyer_id', $user->lawyer_id);
                });
            })
            ->when($this->search, function ($query) {
                $query->where('title', 'like', '%' . $this->search . '%')
                    ->orWhereHas('case', function ($q) {
                        $q->where('case_number', 'like', '%' . $this->search . '%');
                    });
            })
            ->latest()
            ->get();

        return view('livewire.documents.document-index', [
            'documents' => $documents,
        ])->title('إدارة المستندات | ' . firm_name());
    }
}
