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
                <div class="card-header">
                    <h5 class="card-title">Daftar Pengajuan Sakit</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Karyawan</th>
                                    <th>Mulai Sakit</th>
                                    <th>Selesai Sakit</th>
                                    <th>Alasan</th>
                                    <th>Disetujui Oleh</th>
                                    <th>Diketahui Oleh</th>
                                    <th>Status Setujui</th>
                                    <th>Status Ketahui</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($sakits as $sakit)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $sakit->dibuatOleh->nama }}</td>
                                    <td>{{ $sakit->mulai_sakit }}</td>
                                    <td>{{ $sakit->selesai_sakit }}</td>
                                    <td>{{ $sakit->alasan }}</td>
                                    <td>{{ $sakit->disetujuiOleh->nama ?? 'Belum Disetujui' }}</td>
                                    <td>{{ $sakit->diketahuiOleh->nama ?? 'Belum Diketahui' }}</td>
                                    <td>{{ $sakit->setujui }}</td>
                                    <td>{{ $sakit->ketahui }}</td>
                                    <td>
                                        @if($sakit->setujui == 'tunggu' && $sakit->disetujui_oleh == Auth::user()->id)
                                            <form action="{{ route('area-manager.approve.sakit.disetujui-terima', $sakit->id) }}" method="POST" style="display: inline;">
                                                @csrf
                                                <button type="submit" class="btn btn-primary btn-sm">Setujui</button>
                                            </form>
                                            <form action="{{ route('area-manager.approve.sakit.disetujui-tolak', $sakit->id) }}" method="POST" style="display: inline;">
                                                @csrf
                                                <button type="submit" class="btn btn-danger btn-sm">Tolak</button>
                                            </form>
                                        @endif
                                        @if($sakit->ketahui == 'tunggu' && $sakit->diketahui_oleh == Auth::user()->id)
                                            <form action="{{ route('area-manager.approve.sakit.diketahui-terima', $sakit->id) }}" method="POST" style="display: inline;">
                                                @csrf
                                                <button type="submit" class="btn btn-primary btn-sm">Ketahui</button>
                                            </form>
                                            <form action="{{ route('area-manager.approve.sakit.diketahui-tolak', $sakit->id) }}" method="POST" style="display: inline;">
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
            
            <!-- Tabel Riwayat Pengajuan Sakit -->
            <div class="card mt-5">
                <div class="card-header">
                    <h5 class="card-title">Riwayat Pengajuan Sakit</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Karyawan</th>
                                    <th>Mulai Sakit</th>
                                    <th>Selesai Sakit</th>
                                    <th>Alasan</th>
                                    <th>Disetujui Oleh</th>
                                    <th>Diketahui Oleh</th>
                                    <th>Status Setujui</th>
                                    <th>Status Ketahui</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($riwayatSakits as $sakit)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $sakit->dibuatOleh->nama }}</td>
                                    <td>{{ $sakit->mulai_sakit }}</td>
                                    <td>{{ $sakit->selesai_sakit }}</td>
                                    <td>{{ $sakit->alasan }}</td>
                                    <td>{{ $sakit->disetujuiOleh->nama ?? 'Belum Disetujui' }}</td>
                                    <td>{{ $sakit->diketahuiOleh->nama ?? 'Belum Diketahui' }}</td>
                                    <td>{{ ucfirst($sakit->setujui) }}</td>
                                    <td>{{ ucfirst($sakit->ketahui) }}</td>
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
    document.addEventListener('DOMContentLoaded', function () {
        const successMessage = "{{ session('success') }}";
        const errorMessage = "{{ session('error') }}";
        if (successMessage) {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: successMessage,
                confirmButtonText: 'OK'
            });
        } else if (errorMessage) {
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: errorMessage,
                confirmButtonText: 'OK'
            });
        }
    });

    // Mendapatkan elemen untuk tanggal terkini
    const currentDateElement = document.getElementById('current-date');
    const currentDate = new Date();
    const dateString = currentDate.toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
    currentDateElement.textContent = dateString;
</script>
@endsection
