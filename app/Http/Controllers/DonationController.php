<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use App\Models\Seedling;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DonationController extends Controller
{
    public function create(Request $request)
    {
        $selectedSeedlingId = $request->query('seedling_id');
        $seedlings = Seedling::where('quantity_available', '>', 0)->get();
        return view('donations.create', compact('seedlings', 'selectedSeedlingId'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'seedling_id' => 'required|exists:seedlings,id',
            'quantity' => 'required|integer|min:1',
            'payment_method' => 'required|in:qris,bank_transfer',
        ]);

        $seedling = Seedling::findOrFail($request->seedling_id);

        if ($request->quantity > $seedling->quantity_available) {
            return back()->withErrors([
                'quantity' => "Stok bibit tidak mencukupi. Stok tersisa: {$seedling->quantity_available} bibit."
            ])->withInput();
        }

        $totalAmount = $seedling->price * $request->quantity;

        $donation = Donation::create([
            'user_id' => Auth::id(),
            'seedling_id' => $seedling->id,
            'quantity' => $request->quantity,
            'total_amount' => $totalAmount,
            'payment_method' => $request->payment_method,
            'payment_status' => 'pending',
        ]);

        return redirect()->route('donations.pay', $donation->id);
    }

    public function pay(Donation $donation)
    {
        // Safety check: only the owner can pay
        if ($donation->user_id !== Auth::id()) {
            abort(403);
        }

        return view('donations.pay', compact('donation'));
    }

    public function submitProof(Request $request, Donation $donation)
    {
        if ($donation->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'payment_proof' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        // Delete old proof if exists
        if ($donation->payment_proof && Storage::disk('public')->exists($donation->payment_proof)) {
            Storage::disk('public')->delete($donation->payment_proof);
        }

        $proofPath = $request->file('payment_proof')->store('proofs', 'public');

        $donation->update([
            'payment_proof' => $proofPath
        ]);

        return back()->with('success', 'Bukti pembayaran berhasil diunggah. Menunggu verifikasi admin.');
    }

    public function simulateSuccess(Donation $donation)
    {
        if ($donation->user_id !== Auth::id()) {
            abort(403);
        }

        if ($donation->payment_status !== 'pending') {
            return back()->with('error', 'Status donasi ini sudah tidak pending.');
        }

        $seedling = $donation->seedling;
        if ($donation->quantity > $seedling->quantity_available) {
            return back()->with('error', 'Stok bibit tidak mencukupi untuk memproses donasi ini.');
        }

        // Update seedling stock
        $seedling->decrement('quantity_available', $donation->quantity);

        // Update donation status
        $donation->update([
            'payment_status' => 'paid'
        ]);

        // Automatically issue certificate if not exists
        if (!$donation->certificate) {
            \App\Models\Certificate::create([
                'donation_id' => $donation->id,
                'certificate_number' => \App\Http\Controllers\CertificateController::generateNumber(),
                'recipient_name' => $donation->user->name,
                'seedling_name' => $donation->seedling->name,
                'tree_count' => $donation->quantity,
                'issued_date' => now()->toDateString(),
                'signer_name' => 'H. Muhammad Idris, S.Sos',
                'signer_title' => 'Kepala Desa Labuhan Lombok',
                'appreciation_message' => 'Diberikan sebagai bentuk piagam penghargaan dan apresiasi atas kontribusi serta kepedulian nyata dalam aksi penanaman bibit pohon demi penghijauan dan kelestarian alam Desa Labuhan Lombok.',
            ]);
        }

        return redirect()->route('dashboard')->with('success', 'Pembayaran QRIS berhasil disimulasikan! Terima kasih atas donasi Anda.');
    }

    public function approve(Donation $donation)
    {
        if ($donation->payment_status !== 'pending') {
            return back()->with('error', 'Status donasi ini sudah tidak pending.');
        }

        $seedling = $donation->seedling;
        if ($donation->quantity > $seedling->quantity_available) {
            return back()->with('error', 'Stok bibit tidak mencukupi untuk menyetujui donasi ini.');
        }

        // Update seedling stock
        $seedling->decrement('quantity_available', $donation->quantity);

        // Update donation status
        $donation->update([
            'payment_status' => 'paid'
        ]);

        // Automatically issue certificate
        if (!$donation->certificate) {
            \App\Models\Certificate::create([
                'donation_id' => $donation->id,
                'certificate_number' => \App\Http\Controllers\CertificateController::generateNumber(),
                'recipient_name' => $donation->user->name,
                'seedling_name' => $donation->seedling->name,
                'tree_count' => $donation->quantity,
                'issued_date' => now()->toDateString(),
                'signer_name' => 'H. Muhammad Idris, S.Sos',
                'signer_title' => 'Kepala Desa Labuhan Lombok',
                'appreciation_message' => 'Diberikan sebagai bentuk piagam penghargaan dan apresiasi atas kontribusi serta kepedulian nyata dalam aksi penanaman bibit pohon demi penghijauan dan kelestarian alam Desa Labuhan Lombok.',
            ]);
        }

        return back()->with('success', 'Donasi berhasil disetujui dan sertifikat penghargaan resmi telah diterbitkan.');
    }

    public function reject(Donation $donation)
    {
        if ($donation->payment_status !== 'pending') {
            return back()->with('error', 'Status donasi ini sudah tidak pending.');
        }

        $donation->update([
            'payment_status' => 'failed'
        ]);

        return back()->with('success', 'Donasi ditolak.');
    }

    public function exportPdf(Request $request)
    {
        $query = Donation::with(['user', 'seedling'])->orderBy('id', 'desc');
        if ($request->has('status') && in_array($request->status, ['pending', 'paid', 'failed'])) {
            $query->where('payment_status', $request->status);
        }
        $donations = $query->get();

        $totalSeedlings = Donation::where('payment_status', 'paid')->sum('quantity');
        $totalFunds = Donation::where('payment_status', 'paid')->sum('total_amount');
        $totalDonors = Donation::where('payment_status', 'paid')->distinct('user_id')->count('user_id');

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.donations.pdf', compact('donations', 'totalSeedlings', 'totalFunds', 'totalDonors'));
        return $pdf->download('Laporan_Transaksi_Donasi_' . date('Y-m-d_H-i') . '.pdf');
    }

    public function printReport(Request $request)
    {
        $query = Donation::with(['user', 'seedling'])->orderBy('id', 'desc');
        if ($request->has('status') && in_array($request->status, ['pending', 'paid', 'failed'])) {
            $query->where('payment_status', $request->status);
        }
        $donations = $query->get();

        $totalSeedlings = Donation::where('payment_status', 'paid')->sum('quantity');
        $totalFunds = Donation::where('payment_status', 'paid')->sum('total_amount');
        $totalDonors = Donation::where('payment_status', 'paid')->distinct('user_id')->count('user_id');

        return view('admin.donations.print', compact('donations', 'totalSeedlings', 'totalFunds', 'totalDonors'));
    }
}
