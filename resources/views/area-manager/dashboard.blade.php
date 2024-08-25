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
  
  .grid-container {
    display: grid;
    grid-template-columns: 1fr 1fr;
    grid-gap: 20px;
    padding: 20px;
    justify-content: space-between;
    margin-top: -20px;
  }
  
  .grid-item {
    background-color: #ffffff;
    padding: 20px;
    border-radius: 15px;
    overflow-y: auto;
    max-height: 400px;
    position: relative;
  }

  .fixed-title {
    position: sticky;
    top: -20px;
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
    grid-column: span 2;
    overflow-y: hidden;
    max-height: none;
  }
  
  .dashboard-info {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0 20px;
    margin-top: 20px;
  }
  
  #current-date {
    text-align: right;
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
    cursor: pointer;
    transition: background-color 0.3s ease;
    position: relative;
  }
  
  .social-feed-item:hover {
    background-color: #f0f0f0;
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
    background-color: rgb(60, 197, 60);
    color: white;
    border-radius: 15px;
  }
  
  .ditolak {
    background-color: rgb(255, 66, 66);
    color: white;
    border-radius: 15px;
  }
  
  .tunggu {
    background-color: rgb(255, 180, 0);
    color: white;
    border-radius: 15px;
  }

  .dot {
    height: 10px;
    width: 10px;
    background-color: red;
    border-radius: 50%;
    position: absolute;
    top: 10px;
    right: 10px;
  }

  .feed-pengumuman {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
  }

  .feed-pengumuman-item {
    display: flex;
    flex-direction: column;
    gap: 15px;
    background-color: #ffffff;
    border-radius: 10px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  }

  .post-image img {
    width: 100%;
    height: 600px;
    border-radius: 10px;
    object-fit: cover;
  }

  .post-caption {
    padding: 10px;
    background-color: #f3f4f6;
    border-radius: 10px;
  }

  @media only screen and (max-width: 600px) {
    .main-panel {
      left: 0;
      width: 100%;
    }
    .grid-container {
      grid-template-columns: 1fr;
    }
    .grid-item {
      max-width: 100%;
      margin-top: 20px;
    }
    .grid-item.full {
      grid-column: span 1;
    }
    .dashboard-info {
      flex-direction: column;
      align-items: flex-start;
    }
    #current-date {
      text-align: left;
      margin-top: 10px;
    }
    .social-feed-item {
      flex-direction: column;
    }
    .social-feed-status {
      margin-top: 10px;
    }
    .feed-pengumuman {
      display: block;
    }
    .feed-pengumuman-item {
      margin-bottom: 20px;
      border-radius: 10px;
      overflow: hidden;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
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
          Selamat datang di dashboard {{ Auth::user()->nama }} 🎉
        </div>
        <div class="grid-item">
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
          <h3 class="fixed-title">Notifikasi Pengajuan</h3>
          <div class="social-feed">
              @foreach($notifikasiPengajuan as $notifikasi)
              <div class="social-feed-item"
                   data-url="
                      @if($notifikasi instanceof App\Models\Ajucuti)
                          {{ route('area-manager.approve.cuti') }}
                      @elseif($notifikasi instanceof App\Models\Izin)
                          {{ route('area-manager.izin.index') }}
                      @elseif($notifikasi instanceof App\Models\Sakit)
                          {{ route('area-manager.sakit.index') }}
                      @elseif($notifikasi instanceof App\Models\Kasbon)
                          {{ route('area-manager.approve.kasbon') }}
                      @elseif($notifikasi instanceof App\Models\Pengajuan)
                          {{ route('area-manager.pengajuan-barang.index') }}
                      @endif
                   " 
                   data-id="{{ $notifikasi->id }}"
              >
                  @if($notifikasi instanceof App\Models\Ajucuti)
                      <p>Pengajuan Cuti anda belum di-approve</p>
                  @elseif($notifikasi instanceof App\Models\Izin)
                      <p>Pengajuan Izin anda belum di-approve</p>
                  @elseif($notifikasi instanceof App\Models\Sakit)
                      <p>Pengajuan Sakit anda belum di-approve</p>
                  @elseif($notifikasi instanceof App\Models\Kasbon)
                      <p>Pengajuan Kasbon anda belum di-approve</p>
                  @elseif($notifikasi instanceof App\Models\Pengajuan)
                      <p>Pengajuan Barang anda belum di-approve</p>
                  @endif
                  <div class="dot" id="dot-{{ $notifikasi->id }}"></div>
              </div>
              @endforeach
          </div>
        </div>

        <div class="grid-item full">
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
  const currentDateElement = document.getElementById('current-date');
  const currentDate = new Date();
  const dateString = currentDate.toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
  currentDateElement.textContent = dateString;
  
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

      const items = document.querySelectorAll('.social-feed-item');
      items.forEach(function(item) {
          const id = item.getAttribute('data-id');
          const url = item.getAttribute('data-url');
          
          if (localStorage.getItem(`notifikasi-${id}`)) {
              item.querySelector('.dot').style.display = 'none';
          }

          item.addEventListener('click', function() {
              window.location.href = url;
              localStorage.setItem(`notifikasi-${id}`, 'true');
              item.querySelector('.dot').style.display = 'none';
          });
      });
  });
</script>

@endsection
