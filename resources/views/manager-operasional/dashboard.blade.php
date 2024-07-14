@extends('layouts.manager-operasional')

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
  
  .grid-container {
    display: grid;
    grid-template-columns: 1fr 1fr;
    grid-gap: 20px;
    padding: 20px;
    justify-content: space-between;
    margin-top: -20px; /* Memindahkan konten ke pojok kanan atas */
  }
  
  .grid-item {
    background-color: #ffffff; /* Warna latar belakang */
    padding: 20px;
    border-radius: 15px; /* Membuat sudut menjadi bulat */
    overflow-y: auto; /* Menambahkan scroll vertikal jika konten melebihi ukuran grid-item */
    max-height: 400px; /* Menentukan ketinggian maksimum grid-item sebelum scroll muncul */
    position: relative; 
    /* Menjadikan posisi relatif untuk judul */
  }

  .fixed-title {
    position: sticky;
    top: -20px; /* Menempelkan judul 10 piksel dari bagian atas grid-item */
    left: 0;
    right: 0;
    width: 100%;
    background-color: #ffffff;
    z-index: 1;
    padding: 5px;
    margin: 0;
    margin-top: 0;
  }
  
  .grid-item.full {
      grid-column: span 2; /* Satu grid penuh */
      overflow-y: hidden; /* Menghapus overflow-y: auto; */
      max-height: none; /* Mengatur tinggi maksimum menjadi none; */
  }
  
  .dashboard-info {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0 20px;
    margin-top: 20px; /* Tambahkan padding kanan dan kiri sebesar 20px */
  }
  
  #current-date {
    text-align: right; /* Teks berada di pojok kanan */
  }
  
  .social-feed {
    display: flex;
    flex-direction: column;
    gap: 20px;
  }
  
  .social-feed-item {
    display: flex;
    gap: 15px;
    padding: 15px;
    background-color: #ffffff;
    border-radius: 10px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  }
  
  .social-feed-user {
    display: flex;
    align-items: center;
    gap: 10px;
  }
  
  .social-feed-user img {
    width: 40px;
    height: 40px;
    border-radius: 50%;
  }
  
  .social-feed-content {
    flex: 1;
  }
  
  .social-feed-content p {
    margin: 0;
  }
  
  .social-feed-dates {
    display: flex;
    align-items: center;
    gap: 5px;
    font-size: 14px;
  }
  
  .social-feed-status {
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 5px;
    padding: 5px 10px;
    font-weight: bold;
  }
  
  .diterima {
    background-color: rgb(60, 197, 60); /* Perbesar warna hijau */
    color: white;
    border-radius: 15px; /* Memperbesar sudut */
  }
  
  .ditolak {
    background-color: rgb(255, 66, 66); /* Perbesar warna merah */
    color: white;
    border-radius: 15px; /* Memperbesar sudut */
  }
  
  .tunggu {
    background-color: rgb(255, 180, 0); /* Perbesar warna orange */
    color: white;
    border-radius: 15px; /* Memperbesar sudut */
  }

  /* CSS Pengumumaman */
  .feed-pengumuman {
      display: grid;
      grid-template-columns: 1fr 1fr; /* Dua kolom bersebelahan */
      gap: 20px; /* Jarak antara kolom */
  }

  .feed-pengumuman-item {
      display: flex;
      flex-direction: column;
      gap: 15px;
      background-color: #ffffff;
      border-radius: 10px;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  }

  /* CSS untuk konten post image dan caption */
  .post-image img {
      width: 100%;
      height: 600px; /* Tetapkan tinggi gambar */
      border-radius: 10px;
      object-fit: cover; /* Gambar akan terisi dan terpotong jika perlu */
  }

  .post-caption {
      padding: 10px;
      background-color: #f3f4f6;
      border-radius: 10px;
  }

  /* CSS untuk perangkat mobile */
  @media only screen and (max-width: 600px) {
    .main-panel {
        left: 0;
        width: 100%;
      }
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
    
      .grid-container {
        grid-template-columns: 1fr; /* Satu kolom penuh */
      }
    
      .grid-item {
        max-width: 100%; /* Mengisi lebar grid-item */
        margin-top: 20px; /* Menambahkan margin atas */
      }
    
      .grid-item.full {
        grid-column: span 1; /* Satu grid penuh */
      }
    
      .fixed-title {
        top: 0; /* Menempelkan judul pada bagian atas grid-item */
      }
    
      .dashboard-info {
        flex-direction: column; /* Mengubah tata letak menjadi vertikal */
        align-items: flex-start; /* Menata item ke sisi kiri */
      }
    
      #current-date {
        text-align: left; /* Teks berada di sisi kiri */
        margin-top: 10px; /* Menambahkan margin atas */
      }
    
      .social-feed-item {
        flex-direction: column; /* Mengubah tata letak menjadi vertikal */
      }
    
      .social-feed-status {
        margin-top: 10px; /* Menambahkan margin atas */
      }

      .feed-pengumuman {
          display: block; /* Mengubah tata letak menjadi blok sehingga konten pengumuman menjadi satu kolom */
      }

      .feed-pengumuman-item {
          margin-bottom: 20px; /* Menambahkan jarak antara setiap item pengumuman */
          border-radius: 10px; /* Menjadikan sudut item pengumuman menjadi bulat */
          overflow: hidden; /* Menghilangkan overflow agar konten tetap terlihat dengan baik */
          box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); /* Menambahkan bayangan untuk efek visual */
      }
      .post-image {
          flex: 1;
          display: flex;
          justify-content: center;
          align-items: center;
          overflow: hidden;
          border-radius: 10px;
      }

      .post-image img {
          width: 100%;
          height: auto;
          object-fit: cover;
      }

      .post-caption {
          flex: 1;
          padding: 10px;
          background-color: #f3f4f6;
          border-radius: 10px;
      }
    }
</style>

<div class="content-wrapper" id="contentWrapper">
  <div class="row">
    <div class="col-lg-12">
      <div class="dashboard-info">
        <p id="dashboard-info" style="font-weight: bold;">Dashboard {{ Auth::user()->role }}, Divisi {{ Auth::user()->position }}</p>
        <p id="current-date"></p>
        </div>
          <div class="grid-container">
            <div class="grid-item full">
              <!-- Grid penuh layar -->
              Selamat datang di dashboard {{ Auth::user()->nama }} 🎉
            </div>
            <div class="grid-item">
              <!-- Grid sebelah kiri 1 -->
              <h3 class="fixed-title">Riwayat Cuti</h3>
              <div class="social-feed">
                  @foreach($riwayatCuti as $ajucuti)
                  <div class="social-feed-item">
                      <div class="social-feed-user">
                          <img src="{{ asset($ajucuti->user->photo) }}" alt="User Avatar">
                          <div>
                              <strong>{{ $ajucuti->user->nama }}</strong><br>
                              <span>{{ $ajucuti->user->position }}</span>
                          </div>
                      </div>
                      <div class="social-feed-content">
                          <p>{{ $ajucuti->alasan }}</p>
                          <div class="social-feed-dates">
                              <span>{{ $ajucuti->mulai_cuti }}</span>
                              <span>-</span>
                              <span>{{ $ajucuti->selesai_cuti }}</span>
                          </div>
                      </div>
                      <div class="social-feed-status">
                          <span>{{ $ajucuti->status }}</span>
                      </div>
                  </div>
                  @endforeach
              </div>
          </div>

      
          <div class="grid-item">
            <!-- Grid sebelah kiri 2 -->
            <h3 class="fixed-title">Notifikasi Pengajuan Barang</h3>
            <div class="social-feed">
                @foreach($notifikasiPengajuan as $notifikasi)
                <div class="social-feed-item">
                    <!-- Menampilkan nomor referensi -->
                    <p>Nomor Referensi: {{ $notifikasi->nomor_referensi }}</p>
                    
                    <!-- Tombol unduh surat -->
                    <a href="{{ route('manager-operasional.download-surat', $notifikasi->id) }}" class="btn btn-primary">Download Surat</a>
                </div>
                @endforeach
            </div>
        </div>
        
        <div class="grid-item full">
            <!-- Konten Grid 4 (Full Screen) -->
            <h3 class="fixed-title">Pengumuman</h3>
            <div class="feed-pengumuman">
                @foreach($pengumumans as $pengumuman)
                <div class="feed-pengumuman-item">
                    <div class="post-content">
                        <div class="post-image">
                            <img src="{{ asset('storage/' . $pengumuman->image) }}" alt="Pengumuman Image">
                        </div>
                        <div class="post-caption">
                            <p>{{ $pengumuman->caption }}</p>
                        </div>
                    </div>
                </div>
                @endforeach
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
  
  // Set kelas CSS berdasarkan status cuti
  document.addEventListener("DOMContentLoaded", function() {
      const statusElements = document.querySelectorAll('.social-feed-status span');
      statusElements.forEach(function(element) {
          const status = element.textContent.trim();
          let statusClass;
          if (status === 'disetujui') {
              statusClass = 'diterima';
          } else if (status === 'ditolak') {
              statusClass = 'ditolak';
          } else {
              statusClass = 'tunggu';
          }
          element.classList.add(statusClass);
      });
  });
</script>

@endsection
