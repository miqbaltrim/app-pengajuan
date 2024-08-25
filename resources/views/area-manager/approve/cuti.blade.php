@extends('layouts.area-manager')

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
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Daftar Pengajuan Cuti</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
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
                                        @if($ajucuti->status == 'tunggu')
                                        <form action="{{ route('approved', ['id' => $ajucuti->id, 'action' => 'disetujui']) }}" method="POST" style="display: inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-success btn-sm" onclick="confirmApprove('{{ $ajucuti->id }}')">Setuju</button>
                                        </form>
                                        <form action="{{ route('rejected', ['id' => $ajucuti->id, 'action' => 'ditolak']) }}" method="POST" style="display: inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="confirmReject('{{ $ajucuti->id }}')">Tolak</button>
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
            
            <!-- Tabel Riwayat Pengajuan Cuti -->
            <div class="card mt-5">
                <div class="card-header">
                    <h5 class="card-title">Riwayat Pengajuan Cuti</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Karyawan</th>
                                    <th>Mulai Cuti</th>
                                    <th>Selesai Cuti</th>
                                    <th>Alasan</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($riwayatCutis as $ajucuti)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $ajucuti->user->nama }}</td>
                                    <td>{{ $ajucuti->mulai_cuti }}</td>
                                    <td>{{ $ajucuti->selesai_cuti }}</td>
                                    <td>{{ $ajucuti->alasan }}</td>
                                    <td>{{ ucfirst($ajucuti->status) }}</td>
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

<!-- SweetAlert2 library -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    function confirmApprove(id) {
        Swal.fire({
            title: 'Konfirmasi',
            text: "Apakah Anda ingin menyetujui pengajuan cuti ini?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, setujui!'
        }).then((result) => {
            if (result.isConfirmed) {
                // Submit the form for approval
                document.querySelector(`form[action='/area-manager/approve/${id}']`).submit();
            }
        });
    }

    function confirmReject(id) {
        Swal.fire({
            title: 'Konfirmasi',
            text: "Apakah Anda ingin menolak pengajuan cuti ini?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, tolak!'
        }).then((result) => {
            if (result.isConfirmed) {
                // Submit the form for rejection
                document.querySelector(`form[action='/area-manager/reject/${id}']`).submit();
            }
        });
    }

    // Mendapatkan elemen untuk tanggal terkini
    document.addEventListener('DOMContentLoaded', function () {
        const currentDateElement = document.getElementById('current-date');
        const currentDate = new Date();
        const dateString = currentDate.toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
        currentDateElement.textContent = dateString;
    });
</script>
@endsection
