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
                    <h5 class="card-title">Ajukan Cuti</h5>
                </div>
                <div class="card-body">
                    <form id="cutiForm" action="{{ route('staff-office.pengajuan-cuti.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="mulai_cuti">Mulai Cuti</label>
                            <input type="date" class="form-control" id="mulai_cuti" name="mulai_cuti" required>
                        </div>
                        <div class="form-group">
                            <label for="selesai_cuti">Selesai Cuti</label>
                            <input type="date" class="form-control" id="selesai_cuti" name="selesai_cuti" required>
                        </div>
                        <div class="form-group">
                            <label for="alasan">Alasan</label>
                            <textarea class="form-control" id="alasan" name="alasan" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="approved">Approved</label>
                            <select class="form-control" id="approved" name="approved" required>
                                <option value="admin">Admin</option>
                                <option value="direktur">Direktur</option>
                                <option value="manager-operasional">Manager Operasional</option>
                                <option value="manager-territory">Manager Territory</option>
                                <option value="manager-keuangan">Manager Keuangan</option>
                                <option value="area-manager">Area Manager</option>
                                <option value="kepala-cabang">Kepala Cabang</option>
                                <option value="kepala-gudang">Kepala Gudang</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Ajukan Cuti</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- JavaScript untuk menampilkan popup -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const cutiForm = document.getElementById('cutiForm');
        cutiForm.addEventListener('submit', function (event) {
            const mulaiCuti = new Date(document.getElementById('mulai_cuti').value);
            const selesaiCuti = new Date(document.getElementById('selesai_cuti').value);

            if (selesaiCuti < mulaiCuti) {
                event.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Kesalahan',
                    text: 'Tanggal selesai cuti harus sama atau setelah tanggal mulai cuti.',
                    confirmButtonText: 'OK'
                });
            }
        });
    });
    
    // Menampilkan tanggal terkini
    const currentDateElement = document.getElementById('current-date');
    const currentDate = new Date();
    const dateString = currentDate.toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
    currentDateElement.textContent = dateString;
</script>
@endsection
