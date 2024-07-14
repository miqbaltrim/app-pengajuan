@extends('layouts.area-manager')

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
                    <h5 class="card-title">Edit Pengajuan Barang</h5>
                </div>
                <div class="card-body">
                    <div class="undo-button">
                        <button type="button" id="undo" class="btn btn-warning">Undo</button>
                        <a href="/pengajuan" class="btn btn-danger">Back</a>
                    </div>
                    <!-- Tampilkan daftar barang yang sudah diajukan -->
                    <div class="mb-3">
                        <h5>Daftar Barang:</h5>
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Nama Barang</th>
                                    <th>Jumlah</th>
                                    <th>Harga Satuan</th>
                                    <th>Total</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pengajuan->barangs as $barang)
                                    <tr>
                                        <td>{{ $barang->nama_barang }}</td>
                                        <td>{{ $barang->qty }}</td>
                                        <td>{{ $barang->harga_satuan }}</td>
                                        <td>{{ $barang->total }}</td>
                                        <td>
                                            <form action="{{ route('area-manager.pengajuan-barang.delete-item', ['id' => $pengajuan->id, 'item_id' => $barang->id]) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!-- Formulir untuk mengedit pengajuan barang -->
                    <form action="{{ route('area-manager.pengajuan-barang.update', $pengajuan->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="nomor_referensi" class="form-label">Nomor Referensi:</label>
                            <input type="text" class="form-control" id="nomor_referensi" name="nomor_referensi" value="{{ $pengajuan->nomor_referensi }}" readonly required>
                        </div>
                        <!-- Bagian "Disetujui Oleh" -->
                        <div class="mb-3">
                            <label for="disetujui_oleh" class="form-label">Disetujui Oleh:</label>
                            <select class="form-select" id="disetujui_oleh" name="disetujui_oleh" required>
                                <!-- Tampilkan opsi berdasarkan daftar peran -->
                                @foreach(['direktur','manager-operasional','manager-territory','manager-keuangan','area-manager','kepala-cabang','kepala-gudang'] as $role)
                                    <option value="{{ $role }}" {{ $pengajuan->disetujui_oleh == $role ? 'selected' : '' }}>{{ ucfirst($role) }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Bagian "Diketahui Oleh" -->
                        <div class="mb-3">
                            <label for="diketahui_oleh" class="form-label">Diketahui Oleh:</label>
                            <select class="form-select" id="diketahui_oleh" name="diketahui_oleh" required>
                                <!-- Tampilkan opsi berdasarkan daftar peran -->
                                @foreach(['direktur','manager-operasional','manager-territory','manager-keuangan','area-manager','kepala-cabang','kepala-gudang'] as $role)
                                    <option value="{{ $role }}" {{ $pengajuan->diketahui_oleh == $role ? 'selected' : '' }}>{{ ucfirst($role) }}</option>
                                @endforeach
                            </select>
                        </div>
  
                        <!-- Tambahkan bidang input untuk setiap barang -->
                        <div id="barang-fields" data-barangs="{{ json_encode($pengajuan->barangs) }}">
                            @foreach($pengajuan->barangs as $barang)
                                <div class="barang-field">
                                    <div class="mb-3">
                                        <label for="nama_barang[]" class="form-label">Nama Barang:</label>
                                        <input type="text" class="form-control" name="nama_barang[]" value="{{ $barang->nama_barang }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="qty[]" class="form-label">Jumlah:</label>
                                        <input type="number" class="form-control" name="qty[]" value="{{ $barang->qty }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="harga_satuan[]" class="form-label">Harga Satuan:</label>
                                        <input type="number" class="form-control" name="harga_satuan[]" value="{{ $barang->harga_satuan }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="total[]" class="form-label">Total:</label>
                                        <input type="number" class="form-control total" name="total[]" value="{{ $barang->total }}" readonly required>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <!-- Tombol "Tambah Barang" -->
                        <div class="mb-3">
                            <button type="button" id="add-barang" class="btn btn-primary">Tambah Barang</button>
                        </div>
                        <!-- Tombol "Simpan Perubahan" -->
                        <div class="mb-3">
                            <button type="submit" class="btn btn-success">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    // Panggil fungsi untuk mengikat event listener pada input baru
    bindEventListeners();

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

        qtyInputs.forEach(function(qtyInput) {
            qtyInput.addEventListener('input', function() {
                updateTotal();
            });
        });

        hargaSatuanInputs.forEach(function(hargaSatuanInput) {
            hargaSatuanInput.addEventListener('input', function() {
                updateTotal();
            });
        });
    }

    // Fungsi untuk menghitung total
    function updateTotal() {
        var qtyInputs = document.querySelectorAll('input[name="qty[]"]');
        var hargaSatuanInputs = document.querySelectorAll('input[name="harga_satuan[]"]');
        var totalInputs = document.querySelectorAll('.total');

        // Loop melalui setiap barang dan perbarui total
        qtyInputs.forEach(function(qtyInput, index) {
            var qty = parseFloat(qtyInput.value);
            var hargaSatuan = parseFloat(hargaSatuanInputs[index].value);
            var total = qty * hargaSatuan;
            totalInputs[index].value = total.toFixed(2);
        });
    }

    // Isi nilai input untuk setiap barang yang sudah ada
    var barangs = JSON.parse(document.getElementById('barang-fields').dataset.barangs);
    var fields = document.querySelectorAll('.barang-field');
    fields.forEach(function(field, index) {
        var nama_barang = field.querySelector('input[name="nama_barang[]"]');
        var qty = field.querySelector('input[name="qty[]"]');
        var harga_satuan = field.querySelector('input[name="harga_satuan[]"]');
        var total = field.querySelector('.total');
        
        // Ambil data barang dari JSON
        var barang = barangs[index];
        
        // Isi nilai input dengan data barang
        nama_barang.value = barang.nama_barang;
        qty.value = barang.qty;
        harga_satuan.value = barang.harga_satuan;
        total.value = barang.total;
    });

    // Tambahkan event listener untuk tombol "Undo"
    document.getElementById('undo').addEventListener('click', function() {
        var lastField = document.querySelector('#barang-fields .barang-field:last-child');
        if (lastField) {
            lastField.remove();
        }
    });
</script>

@endsection
