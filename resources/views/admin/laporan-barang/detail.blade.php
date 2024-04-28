@extends('layouts.admin')

@section('content')
<style>
    .main-panel{
        position: relative;
        background: #E4E9F7;
        min-height: 100vh;
        top: 0;
        left: 78px;
        width: calc(100% - 78px);
        transition: all 0.5s ease;
        z-index: 2;
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
    .table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }
    .table th,
    .table td {
        border: 1px solid #dee2e6;
        padding: 8px;
        text-align: left;
    }
    .table th {
        background-color: #f8f9fa;
    }
    .button-group {
        margin-top: 20px;
    }
    .button-group button {
        margin-right: 10px;
    }
    /* New style for the back button */
    .back-button {
        position: absolute;
        top: 10px;
        right: 10px;
    }
</style>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Detail Pengajuan Barang</h5>
                    <a href="{{ url()->previous() }}" class="btn btn-danger back-button">Back</a>
                </div>
                <div class="card-body">
                    <div>
                        <table class="table">
                            <tbody>
                                <tr>
                                    <th>Nama Karyawan:</th>
                                    <td>{{ $pengajuan->user->nama }}</td>
                                </tr>
                                <tr>
                                    <th>Daftar Barang</th>
                                    <td>
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>Nama Barang</th>
                                                    <th>Qty</th>
                                                    <th>Harga Satuan</th>
                                                    <th>Total</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php
                                                    $totalPengajuan = 0;
                                                @endphp
                                                @foreach($pengajuan->barangs as $barang)
                                                <tr>
                                                    <td>{{ $barang->nama_barang }}</td>
                                                    <td>{{ $barang->qty }}</td>
                                                    <td>{{ 'Rp ' . number_format($barang->harga_satuan, 0, ',', '.') }}</td>
                                                    <td>{{ 'Rp ' . number_format($barang->total, 0, ',', '.') }}</td>
                                                </tr>
                                                @php
                                                    $totalPengajuan += $barang->total;
                                                @endphp
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Total Pengajuan:</th>
                                    <td>{{ 'Rp ' . number_format($totalPengajuan, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <th>Disetujui Oleh:</th>
                                    <td>{{ $pengajuan->disetujuiOleh->nama }}</td>
                                </tr>
                                <tr>
                                    <th>Diketahui Oleh:</th>
                                    <td>{{ $pengajuan->diketahuiOleh->nama }}</td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="button-group">
                            <!-- Tombol lainnya -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function diterima(pengajuanId, action) {
        console.log('Pengajuan ID:', pengajuanId);
        console.log('Aksi:', action);
    }

    function ditolak(pengajuanId, action) {
        console.log('Pengajuan ID:', pengajuanId);
        console.log('Aksi:', action);
    }
</script>

@endsection
