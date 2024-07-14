@extends('layouts.area-manager')

@section('content')
<style>
    .main-panel {
        position: relative;
        background: #E4E9F7;
        min-height: 100vh;
        top: 0;
        left: 78px;
        width: calc(100% - 78px);
        transition: all 0.5s ease;
        z-index: 2;
    }
    .sidebar.open ~ .main-panel {
        left: 250px;
        width: calc(100% - 250px);
    }
    .main-panel .text {
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
                    <h5 class="card-title">Daftar Pengajuan Kasbon</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Pengaju</th>
                                    <th>Keterangan</th>
                                    <th>Jumlah Kasbon</th>
                                    <th>Cicilan</th>
                                    <th>Disetujui Oleh</th>
                                    <th>Diketahui Oleh</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($kasbons as $kasbon)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $kasbon->user->nama }}</td>
                                    <td>{{ $kasbon->keterangan }}</td>
                                    <td>{{ $kasbon->jml_kasbon }}</td>
                                    <td>{{ $kasbon->cicilan }}</td>
                                    <td>{{ $kasbon->disetujuiOleh->nama ?? '-' }}</td>
                                    <td>{{ $kasbon->diketahuiOleh->nama ?? '-' }}</td>
                                    <td>
                                        @if($kasbon->setujui === 'tunggu' && $kasbon->disetujui_oleh == auth()->user()->id)
                                            <form action="{{ route('area-manager.kasbon.disetujuiTerima', $kasbon->id) }}" method="POST" style="display: inline;">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="btn btn-success btn-sm">Setujui</button>
                                            </form>
                                            <form action="{{ route('area-manager.kasbon.disetujuiTolak', $kasbon->id) }}" method="POST" style="display: inline;">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="btn btn-danger btn-sm">Tolak</button>
                                            </form>
                                        @elseif($kasbon->ketahui === 'tunggu' && $kasbon->diketahui_oleh == auth()->user()->id)
                                            <form action="{{ route('area-manager.kasbon.diketahuiTerima', $kasbon->id) }}" method="POST" style="display: inline;">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="btn btn-success btn-sm">Setujui</button>
                                            </form>
                                            <form action="{{ route('area-manager.kasbon.diketahuiTolak', $kasbon->id) }}" method="POST" style="display: inline;">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="btn btn-danger btn-sm">Tolak</button>
                                            </form>
                                        @else
                                            <button class="btn btn-{{ $kasbon->setujui === 'diterima' ? 'success' : 'danger' }} btn-sm" disabled>{{ ucfirst($kasbon->setujui ?? $kasbon->ketahui) }}</button>
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
