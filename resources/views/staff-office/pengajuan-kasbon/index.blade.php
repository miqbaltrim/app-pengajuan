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
</style>
<div class="container">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Data Pengajuan Barang</h5>
                </div>
                <div class="card-body">
                    <a href="{{ route('staff-office.pengajuan-kasbon.create') }}" class="btn btn-success mb-3">Buat Pengajuan</a>
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
                                @foreach($pengajuans as $pengajuan)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
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
                                            <!-- Tambahkan tautan edit -->
                                            <a href="{{ route('staff-office.pengajuan-kasbon.edit', $pengajuan->id) }}" class="btn btn-primary btn-sm">Edit</a>
                                            
                                            <!-- Tambahkan formulir hapus -->
                                            <form action="{{ route('staff-office.pengajuan-kasbon.destroy', $pengajuan->id) }}" method="POST" style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                            </form>
                                            
                                            <!-- Tambahkan tautan download surat jika sudah disetujui semua -->
                                            @if($pengajuan->setujui === 'diterima' && $pengajuan->ketahui === 'diterima')
                                                <a href="{{ route('staff-office.pengajuan-kasbon.download-surat', $pengajuan->id) }}" class="btn btn-info btn-sm">Download Surat</a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
