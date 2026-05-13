@extends('layouts.app')

@section('title', 'Atur Deadline')

@section('content')

<style>
    .deadline-form-card {
        animation: content-fade-in 0.6s ease-out both;
        background: linear-gradient(135deg, #ffffff 0%, #fbfdff 100%);
    }

    .form-input {
        transition: all 0.2s ease;
        border: 1px solid rgba(2,6,23,0.08);
    }

    .form-input:focus {
        border-color: #c1121f;
        box-shadow: 0 0 0 4px rgba(193, 18, 31, 0.08);
    }

    @keyframes content-fade-in {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .btn-primary {
        background: linear-gradient(135deg, #c1121f 0%, #780116 100%);
        transition: all 0.3s ease;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(193, 18, 31, 0.2);
    }

    .current-deadline-badge {
        background: rgba(193, 18, 31, 0.05);
        border: 1px dashed rgba(193, 18, 31, 0.2);
    }
</style>

<div class="max-w-4xl mx-auto">
    <div class="page-hero mb-6 text-center">
        <div class="title inline-block">
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Atur Deadline Proposal</h1>
            <p class="text-slate-500 mt-2">Tetapkan batas waktu pengumpulan proposal untuk seluruh Ormawa</p>
        </div>
    </div>

    <div class="space-y-8">
        <!-- Current Status Section -->
        <div class="current-deadline-badge rounded-2xl p-6 text-center shadow-sm border border-slate-100 bg-white">
            <p class="text-xs font-bold text-red-600 uppercase tracking-widest mb-2">Deadline Aktif Saat Ini</p>
            @if($deadline)
                <div class="flex flex-col md:flex-row items-center justify-center gap-6">
                    <div class="flex-1">
                        <h3 class="text-2xl font-bold text-slate-800">{{ $deadline->title }}</h3>
                        <p class="text-slate-600 font-medium mt-1">
                            <span class="inline-flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                {{ $deadline->deadline_at->format('d F Y - H:i') }} WIB
                            </span>
                        </p>
                    </div>
                    <form action="{{ route('admin.atur_deadline.delete') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus deadline ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-red-50 text-red-600 hover:bg-red-100 transition-all text-sm font-bold border border-red-200 hover:shadow-md">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            Hapus Deadline
                        </button>
                    </form>
                </div>
            @else
                <p class="text-slate-400 italic py-2">Belum ada deadline yang ditetapkan</p>
            @endif
        </div>

        <!-- Update Form Section -->
        <div class="deadline-form-card bg-white rounded-3xl p-8 shadow-xl border border-slate-100">
            <h3 class="text-xl font-bold text-slate-800 mb-6 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                Perbarui Deadline
            </h3>
            <form action="{{ route('admin.atur_deadline.post') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">



                <!-- Title Input -->
                <div class="flex flex-col gap-2">
                    <label for="title" class="text-sm font-bold text-slate-700 ml-1">Nama Deadline</label>
                    <input 
                        type="text" 
                        name="title" 
                        id="title" 
                        placeholder="Contoh: Deadline Triwulan I" 
                        value="{{ $deadline ? $deadline->title : old('title') }}"
                        required
                        class="form-input w-full rounded-2xl px-5 py-4 text-slate-700 bg-slate-50 focus:bg-white outline-none transition-all"
                    >
                    @error('title')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- DateTime Input -->
                <div class="flex flex-col gap-2">
                    <label for="deadline_at" class="text-sm font-bold text-slate-700 ml-1">Batas Waktu (Tanggal & Jam)</label>
                    <input 
                        type="datetime-local" 
                        name="deadline_at" 
                        id="deadline_at" 
                        value="{{ $deadline ? $deadline->deadline_at->format('Y-m-d\TH:i') : old('deadline_at') }}"
                        required
                        class="form-input w-full rounded-2xl px-5 py-4 text-slate-700 bg-slate-50 focus:bg-white outline-none transition-all"
                    >
                    @error('deadline_at')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit Button -->
                <div class="md:col-span-2 mt-4">
                    <button type="submit" class="btn-primary w-full rounded-2xl py-4 text-white font-bold text-lg shadow-lg">
                        Update Deadline
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

    <!-- Help Card -->
    <div class="mt-8 bg-blue-50 border border-blue-100 rounded-2xl p-6 flex gap-4 items-start">
        <div class="bg-blue-100 p-2 rounded-xl text-blue-600">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>
        <div>
            <h4 class="font-bold text-blue-900">Tips Pengaturan</h4>
            <p class="text-sm text-blue-800/80 leading-relaxed mt-1">
                Deadline yang Anda tetapkan akan langsung muncul di halaman beranda seluruh akun Ormawa dalam bentuk kartu hitung mundur. Gunakan format judul yang jelas seperti <strong>"Triwulan II"</strong> atau <strong>"Pengumpulan Revisi"</strong>.
            </p>
        </div>
    </div>
</div>

@endsection
