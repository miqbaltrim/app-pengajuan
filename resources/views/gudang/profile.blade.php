@extends('layouts.gudang')

@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@2.1.0/css/boxicons.min.css">
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
    .profile-card {
        background-color: #fff;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        padding: 20px;
        margin-bottom: 20px;
        /* Menyesuaikan posisi card */
        margin-left: -10px;
        margin-top: 30px;
    }
    .profile-card .card-header {
        font-size: 18px;
        font-weight: 500;
        margin-bottom: 20px;
    }
    .profile-card .form-control {
        margin-bottom: 15px;
    }
    .profile-picture {
        width: 150px;
        height: 150px;
        border-radius: 50%;
        overflow: hidden;
        margin: auto;
        margin-bottom: 10px;
    }
    .profile-picture img {
        width: 100%;
        height: auto;
    }
    .upload-button {
        text-align: center;
    }
</style>
<div class="container">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-lg-6">
                <div class="profile-card rounded">
                    <div class="card-header">Profile</div>
                    <!-- Profile Picture -->
                    <div class="profile-picture">
                        <img src="{{ asset(Auth::user()->photo) }}" alt="Profile Picture" class="img-fluid">
                    </div>
                    <!-- Upload Button -->
                    <div class="upload-button">
                        <form action="{{ route('profile.upload_photo') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="file" name="photo" class="form-control-file">
                            <button type="submit" class="btn btn-primary">Upload Profile Picture</button>
                        </form>
                    </div>
                    <div class="card-header">Tanda Tangan</div>
                    <div class="profile-picture">
                        <img src="{{ asset(Auth::user()->ttd) }}" alt="Tanda Tangan" class="img-fluid">
                    </div>
                    <!-- Upload Button -->
                    <div class="upload-button">
                        <form action="{{ route('profile.upload_photo') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="file" name="ttd" class="form-control-file">
                            <button type="submit" class="btn btn-primary">Upload Tanda Tangan</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="profile-card rounded">
                    <div class="card-header">Profile Information</div>
                    <!-- Profile Information Form -->
                    <form action="{{ route('profile.update') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="name">Name</label>
                            <input type="text" class="form-control" id="name" name="nama" value="{{ Auth::user()->nama }}">
                        </div>
                        <div class="mb-3">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="{{ Auth::user()->email }}">
                        </div>
                        <div class="mb-3">
                            <label for="password">Password</label>
                            <input type="password" class="form-control" id="password" name="password" value="********">
                        </div>
                        <div class="mb-3">
                            <label for="address">Address</label>
                            <input type="text" class="form-control" id="address" name="alamat" value="{{ Auth::user()->alamat }}">
                        </div>
                        <div class="mb-3">
                            <label for="phone">Phone</label>
                            <input type="text" class="form-control" id="phone" name="telepon" value="{{ Auth::user()->telepon }}">
                        </div>
                        <div class="mb-3">
                            <label for="position" >Department</label>
                            <input type="text" class="form-control" id="role" name="role" value="{{ Auth::user()->role }}">
                        </div>
                        <div class="mb-3">
                            <label for="position">Position</label>
                            <input type="text" class="form-control" id="position" name="position" value="{{ Auth::user()->position }}">
                        </div>
                        
                        <button type="submit" class="btn btn-primary">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
