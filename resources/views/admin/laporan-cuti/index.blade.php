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
    .btn-custom-orange {
        background-color: #fd7e14; /* Oranye */
        color: white;
        border: none;
    }
    .btn-custom-orange:hover {
        background-color: #e36209; /* Oranye lebih gelap saat hover */
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
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title">Laporan Cuti Karyawan</h5>
                    <div class="ml-auto">
                        <a href="#" class="btn btn-danger mr-2" onclick="window.history.back()">Back</a>
                        <button class="btn btn-success" onclick="printTable()">Cetak</button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <form action="{{ route('admin.laporan-cuti.search') }}" method="GET" class="form-inline">
                            <div class="d-flex align-items-start">
                                <div class="form-group mx-sm-2 mb-2">
                                    <label for="tahun" class="sr-only">Cari Data Berdasarkan Tahun:</label>
                                    <input type="text" class="form-control" id="tahun" name="tahun" placeholder="Tahun" style="width: 100px;">
                                </div>
                                <div class="form-group mx-sm-2 mb-2">
                                    <label for="nama" class="sr-only">Cari Data Berdasarkan Nama:</label>
                                    <input type="text" class="form-control" id="nama" name="nama" placeholder="Nama" style="width: 200px;">
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary mb-2 mr-2">Cari</button>
                            <a href="{{ url('admin/laporan-cuti/riwayat-cuti') }}" class="btn btn-custom-orange mb-2">Riwayat Cuti</a>
                            <a href="{{ url('admin/approve/cuti') }}" class="btn btn-success  mb-2">Approve Cuti</a>
                        </form>
                    </div>
                    
                    <div class="table-responsive" id="printableTable">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Karyawan</th>
                                    <th>Tahun</th>
                                    <th>Position</th>
                                    <th>Terpakai</th>
                                    <th>Sisa</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($laporanCuti as $laporan)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $laporan['nama'] ?? '' }}</td>
                                    <td>{{ $laporan['tahun'] ?? '' }}</td>
                                    <td>{{ $laporan['position'] ?? '' }}</td>
                                    <td>{{ $laporan['terpakai'] ?? '' }}</td>
                                    <td>{{ $laporan['sisa'] ?? '' }}</td>
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

<script>
    function printTable() {
        var printContents = document.getElementById("printableTable").innerHTML;
        var originalContents = document.body.innerHTML;
        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;
    }
</script>
<script>
    // Mendapatkan elemen untuk tanggal terkini
    const currentDateElement = document.getElementById('current-date');
    const currentDate = new Date();
    const dateString = currentDate.toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
    currentDateElement.textContent = dateString;
</script>

@endsection
