@extends('layouts.app')
@section('title', 'Buka Lelang Baru')

{{-- 1. Kita butuh CSS Select2 disini --}}
@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
<style>
    /* Styling agar gambar di dropdown rapi */
    .select2-result-repository__avatar {
        width: 50px;
        height: 35px;
        object-fit: cover;
        border-radius: 4px;
        margin-right: 10px;
        vertical-align: middle;
        border: 1px solid #ddd;
    }
    .select2-container .select2-selection--single {
        height: 50px !important; /* Tinggi box disesuaikan */
        display: flex;
        align-items: center;
    }
</style>
@endpush

@section('content')
<div class="card shadow-sm border-0">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Buka Lelang Baru</h5>
        <a href="{{ route('lelang.index') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left"></i> Kembali</a>
    </div>
    <div class="card-body">
        
        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('lelang.store') }}" method="POST">
            @csrf
            <div class="row g-3">
                
                {{-- 1. PILIH BARANG (Full Width biar lega liat gambarnya) --}}
                <div class="col-12">
                    <label class="form-label">Pilih Barang <span class="text-danger">*</span></label>
                    
                    {{-- Tambahkan ID biar bisa dipanggil JS --}}
                    <select name="id_barang" id="selectBarang" class="form-select" required>
                        <option value="" disabled selected>-- Cari & Pilih Barang --</option>
                        @foreach($barangs as $brg)
                            {{-- Kita simpan URL gambar di atribut 'data-image' --}}
                            <option value="{{ $brg->id_barang }}" 
                                    data-image="{{ $brg->gambar ? asset('storage/'.$brg->gambar) : '' }}"
                                    data-price="Rp {{ number_format($brg->harga_awal, 0, ',', '.') }}">
                                {{ $brg->nama_barang }}
                            </option>
                        @endforeach
                    </select>
                    <div class="form-text">Cari nama barang yang ingin dilelang.</div>
                </div>

                {{-- 2. TANGGAL LELANG --}}
                <div class="col-md-6">
                    <label class="form-label">Tanggal Lelang <span class="text-danger">*</span></label>
                    <input type="date" name="tgl_lelang" class="form-control" value="{{ date('Y-m-d') }}" required>
                </div>

                {{-- 3. STATUS (Sesuai Request) --}}
                <div class="col-md-6">
                    <label class="form-label">Status Lelang</label>
                    <select name="status" class="form-select">
                        <option value="dibuka" selected>Dibuka (Open)</option>
                        <option value="ditutup">Ditutup (Closed)</option>
                    </select>
                </div>

            </div>
            <div class="d-flex justify-content-end mt-4">
                <button type="submit" class="btn btn-primary px-4">
                    <i class="bi bi-check-lg me-1"></i> Buka Lelang Sekarang
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

{{-- 2. Script JS untuk mengubah Dropdown biasa jadi Select2 dengan Gambar --}}
@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    $(document).ready(function() {
        // Fungsi untuk memformat tampilan opsi (Gambar + Teks)
        function formatState (opt) {
            if (!opt.id) { return opt.text; } // Untuk placeholder

            var optimage = $(opt.element).attr('data-image'); 
            var optprice = $(opt.element).attr('data-price'); 
            
            // Jika ada gambar, tampilkan image tag
            if(optimage){
                var $opt = $(
                    '<span><img src="' + optimage + '" class="select2-result-repository__avatar" /> ' + opt.text + ' - <b>' + optprice + '</b></span>'
                );
                return $opt;
            } else {
                // Jika tidak ada gambar (No Image)
                var $opt = $(
                    '<span><i class="bi bi-image me-2"></i> ' + opt.text + ' - <b>' + optprice + '</b></span>'
                );
                return $opt;
            }
        };

        // Inisialisasi Select2 pada ID #selectBarang
        $('#selectBarang').select2({
            theme: 'bootstrap-5',
            templateResult: formatState,    // Tampilan saat dropdown dibuka
            templateSelection: formatState  // Tampilan saat item dipilih
        });
    });
</script>
@endpush