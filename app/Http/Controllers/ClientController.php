<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Carbon\Carbon;

class ClientController extends Controller
{
    public function index(Request $request): View
    {
        $query = Client::orderBy('created_at', 'desc');
        
        // Filtrer par groupe si spécifié
        if ($request->has('group') && $request->group != 'all') {
            $query->where('group', $request->group);
        }
        
        $clients = $query->paginate(10);
        return view('clients.index', compact('clients'));
    }

    public function create()
    {
        return view('clients.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'birth_date' => 'required|date',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:1000',
            'group' => 'required|string|in:Box,Taekwondo,Karaté',
            'profile_picture' => 'nullable|image|max:2048',
            'Birth_contract' => 'nullable|image|max:2048'
        ]);

        if ($request->hasFile('profile_picture')) {
            $validated['profile_picture'] = $request->file('profile_picture')->store('profile-pictures', 'public');
        }
        if ($request->hasFile('Birth_contract')) {
            $validated['Birth_contract'] = $request->file('Birth_contract')->store('Birth-contract', 'public');
        }

        $validated['payer_abon'] = now();

        Client::create($validated);

        return redirect()->route('clients.index')
            ->with('success', 'Client added successfully!');
    }

    public function edit(Client $client): View
    {
        return view('clients.edit', compact('client'));
    }

    public function update(Request $request, Client $client): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'birth_date' => 'required|date',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:1000',
            'group' => 'required|string|in:Box,Taekwondo,Karaté',
            'profile_picture' => 'nullable|image|max:2048'
        ]);

        if ($request->hasFile('profile_picture')) {
            $validated['profile_picture'] = $request->file('profile_picture')->store('profile-pictures', 'public');
        }

        $client->update($validated);

        return redirect()->route('clients.index')
            ->with('success', 'Client updated successfully!');
    }

    public function destroy(Client $client): RedirectResponse
    {
        $client->delete();

        return redirect()->route('clients.index')
            ->with('success', 'Client deleted successfully!');
    }

    public function validatePayment(Client $client): RedirectResponse
    {
        $client->payer_abon = Carbon::parse($client->payer_abon)->addMonth();
        $client->save();

        return redirect()->route('clients.index')
            ->with('success', 'Payment validated successfully!');
    }

    
}