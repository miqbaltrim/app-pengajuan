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
                    <a href="{{ route('staff-office.pengajuan-sakit.create') }}" class="btn btn-primary mb-3">Ajukan Sakit</a>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>No</th>
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
                            @foreach ($sakits as $sakit)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $sakit->mulai_sakit }}</td>
                                    <td>{{ $sakit->selesai_sakit }}</td>
                                    <td>{{ $sakit->alasan }}</td>
                                    <td>{{ $sakit->disetujuiOleh->nama ?? 'Belum Disetujui' }}</td>
                                    <td>{{ $sakit->diketahuiOleh->nama ?? 'Belum Diketahui' }}</td>
                                    <td>{{ $sakit->setujui }}</td>
                                    <td>{{ $sakit->ketahui }}</td>
                                    <td>
                                        @if($sakit->setujui === 'diterima' && $sakit->ketahui === 'diterima')
                                            <a href="{{ route('staff-office.pengajuan-sakit.view', $sakit->id) }}" class="btn btn-primary btn-sm">Download Surat</a>
                                        @else
                                            <a href="{{ route('staff-office.pengajuan-sakit.edit', $sakit->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                            <form action="{{ route('staff-office.pengajuan-sakit.destroy', $sakit->id) }}" method="POST" class="d-inline delete-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn btn-danger btn-sm delete-button">Hapus</button>
                                            </form>
                                            @if($sakit->surat_dokter)
                                                <button type="button" class="btn btn-info btn-sm" onclick="lihatSuratDokter('{{ asset('storage/' . $sakit->surat_dokter) }}')">Lihat Surat Dokter</button>
                                            @endif
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

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- JavaScript untuk menampilkan popup -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: '{{ session('success') }}',
                confirmButtonText: 'OK'
            });
        @endif

        @if (session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: '{{ session('error') }}',
                confirmButtonText: 'OK'
            });
        @endif

        document.querySelectorAll('.delete-button').forEach(button => {
            button.addEventListener('click', function() {
                const form = this.closest('.delete-form');
                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: 'Apakah Anda yakin ingin menghapus pengajuan sakit ini?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    });

    function lihatSuratDokter(url) {
        Swal.fire({
            title: 'Surat Dokter',
            imageUrl: url,
            imageAlt: 'Surat Dokter',
            showCloseButton: true,
            showConfirmButton: false,
            footer: `<a href="${url}" download="Surat_Dokter.jpg" class="btn btn-primary mt-2">Unduh Surat Dokter</a>`
        });
    }

    const currentDateElement = document.getElementById('current-date');
    const currentDate = new Date();
    const dateString = currentDate.toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
    currentDateElement.textContent = dateString;
</script>
@endsection
