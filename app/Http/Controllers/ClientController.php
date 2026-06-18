<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ClientController extends Controller
{
    public function index()
    {
        return Inertia::render('Clients/Index', [
            'clients' => Client::ordered()->get(),
        ]);
    }

    public function create()
    {
        return Inertia::render('Clients/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'logo' => 'required|string|max:2048',
            'website_url' => 'nullable|url|max:255',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        Client::create([
            ...$validated,
            'is_active' => $validated['is_active'] ?? true,
            'sort_order' => $validated['sort_order'] ?? 0,
        ]);

        return redirect()->route('dashboard.clients.index')
            ->with('success', 'Client created successfully.');
    }

    public function edit(Client $client)
    {
        return Inertia::render('Clients/Edit', [
            'client' => $client,
        ]);
    }

    public function update(Request $request, Client $client)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'logo' => 'required|string|max:2048',
            'website_url' => 'nullable|url|max:255',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $client->update([
            ...$validated,
            'is_active' => $validated['is_active'] ?? false,
            'sort_order' => $validated['sort_order'] ?? 0,
        ]);

        return redirect()->route('dashboard.clients.index')
            ->with('success', 'Client updated successfully.');
    }

    public function destroy(Client $client)
    {
        $client->delete();

        return redirect()->route('dashboard.clients.index')
            ->with('success', 'Client deleted successfully.');
    }
}
