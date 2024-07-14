@extends('layouts.manager-keuangan')

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

    .card {
        margin-top: 20px;
    }
    /* Tambahkan gaya CSS Anda di sini */
    .container {
        padding: 20px;
    }

    .table {
        width: 100%;
        margin-bottom: 1rem;
        background-color: transparent;
        border-collapse: collapse;
    }

    .table th,
    .table td {
        padding: 0.75rem;
        vertical-align: top;
        border-top: 1px solid #dee2e6;
    }

    .table th {
        vertical-align: bottom;
        border-bottom: 2px solid #dee2e6;
        text-align: left;
    }

    .form-control {
        display: block;
        width: calc(100% - 90px); /* Adjust the width */
        padding: 0.375rem 0.75rem;
        font-size: 1rem;
        line-height: 1.5;
        color: #495057;
        background-color: #fff;
        background-clip: padding-box;
        border: 1px solid #ced4da;
        border-radius: 0.25rem;
        transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    }

    .btn {
        font-weight: 400;
        color: #212529;
        text-align: center;
        vertical-align: middle;
        background-color: transparent;
        border: 1px solid transparent;
        padding: 0.375rem 0.75rem;
        font-size: 1rem;
        line-height: 1.5;
        border-radius: 0.25rem;
        transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out,
            border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    }

    .btn-primary {
        color: #fff;
        background-color: #007bff;
        border-color: #007bff;
    }

    .btn-primary:hover {
        color: #fff;
        background-color: #0069d9;
        border-color: #0062cc;
    }

    .input-group {
        display: flex;
    }

    .input-group .form-control {
        flex: 1;
        margin-right: 10px; /* Adjust the margin between input and button */
    }

    .input-group .btn {
        margin-top: auto;
    }
</style>

<div class="container">
    <h1>Data Gaji Pengguna</h1>
    <table class="table">
        <thead>
            <tr>
                <th scope="col">No</th>
                <th scope="col">Nama</th>
                <th scope="col">Posisi</th>
                <th scope="col">Gaji</th>
                <th scope="col">Action</th> <!-- Kolom untuk menyimpan gaji -->
            </tr>
        </thead>
        <tbody>
            @foreach($users as $key => $user)
                <tr>
                    <th scope="row">{{ $key + 1 }}</th>
                    <td>{{ $user->nama }}</td>
                    <td>{{ $user->position }}</td>
                    <td>{{ $user->gaji ? 'Rp'.number_format($user->gaji->gaji, 0, ',', '.') : '-' }}</td>
                    <td>
                        <form action="{{ route('manager-keuangan.gaji.store', $user->id) }}" method="POST" class="input-group">
                            @csrf
                            @method('PUT') <!-- Menggunakan metode PUT untuk update -->
                            <input type="number" name="gaji" value="{{ $user->gaji->gaji ?? '' }}" class="form-control" placeholder="Masukkan gaji">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
