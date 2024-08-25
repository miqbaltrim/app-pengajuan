<!DOCTYPE html>
<!-- Created by CodingLab |www.youtube.com/CodingLabYT-->
<html lang="en" dir="ltr">
<head>
  <meta charset="UTF-8">
  <title> Responsive Sidebar Menu  | CodingLab </title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- Include BoxIcons CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@2.1.0/css/boxicons.min.css">
  <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css'>
  <style>
    /* Your CSS styles here */
    *{
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: "Poppins" , sans-serif;
    }
    .sidebar{
      position: fixed;
      left: 0;
      top: 0;
      height: 100%;
      width: 90px;
      background: #11101D;
      padding: 6px 14px;
      z-index: 99;
      transition: all 0.5s ease;
    }
    .sidebar.open{
      width: 260px;
      
    }
    .sidebar .logo-details{
      height: 60px;
      display: flex;
      align-items: center;
      position: relative;
    }
    .sidebar .logo-details .icon{
      height: 50px;
      width: auto;
      opacity: 0;
      transition: all 0.5s ease;
    }
    .sidebar .logo-details .icon img{
      height: 100%; /* Mengisi tinggi logo ke dalam ketinggian yang ditetapkan */
      width: 100%; /* Mengisi lebar logo ke dalam lebar yang ditetapkan */
      object-fit: contain;
      height: 50px;
      width: auto;
      opacity: 0;
      transition: all 0.5s ease;
    }
    .sidebar .logo-details .logo_name{
      color: #fff;
      font-size: 20px;
      font-weight: 600;
      opacity: 0;
      transition: all 0.5s ease;
    }
    .sidebar.open .logo-details .icon,
    .sidebar.open .logo-details .logo_name{
      opacity: 1;
    }
    .sidebar .logo-details #btn{
      position: absolute;
      top: 50%;
      right: 0;
      transform: translateY(-50%);
      font-size: 22px;
      transition: all 0.4s ease;
      font-size: 23px;
      text-align: center;
      cursor: pointer;
      transition: all 0.5s ease;
    }
    .sidebar.open .logo-details #btn{
      text-align: right;
    }
    .sidebar i{
      color: #fff;
      height: 60px;
      min-width: 50px;
      font-size: 28px;
      text-align: center;
      line-height: 60px;
    }
    .sidebar .nav-list {
        margin-top: 20px;
        height: 100%;
        margin-left: -20px; /* Menyesuaikan margin kiri */
    }

    .sidebar li{
      position: relative;
      margin: 8px 0;
      list-style: none;
    }
    .sidebar li .tooltip{
      position: absolute;
      top: -20px;
      left: calc(100% + 15px);
      z-index: 3;
      background: #fff;
      box-shadow: 0 5px 10px rgba(0, 0, 0, 0.3);
      padding: 6px 12px;
      border-radius: 4px;
      font-size: 15px;
      font-weight: 400;
      opacity: 0;
      white-space: nowrap;
      pointer-events: none;
      transition: 0s;
    }
    .sidebar li:hover .tooltip{
      opacity: 1;
      pointer-events: auto;
      transition: all 0.4s ease;
      top: 50%;
      transform: translateY(-50%);
    }
    .sidebar.open li .tooltip{
      display: none;
    }
    .sidebar input{
      font-size: 15px;
      color: #FFF;
      font-weight: 400;
      outline: none;
      height: 50px;
      width: 100%;
      width: 50px;
      border: none;
      border-radius: 12px;
      transition: all 0.5s ease;
      background: #1d1b31;
    }
    .sidebar.open input{
      padding: 0 20px 0 50px;
      width: 100%;
    }
    .sidebar .bx-search{
      position: absolute;
      top: 50%;
      left: 0;
      transform: translateY(-50%);
      font-size: 22px;
      background: #1d1b31;
      color: #FFF;
    }
    .sidebar.open .bx-search:hover{
      background: #1d1b31;
      color: #FFF;
    }
    .sidebar .bx-search:hover{
      background: #FFF;
      color: #11101d;
    }
    .sidebar li a{
      display: flex;
      height: 100%;
      width: 100%;
      border-radius: 12px;
      align-items: center;
      text-decoration: none;
      transition: all 0.4s ease;
      background: #11101D;
    }
    .sidebar li a:hover{
      background: #FFF;
    }
    .sidebar li a .links_name{
      color: #fff;
      font-size: 15px;
      font-weight: 400;
      white-space: nowrap;
      opacity: 0;
      pointer-events: none;
      transition: 0.4s;
    }
    .sidebar.open li a .links_name{
      opacity: 1;
      pointer-events: auto;
    }
    .sidebar li a:hover .links_name,
    .sidebar li a:hover i{
      transition: all 0.5s ease;
      color: #11101D;
    }
    .sidebar li i{
      height: 50px;
      line-height: 50px;
      font-size: 18px;
      border-radius: 12px;
    }
    .sidebar li.profile{
      position: fixed;
      height: 60px;
      width: 78px;
      left: 0;
      bottom: -8px;
      padding: 10px 14px;
      background: #1d1b31;
      transition: all 0.5s ease;
      overflow: hidden;
    }
    .sidebar.open li.profile{
      width: 250px;
    }
    .sidebar li .profile-details {
      display: flex;
      align-items: center;
      flex-wrap: nowrap;
    }
    .sidebar li img{
      height: 45px;
      width: 45px;
      object-fit: cover;
      border-radius: 6px;
      margin-right: 10px;
    }
    .sidebar li.profile .name,
    .sidebar li.profile .job{
      max-width: 120px; /* Sesuaikan lebar maksimum sesuai kebutuhan */
      overflow: hidden;
      text-overflow: ellipsis; /* Menampilkan titik-titik elipsis untuk teks yang terlalu panjang */
      white-space: nowrap;
      font-size: 15px;
      font-weight: 400;
      color: #fff;
      white-space: nowrap;
    }
    .sidebar li.profile .job{
      font-size: 12px;
    }
    .sidebar .profile #log_out{
      position: absolute;
      top: 50%;
      right: 0;
      transform: translateY(-50%);
      background: #1d1b31;
      width: 100%;
      height: 60px;
      line-height: 60px;
      border-radius: 0px;
      transition: all 0.5s ease;
    }
    .sidebar.open .profile #log_out{
      width: 50px;
      background: none;
    }
    @media (max-width: 420px) {
      .sidebar li .tooltip{
        display: none;
      }
    }
  </style>
</head>
<body>
  <div class="sidebar">
    <div class="logo-details">
      <img class='icon' src="{{ asset('assets/img/Logo-ABJ.png') }}" alt="Logo">
      <div class="logo_name">PT AYUMIDA BERKAH JAYA</div>
      <i class='bx bx-menu' id="btn"></i>
    </div>
    <ul class="nav-list" style="margin-top: auto;">
      <li>
        <a href="/manager-keuangan/dashboard">
          <i class='bx bx-sidebar'></i>
          <span class="links_name">Dashboard</span>
        </a>
        <span class="tooltip">Dashboard</span>
      </li>
      <li>
        <a href="/manager-keuangan/pengajuan-cuti">
          <i class='bx bx-file'></i>
          <span class="links_name">Pengajuan Cuti</span>
        </a>
        <span class="tooltip">Pengajuan Cuti</span>
      </li>
        <li>
          <a href="{{ route('manager-keuangan.pengajuan-barang.index') }}">
            <i class='bx bx-package' ></i>
            <span class="links_name">Data Pengajuan Barang</span>
          </a>
          <span class="tooltip">Data Pengajuan Barang</span>
        </li>  
        <li>
          <a href="{{ route('manager-keuangan.approve.cuti') }}">
            <i class='bx bx-folder-open' ></i>
            <span class="links_name">Approve Cuti</span>
          </a>
          <span class="tooltip">Approve Cuti</span>
        </li>  
        <li>
          <a href="/manager-keuangan/approve/barang">
            <i class='bx bx-collection' ></i>
            <span class="links_name">Approve Barang</span>
          </a>
          <span class="tooltip">Approve Barang</span>
        </li>  
        <li>
          <a href="{{ route('manager-keuangan.data-gaji.index') }}">
            <i class='bx bx-copy-alt' ></i>
            <span class="links_name">Database Gaji</span>
          </a>
          <span class="tooltip">Database Gaji</span>
        </li>  
        <li>
          <a href="{{ route('manager-keuangan.data-kasbon.index') }}">
            <i class='bx bx-money' ></i>
            <span class="links_name">Data Kasbon</span>
          </a>
          <span class="tooltip">Data Kasbon</span>
        </li>  
      <li class="profile">
        <a href="{{ route(Auth::user()->role . '.profile') }}"> <!-- Tambahkan tautan ke halaman profil di sini -->
          <div class="profile-details">
            <img src="{{ asset(Auth::user()->photo) }}" alt="Profile Picture">
            <div class="name_job">
                <div class="name">{{ Auth::user()->nama }}</div>
                <div class="job">{{ Auth::user()->role }}</div>
            </div>
        </div>
        </a>
        <a href="/logout"> <!-- Tambahkan tautan ke halaman logout di sini -->
          <i class='bx bx-log-out' id="log_out" ></i>
        </a>
      </li>
    </ul>
  </div>
<script>
  let sidebar = document.querySelector(".sidebar");
  let closeBtn = document.querySelector("#btn");
  
  closeBtn.addEventListener("click", ()=>{
    sidebar.classList.toggle("open");
    menuBtnChange(); //calling the function to change closeBtn text(optional)
  });
  
  function menuBtnChange() {
    if(sidebar.classList.contains("open")){
      closeBtn.classList.replace("bx-menu", "bx-x");
    }else {
      closeBtn.classList.replace("bx-x","bx-menu");
    }
  }
  </script>
</body>
</html>
