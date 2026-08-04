<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Sparepart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SparepartController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $lowStock = $request->query('low_stock');

        $spareparts = Sparepart::when($search, function ($query, $search) {
                $query->where('part_name', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%");
            })
            ->when($lowStock, function ($query) {
                $query->whereColumn('stock', '<=', 'min_stock');
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.spareparts.index', compact('spareparts', 'search', 'lowStock'));
    }

    public function create()
    {
        return view('admin.spareparts.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'part_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'stock' => 'required|integer|min:0',
            'min_stock' => 'required|integer|min:0',
            'cost_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'image_url' => 'nullable|url',
        ]);

        $imagePath = $request->image_url;
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('spareparts', 'public');
            $imagePath = Storage::url($path);
        }

        Sparepart::create([
            'part_name' => $request->part_name,
            'description' => $request->description,
            'image' => $imagePath,
            'stock' => $request->stock,
            'min_stock' => $request->min_stock,
            'cost_price' => $request->cost_price,
            'selling_price' => $request->selling_price,
        ]);

        return redirect()->route('admin.spareparts.index')->with('success', 'Data Sparepart berhasil ditambahkan!');
    }

    public function edit(Sparepart $sparepart)
    {
        return view('admin.spareparts.edit', compact('sparepart'));
    }

    public function update(Request $request, Sparepart $sparepart)
    {
        $request->validate([
            'part_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'stock' => 'required|integer|min:0',
            'min_stock' => 'required|integer|min:0',
            'cost_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'image_url' => 'nullable|url',
        ]);

        $imagePath = $sparepart->image;
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('spareparts', 'public');
            $imagePath = Storage::url($path);
        } elseif ($request->filled('image_url')) {
            $imagePath = $request->image_url;
        }

        $sparepart->update([
            'part_name' => $request->part_name,
            'description' => $request->description,
            'image' => $imagePath,
            'stock' => $request->stock,
            'min_stock' => $request->min_stock,
            'cost_price' => $request->cost_price,
            'selling_price' => $request->selling_price,
        ]);

        return redirect()->route('admin.spareparts.index')->with('success', 'Data Sparepart berhasil diperbarui!');
    }

    public function destroy(Sparepart $sparepart)
    {
        $sparepart->delete();
        return redirect()->route('admin.spareparts.index')->with('success', 'Sparepart berhasil dihapus!');
    }
}
