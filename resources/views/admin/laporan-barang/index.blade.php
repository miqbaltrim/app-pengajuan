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
                    <h5 class="card-title">Data Pengajuan Barang</h5>
                    <!-- Add buttons or actions here if needed -->
                </div>
                <div class="card-body">
                                        <!-- Form untuk pencarian -->
                    <form action="{{ route('admin.laporan-barang.search') }}" method="GET" class="search-form">
                        <div class="form-group">
                            <input type="text" class="form-control" name="nama" placeholder="Cari berdasarkan nama karyawan">
                        </div>
                        <button type="submit" class="btn btn-primary">Cari</button>
                    </form> <!-- Penutup form -->
                    <!-- Table to display data -->
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nomor Referensi</th>
                                    <th>Dibuat Oleh</th>
                                    <th>Disetujui Oleh</th>
                                    <th>Diketahui Oleh</th>
                                    <th>Setujui</th>
                                    <th>Ketahui</th>
                                    <th>Action</th> <!-- New column for Action -->
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Loop through your data to populate the table -->
                                @foreach($pengajuanBarang as $pengajuan)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $pengajuan->nomor_referensi }}</td>
                                        <td>{{ $pengajuan->dibuatOleh->nama }}</td>
                                        <td>{{ $pengajuan->disetujuiOleh->nama }}</td>
                                        <td>{{ $pengajuan->diketahuiOleh->nama }}</td>
                                        <td>{{ $pengajuan->setujui }}</td>
                                        <td>{{ $pengajuan->ketahui }}</td>
                                        <td> <!-- New column for Action -->
                                            <a href="{{ route('admin.laporan-barang.detail', $pengajuan->id) }}" class="btn btn-primary">Detail</a> <!-- Link to detail page -->
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

<!-- Include any additional scripts or styles if needed -->
@endsection
