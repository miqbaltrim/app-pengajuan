@extends('layouts.staff-office')

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
                    <h5 class="card-title">Data Kasbon</h5>
                </div>
                <div class="card-body">
                    <a href="{{ route('staff-office.pengajuan-kasbon.create') }}" class="btn btn-success mb-3">Buat Kasbon</a>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>Keterangan</th>
                                    <th>Jumlah Kasbon</th>
                                    <th>Disetujui</th>
                                    <th>Diketahui</th>
                                    <th>Status Setujui</th>
                                    <th>Status Ketahui</th>
                                    <th>Aksi</th> <!-- Kolom untuk tombol edit dan delete -->
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($kasbons as $kasbon)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ optional($kasbon->user)->nama }}</td> <!-- Menampilkan nama pengguna yang membuat kasbon -->
                                        <td>{{ $kasbon->keterangan }}</td>
                                        <td>{{ number_format($kasbon->jml_kasbon, 0, ',', '.') }}</td>
                                        <td>{{ optional($kasbon->disetujuiOleh)->role ?? 'User Tidak Ditemukan' }}</td>
                                        <td>{{ optional($kasbon->diketahuiOleh)->role ?? 'User Tidak Ditemukan' }}</td>
                                        <td>{{ $kasbon->setujui }}</td>
                                        <td>{{ $kasbon->ketahui }}</td>
                                        <td>
                                            @if($kasbon->setujui == 'diterima' && $kasbon->ketahui == 'diterima')
                                                <a href="{{ route('staff-office.pengajuan-kasbon.download', $kasbon->id) }}" class="btn btn-success btn-sm">Download</a>
                                            @else
                                                <a href="{{ route('staff-office.pengajuan-kasbon.edit', $kasbon->id) }}" class="btn btn-primary btn-sm">Edit</a>
                                                <form action="{{ route('staff-office.pengajuan-kasbon.destroy', $kasbon->id) }}" method="POST" style="display: inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">Delete</button>
                                                </form>
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
<script>
    // Mendapatkan elemen untuk tanggal terkini
    const currentDateElement = document.getElementById('current-date');
    // Mendapatkan tanggal hari ini
    const currentDate = new Date();
    // Mendapatkan string tanggal dengan format tertentu
    const dateString = currentDate.toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
    // Menampilkan tanggal terkini pada elemen currentDateElement
    currentDateElement.textContent = dateString;
</script>
@endsection
