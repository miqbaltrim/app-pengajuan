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
                    <h5 class="card-title">Daftar Pengajuan Cuti</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Karyawan</th>
                                    <th>Mulai Cuti</th>
                                    <th>Selesai Cuti</th>
                                    <th>Alasan</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($ajucutis as $ajucuti)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $ajucuti->user->nama }}</td>
                                    <td>{{ $ajucuti->mulai_cuti }}</td>
                                    <td>{{ $ajucuti->selesai_cuti }}</td>
                                    <td>{{ $ajucuti->alasan }}</td>
                                    <td>{{ $ajucuti->status }}</td>
                                    <td>
                                        @if($ajucuti->status == 'tunggu')
                                        <form action="{{ route('approved', ['id' => $ajucuti->id, 'action' => 'disetujui']) }}" method="POST" style="display: inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-success btn-sm">Setuju</button>
                                        </form>
                                        <form action="{{ route('rejected', ['id' => $ajucuti->id, 'action' => 'ditolak']) }}" method="POST" style="display: inline;">
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
