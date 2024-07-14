@extends('layouts.direktur')

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
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title">Formulir Pengajuan Cuti</h5>
                    <a href="{{ route('direktur.pengajuan-cuti.index') }}" class="btn btn-danger">Back</a>
                </div>
                <div class="card-body">
                    <!-- Alert untuk menampilkan pesan kesalahan -->
                    @if(session('error'))
                        <div class="alert alert-danger" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif
                    <form action="{{ route('direktur.pengajuan-cuti.update', $ajucuti->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="mulai_cuti" class="form-label">Tanggal Mulai Cuti</label>
                            <input type="date" class="form-control" id="mulai_cuti" name="mulai_cuti" value="{{ $ajucuti->mulai_cuti }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="selesai_cuti" class="form-label">Tanggal Selesai Cuti</label>
                            <input type="date" class="form-control" id="selesai_cuti" name="selesai_cuti" value="{{ $ajucuti->selesai_cuti }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="alasan" class="form-label">Alasan Cuti</label>
                            <textarea class="form-control" id="alasan" name="alasan" rows="3" required>{{ $ajucuti->alasan }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label for="approved" class="form-label">Approve</label>
                            <select class="form-select" id="approved" name="approved" required>
                                <option value="admin" @if($ajucuti->approved == 'admin') selected @endif>Admin</option>
                                <option value="direktur" @if($ajucuti->approved == 'direktur') selected @endif>Direktur</option>
                                <option value="manager-operasional" @if($ajucuti->approved == 'manager-operasional') selected @endif>Manager Operasional</option>
                                <option value="manager-territory" @if($ajucuti->approved == 'manager-territory') selected @endif>Manager Territory</option>
                                <option value="manager-keuangan" @if($ajucuti->approved == 'manager-keuangan') selected @endif>Manager Keuangan</option>
                                <option value="area-manager" @if($ajucuti->approved == 'area-manager') selected @endif>Area Manager</option>
                                <option value="kepala-cabang" @if($ajucuti->approved == 'kepala-cabang') selected @endif>Kepala Cabang</option>
                                <option value="kepala-gudang" @if($ajucuti->approved == 'kepala-gudang') selected @endif>Kepala Gudang</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Update Pengajuan Cuti</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
