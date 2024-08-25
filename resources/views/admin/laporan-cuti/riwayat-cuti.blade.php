@extends('layouts.admin')

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
    .modal {
        display: none; 
        position: fixed; 
        z-index: 1050; 
        left: 0;
        top: 0;
        width: 100%; 
        height: 100%; 
        overflow: hidden; 
        background-color: rgba(0,0,0,0.5); 
    }
    .modal-content {
        background-color: #fff;
        margin: 10% auto;
        padding: 20px;
        border: 1px solid #888;
        width: 80%;
        max-width: 600px;
        border-radius: 8px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.3);
        position: relative;
        text-align: left;
    }
    .modal-header, .modal-body, .modal-footer {
        padding: 10px 20px;
    }
    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px solid #e9ecef;
    }
    .modal-header h2 {
        margin: 0;
    }
    .close {
        color: #aaa;
        font-size: 24px;
        font-weight: bold;
        cursor: pointer;
    }
    .close:hover,
    .close:focus {
        color: #000;
        text-decoration: none;
    }
    .modal-body p {
        margin: 10px 0;
    }
    .modal-footer {
        display: flex;
        justify-content: flex-end;
        border-top: 1px solid #e9ecef;
    }
</style>
<div class="container">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title">Riwayat Cuti Karyawan</h5>
                    <div class="ml-auto">
                        <a href="#" class="btn btn-danger mr-2" onclick="window.history.back()">Back</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <form action="{{ route('admin.laporan-cuti.searchByMonth') }}" method="POST" class="form-inline">
                            @csrf
                            <div class="d-flex align-items-start">
                                <div class="form-group mx-sm-2 mb-2">
                                    <label for="tahun" class="sr-only">Tahun:</label>
                                    <input type="text" class="form-control" id="tahun" name="tahun" placeholder="Tahun" style="width: 100px;">
                                </div>
                                <div class="form-group mx-sm-2 mb-2">
                                    <label for="bulan" class="sr-only">Bulan:</label>
                                    <select class="form-control" id="bulan" name="bulan">
                                        <option value="">Pilih Bulan</option>
                                        @foreach(range(1, 12) as $month)
                                            <option value="{{ $month }}">{{ \Carbon\Carbon::create()->month($month)->format('F') }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary mb-2 mr-2">Cari</button>
                        </form>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Karyawan</th>
                                    <th>Mulai Cuti</th>
                                    <th>Selesai Cuti</th>
                                    <th>Detail</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($riwayatCuti as $ajucuti)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $ajucuti->user->nama }}</td>
                                    <td>{{ $ajucuti->mulai_cuti }}</td>
                                    <td>{{ $ajucuti->selesai_cuti }}</td>
                                    <td><button class="btn btn-primary" onclick="openModal({{ $ajucuti->id }})">Detail</button></td>
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

@foreach($riwayatCuti as $ajucuti)
<div id="modal-{{ $ajucuti->id }}" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Detail Pengajuan Cuti</h2>
            <span class="close" onclick="closeModal({{ $ajucuti->id }})">&times;</span>
        </div>
        <div class="modal-body">
            <p><strong>Nama Karyawan:</strong> {{ $ajucuti->user->nama }}</p>
            <p><strong>Mulai Cuti:</strong> {{ $ajucuti->mulai_cuti }}</p>
            <p><strong>Selesai Cuti:</strong> {{ $ajucuti->selesai_cuti }}</p>
            <p><strong>Jumlah Hari:</strong> {{ \Carbon\Carbon::parse($ajucuti->mulai_cuti)->diffInDays(\Carbon\Carbon::parse($ajucuti->selesai_cuti)) + 1 }}</p>
            <p><strong>Alasan:</strong> {{ $ajucuti->alasan }}</p>
            <p><strong>Status:</strong> {{ $ajucuti->status }}</p>
            <p><strong>Disetujui Oleh:</strong> {{ $ajucuti->approved }}</p>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" onclick="closeModal({{ $ajucuti->id }})">Close</button>
        </div>
    </div>
</div>
@endforeach

<script>
    function openModal(id) {
        document.getElementById('modal-' + id).style.display = 'block';
    }

    function closeModal(id) {
        document.getElementById('modal-' + id).style.display = 'none';
    }

    window.onclick = function(event) {
        var modals = document.getElementsByClassName('modal');
        for (var i = 0; i < modals.length; i++) {
            if (event.target == modals[i]) {
                modals[i].style.display = 'none';
            }
        }
    }
</script>

@endsection
