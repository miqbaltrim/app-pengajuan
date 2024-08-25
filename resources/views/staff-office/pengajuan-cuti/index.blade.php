@extends('layouts.staff-office')

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
                    <h5 class="card-title">Pengajuan Cuti</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        @if($remainingCuti > 0)
                            <a href="{{ route('staff-office.pengajuan-cuti.create') }}" class="btn btn-success">Ajukan Cuti</a>
                        @else
                            <p>Maaf, Anda tidak memiliki cuti tersisa.</p>
                            <form action="{{ route('staff-office.pengajuan-cuti.reset') }}" method="POST" style="display: inline;">
                                @csrf
                                <button type="submit" class="btn btn-primary">Reset Jumlah Cuti</button>
                            </form>
                        @endif
                        <div class="text" style="position: absolute; top: 10px; right: 10px;">
                            Sisa Cuti: {{ $remainingCuti }}
                        </div>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Nomor</th>
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
                                        @if($ajucuti->status == 'disetujui' || $ajucuti->status == 'selesai')
                                            <a href="{{ route('staff-office.pengajuan-cuti.view', $ajucuti->id) }}" class="btn btn-success btn-sm">View</a>
                                        @endif
                                        @if($ajucuti->status == 'tunggu')
                                            <a href="{{ route('staff-office.pengajuan-cuti.edit', $ajucuti->id) }}" class="btn btn-primary btn-sm">Edit</a>
                                            <form action="{{ route('staff-office.pengajuan-cuti.destroy', $ajucuti->id) }}" method="POST" style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm delete-btn">Delete</button>
                                            </form>
                                            <a href="https://wa.me/?text={{ rawurlencode(
                                                'Notifikasi Pengajuan Cuti dari *' . $ajucuti->user->nama . "*\n" .
                                                '--------------------------------'. "\n" .
                                                '*Tanggal:* ' . $ajucuti->created_at->format('Y-m-d') . "\n" .
                                                '*Mulai:* ' . $ajucuti->mulai_cuti . "\n" .
                                                '*Selesai:* ' . $ajucuti->selesai_cuti . "\n" .
                                                '*Alasan:* ' . substr($ajucuti->alasan, 0, 30) ."\n" .
                                                '*Status:* ' . $ajucuti->status . "\n\n" .
                                                'Link ke Detail: http://127.0.0.1:8000/login'
                                            ) }}" class="btn btn-info btn-sm">Kirim ke Manager</a>
                                        @endif
                                        @if ($ajucuti->status == 'ditolak')
    <form action="{{ route('staff-office.pengajuan-cuti.resubmit', $ajucuti->id) }}" method="POST">
        @csrf
        @method('PUT')
        <button type="submit" class="btn btn-primary">Ajukan Kembali</button>
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

    // JavaScript untuk penghapusan tanpa konfirmasi dan menampilkan popup
    document.querySelectorAll('.delete-btn').forEach(button => {
        button.addEventListener('click', function (e) {
            e.preventDefault(); // Mencegah pengiriman form default
            const form = this.closest('form'); // Selector yang benar untuk form
            form.submit();

            // Menampilkan popup sukses setelah pengiriman form
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: 'Pengajuan cuti telah dihapus.',
                confirmButtonText: 'OK'
            }).then(() => {
                location.reload(); // Memuat ulang halaman setelah popup ditutup
            });
        });
    });
</script>
<script>
    // Mendapatkan elemen untuk tanggal terkini
    const currentDateElement = document.getElementById('current-date');
    // Mendapatkan tanggal hari ini
    const currentDate = new Date();
    // Mendapatkan string tanggal dengan format tertentu
    const dateString = currentDate.toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
    // Menampilkan tanggal terkini pada elemen currentDateElement
    currentDateElement.textContent = dateString;
</script>
@endsection
