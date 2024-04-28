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
</style>
<div class="container">
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

@endsection
