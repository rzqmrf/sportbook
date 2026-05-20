{{-- resources/views/admin/lapangan/create.blade.php --}}
@extends('layouts.admin')

@section('title', 'Tambah Lapangan')
@section('page-title', 'Tambah Lapangan')

@section('content')
<div class="max-w-6xl mx-auto pb-12">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <nav class="flex items-center text-xs text-gray-400 mb-1.5 gap-1.5">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-green-600 transition">Dashboard</a>
                <i class="fa-solid fa-chevron-right text-[10px]"></i>
                <a href="{{ route('admin.lapangan.index') }}" class="hover:text-green-600 transition">Lapangan</a>
                <i class="fa-solid fa-chevron-right text-[10px]"></i>
                <span class="text-gray-600 font-medium">Tambah Baru</span>
            </nav>
            <h1 class="text-2xl font-bold text-gray-900">Tambah Lapangan</h1>
            <p class="text-sm text-gray-500 mt-0.5">Daftarkan lapangan olahraga baru ke dalam sistem.</p>
        </div>
        <a href="{{ route('admin.lapangan.index') }}"
           class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-200 text-gray-600 rounded-xl font-medium text-sm hover:bg-gray-50 transition shadow-sm w-fit shrink-0">
            <i class="fa-solid fa-arrow-left text-xs"></i> Kembali
        </a>
    </div>

    {{-- Form --}}
    <form action="{{ route('admin.lapangan.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        @include('admin.lapangan._form')

        {{-- Action Bar --}}
        <div class="mt-6 flex flex-col sm:flex-row items-center justify-between bg-white px-6 py-4 rounded-2xl border border-gray-100 shadow-sm gap-4">
            <p class="text-xs text-gray-400 flex items-center gap-1.5">
                <i class="fa-solid fa-circle-info text-green-500"></i>
                Field bertanda <span class="text-red-500 mx-0.5">*</span> wajib diisi.
            </p>
            <div class="flex items-center gap-3">
                <button type="reset"
                        class="px-5 py-2.5 text-sm text-gray-500 font-medium hover:text-gray-700 transition rounded-xl hover:bg-gray-50">
                    Reset
                </button>
                <button type="submit"
                        class="inline-flex items-center gap-2 px-6 py-2.5 bg-green-600 hover:bg-green-700 text-white font-semibold text-sm rounded-xl transition shadow-md shadow-green-200 hover:-translate-y-px active:translate-y-0">
                    <i class="fa-solid fa-paper-plane text-xs"></i>
                    Simpan Lapangan
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
