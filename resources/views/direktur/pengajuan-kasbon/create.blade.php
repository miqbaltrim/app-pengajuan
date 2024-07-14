@extends('layouts.direktur')

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
                    <form action="{{ route('direktur.pengajuan-kasbon.store') }}" method="POST" id="kasbon-form">
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
@endsection
