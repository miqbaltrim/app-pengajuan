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
                                            <form action="{{ route('manager-operasional.kasbon.disetujuiTerima', $kasbon->id) }}" method="POST" style="display: inline;">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="btn btn-success btn-sm">Setujui</button>
                                            </form>
                                            <form action="{{ route('manager-operasional.kasbon.disetujuiTolak', $kasbon->id) }}" method="POST" style="display: inline;">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="btn btn-danger btn-sm">Tolak</button>
                                            </form>
                                        @elseif($kasbon->ketahui === 'tunggu' && $kasbon->diketahui_oleh == auth()->user()->id)
                                            <form action="{{ route('manager-operasional.kasbon.diketahuiTerima', $kasbon->id) }}" method="POST" style="display: inline;">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="btn btn-success btn-sm">Setujui</button>
                                            </form>
                                            <form action="{{ route('manager-operasional.kasbon.diketahuiTolak', $kasbon->id) }}" method="POST" style="display: inline;">
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
            <!-- Tabel Riwayat Kasbon -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Riwayat Kasbon</h5>
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
                                    <th>Status Persetujuan</th>
                                    <th>Status Diketahui</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($kasbonHistory as $history)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $history->user->nama }}</td>
                                    <td>{{ $history->keterangan }}</td>
                                    <td>{{ number_format($history->jml_kasbon, 0, ',', '.') }}</td>
                                    <td>{{ $history->cicilan }}</td>
                                    <td>{{ $history->disetujuiOleh->nama ?? '-' }}</td>
                                    <td>{{ $history->diketahuiOleh->nama ?? '-' }}</td>
                                    <td>{{ ucfirst($history->setujui) }}</td>
                                    <td>{{ ucfirst($history->ketahui) }}</td>
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
<!-- JavaScript untuk menampilkan popup -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>

    // Mendapatkan elemen untuk tanggal terkini
    const currentDateElement = document.getElementById('current-date');
    const currentDate = new Date();
    const dateString = currentDate.toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
    currentDateElement.textContent = dateString;
</script>
@endsection
