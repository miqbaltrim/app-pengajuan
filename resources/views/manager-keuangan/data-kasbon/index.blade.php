@extends('layouts.manager-keuangan')

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
      margin: 5px
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
                    <h5 class="card-title">Riwayat Kasbon</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>Tanggal Kasbon</th>
                                    <th>Keterangan</th>
                                    <th>Jumlah Kasbon</th>
                                    <th>Sisa Cicilan</th>
                                    <th>Status Setujui</th>
                                    <th>Status Ketahui</th>
                                    <th>Status Cicilan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($kasbons as $kasbon)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ optional($kasbon->user)->nama }}</td>
                                        <td>{{ \Carbon\Carbon::parse($kasbon->created_at)->format('Y-m-d') }}</td>
                                        <td>{{ $kasbon->keterangan }}</td>
                                        <td>{{ number_format($kasbon->jml_kasbon, 0, ',', '.') }}</td>
                                        <td>{{ number_format($kasbon->sisa_cicilan, 0, ',', '.') }}</td>
                                        <td>{{ $kasbon->setujui }}</td>
                                        <td>{{ $kasbon->ketahui }}</td>
                                        <td>{{ $kasbon->status_cicilan }}</td>
                                        <td>
                                            @if($kasbon->status_cicilan == 'belum lunas' && $kasbon->setujui == 'diterima' && $kasbon->ketahui == 'diterima')
                                                <button class="btn btn-primary btn-sm" onclick="confirmUpdateStatus('{{ route('manager-keuangan.data-kasbon.updateStatusCicilan', $kasbon->id) }}')">Lunas</button>
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
</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Mendapatkan elemen untuk tanggal terkini
    const currentDateElement = document.getElementById('current-date');
    // Mendapatkan tanggal hari ini
    const currentDate = new Date();
    // Mendapatkan string tanggal dengan format tertentu
    const dateString = currentDate.toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
    // Menampilkan tanggal terkini pada elemen currentDateElement
    currentDateElement.textContent = dateString;

    function confirmUpdateStatus(url) {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Anda ingin mengubah status cicilan menjadi lunas?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, ubah!'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = url;
            }
        })
    }
</script>
@endsection
