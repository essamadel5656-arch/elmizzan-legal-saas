<?php

namespace App\Livewire\Clients;

use Livewire\Component;
use App\Models\Client;

class ClientShow extends Component
{
    public Client $client;

    public function mount(Client $client): void
    {
        $this->client = $client->load(['cases.court']);
    }

    public function render()
    {
        return view('livewire.clients.client-show', [
            'client' => $this->client,
        ])->title('ملف العميل: ' . $this->client->name . ' | ' . firm_name());
    }
}
