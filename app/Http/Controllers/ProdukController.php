<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth; // Tambahkan ini

class ProdukController extends Controller
{
    public function index() : View
    {
        $produks = Produk::latest()->paginate(10);

        // Cek role user untuk arahkan ke view yang sesuai
        $view = Auth::user()->role === 'petugas'
            ? 'petugas.produks.index'
            : 'admin.produks.index';

        return view($view, compact('produks'));
    }

    public function create(): View
    {
        return view('admin.produks.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'image'         => 'required|image|mimes:jpeg,jpg,png|max:2048',
            'title'         => 'required|min:1',
            'description'   => 'required|min:1',
            'price'         => 'required|numeric',
            'stock'         => 'required|numeric'
        ]);

        $image = $request->file('image');
        $imageName = $image->hashName();
        $image->storeAs('produks', $imageName, 'public');

        $imageUrl = $imageName;

        Produk::create([
            'image'         => $imageUrl,
            'title'         => $request->title,
            'description'   => $request->description,
            'price'         => $request->price,
            'stock'         => $request->stock
        ]);

        return redirect()->route('admin.produks.index')->with(['success' => 'Data Berhasil Disimpan!']);
    }

    public function show(string $id): View
    {
        $produk = Produk::findOrFail($id);
        return view('admin.produks.show', compact('produk'));
    }

    public function edit(string $id): View
    {
        $produk = Produk::findOrFail($id);
        return view('admin.produks.edit', compact('produk'));
    }

    public function update(Request $request, $id): RedirectResponse
    {
        $request->validate([
            'image'         => 'image|mimes:jpeg,jpg,png|max:2048',
            'title'         => 'required|min:1',
            'description'   => 'required|min:1',
            'price'         => 'required|numeric',
            'stock'         => 'required|numeric'
        ]);

        $produk = Produk::findOrFail($id);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $image->storeAs('public/produks', $image->hashName());
            Storage::delete('public/produks/'.$produk->image);

            $produk->update([
                'image'         => $image->hashName(),
                'title'         => $request->title,
                'description'   => $request->description,
                'price'         => $request->price,
                'stock'         => $request->stock
            ]);
        } else {
            $produk->update([
                'title'         => $request->title,
                'description'   => $request->description,
                'price'         => $request->price,
                'stock'         => $request->stock
            ]);
        }

        return redirect()->route('admin.produks.index')->with(['success' => 'Data Berhasil Diubah!']);
    }

    public function destroy($id): RedirectResponse
    {
        $produk = Produk::findOrFail($id);
        Storage::delete('public/produks/'. $produk->image);
        $produk->delete();

        return redirect()->route('admin.produks.index')->with(['success' => 'Data Berhasil Dihapus!']);
    }

    public function updateStock(Request $request, $id)
    {
        $request->validate([
            'stock' => 'required|integer|min:1',
        ]);

        $produk = Produk::findOrFail($id);
        $produk->stock += $request->stock;
        $produk->save();

        return redirect()->back()->with('success', 'Stok berhasil ditambahkan!');
    }
}
