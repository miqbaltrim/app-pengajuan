@extends('layouts.manager-operasional')

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
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Pengajuan Cuti</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        @if($totalCuti > 0)
                            <a href="{{ route('manager-operasional.pengajuan-cuti.create') }}" class="btn btn-success">Ajukan Cuti</a>
                        @else
                            <p>Maaf, Anda tidak memiliki cuti tersisa.</p>
                            <form action="{{ route('manager-operasional.pengajuan-cuti.reset') }}" method="POST" style="display: inline;">
                                @csrf
                                <button type="submit" class="btn btn-primary">Reset Jumlah Cuti</button>
                            </form>
                        @endif
                        <div class="text" style="position: absolute; top: 10px; right: 10px;">
                            Jumlah Cuti: {{ $totalCuti }}
                        </div>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Nomor</th>
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
                                        @if($ajucuti->status != 'ditolak' && $ajucuti->status == 'disetujui')
                                            <a href="{{ route('manager-operasional.pengajuan-cuti.view', $ajucuti->id) }}" class="btn btn-success btn-sm">View</a>
                                        @endif
                                        @if($ajucuti->status != 'ditolak' && $ajucuti->status != 'disetujui')
                                            <a href="{{ route('manager-operasional.pengajuan-cuti.edit', $ajucuti->id) }}" class="btn btn-primary btn-sm">Edit</a>
                                            <form action="{{ route('manager-operasional.pengajuan-cuti.destroy', $ajucuti->id) }}" method="POST" style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
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
