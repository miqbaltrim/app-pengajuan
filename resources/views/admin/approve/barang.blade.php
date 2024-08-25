@extends('layouts.admin')

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
        margin: 5px;
    }
    .card {
        margin-top: 20px;
    }
    .jumbotron-bg {
        background-color: #f8f9fa;
        border-radius: 15px;
        padding: 20px;
        margin-bottom: 10px;
        margin-left: 10px;
        margin-right: 10px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        height: 60px;
        margin-top: 25px;
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
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Daftar Pengajuan Barang</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Karyawan</th>
                                    <th>Daftar Barang</th>
                                    <th>Disetujui Oleh</th>
                                    <th>Diketahui Oleh</th>
                                    <th>Disetujui</th>
                                    <th>Diketahui</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pengajuanBarang as $pengajuan)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $pengajuan->user->nama }}</td>
                                    <td>
                                        <ul>
                                            @foreach($pengajuan->barangs as $barang)
                                            <li>{{ $barang->nama_barang }} - {{ $barang->qty }}</li>
                                            @endforeach
                                        </ul>
                                    </td>
                                    <td>{{ $pengajuan->disetujuiOleh->role ?? 'Belum Disetujui' }}</td>
                                    <td>{{ $pengajuan->diketahuiOleh->role ?? 'Belum Diketahui' }}</td>
                                    <td>{{ $pengajuan->setujui }}</td>
                                    <td>{{ $pengajuan->ketahui }}</td>
                                    <td>
                                        <a href="{{ route('admin.approve.barang.detailBarang', ['id' => $pengajuan->id]) }}" class="btn btn-info btn-sm">Detail</a>
                                        @php
                                            $userRole = Auth::user()->role;
                                            $disetujuiOleh = $pengajuan->disetujuiOleh->role ?? '';
                                            $diketahuiOleh = $pengajuan->diketahuiOleh->role ?? '';
                                        @endphp

                                        {{-- Button Disetujui --}}
                                        @if($disetujuiOleh === $userRole && $pengajuan->setujui === 'tunggu')
                                            <form action="{{ route('admin.approve.barang.disetujui-terima', ['id' => $pengajuan->id]) }}" method="POST" style="display: inline;">
                                                @csrf
                                                <button type="submit" class="btn btn-success btn-sm">Setujui</button>
                                            </form>
                                            <form action="{{ route('admin.approve.barang.disetujui-tolak', ['id' => $pengajuan->id]) }}" method="POST" style="display: inline;">
                                                @csrf
                                                <button type="submit" class="btn btn-danger btn-sm">Tolak</button>
                                            </form>
                                        @endif

                                        {{-- Button Diketahui --}}
                                        @if($diketahuiOleh === $userRole && $pengajuan->ketahui === 'tunggu')
                                            <form action="{{ route('admin.approve.barang.diketahui-terima', ['id' => $pengajuan->id]) }}" method="POST" style="display: inline;">
                                                @csrf
                                                <button type="submit" class="btn btn-info btn-sm">Ketahui</button>
                                            </form>
                                            <form action="{{ route('admin.approve.barang.diketahui-tolak', ['id' => $pengajuan->id]) }}" method="POST" style="display: inline;">
                                                @csrf
                                                <button type="submit" class="btn btn-danger btn-sm">Tolak</button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- Tabel Riwayat Pengajuan Barang -->
            <div class="card mt-5">
                <div class="card-header">
                    <h5 class="card-title">Riwayat Pengajuan Barang</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Karyawan</th>
                                    <th>Daftar Barang</th>
                                    <th>Disetujui Oleh</th>
                                    <th>Diketahui Oleh</th>
                                    <th>Disetujui</th>
                                    <th>Diketahui</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($riwayatPengajuan as $pengajuan)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $pengajuan->user->nama }}</td>
                                    <td>
                                        <ul>
                                            @foreach($pengajuan->barangs as $barang)
                                            <li>{{ $barang->nama_barang }} - {{ $barang->qty }}</li>
                                            @endforeach
                                        </ul>
                                    </td>
                                    <td>{{ $pengajuan->disetujuiOleh->role ?? 'Belum Disetujui' }}</td>
                                    <td>{{ $pengajuan->diketahuiOleh->role ?? 'Belum Diketahui' }}</td>
                                    <td>{{ ucfirst($pengajuan->setujui) }}</td>
                                    <td>{{ ucfirst($pengajuan->ketahui) }}</td>
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
<!-- JavaScript untuk menampilkan popup -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Mendapatkan elemen untuk tanggal terkini
    const currentDateElement = document.getElementById('current-date');
    const currentDate = new Date();
    const dateString = currentDate.toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
    currentDateElement.textContent = dateString;
</script>
@endsection
