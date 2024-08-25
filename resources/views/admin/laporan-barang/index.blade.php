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
    .search-form {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }
    .search-form .form-group {
        flex: 1;
        min-width: 200px;
    }
    .search-form button {
        align-self: flex-end;
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
                    <h5 class="card-title">Data Pengajuan Barang</h5>
                    <!-- Add buttons or actions here if needed -->
                </div>
                <div class="card-body">
                    <!-- Form untuk pencarian -->
                    <a href="{{ url('admin/approve/barang') }}" class="btn btn-success mb-2">Approve Barang</a>
                    <form action="{{ route('admin.laporan-barang.search') }}" method="GET" class="search-form">
                        <div class="form-group">
                            <input type="text" class="form-control" name="nama" placeholder="Cari berdasarkan nama karyawan">
                        </div>
                        <div class="form-group">
                            <input type="number" class="form-control" name="tahun" placeholder="Tahun">
                        </div>
                        <div class="form-group">
                            <select class="form-control" name="bulan">
                                <option value="">Pilih Bulan</option>
                                @foreach(range(1, 12) as $month)
                                    <option value="{{ $month }}">{{ \Carbon\Carbon::create()->month($month)->format('F') }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Cari</button>
                    </form>

                    <!-- Table to display data -->
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nomor Referensi</th>
                                    <th>Tanggal</th>
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
                                        <td>{{ \Carbon\Carbon::parse($pengajuan->created_at)->format('Y-m-d') }}</td>
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

<script>
    // Mendapatkan elemen untuk tanggal terkini
    const currentDateElement = document.getElementById('current-date');
    const currentDate = new Date();
    const dateString = currentDate.toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
    currentDateElement.textContent = dateString;
</script>
@endsection
