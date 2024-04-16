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
      margin: 5px
    }
</style>

    <div class="content-wrapper" id="contentWrapper">
        <div class="row">
            <div class="col-lg-12">
                <h1>Selamat Datang di Dashboard {{ Auth::user()->role }}</h1>
                <!-- Tambahkan konten lain sesuai kebutuhan -->
            </div>
        </div>
    </div>
@endsection
