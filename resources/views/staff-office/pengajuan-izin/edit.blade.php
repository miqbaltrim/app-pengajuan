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
                    <h5 class="card-title">Edit Pengajuan Izin</h5>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form id="izinForm" action="{{ route('staff-office.pengajuan-izin.update', $izin->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="mulai_izin">Mulai Izin</label>
                            <input type="date" class="form-control" id="mulai_izin" name="mulai_izin" value="{{ $izin->mulai_izin }}" required>
                        </div>
                        <div class="form-group">
                            <label for="selesai_izin">Selesai Izin</label>
                            <input type="date" class="form-control" id="selesai_izin" name="selesai_izin" value="{{ $izin->selesai_izin }}" required>
                        </div>
                        <div class="form-group">
                            <label for="alasan">Alasan</label>
                            <textarea class="form-control" id="alasan" name="alasan" required>{{ $izin->alasan }}</textarea>
                        </div>
                        <div class="form-group">
                            <label for="disetujui_oleh">Disetujui Oleh</label>
                            <select class="form-control" id="disetujui_oleh" name="disetujui_oleh" required>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ $user->id == $izin->disetujui_oleh ? 'selected' : '' }}>
                                        {{ $user->nama }} - {{ $user->role }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="diketahui_oleh">Diketahui Oleh</label>
                            <select class="form-control" id="diketahui_oleh" name="diketahui_oleh" required>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ $user->id == $izin->diketahui_oleh ? 'selected' : '' }}>
                                        {{ $user->nama }} - {{ $user->role }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
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
        const izinForm = document.getElementById('izinForm');
        izinForm.addEventListener('submit', function (event) {
            const mulaiIzin = new Date(document.getElementById('mulai_izin').value);
            const selesaiIzin = new Date(document.getElementById('selesai_izin').value);

            if (selesaiIzin < mulaiIzin) {
                event.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Kesalahan',
                    text: 'Tanggal selesai izin harus sama atau setelah tanggal mulai izin.',
                    confirmButtonText: 'OK'
                });
            }
        });

        // Cek session flash message untuk pesan error
        @if (session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Izin Ditolak',
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
