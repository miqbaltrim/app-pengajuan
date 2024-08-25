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
        background-color: #f8f9fa; /* Warna latar belakang */
        border-radius: 15px; /* Sudut bulat */
        padding: 20px; /* Padding */
        margin-bottom: 10px; /* Margin bawah */
        margin-left: 10px; /* Margin kiri */
        margin-right: 10px; /* Margin kanan */
        display: flex; /* Gunakan fleksibel layout */
        justify-content: space-between; /* Posisikan teks di ujung kiri dan kanan */
        align-items: center; /* Posisikan teks di tengah secara vertikal */
        height: 60px;
        margin-top: 25px; /* Tinggi jumbotron */
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
                    <h5 class="card-title">Pengajuan Izin</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Nomor</th>
                                    <th>Nama Karyawan</th>
                                    <th>Mulai Izin</th>
                                    <th>Selesai Izin</th>
                                    <th>Alasan</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($izins as $izin)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $izin->dibuatOleh->nama }}</td>
                                    <td>{{ $izin->mulai_izin }}</td>
                                    <td>{{ $izin->selesai_izin }}</td>
                                    <td>{{ $izin->alasan }}</td>
                                    <td>{{ $izin->setujui }}</td>
                                    <td>
                                        @if($izin->setujui == 'tunggu' && $izin->disetujui_oleh == Auth::user()->id)
                                            <form action="{{ route('manager-operasional.approve.izin.disetujui.terima', $izin->id) }}" method="POST" style="display: inline;">
                                                @csrf
                                                <button type="submit" class="btn btn-primary btn-sm">Setujui</button>
                                            </form>
                                            <form action="{{ route('manager-operasional.approve.izin.disetujui.tolak', $izin->id) }}" method="POST" style="display: inline;">
                                                @csrf
                                                <button type="submit" class="btn btn-danger btn-sm">Tolak</button>
                                            </form>
                                        @endif
                                        @if($izin->ketahui == 'tunggu' && $izin->diketahui_oleh == Auth::user()->id)
                                            <form action="{{ route('manager-operasional.approve.izin.diketahui.terima', $izin->id) }}" method="POST" style="display: inline;">
                                                @csrf
                                                <button type="submit" class="btn btn-primary btn-sm">Ketahui</button>
                                            </form>
                                            <form action="{{ route('manager-operasional.approve.izin.diketahui.tolak', $izin->id) }}" method="POST" style="display: inline;">
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
            <!-- Tabel Riwayat Pengajuan Izin -->
            <div class="card mt-5">
                <div class="card-header">
                    <h5 class="card-title">Riwayat Pengajuan Izin</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Nomor</th>
                                    <th>Nama Karyawan</th>
                                    <th>Mulai Izin</th>
                                    <th>Selesai Izin</th>
                                    <th>Alasan</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($riwayatIzins as $izin)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $izin->dibuatOleh->nama }}</td>
                                    <td>{{ $izin->mulai_izin }}</td>
                                    <td>{{ $izin->selesai_izin }}</td>
                                    <td>{{ $izin->alasan }}</td>
                                    <td>{{ ucfirst($izin->setujui) }}</td>
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
