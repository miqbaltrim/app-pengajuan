@extends('layouts.manager-keuangan')

@section('content')
<style>
  .main-panel{
      position: relative;
      background: #E4E9F7;
      min-height: 100vh;
      top: 0;
      left: 78px;
      width: calc(100% - 78px);
      transition: all 0.5s ease;
      z-index: 2;
    }
    .sidebar.open ~ .main-panel{
      left: 250px;
      width: calc(100% - 250px);
    }
    .main-panel .text{
      display: inline-block;
      color: #11101d;
      font-size: 25px;
      font-weight: 500;
      margin: 5px
    }
    /* Pagination styling */
    .page {
        display: flex;
        justify-content: center;
        align-items: center;
        margin-top: 20px;
    }

    .page__btn,
    .page__numbers {
        display: flex;
        justify-content: center;
        align-items: center;
        margin: 0 5px;
        font-size: 16px;
        cursor: pointer;
        color: #000; /* warna teks default hitam */
    }

    .page__dots {
        color: #6c757d;
    }

    .active a {
        color: #007bff; /* warna teks biru hanya untuk yang aktif */
    }

    .inactive a {
        color: #000; /* warna teks hitam untuk yang tidak aktif */
    }

</style>
<div class="container">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Data Pengajuan Barang</h5>
                </div>
                <div class="card-body">
                    <a href="{{ route('manager-keuangan.pengajuan-barang.create') }}" class="btn btn-success mb-3">Buat Pengajuan</a>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Nomor</th>
                                    <th>Nomor Referensi</th>
                                    <th>Dibuat Oleh</th>
                                    <th>Disetujui Oleh</th>
                                    <th>Diketahui Oleh</th>
                                    <th>Daftar Barang</th>
                                    <th>Approve Disetujui</th>
                                    <th>Approve Diketahui</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $startNumber = ($pengajuans->currentPage() - 1) * $pengajuans->perPage() + 1;
                                @endphp
                                @foreach($pengajuans as $pengajuan)
                                    <tr>
                                        <td>{{ $startNumber++ }}</td>
                                        <td>{{ $pengajuan->nomor_referensi }}</td>
                                        <td>{{ $pengajuan->dibuatOleh->nama ?? 'User Tidak Ditemukan' }}</td> <!-- Menampilkan nama pengguna yang membuat pengajuan -->
                                        <td>{{ $pengajuan->disetujuiOleh->role ?? 'User Tidak Ditemukan' }}</td>
                                        <td>{{ $pengajuan->diketahuiOleh->role ?? 'User Tidak Ditemukan' }}</td>
                                        <td>
                                            <!-- Tampilkan daftar barang terkait pengajuan -->
                                            <ul>
                                                @foreach($pengajuan->barangs as $barang)
                                                    <li>{{ $barang->nama_barang }} ({{ $barang->qty }})</li>
                                                @endforeach
                                            </ul>
                                        </td>
                                        <td>{{ $pengajuan->setujui }}</td>
                                        <td>{{ $pengajuan->ketahui }}</td>
                                        <td>
                                            <!-- Tambahkan tautan edit dan formulir hapus hanya jika pengajuan belum disetujui atau belum diketahui -->
                                            @if($pengajuan->setujui !== 'diterima' || $pengajuan->ketahui !== 'diterima')
                                            <!-- Tambahkan tautan edit -->
                                            <a href="{{ route('manager-keuangan.pengajuan-barang.edit', $pengajuan->id) }}" class="btn btn-primary btn-sm">Edit</a>

                                            <!-- Tambahkan formulir hapus -->
                                            <form action="{{ route('manager-keuangan.pengajuan-barang.destroy', $pengajuan->id) }}" method="POST" style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                            </form>
                                            @endif

                                            <!-- Tambahkan tautan download surat jika sudah disetujui semua -->
                                            @if($pengajuan->setujui === 'diterima' && $pengajuan->ketahui === 'diterima')
                                                <a href="{{ route('manager-keuangan.pengajuan-barang.download-surat', $pengajuan->id) }}" class="btn btn-info btn-sm">Download Surat</a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-center">
                        <ul class="page">
                            <li class="page__btn">
                                @if ($pengajuans->onFirstPage())
                                    <span class="bx bx-chevron-left inactive"></span>
                                @else
                                    <a href="{{ $pengajuans->previousPageUrl() }}"><span class="bx bx-chevron-left"></span></a>
                                @endif
                            </li>
                            @for ($i = 1; $i <= $pengajuans->lastPage(); $i++)
                                <li class="page__numbers @if ($pengajuans->currentPage() == $i) active @else inactive @endif">
                                    <a href="{{ $pengajuans->setPath(url()->current())->url($i) }}">{{ $i }}</a>
                                </li>
                            @endfor
                            <li class="page__btn">
                                @if ($pengajuans->hasMorePages())
                                    <a href="{{ $pengajuans->setPath(url()->current())->nextPageUrl() }}"><span class="bx bx-chevron-right"></span></a>
                                @else
                                    <span class="bx bx-chevron-right inactive"></span>
                                @endif
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
