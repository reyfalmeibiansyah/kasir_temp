<?php

namespace App\Http\Controllers;

use App\Models\Penjualan;
use App\Models\Produk;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Exports\PenjualanExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class PembelianController extends Controller
{
    public function index()
    {
        $penjualans = Penjualan::with('members')->latest()->paginate(10);
        return view('petugas.pembelian.index', compact('penjualans'));
    }

    public function create()
    {
        $produks = Produk::where('stock', '>', 0)->get();
        return view('petugas.pembelian.create', compact('produks'));
    }

    public function store(Request $request)
    {
        // Validasi awal
        $rules = [
            'produk_id'      => 'required|exists:produks,id',
            'jumlah'         => 'required|integer|min:1',
            'total_payment'  => 'required|numeric',
            'is_member'      => 'required|in:bukan_member,member',
        ];

        // Validasi tambahan jika member
        if ($request->is_member === 'member') {
            $rules['customer_phone'] = 'required|string|max:20';
        }

        $request->validate($rules);

        $produk = Produk::findOrFail($request->produk_id);

        if ($produk->stock < $request->jumlah) {
            return back()->withInput()->with('error', 'Stok produk tidak mencukupi. Stok tersedia: ' . $produk->stock);
        }

        // Proses data member jika dipilih
        $member = null;
        if ($request->is_member === 'member') {
            $member = Member::firstOrCreate(
                ['no_hp' => $request->customer_phone],
                ['nama_member' => 'Member Baru']
            );
        } else {
            // Jika bukan member, isi customer_phone default
            $request->merge(['customer_phone' => '-']);
        }

        // Generate nomor invoice unik
        $invoiceNumber = 'INV-' . now()->format('Ymd') . '-' . strtoupper(uniqid());

        // Hitung total bayar
        $total = $request->total_payment;

        $penjualan = Penjualan::create([
            'member_id'         => $member ? $member->id : null,
            'invoice_number'    => $invoiceNumber,
            'tanggal_penjualan' => now(),
            'total_payment'     => $total,
            'user_id'           => Auth::id(),
            'produk_id'         => $produk->id,
            'qty'               => $request->jumlah,
            'point_used'        => 0,
            'change'            => 0,
            'customer_phone'    => $request->customer_phone,
        ]);

        // Update stok
        $produk->decrement('stock', $request->jumlah);

        return redirect()->route('petugas.pembelian.struk', $penjualan->id);
    }

    public function export()
    {
        return Excel::download(new PenjualanExport, 'data_pembelian.xlsx');
    }

    public function show($id)
    {
        $pembelian = Penjualan::with(['detailPenjualan.produk', 'user', 'members'])->findOrFail($id);
        return view('petugas.pembelian.show', compact('pembelian'));
    }

    public function showPdf($id)
    {
        $pembelian = Penjualan::with(['detailPenjualan.produk', 'user', 'members'])->findOrFail($id);
        $pdf = Pdf::loadView('petugas.pembelian.pdf', compact('pembelian'));
        return $pdf->stream('struk-pembelian.pdf');
    }

    public function downloadPdf($id)
    {
        $pembelian = Penjualan::with(['detailPenjualan.produk', 'user', 'members'])->findOrFail($id);
        $pdf = Pdf::loadView('petugas.pembelian.pdf', compact('pembelian'));
        return $pdf->download('struk-pembelian.pdf');
    }

    public function detail(Request $request)
    {
        $request->validate([
            'produk_id' => 'required|exists:produks,id',
            'jumlah' => 'required|integer|min:1',
        ]);

        $produk = Produk::findOrFail($request->produk_id);
        $jumlah = $request->jumlah;
        $total = $produk->price * $jumlah;

        return view('petugas.pembelian.detail', [
            'produk' => $produk,
            'jumlah' => $jumlah,
            'total' => $total,
        ]);
    }

    public function struk($id)
    {
        $penjualan = Penjualan::with(['produk', 'members'])->findOrFail($id);
        return view('petugas.pembelian.struk', compact('penjualan'));
    }
}
