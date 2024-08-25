@extends('layouts.staff-office')

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
    .jumbotron-bg {
        background-color: #f8f9fa; /* Warna latar belakang */
        border-radius: 15px; /* Sudut bulat */
        padding: 20px; /* Padding */
        margin-bottom: 10px; /* Margin bawah */
        margin-left: 10px; /* Margin kiri */
        margin-right: 10px; /* Margin kanan */
        display: flex; /* Gunakan fleksibel layout */
        justify-content: space-between; /* Posisikan teks di ujung kiri dan kanan */
        align-items: center; /* Posisikan teks di tengah secara vertikal */
        height: 60px;
        margin-top: 25px; /* Tinggi jumbotron */
    }
</style>
<div class="container">
    <div class="row">
        <div class="col-lg-12">
          <div class="jumbotron jumbotron-bg">
            <p><strong>{{ Auth::user()->nama }} - Divisi {{ Auth::user()->position }}</strong></p>
            <p id="current-date">tanggal</p> 
          </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Data Pengajuan Barang</h5>
                </div>
                <div class="card-body">
                    <a href="{{ route('staff-office.pengajuan-barang.create') }}" class="btn btn-success mb-3">Buat Pengajuan</a>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Nomor</th>
                                    <th>Nomor Referensi</th>
                                    <th>Disetujui Oleh</th>
                                    <th>Diketahui Oleh</th>
                                    <th>Daftar Barang</th>
                                    <th>Approve Disetujui</th>
                                    <th>Approve Diketahui</th>
                                    <th>Alasan</th>
                                    <th>Actions</th>
                                    <th>File Nota</th> <!-- Tambahkan kolom untuk melihat file nota -->
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
                                        <td>{{ $pengajuan->alasan }}</td>
                                        <td>
                                            <!-- Tambahkan tautan edit dan formulir hapus hanya jika pengajuan belum disetujui atau belum diketahui -->
                                            @if($pengajuan->setujui !== 'diterima' || $pengajuan->ketahui !== 'diterima')
                                            <!-- Tambahkan tautan edit -->
                                            <a href="{{ route('staff-office.pengajuan-barang.edit', $pengajuan->id) }}" class="btn btn-primary btn-sm">Edit</a>

                                            <!-- Tambahkan formulir hapus -->
                                            <form action="{{ route('staff-office.pengajuan-barang.destroy', $pengajuan->id) }}" method="POST" style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                            </form>
                                            @endif

                                            <!-- Tambahkan tautan download surat jika sudah disetujui semua -->
                                            @if($pengajuan->setujui === 'diterima' && $pengajuan->ketahui === 'diterima')
                                                <a href="{{ route('staff-office.pengajuan-barang.download-surat', $pengajuan->id) }}" class="btn btn-info btn-sm">Download Surat</a>

                                                <!-- Tambahkan Form Upload Nota -->
                                                <form action="{{ route('staff-office.pengajuan-barang.upload-nota', $pengajuan->id) }}" method="POST" enctype="multipart/form-data" style="display: inline;">
                                                    @csrf
                                                    <input type="file" name="bukti_nota" accept="application/pdf" style="display: none;" id="uploadNota{{ $pengajuan->id }}" onchange="this.form.submit()">
                                                    <label for="uploadNota{{ $pengajuan->id }}" class="btn btn-warning btn-sm" style="cursor: pointer;">Upload Nota</label>
                                                </form>
                                            @endif
                                        </td>
                                        <td>
                                            <!-- Tampilkan link untuk melihat file nota jika ada -->
                                            @if($pengajuan->bukti_nota)
                                                <a href="{{ asset('storage/' . $pengajuan->bukti_nota) }}" target="_blank" class="btn btn-success btn-sm">Lihat Nota</a>
                                            @else
                                                <span class="text-danger">Belum Ada Nota</span>
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
<script>
    // Mendapatkan elemen untuk tanggal terkini
    const currentDateElement = document.getElementById('current-date');
    // Mendapatkan tanggal hari ini
    const currentDate = new Date();
    // Mendapatkan string tanggal dengan format tertentu
    const dateString = currentDate.toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
    // Menampilkan tanggal terkini pada elemen currentDateElement
    currentDateElement.textContent = dateString;
</script>
@endsection
