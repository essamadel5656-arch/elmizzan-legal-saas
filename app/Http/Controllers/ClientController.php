<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\User;
use App\Mail\ClientWelcomeMail;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Lawyer;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource with search functionality.
     * Filters clients by name, phone, email, nid, or address based on search query.
     */
public function index(Request $request)
{
    $search = $request->input('search');
    $user = auth()->user();

    $clients = Client::when($user->role === 'lawyer', function ($query) use ($user) {
            $query->whereHas('cases', function ($q) use ($user) {
                $q->where('lawyer_id', $user->lawyer_id);
            });
        })
        ->when($search, function ($query) use ($search) {
            // لف الـ orWhere في closure عشان ميأثرش على فلتر المحامي
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('nid', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%");
            });
        })
        ->orderBy('created_at', 'desc')
        ->get();

    return view('client.index', compact('clients', 'search'));
}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // 🔒 Lawyers & assistants can create clients; admin always passes via policy
        $this->authorize('create', Client::class);

        $clients = Client::all();
        return view('client.add_client', compact('clients'));
    }
    
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 🔒 Lawyers & assistants can create clients
        $this->authorize('create', Client::class);

        $validatedData = $request->validate([
            'name' => 'required|string|max:100',
             'address' => 'required|string|max:100',
            'phone' => 'required|string|max:11',
            'email' => 'required|string|email|max:100|unique:clients,email',
            'nid' => 'required|string|max:14',
            'note' => 'nullable|string|max:100',
            // 'case_id' => 'required|exists:cases,id',
        ]);

        $client = Client::create($validatedData);

        $token = Str::random(60);
        $user = User::create([
            'name'                        => $client->name,
            'email'                       => $client->email,
            'password'                    => Hash::make(Str::random(16)),
            'role'                        => 'client',
            'client_id'                   => $client->id,
            'activation_token'            => $token,
            'activation_token_expires_at' => now()->addHours(48),
        ]);

        try {
            Mail::to($client->email)->send(new ClientWelcomeMail($user, $client, $token));
        } catch (\Throwable $e) {
            // Fail silently if mail service is unavailable
        }

        return redirect()->route('clients.show', $client)->with('success', 'تم إضافة العميل بنجاح وإرسال رابط التفعيل لبريده الإلكتروني.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Client $client)
    {
        return view('client.show', compact('client'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Client $client)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Client $client)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Client $client)
    {
        // 🔒 Only admin can delete clients
        $this->authorize('delete', $client);

        $client->delete();
        return redirect()->route('clients.index')->with('success', 'تم حذف العميل بنجاح.');
    }
}

