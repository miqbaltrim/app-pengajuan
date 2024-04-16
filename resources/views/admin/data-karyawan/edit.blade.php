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
    .back-button {
        position: absolute;
        top: 10px;
        right: 10px;
        background: #f23434;
    }
</style>
<div class="container">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Edit Karyawan</h5>
                    <a href="{{ route('admin.data-karyawan.index') }}" class="btn btn-secondary back-button">Back</a> <!-- Tombol Back -->
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.data-karyawan.update', $user->id) }}" method="POST" enctype="multipart/form-data" >
                        @csrf
                        @method('PUT') <!-- Tambahkan method PUT untuk update -->
                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama</label>
                            <input type="text" class="form-control" id="nama" name="nama" value="{{ $user->nama }}">
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="{{ $user->email }}">
                        </div>
                        <div class="mb-3">
                            <label for="role" class="form-label">Role</label>
                            <select class="form-select" id="role" name="role">
                                <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                                <option value="direktur" {{ $user->role == 'direktur' ? 'selected' : '' }}>Direktur</option>
                                <option value="manager-operasional" {{ $user->role == 'manager-operasional' ? 'selected' : '' }}>Manager Operasional</option>
                                <option value="manager-territory" {{ $user->role == 'manager-territory' ? 'selected' : '' }}>Manager Territory</option>
                                <option value="manager-keuangan" {{ $user->role == 'manager-keuangan' ? 'selected' : '' }}>Manager Keuangan</option>
                                <option value="area-manager" {{ $user->role == 'area-manager' ? 'selected' : '' }}>Area Manager</option>
                                <option value="kepala-cabang" {{ $user->role == 'kepala-cabang' ? 'selected' : '' }}>Kepala Cabang</option>
                                <option value="kepala-gudang" {{ $user->role == 'kepala-gudang' ? 'selected' : '' }}>Kepala Gudang</option>
                                <option value="staff-office" {{ $user->role == 'staff-office' ? 'selected' : '' }}>Staff Office</option>
                                <option value="gudang" {{ $user->role == 'gudang' ? 'selected' : '' }}>Gudang</option>
                                <!-- Tambahkan option untuk role lainnya sesuai dengan kebutuhan -->
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="alamat" class="form-label">Alamat</label>
                            <textarea class="form-control" id="alamat" name="alamat" rows="3">{{ $user->alamat }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label for="telepon" class="form-label">Telepon</label>
                            <input type="text" class="form-control" id="telepon" name="telepon" value="{{ $user->telepon }}">
                        </div>
                        <div class="mb-3">
                            <label for="position" class="form-label">Position</label>
                            <input type="text" class="form-control" id="position" name="position" value="{{ $user->position }}">
                        </div>
                        <div class="mb-3">
                            <label for="photo" class="form-label">Photo</label>
                            <input type="file" class="form-control" id="photo" name="photo">
                            @if($user->photo)
                            <div>
                                <img src="{{ asset($user->photo) }}" alt="User Photo" class="img-thumbnail" width="100">
                            </div>
                            @endif
                        </div>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Script untuk menampilkan popup sukses -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script>
    document.getElementById('updateForm').addEventListener('submit', function(event) {
        event.preventDefault(); // Menghentikan pengiriman formulir agar bisa di-handle secara manual

        // Kirim permintaan AJAX ke server untuk menyimpan data
        fetch(this.action, {
            method: this.method,
            body: new FormData(this), // Gunakan FormData untuk mengirim data formulir
        })
        .then(response => response.json())
        .then(data => {
            // Tampilkan popup sukses jika data berhasil disimpan
            Swal.fire({
                title: 'Success',
                text: 'Data berhasil diperbarui!',
                icon: 'success'
            });
        })
        .catch(error => {
            console.error('Error:', error);
            // Tampilkan popup error jika terjadi kesalahan
            Swal.fire({
                title: 'Error',
                text: 'Terjadi kesalahan saat memperbarui data.',
                icon: 'error'
            });
        });
    });
</script>
@endsection
