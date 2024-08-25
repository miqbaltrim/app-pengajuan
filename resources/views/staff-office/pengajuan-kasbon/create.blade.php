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
        position: relative; /* menambahkan positioning ke card */
    }

    .undo-button {
        position: absolute;
        top: 50px;
        right: 10px;
    }

    .barang-field {
        margin-bottom: 20px; /* tambahkan margin bottom agar ada ruang antara setiap bidang */
    }
</style>
<div class="container">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Buat Kasbon</h5>
                </div>
                <div class="card-body">
                    <!-- Formulir untuk membuat kasbon baru -->
                    <form action="{{ route('staff-office.pengajuan-kasbon.store') }}" method="POST" id="kasbon-form">
                        @csrf
                        <!-- Bagian "Keterangan" -->
                        <div class="mb-3">
                            <label for="keterangan" class="form-label">Keterangan:</label>
                            <textarea class="form-control" id="keterangan" name="keterangan" rows="3" required></textarea>
                        </div>
                        <!-- Bagian "Jumlah Kasbon" -->
                        <div class="mb-3">
                            <label for="jml_kasbon" class="form-label">Jumlah Kasbon:</label>
                            <input type="number" class="form-control" id="jml_kasbon" name="jml_kasbon" required>
                        </div>
                        <!-- Bagian "Cicilan" -->
                        <div class="mb-3">
                            <label for="cicilan" class="form-label">Cicilan:</label>
                            <select class="form-select" id="cicilan" name="cicilan" required>
                                @for ($i = 1; $i <= 12; $i++)
                                    <option value="{{ $i }}">{{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                        <!-- Bagian "Sisa Cicilan" -->
                        <div class="mb-3">
                            <label for="sisa_cicilan" class="form-label">Sisa Cicilan:</label>
                            <input type="number" class="form-control" id="sisa_cicilan" name="sisa_cicilan" readonly>
                        </div>
                        <!-- Bagian "Disetujui Oleh" -->
                        <div class="mb-3">
                            <label for="disetujui_oleh" class="form-label">Disetujui Oleh:</label>
                            <select class="form-select" id="disetujui_oleh" name="disetujui_oleh" required>
                                @foreach(['admin','direktur','manager-operasional','manager-territory','manager-keuangan','area-manager','kepala-cabang','kepala-gudang'] as $role)
                                    <option value="{{ $role }}">{{ ucfirst($role) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <!-- Bagian "Diketahui Oleh" -->
                        <div class="mb-3">
                            <label for="diketahui_oleh" class="form-label">Diketahui Oleh:</label>
                            <select class="form-select" id="diketahui_oleh" name="diketahui_oleh" required>
                                @foreach(['admin','direktur','manager-operasional','manager-territory','manager-keuangan','area-manager','kepala-cabang','kepala-gudang'] as $role)
                                    <option value="{{ $role }}">{{ ucfirst($role) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <!-- Tombol "Submit" -->
                        <div class="mb-3">
                            <button type="submit" class="btn btn-success">Buat Kasbon</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Menyertakan SweetAlert2 -->
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Script untuk validasi jumlah kasbon dan menghitung sisa cicilan -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Ambil data gaji dari elemen HTML atau variabel JavaScript
        const gaji = {{ auth()->user()->gaji->gaji ?? 0 }}; // Pastikan gaji tersedia di sini
        const totalKasbon = {{ $totalKasbon ?? 0 }}; // Total kasbon yang sudah diajukan
        const form = document.getElementById('kasbon-form');
        const jmlKasbonInput = document.getElementById('jml_kasbon');
        const cicilanSelect = document.getElementById('cicilan');
        const sisaCicilanInput = document.getElementById('sisa_cicilan');

        // Fungsi untuk menghitung sisa cicilan
        function hitungSisaCicilan() {
            const jmlKasbon = parseFloat(jmlKasbonInput.value);
            const cicilan = parseInt(cicilanSelect.value);
            if (!isNaN(jmlKasbon) && !isNaN(cicilan) && cicilan > 0) {
                const sisaCicilan = jmlKasbon / cicilan;
                sisaCicilanInput.value = sisaCicilan.toFixed(2);
            } else {
                sisaCicilanInput.value = '';
            }
        }

        jmlKasbonInput.addEventListener('input', hitungSisaCicilan);
        cicilanSelect.addEventListener('change', hitungSisaCicilan);

        form.addEventListener('submit', function (e) {
            const jmlKasbon = parseFloat(jmlKasbonInput.value);

            // Cek apakah jumlah kasbon baru melebihi sisa gaji
            if (jmlKasbon + totalKasbon > gaji) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Jumlah total kasbon tidak boleh melebihi gaji.',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'OK'
                });
            } else if (jmlKasbon === gaji) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Maaf, pengajuan tidak boleh sama dengan gaji Anda.',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'OK'
                });
            }
        });
    });
</script>
@endsection
