<?php

namespace App\Http\Controllers;

use App\Models\Seedling;
use App\Models\Donation;
use App\Models\PlantingProof;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SeedlingController extends Controller
{
    public function welcome()
    {
        $seedlings = Seedling::all();
        $proofs = PlantingProof::orderBy('id', 'desc')->get();
        // Load initial stats for Server-Side Rendering
        $totalSeedlings = Donation::where('payment_status', 'paid')->sum('quantity');
        $totalFunds = Donation::where('payment_status', 'paid')->sum('total_amount');
        $totalDonors = Donation::where('payment_status', 'paid')->distinct('user_id')->count('user_id');

        return view('welcome', compact('seedlings', 'proofs', 'totalSeedlings', 'totalFunds', 'totalDonors'));
    }

    public function index()
    {
        $seedlings = Seedling::orderBy('id', 'desc')->paginate(10);
        return view('admin.seedlings.index', compact('seedlings'));
    }

    public function create()
    {
        return view('admin.seedlings.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'quantity_available' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('seedlings', 'public');
        }

        Seedling::create([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'quantity_available' => $request->quantity_available,
            'image_path' => $imagePath
        ]);

        return redirect()->route('admin.seedlings.index')->with('success', 'Bibit pohon berhasil ditambahkan.');
    }

    public function edit(Seedling $seedling)
    {
        return view('admin.seedlings.edit', compact('seedling'));
    }

    public function update(Request $request, Seedling $seedling)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'quantity_available' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $imagePath = $seedling->image_path;
        if ($request->hasFile('image')) {
            // Delete old image if it exists
            if ($imagePath && Storage::disk('public')->exists($imagePath)) {
                Storage::disk('public')->delete($imagePath);
            }
            $imagePath = $request->file('image')->store('seedlings', 'public');
        }

        $seedling->update([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'quantity_available' => $request->quantity_available,
            'image_path' => $imagePath
        ]);

        return redirect()->route('admin.seedlings.index')->with('success', 'Bibit pohon berhasil diperbarui.');
    }

    public function destroy(Seedling $seedling)
    {
        if ($seedling->image_path && Storage::disk('public')->exists($seedling->image_path)) {
            Storage::disk('public')->delete($seedling->image_path);
        }
        $seedling->delete();

        return redirect()->route('admin.seedlings.index')->with('success', 'Bibit pohon berhasil dihapus.');
    }
}
