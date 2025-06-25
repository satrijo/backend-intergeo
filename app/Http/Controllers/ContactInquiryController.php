<?php

namespace App\Http\Controllers;

use App\Models\ContactInquiry;
use App\Models\ContactInquiryStatusLog;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Inertia\Inertia;
use Inertia\Response;

class ContactInquiryController extends Controller
{
    public function index(): Response
    {
        $inquiries = ContactInquiry::orderBy('created_at', 'desc')->paginate(10);
        
        return Inertia::render('ContactInquiries/Index', [
            'inquiries' => $inquiries
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone_number' => 'required|string|max:20',
            'service_type' => 'nullable|string|max:255',
            'project_description' => 'required|string|max:1000',
        ]);

        $inquiry = ContactInquiry::create($validated);

        // Tambahkan log status awal
        ContactInquiryStatusLog::create([
            'contact_inquiry_id' => $inquiry->id,
            'status' => 'new',
            'notes' => null,
            'updated_by' => null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Permintaan konsultasi survey Anda telah terkirim! Tim kami akan menghubungi Anda dalam 24 jam.',
            'inquiry' => $inquiry
        ]);
    }

    public function show(ContactInquiry $contactInquiry): Response
    {
        $contactInquiry->load(['statusLogs.user']);
        return Inertia::render('ContactInquiries/Show', [
            'inquiry' => $contactInquiry,
            'statusLogs' => $contactInquiry->statusLogs()->with('user')->orderBy('created_at')->get(),
        ]);
    }

    public function update(Request $request, ContactInquiry $contactInquiry)
    {
        $validated = $request->validate([
            'status' => 'required|in:new,contacted,in_progress,completed,cancelled',
            'notes' => 'nullable|string|max:1000',
        ]);

        $contactInquiry->update($validated);

        // Tambah log status
        ContactInquiryStatusLog::create([
            'contact_inquiry_id' => $contactInquiry->id,
            'status' => $validated['status'],
            'notes' => $validated['notes'] ?? null,
            'updated_by' => auth()->id(),
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Status inquiry berhasil diperbarui',
                'inquiry' => $contactInquiry
            ]);
        }

        return redirect()
            ->route('dashboard.contact-inquiries.show', $contactInquiry->id)
            ->with('success', 'Status inquiry berhasil diperbarui.');
    }
}
