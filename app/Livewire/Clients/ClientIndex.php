<?php

namespace App\Livewire\Clients;

use Livewire\Component;
use App\Models\Client;

class ClientIndex extends Component
{
    public string $search = '';

    public function clearSearch(): void
    {
        $this->search = '';
    }

    public function delete(int $id): void
    {
        $client = Client::findOrFail($id);
        $this->authorize('delete', $client);

        $client->delete();
        session()->flash('success', 'تم حذف العميل بنجاح.');
    }

    public function render()
    {
        $user = auth()->user();

        $clients = Client::when($user && $user->role === 'lawyer', function ($query) use ($user) {
                $query->whereHas('cases', function ($q) use ($user) {
                    $q->where('lawyer_id', $user->lawyer_id);
                });
            })
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', "%{$this->search}%")
                      ->orWhere('phone', 'like', "%{$this->search}%")
                      ->orWhere('email', 'like', "%{$this->search}%")
                      ->orWhere('nid', 'like', "%{$this->search}%")
                      ->orWhere('address', 'like', "%{$this->search}%");
                });
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return view('livewire.clients.client-index', [
            'clients' => $clients,
        ])->title('قائمة العملاء | ' . firm_name());
    }
}
