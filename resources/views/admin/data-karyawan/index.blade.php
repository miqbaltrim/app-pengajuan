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
</style>
<div class="container">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Data Karyawan</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <a href="{{ route('admin.data-karyawan.create') }}" class="btn btn-success">Tambah Karyawan</a>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Nomor</th>
                                    <th>Photo</th>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Position</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $user)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            @if($user->photo)
                                                <img src="{{ asset($user->photo) }}" alt="User Photo" class="img-thumbnail" width="50">
                                            @else
                                                No Photo
                                            @endif
                                        </td>
                                        <td>{{ $user->nama }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ $user->position }}</td>
                                        <td>
                                            <a href="{{ route('admin.data-karyawan.edit', $user->id) }}" class="btn btn-primary btn-sm">Edit</a>
                                            <form action="{{ route('admin.data-karyawan.destroy', $user->id) }}" method="POST" style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                            </form>
                                            <!-- Popup reset password -->
                                            <button type="button" class="btn btn-warning btn-sm" onclick="confirmReset('{{ route('admin.data-karyawan.reset-password', $user->id) }}')">Reset</button>
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

<!-- SweetAlert2 library -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

<script>
    function confirmReset(url) {
        Swal.fire({
            title: 'Konfirmasi',
            text: "Apakah Anda yakin ingin mereset password karyawan ini?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, reset password!'
        }).then((result) => {
            if (result.isConfirmed) {
                // Lakukan request reset password
                window.location.href = url;

                // Tampilkan popup setelah reset password berhasil
                Swal.fire({
                    title: 'Success',
                    text: 'Password berhasil direset!',
                    icon: 'success'
                });
            }
        });
    }
</script>
@endsection
