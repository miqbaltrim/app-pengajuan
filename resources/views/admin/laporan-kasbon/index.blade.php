@extends('layouts.admin')

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
    .modal {
        display: none; 
        position: fixed; 
        z-index: 1050; 
        left: 0;
        top: 0;
        width: 100%; 
        height: 100%; 
        overflow: hidden; 
        background-color: rgba(0,0,0,0.5); 
    }
    .modal-content {
        background-color: #fff;
        margin: 10% auto;
        padding: 20px;
        border: 1px solid #888;
        width: 80%;
        max-width: 600px;
        border-radius: 8px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.3);
        position: relative;
        text-align: left;
    }
    .modal-header, .modal-body, .modal-footer {
        padding: 10px 20px;
    }
    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px solid #e9ecef;
    }
    .modal-header h2 {
        margin: 0;
    }
    .close {
        color: #aaa;
        font-size: 24px;
        font-weight: bold;
        cursor: pointer;
    }
    .close:hover,
    .close:focus {
        color: #000;
        text-decoration: none;
    }
    .modal-body p {
        margin: 10px 0;
    }
    .modal-footer {
        display: flex;
        justify-content: flex-end;
        border-top: 1px solid #e9ecef;
    }
    .form-inline .form-group {
        margin-right: 10px;
    }
    .form-inline .form-control {
        width: auto;
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
    .sidebar.open ~ .main-panel{
        left: 250px;
        width: calc(100% - 250px);
    }
    .main-panel .text{
        display: inline-block;
        color: #11101d;
        font-size: 25px;
        font-weight: 500;
        margin: 5px;
    }
    .card {
        margin-top: 20px;
    }
    .search-form {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }
    .search-form .form-group {
        flex: 1;
        min-width: 200px;
    }
    .search-form button {
        align-self: flex-end;
    }
    .status-rounded {
        display: inline-block;
        padding: 5px 10px;
        border-radius: 12px;
        color: white;
        font-weight: bold;
    }
    .status-lunas {
        background-color: green;
    }
    .status-belum-lunas {
        background-color: red;
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
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title">Data Pengajuan Kasbon</h5>
                </div>
                <div class="card-body">
                    <!-- Form untuk pencarian -->
                    <a href="{{ url('admin/approve/kasbon') }}" class="btn btn-success mb-2">Approve Kasbon</a>
                    <form action="{{ route('admin.laporan-barang.search') }}" method="GET" class="search-form">
                        <div class="form-group">
                            <input type="text" class="form-control" name="nama" placeholder="Cari berdasarkan nama karyawan">
                        </div>
                        <div class="form-group">
                            <input type="number" class="form-control" name="tahun" placeholder="Tahun">
                        </div>
                        <div class="form-group">
                            <select class="form-control" name="bulan">
                                <option value="">Pilih Bulan</option>
                                @foreach(range(1, 12) as $month)
                                    <option value="{{ $month }}">{{ \Carbon\Carbon::create()->month($month)->format('F') }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Cari</button>
                    </form>

                    <!-- Table to display data -->
                    <div class="table-responsive mt-3">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Karyawan</th>
                                    <th>Tanggal</th>
                                    <th>Nominal Kasbon</th>
                                    <th>Cicilan</th>
                                    <th>Sisa Kasbon</th>
                                    <th>Masukan Sisanya</th>
                                    <th>Status Kasbon</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Loop through your data to populate the table -->
                                @foreach($kasbonPengajuan as $kasbon)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $kasbon->user->nama }}</td>
                                        <td>{{ \Carbon\Carbon::parse($kasbon->created_at)->format('Y-m-d') }}</td>
                                        <td>{{ 'Rp ' . number_format($kasbon->jml_kasbon, 0, ',', '.') }}</td>
                                        <td>{{ $kasbon->cicilan }}</td>
                                        <td>{{ 'Rp ' . number_format($kasbon->sisa_cicilan, 0, ',', '.') }}</td>
                                        <td>
                                            <form action="{{ route('admin.laporan-kasbon.update', $kasbon->id) }}" method="POST" class="input-group">
                                                @csrf
                                                @method('PUT')
                                                <input type="text" name="sisa_cicilan" value="{{ number_format($kasbon->sisa_cicilan, 2, ',', '.') }}" class="form-control format-rupiah" placeholder="Masukkan sisa cicilan">
                                                <button type="submit" class="btn btn-primary">Simpan</button>
                                            </form>
                                        </td>
                                        <td>{{ $kasbon->status_cicilan}}</td>
                                        <td><button class="btn btn-primary" onclick="openModal({{ $kasbon->id }})">Detail</button></td>
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

@foreach($kasbonPengajuan as $kasbon)
<div id="modal-{{ $kasbon->id }}" class="modal" >
    <div class="modal-content">
        <div class="modal-header">
            <h2>Detail Pengajuan Kasbon</h2>
            <span class="close" onclick="closeModal({{ $kasbon->id }})">&times;</span>
        </div>
        <div class="modal-body" id="kasbon-content-{{ $kasbon->id }}">
            <table style="width: 100%; border-collapse: collapse;">
                <h5>Detail Tagihan Kasbon {{ $kasbon->user->nama }}</h5>
                <tr>
                    <th style="text-align: left; padding: 8px;">Nama Karyawan</th>
                    <td style="padding: 8px;">: {{ $kasbon->user->nama }}</td>
                </tr>
                <tr>
                    <th style="text-align: left; padding: 8px;">Tanggal</th>
                    <td style="padding: 8px;">: {{ \Carbon\Carbon::parse($kasbon->created_at)->format('Y-m-d H:i:s') }}</td>
                </tr>
                <tr>
                    <th style="text-align: left; padding: 8px;">Nominal Kasbon</th>
                    <td style="padding: 8px;">: {{ 'Rp ' . number_format($kasbon->jml_kasbon, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <th style="text-align: left; padding: 8px;">Keterangan</th>
                    <td style="padding: 8px;">: {{ $kasbon->keterangan }}</td>
                </tr>
                <tr>
                    <th style="text-align: left; padding: 8px;">Sisa Cicilan</th>
                    <td style="padding: 8px;">: {{ 'Rp ' . number_format($kasbon->sisa_cicilan, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <th style="text-align: left; padding: 8px;">Status Kasbon</th>
                    <td style="padding: 8px;"> :
                        <span class="status-rounded {{ $kasbon->status_cicilan == 'lunas' ? 'status-lunas' : 'status-belum-lunas' }}">
                            {{ $kasbon->status_cicilan }}
                        </span>
                    </td>
                </tr>
            </table>
        </div>
        <div class="modal-footer">
            <button class="btn btn-primary" onclick="generatePDF({{ $kasbon->id }})">Print</button>
            <button class="btn btn-secondary" onclick="closeModal({{ $kasbon->id }})">Close</button>
        </div>
    </div>
</div>

@endforeach
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        @if(session('success'))
            Swal.fire({
                title: 'Berhasil!',
                text: '{{ session('success') }}',
                icon: 'success',
                confirmButtonText: 'OK'
            });
        @elseif(session('error'))
            Swal.fire({
                title: 'Gagal!',
                text: '{{ session('error') }}',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        @endif
    });
</script>
<script>
    function openModal(id) {
        document.getElementById('modal-' + id).style.display = 'block';
    }

    function closeModal(id) {
        document.getElementById('modal-' + id).style.display = 'none';
    }

    window.onclick = function(event) {
        var modals = document.getElementsByClassName('modal');
        for (var i = 0; i < modals.length; i++) {
            if (event.target == modals[i]) {
                modals[i].style.display = 'none';
            }
        }
    }
    function formatRupiah(angka, prefix) {
        var number_string = angka.replace(/[^,\d]/g, '').toString(),
            split = number_string.split(','),
            sisa = split[0].length % 3,
            rupiah = split[0].substr(0, sisa),
            ribuan = split[0].substr(sisa).match(/\d{3}/gi);

        // Tambahkan titik jika yang diinput sudah menjadi angka ribuan
        if (ribuan) {
            separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }

        rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
        return prefix == undefined ? rupiah : (rupiah ? 'Rp ' + rupiah : '');
    }

    document.querySelectorAll('.format-rupiah').forEach(function(element) {
        element.addEventListener('keyup', function(e) {
            this.value = formatRupiah(this.value, 'Rp ');
        });
    });
</script>
<script>
    // Mendapatkan elemen untuk tanggal terkini
    const currentDateElement = document.getElementById('current-date');
    const currentDate = new Date();
    const dateString = currentDate.toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
    currentDateElement.textContent = dateString;

</script>
<script>
        function generatePDF(id) {
        const element = document.getElementById('kasbon-content-' + id);

        html2canvas(element).then((canvas) => {
            const imgData = canvas.toDataURL('image/png');
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF();

            // Adjust image size and position within the PDF
            const imgWidth = 190;
            const pageHeight = 295;  
            const imgHeight = (canvas.height * imgWidth) / canvas.width;
            let heightLeft = imgHeight;
            let position = 10;

            doc.addImage(imgData, 'PNG', 10, position, imgWidth, imgHeight);
            heightLeft -= pageHeight;

            while (heightLeft >= 0) {
                position = heightLeft - imgHeight;
                doc.addPage();
                doc.addImage(imgData, 'PNG', 10, position, imgWidth, imgHeight);
                heightLeft -= pageHeight;
            }

            doc.save('laporan_kasbon_' + id + '.pdf');
        });
    }
</script>
@endsection
