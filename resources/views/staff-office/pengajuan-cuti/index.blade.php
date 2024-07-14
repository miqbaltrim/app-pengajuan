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
                        @if($totalCuti > 0)
                            <a href="{{ route('staff-office.pengajuan-cuti.create') }}" class="btn btn-success">Ajukan Cuti</a>
                        @else
                            <p>Maaf, Anda tidak memiliki cuti tersisa.</p>
                            <form action="{{ route('staff-office.pengajuan-cuti.reset') }}" method="POST" style="display: inline;">
                                @csrf
                                <button type="submit" class="btn btn-primary">Reset Jumlah Cuti</button>
                            </form>
                        @endif
                        <div class="text" style="position: absolute; top: 10px; right: 10px;">
                            Jumlah Cuti: {{ $totalCuti }}
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
                                        @if($ajucuti->status != 'ditolak' && $ajucuti->status == 'disetujui')
                                            <a href="{{ route('staff-office.pengajuan-cuti.view', $ajucuti->id) }}" class="btn btn-success btn-sm">View</a>
                                        @endif
                                        @if($ajucuti->status != 'ditolak' && $ajucuti->status != 'disetujui')
                                            <a href="{{ route('staff-office.pengajuan-cuti.edit', $ajucuti->id) }}" class="btn btn-primary btn-sm">Edit</a>
                                            <form action="{{ route('staff-office.pengajuan-cuti.destroy', $ajucuti->id) }}" method="POST" style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm delete-btn">Delete</button>
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

    // JavaScript untuk konfirmasi penghapusan
    document.querySelectorAll('.delete-btn').forEach(button => {
        button.addEventListener('click', function () {
            const form = this.closest('.delete-form');
            Swal.fire({
                title: 'Anda yakin?',
                text: "Pengajuan cuti akan dihapus dan tidak bisa dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus!'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
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
