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
    
    public function index(Request $request)
{
    $keyword = $request->input('search');

    $penjualans = Penjualan::with('members')
        ->when($keyword, function ($query, $keyword) {
            $query->whereHas('members', function ($q) use ($keyword) {
                $q->where('nama_member', 'like', '%' . $keyword . '%');
            });
        })
        ->latest()
        ->paginate(10)
        ->appends(['search' => $keyword]); // biar tetap nyimpen keyword saat pagination

    return view('petugas.pembelian.index', compact('penjualans'));
}

    public function indexAdmin(Request $request)
{
    $keyword = $request->input('search');

    $penjualans = Penjualan::with('members')
        ->when($keyword, function ($query, $keyword) {
            $query->whereHas('members', function ($q) use ($keyword) {
                $q->where('nama_member', 'like', '%' . $keyword . '%');
            });
        })
        ->latest()
        ->paginate(10)
        ->appends(['search' => $keyword]); // biar tetap nyimpen keyword saat pagination

    return view('admin.pembelian.index', compact('penjualans'));
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
        $rules['customer_phone'] = 'required|string|max:20|exists:members,nomor_telepon';
    }

    $request->validate($rules);

    $produk = Produk::findOrFail($request->produk_id);

    if ($produk->stock < $request->jumlah) {
        return back()->withInput()->with('error', 'Stok produk tidak mencukupi. Stok tersedia: ' . $produk->stock);
    }

    // Proses data member jika dipilih
    $member = null;
    if ($request->is_member === 'member') {
        $member = Member::where('nomor_telepon', $request->customer_phone)->first();
        
        if (!$member) {
            return back()->withInput()->with('error', 'Member dengan nomor telepon tersebut tidak ditemukan.');
        }
    } else {
        // Jika bukan member, isi customer_phone default
        $request->merge(['customer_phone' => '-']);
    }

    // Generate nomor invoice unik
    $invoiceNumber = 'INV-' . now()->format('Ymd') . '-' . strtoupper(uniqid());

    // Hitung total harga barang
    $totalHargaBarang = $produk->price * $request->jumlah;

    // Total uang dibayar dari input
    $totalDibayar = $request->total_payment;

    // Hitung uang kembalian
    $uangKembalian = max(0, $totalDibayar - $totalHargaBarang);

    // Simpan ke tabel Penjualan
    $penjualan = Penjualan::create([
        'member_id'         => $member ? $member->id : null,
        'invoice_number'    => $invoiceNumber,
        'tanggal_penjualan' => now(),
        'total_payment'     => $totalDibayar,
        'user_id'           => Auth::id(),
        'point_used'        => 0,
        'change'            => $uangKembalian,
        'customer_phone'    => $request->customer_phone,
    ]);

    // Simpan ke tabel detail_penjualans
    $penjualan->detailPenjualan()->create([
        'produk_id' => $produk->id,
        'qty'       => $request->jumlah,
        'price'     => $produk->price,
        'sub_total' => $produk->price * $request->jumlah
    ]);

    // Update stok
    $produk->decrement('stock', $request->jumlah);

    return redirect()->route('petugas.pembelian.struk', $penjualan->id);
}

    
public function storeStep2(Request $request)
{
    $request->validate([
        'nama_member'   => 'required|string|max:255',
        'total_payment' => 'required|numeric',
        'point_used'    => 'nullable|in:1',
    ]);

    // Cari atau buat member baru
    $member = Member::where('nama_member', $request->nama_member)->first();

    if (!$member) {
        $member = Member::create([
            'nama_member'    => $request->nama_member,
            'nomor_telepon'  => $request->customer_phone ?? '-',
            'points'         => 0,
        ]);
    }

    // Hitung total belanja dari session
    $totalBelanja = 0;
    if (session()->has('selected_products')) {
        foreach (session('selected_products') as $item) {
            $totalBelanja += $item['sub_total'];
        }
    }

    $pointUsed = 0;
    $potongan = 0;
    $totalPayment = $totalBelanja;

    // Proses penggunaan poin jika dicentang dan member memiliki poin
    if ($request->has('point_used') && $member->points > 0) {
        $pointValue = 1000; // 1 poin = Rp 1000
        $maxPotongan = $member->points * $pointValue;

        // Jika poin mencukupi untuk seluruh transaksi
        if ($maxPotongan >= $totalBelanja) {
            $pointUsed = ceil($totalBelanja / $pointValue);
            $potongan = $pointUsed * $pointValue;
            $totalPayment = 0;
        } 
        // Jika poin tidak mencukupi untuk seluruh transaksi
        else {
            $pointUsed = $member->points;
            $potongan = $maxPotongan;
            $totalPayment = $totalBelanja - $potongan;
        }

        // Kurangi poin member
        $member->points -= $pointUsed;
        $member->save();
    }

    // Ambil jumlah uang dibayar dari input
    $totalDibayar = $request->total_payment;

    // Hitung uang kembalian
    $uangKembalian = max(0, $totalDibayar - $totalPayment);

    // Buat record penjualan
    $penjualan = Penjualan::create([
        'invoice_number'    => 'INV-' . now()->format('Ymd') . '-' . strtoupper(uniqid()),
        'user_id'           => Auth::id(),
        'member_id'         => $member->id,
        'customer_phone'    => $member->nomor_telepon,
        'is_member'         => true,
        'total_payment'     => $totalDibayar,
        'point_used'        => $pointUsed,
        'change'            => $uangKembalian,
        'tanggal_penjualan' => now()->timezone('Asia/Jakarta'),
    ]);

    // Simpan detail penjualan
    if (session()->has('selected_products')) {
        foreach (session('selected_products') as $item) {
            $produk = Produk::where('title', $item['nama_produk'])->first();
            if ($produk) {
                $penjualan->detailPenjualan()->create([
                    'produk_id' => $produk->id,
                    'qty'       => $item['qty'],
                    'price'     => $item['harga_produk'],
                    'sub_total' => $item['sub_total']
                ]);
                $produk->decrement('stock', $item['qty']);
            }
        }
    }

    // Tambahkan poin baru jika ada pembayaran
    if ($totalPayment > 0) {
        $poin_baru = floor($totalPayment / 100); // 1 poin per Rp 100
        $member->points += $poin_baru;
        $member->save();
    }

    // Hapus session
    session()->forget(['selected_products', 'total_payment']);

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
    $pdf = Pdf::loadView('petugas.pembelian.pdf', compact('pembelian'))->setPaper('A5', 'portrait');
    return $pdf->stream('struk-pembelian-' . $pembelian->invoice_number . '.pdf');
}

public function downloadPdf($id)
{
    $pembelian = Penjualan::with(['detailPenjualan.produk', 'user', 'members'])->findOrFail($id);
    $pdf = Pdf::loadView('petugas.pembelian.pdf', compact('pembelian'))->setPaper('A5', 'portrait');
    return $pdf->download('struk-pembelian-' . $pembelian->invoice_number . '.pdf');
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
        $penjualan = Penjualan::with(['detailPenjualan.produk', 'user', 'members'])->findOrFail($id);
        return view('petugas.pembelian.struk', compact('penjualan'));
    }

    public function member(Request $request)
    {
        $produk = Produk::find($request->produk_id);
        if (!$produk) {
            return back()->with('error', 'Produk tidak ditemukan.');
        }
    
        if ($produk->stock < $request->jumlah) {
            return back()->with('error', 'Stok produk tidak mencukupi.');
        }
    
        session([
            'selected_products' => [
                [
                    'nama_produk'  => $produk->title,
                    'qty'          => $request->jumlah,
                    'harga_produk' => $request->harga,
                    'sub_total'    => $request->harga * $request->jumlah
                ]
            ],
            'total_payment' => $request->total_payment
        ]);
    
        // Ambil data member dari input nomor telepon
        $customerPhone = $request->input('customer_phone');
        $member = Member::where('nomor_telepon', $customerPhone)->first();
    
        // Cek poin jika member ditemukan
        $point = $member ? $member->points : 0;
    
        return view('petugas.pembelian.member', [
            'selectedProducts' => session('selected_products'),
            'total_payment' => session('total_payment'),
            'member' => $member,
            'point' => $point
        ]);
    }
      
}