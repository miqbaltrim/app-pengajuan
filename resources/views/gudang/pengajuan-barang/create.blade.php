@extends('layouts.gudang')

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
                    <h5 class="card-title">Buat Pengajuan Barang</h5>
                </div>
                <div class="card-body">
                    <!-- Tombol "Undo" -->
                    <div class="undo-button">
                        <button type="button" id="undo" class="btn btn-warning">Undo</button>
                        <a href="/pengajuan" class="btn btn-danger">Back</a>
                    </div>
                    <!-- Formulir untuk menambahkan barang baru -->
                    <form action="{{ route('gudang.pengajuan-barang.store') }}" method="POST" id="barang-form">
                        @csrf
                        <div class="mb-3">
                            <label for="nomor_referensi" class="form-label">Nomor Referensi:</label>
                            <input type="text" class="form-control" id="nomor_referensi" name="nomor_referensi" value="{{ $nextReferenceNumber }}" readonly required>
                        </div>
                        <!-- Bagian "Disetujui Oleh" -->
                        <div class="mb-3">
                            <label for="disetujui_oleh" class="form-label">Disetujui Oleh:</label>
                            <select class="form-select" id="disetujui_oleh" name="disetujui_oleh" required>
                                <!-- Tampilkan opsi berdasarkan daftar peran -->
                                @foreach(['direktur','manager-operasional','manager-territory','manager-keuangan','area-manager','kepala-cabang','kepala-gudang'] as $role)
                                    <option value="{{ $role }}">{{ ucfirst($role) }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Bagian "Diketahui Oleh" -->
                        <div class="mb-3">
                            <label for="diketahui_oleh" class="form-label">Diketahui Oleh:</label>
                            <select class="form-select" id="diketahui_oleh" name="diketahui_oleh" required>
                                <!-- Tampilkan opsi berdasarkan daftar peran -->
                                @foreach(['direktur','manager-operasional','manager-territory','manager-keuangan','area-manager','kepala-cabang','kepala-gudang'] as $role)
                                    <option value="{{ $role }}">{{ ucfirst($role) }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <!-- Tambahkan bidang input untuk setiap barang -->
                        <div id="barang-fields">
                            <div class="barang-field">
                                <div class="mb-3">
                                    <label for="nama_barang[]" class="form-label">Nama Barang:</label>
                                    <input type="text" class="form-control" name="nama_barang[]" required>
                                </div>
                                <div class="mb-3">
                                    <label for="qty[]" class="form-label">Jumlah:</label>
                                    <input type="number" class="form-control" name="qty[]" required>
                                </div>
                                <div class="mb-3">
                                    <label for="harga_satuan[]" class="form-label">Harga Satuan:</label>
                                    <input type="number" class="form-control" name="harga_satuan[]" required>
                                </div>
                                <div class="mb-3">
                                    <label for="total[]" class="form-label">Total:</label>
                                    <input type="number" class="form-control total" name="total[]" readonly required>
                                </div>
                            </div>
                        </div>
                        <!-- Tombol "Tambah Barang" -->
                        <div class="mb-3">
                            <button type="button" id="add-barang" class="btn btn-primary">Tambah Barang</button>
                        </div>
                        <!-- Tombol "Submit" -->
                        <div class="mb-3">
                            <button type="submit" class="btn btn-success">Simpan Pengajuan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    document.getElementById('add-barang').addEventListener('click', function() {
    var field = document.querySelector('.barang-field').cloneNode(true);
    // Bersihkan nilai bidang input
    field.querySelectorAll('input').forEach(function(input) {
        input.value = '';
    });
    document.getElementById('barang-fields').appendChild(field);
    
    // Panggil fungsi untuk mengikat event listener pada input baru
    bindEventListeners();
});

// Fungsi untuk mengikat event listener pada setiap input
function bindEventListeners() {
    var qtyInputs = document.querySelectorAll('input[name="qty[]"]');
    var hargaSatuanInputs = document.querySelectorAll('input[name="harga_satuan[]"]');
    
    qtyInputs.forEach(function(qtyInput, index) {
        qtyInput.addEventListener('input', function() {
            updateTotal(index);
        });
    });
    
    hargaSatuanInputs.forEach(function(hargaSatuanInput, index) {
        hargaSatuanInput.addEventListener('input', function() {
            updateTotal(index);
        });
    });
}

// Fungsi untuk menghitung total
function updateTotal(index) {
    var qtyInputs = document.querySelectorAll('input[name="qty[]"]');
    var hargaSatuanInputs = document.querySelectorAll('input[name="harga_satuan[]"]');
    var totalInputs = document.querySelectorAll('.total');
    
    var qty = parseFloat(qtyInputs[index].value);
    var hargaSatuan = parseFloat(hargaSatuanInputs[index].value);
    var total = qty * hargaSatuan;
    totalInputs[index].value = total.toFixed(2);
}

// Panggil fungsi untuk mengikat event listener pada input yang sudah ada saat halaman dimuat
bindEventListeners();

document.getElementById('undo').addEventListener('click', function() {
    var lastField = document.querySelector('#barang-fields .barang-field:last-child');
    if (lastField) {
        lastField.remove();
    }
});

function padWithZeroes(number, length) {
    var padded = number.toString();
    while (padded.length < length) {
        padded = '0' + padded;
    }
    return padded;
}
</script>

@endsection
