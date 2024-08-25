@extends('layouts.kepala-gudang')

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
<div class="container">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Edit Kasbon</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('kepala-gudang.pengajuan-kasbon.update', $kasbon->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="keterangan">Keterangan</label>
                            <input type="text" class="form-control" id="keterangan" name="keterangan" value="{{ $kasbon->keterangan }}" required>
                        </div>
                        <div class="form-group">
                            <label for="jml_kasbon">Jumlah Kasbon</label>
                            <input type="number" class="form-control" id="jml_kasbon" name="jml_kasbon" value="{{ $kasbon->jml_kasbon }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="cicilan" class="form-label">Cicilan:</label>
                            <select class="form-select" id="cicilan" name="cicilan" value="{{ $kasbon->cicilan }}" required>
                                @for ($i = 1; $i <= 12; $i++)
                                    <option value="{{ $i }}">{{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="disetujui_oleh" class="form-label">Disetujui Oleh:</label>
                            <select class="form-select" id="disetujui_oleh" name="disetujui_oleh" required>
                                @foreach(['admin','direktur','manager-operasional','manager-territory','manager-keuangan','area-manager','kepala-cabang','kepala-gudang'] as $role)
                                    <option value="{{ $role }}" {{ $kasbon->disetujui_oleh == $role ? 'selected' : '' }}>{{ ucfirst($role) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="diketahui_oleh" class="form-label">Diketahui Oleh:</label>
                            <select class="form-select" id="diketahui_oleh" name="diketahui_oleh" required>
                                @foreach(['admin','direktur','manager-operasional','manager-territory','manager-keuangan','area-manager','kepala-cabang','kepala-gudang'] as $role)
                                    <option value="{{ $role }}" {{ $kasbon->diketahui_oleh == $role ? 'selected' : '' }}>{{ ucfirst($role) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <!-- Tambahkan input lainnya sesuai kebutuhan -->

                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
