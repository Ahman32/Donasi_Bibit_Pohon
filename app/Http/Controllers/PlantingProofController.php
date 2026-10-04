<?php

namespace App\Http\Controllers;

use App\Models\PlantingProof;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PlantingProofController extends Controller
{
    public function index()
    {
        $proofs = PlantingProof::orderBy('id', 'desc')->paginate(12);
        return view('admin.proofs.index', compact('proofs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:planting,donation',
            'image' => 'required|image|mimes:jpeg,png,jpg,webp,gif|max:4096',
            'location' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'planted_at' => 'nullable|date',
        ]);

        $imagePath = $request->file('image')->store('proofs', 'public');

        PlantingProof::create([
            'title' => $request->title,
            'type' => $request->type,
            'image_path' => $imagePath,
            'location' => $request->location,
            'description' => $request->description,
            'planted_at' => $request->planted_at ?? now(),
        ]);

        $typeLabel = $request->type === 'planting' ? 'Bukti penanaman pohon' : 'Bukti donasi';
        return redirect()->route('admin.proofs.index')->with('success', "{$typeLabel} berhasil ditambahkan!");
    }

    public function destroy(PlantingProof $proof)
    {
        if ($proof->image_path && Storage::disk('public')->exists($proof->image_path)) {
            Storage::disk('public')->delete($proof->image_path);
        }
        $proof->delete();

        return redirect()->route('admin.proofs.index')->with('success', 'Gambar bukti berhasil dihapus.');
    }
}
