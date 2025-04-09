@extends('layout.sidebar')
@section('content')
        <div class="page-wrapper">
            <div class="page-breadcrumb">
                <div class="row align-items-center">
                    <div class="col-6">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0 d-flex align-items-center">
                              <li class="breadcrumb-item"><a href="index.html" class="link"><i class="mdi mdi-home-outline fs-4"></i></a></li>
                              <li class="breadcrumb-item active" aria-current="page">Produk</li>
                            </ol>
                          </nav>
                        <h1 class="mb-0 fw-bold">Daftar Produk</h1> 
                    </div>
                </div>
            </div>
            <!-- ============================================================== -->
            <!-- Bread crumb and right sidebar toggle -->
            <!-- ============================================================== -->
            <div class="container mt-5">
                <div class="row">
                    <div class="col-md-12">

                        <div class="card border-0 shadow-sm rounded">
                            <div class="card-body">
                                <a href="{{ route('admin.produks.create') }}" class="btn btn-md btn-primary mb-3">Tambah Produk</a>
                                <table class="table table-bordered text-center">
                                    <thead class="table-light">
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Gambar</th>
                                            <th scope="col">Nama Produk</th>
                                            <th scope="col">Harga</th>
                                            <th scope="col">Stok</th>
                                            <th scope="col" style="width: 25%">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($produks as $key => $produk)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>
                                                <img src="{{ asset('/storage/produks/'.$produk->image) }}" class="rounded" style="width: 60px; height: 60px; object-fit: cover;">
                                            </td>
                                            <td>{{ $produk->title }}</td>
                                            <td>{{ "Rp " . number_format($produk->price,0,',','.') }}</td>
                                            <td>{{ $produk->stock }}</td>
                                            <td>
                                                <a href="{{ route('admin.produks.show', $produk->id) }}" class="btn btn-sm btn-dark">Show</a>
                                                <a href="{{ route('admin.produks.edit', $produk->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                                <form action="{{ route('admin.produks.destroy', $produk->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Apakah Anda yakin?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                                                </form>
                                        
                                                <!-- Tombol Tambah Stok (Modal Trigger) -->
                                                <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#tambahStokModal{{ $produk->id }}">
                                                    Tambah Stok
                                                </button>
                                        
                                                <!-- Modal Tambah Stok -->
                                                <div class="modal fade" id="tambahStokModal{{ $produk->id }}" tabindex="-1" aria-labelledby="tambahStokLabel{{ $produk->id }}" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="tambahStokLabel{{ $produk->id }}">Tambah Stok - {{ $produk->title }}</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <form action="{{ route('admin.produk.updateStock', $produk->id) }}" method="POST">
                                                                    @csrf
                                                                    <div class="mb-3">
                                                                        <label for="stock" class="form-label">Jumlah Stok</label>
                                                                        <input type="number" class="form-control" name="stock" min="1" required>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                                        <button type="submit" class="btn btn-primary">Tambah Stok</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        
                                        @endforeach
                                    </tbody>
                                </table>
                                {{ $produks->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
            <script>
                @if(session('success'))
                    Swal.fire({
                        icon: "success",
                        title: "BERHASIL",
                        text: "{{ session('success') }}",
                        showConfirmButton: false,
                        timer: 2000
                    });
                @elseif(session('error'))
                    Swal.fire({
                        icon: "error",
                        title: "GAGAL!",
                        text: "{{ session('error') }}",
                        showConfirmButton: false,
                        timer: 2000
                    });
                @endif
            </script>
@endsection