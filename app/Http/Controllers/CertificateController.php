<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use App\Models\Donation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class CertificateController extends Controller
{
    /**
     * Admin: List all certificates and eligible donations.
     */
    public function index()
    {
        $certificates = Certificate::with(['donation.user', 'donation.seedling'])
            ->orderBy('id', 'desc')
            ->paginate(10);

        $eligibleDonations = Donation::with(['user', 'seedling'])
            ->where('payment_status', 'paid')
            ->whereDoesntHave('certificate')
            ->orderBy('id', 'desc')
            ->get();

        $totalCertificates = Certificate::count();
        $totalDonorsWithCert = Certificate::distinct('recipient_name')->count('recipient_name');

        return view('admin.certificates.index', compact(
            'certificates',
            'eligibleDonations',
            'totalCertificates',
            'totalDonorsWithCert'
        ));
    }

    /**
     * Admin: Show form to create a new certificate.
     */
    public function create(Request $request)
    {
        $selectedDonationId = $request->query('donation_id');
        $selectedDonation = null;

        if ($selectedDonationId) {
            $selectedDonation = Donation::with(['user', 'seedling'])
                ->where('id', $selectedDonationId)
                ->where('payment_status', 'paid')
                ->first();
        }

        $eligibleDonations = Donation::with(['user', 'seedling'])
            ->where('payment_status', 'paid')
            ->whereDoesntHave('certificate')
            ->orderBy('id', 'desc')
            ->get();

        $generatedNumber = self::generateNumber();

        return view('admin.certificates.create', compact(
            'eligibleDonations',
            'selectedDonation',
            'generatedNumber'
        ));
    }

    /**
     * Admin: Store a newly created certificate.
     */
    public function store(Request $request)
    {
        $request->validate([
            'donation_id' => 'required|exists:donations,id|unique:certificates,donation_id',
            'certificate_number' => 'required|string|max:100|unique:certificates,certificate_number',
            'recipient_name' => 'required|string|max:255',
            'seedling_name' => 'required|string|max:255',
            'tree_count' => 'required|integer|min:1',
            'issued_date' => 'required|date',
            'signer_name' => 'required|string|max:255',
            'signer_title' => 'required|string|max:255',
            'appreciation_message' => 'nullable|string|max:1000',
        ]);

        $donation = Donation::findOrFail($request->donation_id);

        if ($donation->payment_status !== 'paid') {
            return back()->with('error', 'Sertifikat hanya dapat diterbitkan untuk donasi yang sudah berstatus lunas / sukses.');
        }

        $certificate = Certificate::create([
            'donation_id' => $donation->id,
            'certificate_number' => $request->certificate_number,
            'recipient_name' => $request->recipient_name,
            'seedling_name' => $request->seedling_name,
            'tree_count' => $request->tree_count,
            'issued_date' => $request->issued_date,
            'signer_name' => $request->signer_name,
            'signer_title' => $request->signer_title,
            'appreciation_message' => $request->appreciation_message,
        ]);

        return redirect()->route('admin.certificates.index')
            ->with('success', 'Sertifikat penghargaan berhasil diterbitkan dengan nomor: ' . $certificate->certificate_number);
    }

    /**
     * Admin: Quick 1-click certificate generation for a paid donation.
     */
    public function quickGenerate(Donation $donation)
    {
        if ($donation->payment_status !== 'paid') {
            return back()->with('error', 'Sertifikat hanya dapat diterbitkan untuk donasi yang sudah lunas.');
        }

        if ($donation->certificate) {
            return back()->with('info', 'Sertifikat untuk donasi ini sudah pernah diterbitkan sebelumnya.');
        }

        $certificateNumber = self::generateNumber();

        $certificate = Certificate::create([
            'donation_id' => $donation->id,
            'certificate_number' => $certificateNumber,
            'recipient_name' => $donation->user->name,
            'seedling_name' => $donation->seedling->name,
            'tree_count' => $donation->quantity,
            'issued_date' => now()->toDateString(),
            'signer_name' => 'H. Muhammad Idris, S.Sos',
            'signer_title' => 'Kepala Desa Labuhan Lombok',
            'appreciation_message' => 'Diberikan sebagai bentuk piagam penghargaan dan apresiasi atas kontribusi serta kepedulian nyata dalam aksi penanaman bibit pohon demi penghijauan dan kelestarian alam Desa Labuhan Lombok.',
        ]);

        return back()->with('success', 'Sertifikat penghargaan #' . $certificate->certificate_number . ' berhasil diterbitkan!');
    }

    /**
     * Admin: Show form to edit an existing certificate.
     */
    public function edit(Certificate $certificate)
    {
        return view('admin.certificates.edit', compact('certificate'));
    }

    /**
     * Admin: Update an existing certificate.
     */
    public function update(Request $request, Certificate $certificate)
    {
        $request->validate([
            'certificate_number' => 'required|string|max:100|unique:certificates,certificate_number,' . $certificate->id,
            'recipient_name' => 'required|string|max:255',
            'seedling_name' => 'required|string|max:255',
            'tree_count' => 'required|integer|min:1',
            'issued_date' => 'required|date',
            'signer_name' => 'required|string|max:255',
            'signer_title' => 'required|string|max:255',
            'appreciation_message' => 'nullable|string|max:1000',
        ]);

        $certificate->update($request->only([
            'certificate_number',
            'recipient_name',
            'seedling_name',
            'tree_count',
            'issued_date',
            'signer_name',
            'signer_title',
            'appreciation_message',
        ]));

        return redirect()->route('admin.certificates.index')
            ->with('success', 'Sertifikat #' . $certificate->certificate_number . ' berhasil diperbarui.');
    }

    /**
     * Admin: Revoke / delete a certificate.
     */
    public function destroy(Certificate $certificate)
    {
        $number = $certificate->certificate_number;
        $certificate->delete();

        return back()->with('success', 'Sertifikat #' . $number . ' berhasil dihapus.');
    }

    /**
     * Donor / Admin: Download certificate as PDF.
     */
    public function downloadPdf(Certificate $certificate)
    {
        $this->authorizeAccess($certificate);

        $pdf = Pdf::loadView('certificates.pdf', compact('certificate'))
            ->setPaper('a4', 'landscape');

        $filename = 'Sertifikat_Donasi_' . str_replace(['/', '\\', ' '], '_', $certificate->certificate_number) . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * Donor / Admin: Preview certificate in browser.
     */
    public function preview(Certificate $certificate)
    {
        $this->authorizeAccess($certificate);

        $pdf = Pdf::loadView('certificates.pdf', compact('certificate'))
            ->setPaper('a4', 'landscape');

        return $pdf->stream('Sertifikat_Preview.pdf');
    }

    /**
     * Donor: Download certificate by donation ID.
     */
    public function downloadByDonation(Donation $donation)
    {
        if (!Auth::user()->isAdmin() && $donation->user_id !== Auth::id()) {
            abort(403, 'Akses tidak diizinkan.');
        }

        if (!$donation->certificate) {
            return back()->with('error', 'Sertifikat untuk donasi ini belum diterbitkan oleh Admin. Silakan tunggu verifikasi admin.');
        }

        return $this->downloadPdf($donation->certificate);
    }

    /**
     * Helper to verify user permission to view certificate.
     */
    protected function authorizeAccess(Certificate $certificate)
    {
        $user = Auth::user();
        if (!$user) {
            abort(401);
        }

        if (!$user->isAdmin() && $certificate->donation->user_id !== $user->id) {
            abort(403, 'Anda tidak memiliki hak akses untuk mengunduh sertifikat ini.');
        }
    }

    /**
     * Helper: Generate sequential certificate number.
     */
    public static function generateNumber()
    {
        $year = date('Y');
        $month = date('m');
        $count = Certificate::whereYear('created_at', $year)->count() + 1;
        return sprintf('CERT/LBL/%s/%s/%04d', $year, $month, $count);
    }
}
