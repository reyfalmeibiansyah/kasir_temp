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

        // Hitung total bayar
        $total = $request->total_payment;

        $penjualan = Penjualan::create([
            'member_id'         => $member ? $member->id : null,
            'invoice_number'    => $invoiceNumber,
            'tanggal_penjualan' => now(),
            'total_payment'     => $total,
            'user_id'           => Auth::id(),
            'point_used'        => 0,
            'change'            => 0,
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
    // Validasi input
    $request->validate([
        'nama_member'   => 'required|string|max:255',
        'total_payment' => 'required|numeric',
        'point_used'    => 'nullable|in:1',
    ]);

    $member = Member::where('nama_member', $request->nama_member)->first();

    if (!$member) {
        $member = Member::create([
            'nama_member'    => $request->nama_member,
            'nomor_telepon'  => $request->customer_phone ?? '-', // ambil dari input form jika ada
            'points'         => 0,
        ]);        
    }
    

    // Proses penggunaan poin (jika ada)
    $totalPayment = $request->total_payment;
    $pointUsed = 0;

    if ($request->has('point_used') && $member->points > 0) {
        $pointValue = 1000; // Tentukan nilai 1 poin
        $maxPotongan = $member->points * $pointValue;

        if ($maxPotongan > $totalPayment) {
            $pointUsed = ceil($totalPayment / $pointValue);
            $totalPayment = 0;
        } else {
            $pointUsed = $member->points;
            $totalPayment -= $maxPotongan;
        }

        // Kurangi poin dari member
        $member->points -= $pointUsed;
        $member->save();
    }


    // Simpan data penjualan
    $penjualan = Penjualan::create([
        'invoice_number'    => 'INV-' . now()->format('Ymd') . '-' . strtoupper(uniqid()),
        'user_id'           => Auth::id(),
        'member_id'         => $member->id,
        'customer_phone'    => $member->nomor_telepon,
        'is_member'         => true,
        'total_payment'     => $totalPayment,
        'point_used'        => $pointUsed,
        'change'            => 0,
        'tanggal_penjualan' => now()->timezone('Asia/Jakarta'),
    ]);

    // Simpan detail produk dari session
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

                // Update stok produk
                $produk->decrement('stock', $item['qty']);
            }
        }
    }

    // Tambahkan poin baru ke member setelah transaksi
    $poin_baru = floor($totalPayment / 100);  // 1 poin per 100 rupiah
    $member->points += $poin_baru;
    $member->save();

    // Hapus session produk yang dipilih
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
        // Validasi produk yang dipilih
        $produk = Produk::find($request->produk_id);
        if (!$produk) {
            return back()->with('error', 'Produk tidak ditemukan.');
        }
    
        // Validasi jumlah produk
        if ($produk->stock < $request->jumlah) {
            return back()->with('error', 'Stok produk tidak mencukupi.');
        }
    
        // Simpan data produk ke dalam session
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
    
        // Ambil data member dari input
        $nama_member = $request->input('nama_member');
        $member = Member::where('nama_member', $nama_member)->first();
    
        // Cek apakah member lama atau member baru
        if ($member) {
            // Member lama, ambil point-nya
            $point = $member->points;
        } else {
            // Member baru, point-nya 0
            $point = 0;
        }
    
        return view('petugas.pembelian.member', [
            'selectedProducts' => session('selected_products'),
            'total_payment' => session('total_payment'),
            'member' => $member,
            'point' => $point
        ]);
    }    
}