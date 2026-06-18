<?php

namespace App\Http\Controllers;

use App\Models\ContactInquiry;
use App\Models\ContactInquiryStatusLog;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;
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

        $this->sendNewInquiryNotification($inquiry);

        return response()->json([
            'success' => true,
            'message' => 'Permintaan konsultasi survey Anda telah terkirim! Tim kami akan menghubungi Anda dalam 24 jam.',
            'inquiry' => $inquiry
        ]);
    }

    private function sendNewInquiryNotification(ContactInquiry $inquiry): void
    {
        $adminEmail = config('mail.admin_notification_address', env('CONTACT_INQUIRY_NOTIFICATION_EMAIL', 'intergeo.mitigasi@gmail.com'));
        $dashboardUrl = route('dashboard.contact-inquiries.show', $inquiry->id);

        $message = "[NOTIFIKASI INQUIRY BARU]\n" .
            "PT. Intergeo Mitigasi\n" .
            "==================================================\n\n" .
            "Ada permintaan konsultasi baru dari website.\n\n" .
            "DATA PENGIRIM\n" .
            "--------------------------------------------------\n" .
            "Nama          : {$inquiry->full_name}\n" .
            "Email         : {$inquiry->email}\n" .
            "Nomor Telepon : {$inquiry->phone_number}\n" .
            "Jenis Layanan : " . ($inquiry->service_type ?: 'Tidak ditentukan') . "\n\n" .
            "PESAN / DESKRIPSI PROYEK\n" .
            "--------------------------------------------------\n" .
            "{$inquiry->project_description}\n\n" .
            "TINDAK LANJUT YANG DISARANKAN\n" .
            "--------------------------------------------------\n" .
            "1. Buka detail inquiry di dashboard:\n" .
            "   {$dashboardUrl}\n\n" .
            "2. Gunakan form 'Balas via Email' di dashboard jika ingin balasan tercatat di sistem.\n\n" .
            "3. Jika ingin membalas langsung dari inbox email, klik Reply pada email ini.\n" .
            "   Reply-To sudah diarahkan ke email pengirim: {$inquiry->email}\n\n" .
            "CATATAN INTERNAL\n" .
            "--------------------------------------------------\n" .
            "Field 'Catatan Internal Admin' di dashboard hanya untuk tracking internal.\n" .
            "Jangan gunakan field tersebut sebagai balasan ke pengirim.";

        try {
            Mail::raw($message, function ($mail) use ($adminEmail, $inquiry) {
                $mail->to($adminEmail, 'PT. Intergeo Mitigasi')
                    ->replyTo($inquiry->email, $inquiry->full_name)
                    ->subject('Pesan masuk baru dari website - ' . $inquiry->full_name);
            });
        } catch (Throwable $exception) {
            Log::error('Failed to send new contact inquiry notification email', [
                'contact_inquiry_id' => $inquiry->id,
                'admin_email' => $adminEmail,
                'error' => $exception->getMessage(),
            ]);
        }
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

        // Tambah log status internal
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

    public function destroy(ContactInquiry $contactInquiry)
    {
        $contactInquiry->statusLogs()->delete();
        $contactInquiry->delete();

        return redirect()
            ->route('dashboard.contact-inquiries.index')
            ->with('success', 'Inquiry berhasil dihapus.');
    }

    public function reply(Request $request, ContactInquiry $contactInquiry)
    {
        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:5000',
        ]);

        try {
            Mail::raw($validated['message'], function ($mail) use ($contactInquiry, $validated) {
                $mail->to($contactInquiry->email, $contactInquiry->full_name)
                    ->subject($validated['subject']);

                if (config('mail.reply_to.address')) {
                    $mail->replyTo(config('mail.reply_to.address'), config('mail.reply_to.name'));
                }
            });
        } catch (Throwable $exception) {
            Log::error('Failed to send contact inquiry reply email', [
                'contact_inquiry_id' => $contactInquiry->id,
                'email' => $contactInquiry->email,
                'error' => $exception->getMessage(),
            ]);

            return redirect()
                ->route('dashboard.contact-inquiries.show', $contactInquiry->id)
                ->with('error', 'Email balasan gagal dikirim. Periksa konfigurasi SMTP lalu coba lagi.');
        }

        $contactInquiry->update([
            'status' => $contactInquiry->status === 'new' ? 'contacted' : $contactInquiry->status,
            'notes' => trim(($contactInquiry->notes ? $contactInquiry->notes . "\n\n" : '') . 'Email balasan dikirim pada ' . now()->format('d M Y H:i') . ' oleh ' . optional(auth()->user())->name . '.'),
        ]);

        ContactInquiryStatusLog::create([
            'contact_inquiry_id' => $contactInquiry->id,
            'status' => $contactInquiry->status,
            'notes' => 'Email balasan dikirim ke ' . $contactInquiry->email . '. Subject: ' . $validated['subject'],
            'updated_by' => auth()->id(),
        ]);

        return redirect()
            ->route('dashboard.contact-inquiries.show', $contactInquiry->id)
            ->with('success', 'Email balasan berhasil dikirim.');
    }
}
