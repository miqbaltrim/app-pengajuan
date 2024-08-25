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
                    <h5 class="card-title">Ajukan Sakit</h5>
                </div>
                <div class="card-body">
                    <form id="sakitForm" action="{{ route('staff-office.pengajuan-sakit.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="mulai_sakit">Mulai Sakit</label>
                            <input type="date" class="form-control" id="mulai_sakit" name="mulai_sakit" required>
                        </div>
                        <div class="form-group">
                            <label for="selesai_sakit">Selesai Sakit</label>
                            <input type="date" class="form-control" id="selesai_sakit" name="selesai_sakit" required>
                        </div>
                        <div class="form-group">
                            <label for="alasan">Alasan</label>
                            <textarea class="form-control" id="alasan" name="alasan" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="disetujui_oleh">Disetujui Oleh</label>
                            <select class="form-control" id="disetujui_oleh" name="disetujui_oleh" required>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="diketahui_oleh">Diketahui Oleh</label>
                            <select class="form-control" id="diketahui_oleh" name="diketahui_oleh" required>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="surat_dokter">Surat Dokter (Jika lebih dari 3 hari wajib diupload)</label>
                            <input type="file" class="form-control" id="surat_dokter" name="surat_dokter" accept="image/*">
                        </div>
                        <button type="submit" class="btn btn-primary">Ajukan Sakit</button>
                    </form>
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
        const sakitForm = document.getElementById('sakitForm');
        sakitForm.addEventListener('submit', function (event) {
            const mulaiSakit = new Date(document.getElementById('mulai_sakit').value);
            const selesaiSakit = new Date(document.getElementById('selesai_sakit').value);
            const daysRequested = (selesaiSakit - mulaiSakit) / (1000 * 60 * 60 * 24) + 1;

            if (daysRequested > 3 && !document.getElementById('surat_dokter').value) {
                event.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Kesalahan',
                    text: 'Jika izin sakit lebih dari 3 hari, wajib mengunggah surat dokter.',
                    confirmButtonText: 'OK'
                });
            }

            if (selesaiSakit < mulaiSakit) {
                event.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Kesalahan',
                    text: 'Tanggal selesai sakit harus sama atau setelah tanggal mulai sakit.',
                    confirmButtonText: 'OK'
                });
            }
        });

        // Cek session flash message untuk pesan error
        @if (session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Pengajuan Ditolak',
                text: '{{ session('error') }}',
                confirmButtonText: 'OK'
            });
        @endif
    });

    // Menampilkan tanggal terkini
    const currentDateElement = document.getElementById('current-date');
    const currentDate = new Date();
    const dateString = currentDate.toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
    currentDateElement.textContent = dateString;
</script>
@endsection
