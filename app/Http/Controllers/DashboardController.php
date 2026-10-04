<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use App\Models\Seedling;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    // Fetch stats in JSON format for real-time polling
    public function statsJson()
    {
        $totalSeedlings = Donation::where('payment_status', 'paid')->sum('quantity');
        $totalFunds = Donation::where('payment_status', 'paid')->sum('total_amount');
        $totalDonors = Donation::where('payment_status', 'paid')->distinct('user_id')->count('user_id');
        
        $recent = Donation::with(['user', 'seedling'])
            ->where('payment_status', 'paid')
            ->orderBy('id', 'desc')
            ->take(5)
            ->get()
            ->map(function ($donation) {
                return [
                    'donor_name' => $donation->user->name,
                    'seedling_name' => $donation->seedling->name,
                    'quantity' => $donation->quantity,
                    'total_amount' => number_format($donation->total_amount, 0, ',', '.'),
                    'time_ago' => $donation->created_at->diffForHumans(),
                ];
            });

        return response()->json([
            'total_seedlings' => number_format($totalSeedlings, 0, ',', '.'),
            'total_funds' => 'Rp ' . number_format($totalFunds, 0, ',', '.'),
            'total_donors' => number_format($totalDonors, 0, ',', '.'),
            'recent' => $recent
        ]);
    }

    public function userDashboard()
    {
        $user = Auth::user();
        if ($user->isAdmin()) {
            return redirect('/admin');
        }

        // Personal Stats
        $mySeedlings = Donation::where('user_id', $user->id)->where('payment_status', 'paid')->sum('quantity');
        $myTotalAmount = Donation::where('user_id', $user->id)->where('payment_status', 'paid')->sum('total_amount');
        $myDonations = Donation::with(['seedling', 'certificate'])
            ->where('user_id', $user->id)
            ->orderBy('id', 'desc')
            ->get();

        $seedlings = Seedling::all();

        return view('dashboard', compact('mySeedlings', 'myTotalAmount', 'myDonations', 'seedlings'));
    }

    public function adminDashboard()
    {
        // Admin Overview Stats
        $totalSeedlings = Donation::where('payment_status', 'paid')->sum('quantity');
        $totalFunds = Donation::where('payment_status', 'paid')->sum('total_amount');
        $totalDonors = Donation::where('payment_status', 'paid')->distinct('user_id')->count('user_id');

        // All donations for verification
        $allDonations = Donation::with(['user', 'seedling', 'certificate'])
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('admin.dashboard', compact('totalSeedlings', 'totalFunds', 'totalDonors', 'allDonations'));
    }
}
