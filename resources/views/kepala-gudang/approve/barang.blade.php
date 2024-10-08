@extends('layouts.kepala-gudang')

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
</style>
<div class="container">
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
                                        <a href="{{ route('kepala-gudang.approve.barang.detailBarang', ['id' => $pengajuan->id]) }}" class="btn btn-info btn-sm">Detail</a>
                                        @php
                                            $userRole = Auth::user()->role;
                                            $disetujuiOleh = $pengajuan->disetujuiOleh->role ?? '';
                                            $diketahuiOleh = $pengajuan->diketahuiOleh->role ?? '';
                                        @endphp

                                        {{-- Button Disetujui --}}
                                        @if($disetujuiOleh === $userRole && $pengajuan->setujui === 'tunggu')
                                            <form action="{{ route('kepala-gudang.approve.barang.disetujui-terima', ['id' => $pengajuan->id]) }}" method="POST" style="display: inline;">
                                                @csrf
                                                <button type="submit" class="btn btn-success btn-sm">Setujui</button>
                                            </form>
                                            <form action="{{ route('kepala-gudang.approve.barang.disetujui-tolak', ['id' => $pengajuan->id]) }}" method="POST" style="display: inline;">
                                                @csrf
                                                <button type="submit" class="btn btn-danger btn-sm">Tolak</button>
                                            </form>
                                        @endif

                                        {{-- Button Diketahui --}}
                                        @if($diketahuiOleh === $userRole && $pengajuan->ketahui === 'tunggu')
                                            <form action="{{ route('kepala-gudang.approve.barang.diketahui-terima', ['id' => $pengajuan->id]) }}" method="POST" style="display: inline;">
                                                @csrf
                                                <button type="submit" class="btn btn-info btn-sm">Ketahui</button>
                                            </form>
                                            <form action="{{ route('kepala-gudang.approve.barang.diketahui-tolak', ['id' => $pengajuan->id]) }}" method="POST" style="display: inline;">
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
        </div>
    </div>
</div>
@endsection
