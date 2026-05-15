<?php

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Route;
use Barryvdh\DomPDF\Facade\Pdf;

Route::get("/", function () {
    $publikasis = \App\Models\PublikasiKegiatan::where('status', 'Disetujui')
        ->orderBy('created_at', 'desc')
        ->get();
    return view("landing", compact('publikasis'));
})->name("landing");
Route::get("/home", function () {
    if (Illuminate\Support\Facades\Auth::check()) {
        $user = Illuminate\Support\Facades\Auth::user();
        
        // Redirect logic based on role
        if ($user->isAdmin() || $user->isSuperAdmin()) {
            $redirectRoute = 'admin.beranda_ormawa';
        } elseif ($user->isMahasiswa()) {
            $redirectRoute = 'organisasi.beranda_mahasiswa';
        } else {
            // Default for DPMBEM, Ormawa Institusi, Ormawa Prodi, etc.
            $redirectRoute = 'organisasi.index';
        }
        
        return redirect()->route($redirectRoute);
    }
    return redirect()->route("login");
});

// ── Guest ─────────────────────────────────────────────────────────────────────
Route::middleware("guest")->group(function () {
    Route::get("/login", fn() => view("auth.login"))->name("login");

    Route::get("/register", fn() => view("auth.register"))->name("register");

    Route::post("/register", function (Request $request) {
        $validated = $request->validate([
            "name"               => ["required", "string", "max:255"],
            "nim"                => ["required", "string", "max:20", "unique:users,nim"],
            "prodi"              => ["required", "string", "max:100"],
            "email"              => ["required", "email", "max:255", "unique:users,email"],
            "password"           => ["required", "string", "min:8", "confirmed"],
        ], [
            "name.required"      => "Nama lengkap tidak boleh kosong.",
            "nim.required"       => "NIM tidak boleh kosong.",
            "nim.unique"         => "NIM sudah terdaftar.",
            "prodi.required"     => "Program studi harus dipilih.",
            "email.required"     => "Email tidak boleh kosong.",
            "email.email"        => "Format email tidak valid.",
            "email.unique"       => "Email sudah terdaftar.",
            "password.required"  => "Password tidak boleh kosong.",
            "password.min"       => "Password minimal 8 karakter.",
            "password.confirmed" => "Konfirmasi password tidak cocok.",
        ]);

        $nameParts = preg_split('/\s+/', trim($validated['name']), 2);
        $namaDepan = $nameParts[0] ?? $validated['name'];
        $namaBelakang = $nameParts[1] ?? '';

        $usernameBase = Str::lower(preg_replace('/[^a-z0-9]/i', '', Str::before($validated['email'], '@')) ?: 'user');
        $username = $usernameBase;
        $counter = 1;

        while (User::where('username', $username)->exists()) {
            $username = $usernameBase . $counter;
            $counter++;
        }

        $user = User::create([
            'username' => $username,
            'nim' => $validated['nim'],
            'nama_depan' => $namaDepan,
            'nama_belakang' => $namaBelakang,
            'prodi' => $validated['prodi'],
            'email' => $validated['email'],
            'password' => $validated['password'],
            'role' => 'Mahasiswa',
            'is_active' => true,
        ]);

        try {
            $user->assignRole('Mahasiswa');
            $user->syncPermissions(config('permissions.role_defaults.Mahasiswa', []));
        } catch (\Throwable $e) {
            Log::warning('Unable to assign Mahasiswa role on web register: ' . $e->getMessage());
        }

        Auth::login($user);
        $request->session()->regenerate();
        $request->session()->put('dummy_user', array_merge(
            $user->only(['id_user', 'username', 'nim', 'nama_depan', 'nama_belakang', 'prodi', 'email', 'role', 'is_active']),
            ['display_name' => trim($user->nama_depan . ' ' . $user->nama_belakang)]
        ));

        return redirect()->route('organisasi.beranda_mahasiswa')->with('success', 'Akun berhasil dibuat. Silakan mulai menggunakan aplikasi.');
    })->name("register.post");

    Route::post("/login", function (Request $request) {
        $credentials = $request->validate(
            [
                "username" => ["required", "string"],
                "password" => ["required", "string"],
            ],
            [
                "username.required" => "Username / NIM tidak boleh kosong.",
                "password.required" => "Password tidak boleh kosong.",
            ],
        );

        $user = User::query()
            ->where('username', $credentials['username'])
            ->orWhere('nim', $credentials['username'])
            ->orWhere('email', $credentials['username'])
            ->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return back()
                ->withErrors(["username" => "Username atau password salah."])
                ->withInput($request->only("username"));
        }

        if (!$user->is_active) {
            return back()
                ->withErrors(["username" => "Akun Anda belum aktif."])
                ->withInput($request->only("username"));
        }

        Auth::login($user);
        $request->session()->regenerate();
        $request->session()->put('dummy_user', array_merge(
            $user->only(['id_user', 'username', 'nim', 'nama_depan', 'nama_belakang', 'prodi', 'email', 'role', 'is_active']),
            ['display_name' => trim($user->nama_depan . ' ' . $user->nama_belakang)]
        ));

        // Redirect logic based on role
        if ($user->isAdmin() || $user->isSuperAdmin()) {
            $redirectRoute = 'admin.beranda_ormawa';
        } elseif ($user->isMahasiswa()) {
            $redirectRoute = 'organisasi.beranda_mahasiswa';
        } else {
            // Default for DPMBEM, Ormawa Institusi, Ormawa Prodi, etc.
            $redirectRoute = 'organisasi.index';
        }

        return redirect()
            ->route($redirectRoute)
            ->with("success", "Selamat datang kembali, " . ($user->nama_depan ?: $user->username) . "!");
    })->name("login.post");
});

// ── Logout ────────────────────────────────────────────────────────────────────
Route::post("/logout", function (Request $request) {
    Auth::logout();
    $request->session()->forget("dummy_user");
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect()->route("login");
})->name("logout");

Route::get("/logout", fn() => redirect()->route("login"));

// ── Admin ────────────────────────────────────────────────────────────────────
Route::get("/admin/form_verifikasi/{id}", function ($id) {
    $user = auth()->user();

    if (!$user || (!$user->isAdmin() && !$user->isSuperAdmin())) {
        return redirect()->route("login");
    }

    $proposal = \App\Models\ProposalKegiatan::with('user')->findOrFail($id);
    return view("admin.form_verifikasi", compact("proposal"));
})->name("admin.form_verifikasi");

Route::get("/admin/detail_kegiatan/{id}", function ($id) {
    $proposal = \App\Models\ProposalKegiatan::with('lpj')->findOrFail($id);
    return view("organisasi.show", compact("proposal"));
})->name("admin.detail_kegiatan");

Route::post("/admin/form_verifikasi/{id}", function (Illuminate\Http\Request $request, $id) {
    $proposal = \App\Models\ProposalKegiatan::with('lpj')->findOrFail($id);
    
    // Determine which phase we are in
    $lpj = $proposal->lpj->first();
    $isLpjPhase = ($proposal->status == 'Disetujui' || $proposal->status == 'Selesai' || $proposal->status == 'Cek LPJ') && $lpj;

    if ($isLpjPhase) {
        // Handling LPJ Verification
        if ($request->status == 'Revisi') {
            $lpj->update([
                'status_lpj' => 'Revisi',
                'catatan_admin' => $request->revisi
            ]);
            $proposal->update(['status' => 'Revisi LPJ']);
        } elseif ($request->status == 'Selesai') {
            $lpj->update([
                'status_lpj' => 'Disetujui',
                'catatan_admin' => null
            ]);
            $proposal->update(['status' => 'Selesai']);
        }
    } else {
        // Handling Proposal Verification
        $updateData = [
            'anggaran_disetujui' => $request->besar_anggaran,
            'status' => $request->status,
            'catatan_admin' => $request->revisi,
        ];

        if ($request->hasFile('lpj_keuangan')) {
            $file = $request->file('lpj_keuangan');
            $originalName = str_replace(' ', '_', $file->getClientOriginalName());
            $fileName = time() . '_' . $originalName;
            $filePath = $file->storeAs('lpj_keuangan', $fileName, 'public');
            $updateData['file_lpj_keuangan'] = $filePath;
        }

        $proposal->update($updateData);
    }
    
    return redirect()->route('admin.beranda_ormawa')->with('success', 'Verifikasi berhasil disimpan.');
})->name("admin.form_verifikasi.update");

Route::get("/admin/input_template_dokumen", function () {
    $user = auth()->user();

    if (!$user || (!$user->isAdmin() && !$user->isSuperAdmin())) {
        return redirect()->route("login");
    }

    return view("admin.input_template_dokumen");
})->name("admin.input_template_dokumen");

Route::get("/admin/template_proposal", function () {
    $user = auth()->user();

    if (!$user || (!$user->isAdmin() && !$user->isSuperAdmin())) {
        return redirect()->route("login");
    }

    return view("admin.template_proposal");
})->name("admin.template_proposal");

Route::get("/admin/kontrol_akun", function () {
    $user = auth()->user();

    if (!$user || (!$user->isAdmin() && !$user->isSuperAdmin())) {
        return redirect()->route("login");
    }

    return view("admin.kontrol_akun");
})->name("admin.kontrol_akun");

Route::get("/admin/users", [App\Http\Controllers\Admin\UserController::class, "index"])->name("admin.users.index");
Route::post("/admin/users/{user}/role", [App\Http\Controllers\Admin\UserController::class, "updateRole"])->name("admin.users.update_role");

Route::get("/admin/monitoring-anggaran", function () {
    $user = auth()->user();

    if (!$user || (!$user->isAdmin() && !$user->isSuperAdmin() && !$user->isDpmbem())) {
        return redirect()->route("login");
    }

    try {
        $totalProposal = \App\Models\ProposalKegiatan::count();
        $totalDiajukan = \App\Models\ProposalKegiatan::sum('besar_ajuan');
        $totalDisetujui = \App\Models\ProposalKegiatan::where('status', 'Disetujui')->sum('anggaran_disetujui');
        $totalLpj = \App\Models\LpjKegiatan::count();
        $lpjDisetujui = \App\Models\LpjKegiatan::where('status_lpj', 'Disetujui')->count();
        $lpjRevisi = \App\Models\LpjKegiatan::where('status_lpj', 'Revisi')->count();

        $proposals = \App\Models\ProposalKegiatan::with('user')
            ->select('id_proposal', 'id_user', 'ajuan_triwulan', 'nama_kegiatan', 'besar_ajuan', 'anggaran_disetujui', 'status')
            ->orderByDesc('id_proposal')
            ->limit(8)
            ->get();
    } catch (\Throwable $e) {
        \Log::warning('Monitoring anggaran web page unavailable: ' . $e->getMessage());
        $totalProposal = $totalDiajukan = $totalDisetujui = $totalLpj = $lpjDisetujui = $lpjRevisi = 0;
        $proposals = collect();
    }

    $summary = [
        'totalProposal' => $totalProposal,
        'totalDiajukan' => $totalDiajukan,
        'totalDisetujui' => $totalDisetujui,
        'totalLpj' => $totalLpj,
        'lpjDisetujui' => $lpjDisetujui,
        'lpjRevisi' => $lpjRevisi,
    ];

    return view("admin.monitoring_anggaran", compact("summary", "proposals"));
})->name("admin.monitoring_anggaran");

Route::get("/admin/monitoring-anggaran/export-pdf", function () {
    $user = auth()->user();

    if (!$user || (!$user->isAdmin() && !$user->isSuperAdmin() && !$user->isDpmbem())) {
        return redirect()->route("login");
    }

    try {
        $totalProposal  = \App\Models\ProposalKegiatan::count();
        $totalDiajukan  = \App\Models\ProposalKegiatan::sum('besar_ajuan');
        $totalDisetujui = \App\Models\ProposalKegiatan::where('status', 'Disetujui')->sum('anggaran_disetujui');
        $totalLpj       = \App\Models\LpjKegiatan::count();
        $lpjDisetujui   = \App\Models\LpjKegiatan::where('status_lpj', 'Disetujui')->count();
        $lpjRevisi      = \App\Models\LpjKegiatan::where('status_lpj', 'Revisi')->count();

        $proposals = \App\Models\ProposalKegiatan::with('user')
            ->select('id_proposal', 'id_user', 'ajuan_triwulan', 'nama_kegiatan', 'besar_ajuan', 'anggaran_disetujui', 'status')
            ->orderByDesc('id_proposal')
            ->limit(8)
            ->get();
    } catch (\Throwable $e) {
        \Log::warning('Monitoring anggaran export unavailable: ' . $e->getMessage());
        $totalProposal = $totalDiajukan = $totalDisetujui = $totalLpj = $lpjDisetujui = $lpjRevisi = 0;
        $proposals = collect();
    }

    $summary = [
        'totalProposal'  => $totalProposal,
        'totalDiajukan'  => $totalDiajukan,
        'totalDisetujui' => $totalDisetujui,
        'totalLpj'       => $totalLpj,
        'lpjDisetujui'   => $lpjDisetujui,
        'lpjRevisi'      => $lpjRevisi,
    ];

    $pdf = Pdf::loadView("admin.monitoring_anggaran_export_pdf", compact("summary", "proposals"));
    return $pdf->download("monitoring_anggaran_" . now()->format('YmdHis') . ".pdf");
})->name("admin.monitoring_anggaran.export_pdf");

Route::get("/admin/beranda_ormawa", function () {
    $user = auth()->user();

    if (!$user || (!$user->isAdmin() && !$user->isSuperAdmin())) {
        return redirect()->route("login");
    }

    return view("admin.beranda_ormawa");
})->name("admin.beranda_ormawa");

Route::get("/admin/beranda_ormawa/export-pdf", function () {
    $user = auth()->user();

    if (!$user || (!$user->isAdmin() && !$user->isSuperAdmin())) {
        return redirect()->route("login");
    }

    $activities = [
        [
            'tw' => '1',
            'ormawa' => 'Manggala',
            'nama_kegiatan' => 'Buka Bersama Manggala',
            'resiko' => 'Sedang',
            'waktu' => '17 Maret 2026',
            'ajuan' => 'Rp 200.000',
            'anggaran' => 'Rp 200.000',
            'status' => 'Selesai',
        ],
        [
            'tw' => '1',
            'ormawa' => 'Manggala',
            'nama_kegiatan' => 'Buka Bersama Manggala',
            'resiko' => 'Tinggi',
            'waktu' => '17 Maret 2026',
            'ajuan' => 'Rp 200.000',
            'anggaran' => 'Rp 200.000',
            'status' => 'Pencairan',
        ],
        [
            'tw' => '1',
            'ormawa' => 'Manggala',
            'nama_kegiatan' => 'Buka Bersama Manggala',
            'resiko' => 'Sedang',
            'waktu' => '17 Maret 2026',
            'ajuan' => 'Rp 200.000',
            'anggaran' => 'Rp 200.000',
            'status' => 'Acc',
        ],
        [
            'tw' => '1',
            'ormawa' => 'Manggala',
            'nama_kegiatan' => 'Buka Bersama Manggala',
            'resiko' => 'Rendah',
            'waktu' => '17 Maret 2026',
            'ajuan' => 'Rp 200.000',
            'anggaran' => 'Rp 200.000',
            'status' => 'Revisi',
        ],
        [
            'tw' => '1',
            'ormawa' => 'Manggala',
            'nama_kegiatan' => 'Buka Bersama Manggala',
            'resiko' => 'Sedang',
            'waktu' => '17 Maret 2026',
            'ajuan' => 'Rp 200.000',
            'anggaran' => 'Rp 200.000',
            'status' => 'Ajuan baru',
        ],
    ];

    $statusStyles = [
        'Selesai' => 'done',
        'Pencairan' => 'pending',
        'Acc' => 'info',
        'Revisi' => 'revisi',
        'Ajuan baru' => 'new',
    ];

    $pdf = Pdf::loadView("admin.beranda_ormawa_export_pdf", compact("activities", "statusStyles"));
    return $pdf->download("beranda_ormawa_" . now()->format('YmdHis') . ".pdf");
})->name("admin.beranda_ormawa.export_pdf");

Route::get("/admin/prestasi_mahasiswa", function () {
    $user = auth()->user();

    if (!$user || (!$user->isAdmin() && !$user->isSuperAdmin())) {
        return redirect()->route("login");
    }

    return view("admin.prestasi_mahasiswa");
})->name("admin.prestasi_mahasiswa");

Route::get("/admin/prestasi_mahasiswa/export-pdf", function () {
    $user = auth()->user();

    if (!$user || (!$user->isAdmin() && !$user->isSuperAdmin())) {
        return redirect()->route("login");
    }

    $prestasi = [
        [
            'nim' => '23110401',
            'nama' => 'Melani',
            'prodi' => 'Rekayasa Perangkat Lunak',
            'prestasi' => 'Juara 3',
            'nama_event' => 'Sevent',
            'penyelenggara' => 'HMSE',
            'tingkat' => 'Nasional',
        ],
        [
            'nim' => '23110401',
            'nama' => 'Melani',
            'prodi' => 'Rekayasa Perangkat Lunak',
            'prestasi' => 'Juara 3',
            'nama_event' => 'Sevent',
            'penyelenggara' => 'HMSE',
            'tingkat' => 'Nasional',
        ],
    ];

    $pdf = Pdf::loadView("admin.prestasi_mahasiswa_export_pdf", compact("prestasi"));
    return $pdf->download("prestasi_mahasiswa_" . now()->format('YmdHis') . ".pdf");
})->name("admin.prestasi_mahasiswa.export_pdf");

Route::get("/admin/detail_prestasi/{id}", function ($id) {
    $user = auth()->user();

    if (!$user || (!$user->isAdmin() && !$user->isSuperAdmin())) {
        return redirect()->route("login");
    }

    return view("admin.detail_prestasi", ['id' => $id]);
})->name("admin.detail_prestasi");

Route::post("/admin/prestasi_mahasiswa/{id}/verify", function (Illuminate\Http\Request $request, $id) {
    $user = auth()->user();
    if (!$user || (!$user->isAdmin() && !$user->isSuperAdmin())) {
        return response()->json(['error' => 'Unauthorized'], 403);
    }

    $prestasi = \App\Models\Prestasi::findOrFail($id);
    $prestasi->update([
        'status_verifikasi' => $request->status,
        // If there's a column for notes, we could add it here
    ]);

    return redirect()->route('admin.prestasi_mahasiswa')->with('success', 'Status prestasi berhasil diperbarui.');
})->name("admin.prestasi_mahasiswa.verify");

Route::delete("/admin/prestasi_mahasiswa/{id}", function ($id) {
    $user = auth()->user();
    if (!$user || (!$user->isAdmin() && !$user->isSuperAdmin())) {
        return response()->json(['error' => 'Unauthorized'], 403);
    }

    \App\Models\Prestasi::findOrFail($id)->delete();

    return redirect()->route('admin.prestasi_mahasiswa')->with('success', 'Data prestasi berhasil dihapus.');
})->name("admin.prestasi_mahasiswa.delete");

Route::get("/admin/prestasi_ormawa", function () {
    $user = auth()->user();

    if (!$user || (!$user->isAdmin() && !$user->isSuperAdmin())) {
        return redirect()->route("login");
    }

    return view("admin.prestasi_ormawa");
})->name("admin.prestasi_ormawa");

Route::get("/admin/verifikasi-publikasi", function () {
    $user = auth()->user();
    if (!$user || (!$user->isAdmin() && !$user->isSuperAdmin())) {
        return redirect()->route("login");
    }
    $publikasis = \App\Models\PublikasiKegiatan::with('user')->orderBy('created_at', 'desc')->get();
    return view("admin.verifikasi_publikasi", compact("publikasis"));
})->name("admin.verifikasi_publikasi");

Route::post("/admin/verifikasi-publikasi/{id}", function (Illuminate\Http\Request $request, $id) {
    $user = auth()->user();
    if (!$user || (!$user->isAdmin() && !$user->isSuperAdmin())) {
        return redirect()->route("login");
    }
    $publikasi = \App\Models\PublikasiKegiatan::findOrFail($id);
    $publikasi->update([
        'status' => $request->status,
        'catatan_admin' => $request->catatan,
    ]);
    return redirect()->route("admin.verifikasi_publikasi")->with("success", "Status publikasi berhasil diperbarui.");
})->name("admin.verifikasi_publikasi.update");

Route::get("/admin/atur-deadline", function () {
    $user = auth()->user();

    if (!$user || (!$user->isAdmin() && !$user->isSuperAdmin())) {
        return redirect()->route("login");
    }

    $deadline = \App\Models\Deadline::where('is_active', true)->latest()->first();
    return view("admin.atur_deadline", compact('deadline'));
})->name("admin.atur_deadline");

Route::post("/admin/atur-deadline", function (\Illuminate\Http\Request $request) {
    $user = auth()->user();

    if (!$user || (!$user->isAdmin() && !$user->isSuperAdmin())) {
        return redirect()->route("login");
    }

    $request->validate([
        'title' => 'required|string|max:255',
        'deadline_at' => 'required|date',
    ]);

    // Deactivate previous deadlines
    \App\Models\Deadline::where('is_active', true)->update(['is_active' => false]);

    \App\Models\Deadline::create([
        'title' => $request->title,
        'deadline_at' => $request->deadline_at,
        'is_active' => true,
    ]);

    return redirect()->route("admin.atur_deadline")->with("success", "Deadline berhasil diperbarui.");
})->name("admin.atur_deadline.post");

Route::delete("/admin/atur-deadline", function () {
    $user = auth()->user();

    if (!$user || (!$user->isAdmin() && !$user->isSuperAdmin())) {
        return redirect()->route("login");
    }

    \App\Models\Deadline::where('is_active', true)->delete();

    return redirect()->route("admin.atur_deadline")->with("success", "Deadline berhasil dihapus.");
})->name("admin.atur_deadline.delete");

Route::get("/admin/prestasi_ormawa/export-pdf", function () {
    $user = auth()->user();

    if (!$user || (!$user->isAdmin() && !$user->isSuperAdmin())) {
        return redirect()->route("login");
    }

    $activities = [
        [
            'tw' => '1',
            'ormawa' => 'Manggala',
            'nama_kegiatan' => 'Buka Bersama Manggala',
            'resiko' => 'Sedang',
            'waktu' => '17 Maret 2026',
            'ajuan' => 'Rp 200.000',
            'anggaran' => 'Rp 200.000',
            'status' => 'Selesai',
        ],
        [
            'tw' => '1',
            'ormawa' => 'Manggala',
            'nama_kegiatan' => 'Buka Bersama Manggala',
            'resiko' => 'Tinggi',
            'waktu' => '17 Maret 2026',
            'ajuan' => 'Rp 200.000',
            'anggaran' => 'Rp 200.000',
            'status' => 'Pencairan',
        ],
        [
            'tw' => '1',
            'ormawa' => 'Manggala',
            'nama_kegiatan' => 'Buka Bersama Manggala',
            'resiko' => 'Sedang',
            'waktu' => '17 Maret 2026',
            'ajuan' => 'Rp 200.000',
            'anggaran' => 'Rp 200.000',
            'status' => 'Acc',
        ],
        [
            'tw' => '1',
            'ormawa' => 'Manggala',
            'nama_kegiatan' => 'Buka Bersama Manggala',
            'resiko' => 'Rendah',
            'waktu' => '17 Maret 2026',
            'ajuan' => 'Rp 200.000',
            'anggaran' => 'Rp 200.000',
            'status' => 'Revisi',
        ],
        [
            'tw' => '1',
            'ormawa' => 'Manggala',
            'nama_kegiatan' => 'Buka Bersama Manggala',
            'resiko' => 'Sedang',
            'waktu' => '17 Maret 2026',
            'ajuan' => 'Rp 200.000',
            'anggaran' => 'Rp 200.000',
            'status' => 'Ajuan baru',
        ],
    ];

    $statusStyles = [
        'Selesai' => 'done',
        'Pencairan' => 'pending',
        'Acc' => 'info',
        'Revisi' => 'revisi',
        'Ajuan baru' => 'new',
    ];

    return view("admin.prestasi_ormawa_export_pdf", compact("activities", "statusStyles"));
})->name("admin.prestasi_ormawa.export_pdf");

// ── Organisasi Mahasiswa ──────────────────────────────────────────────────────
Route::prefix("organisasi")
    ->middleware('auth')
    ->name("organisasi.")
    ->group(function () {
        Route::get('/', function () {
            $user = auth()->user();
            try {
                $total = \App\Models\ProposalKegiatan::where('id_user', $user->id_user)->count();
                $revisi = \App\Models\ProposalKegiatan::where('id_user', $user->id_user)->where('status', 'Revisi')->count();
                $disetujui = \App\Models\ProposalKegiatan::where('id_user', $user->id_user)->where('status', 'Disetujui')->count();
                $ditolak = \App\Models\ProposalKegiatan::where('id_user', $user->id_user)->where('status', 'Ditolak')->count();
                $deadline = \App\Models\Deadline::where('is_active', true)->latest()->first();
            } catch (\Throwable $e) {
                $total = $revisi = $disetujui = $ditolak = 0;
                $deadline = null;
                \Log::warning('Proposal counts or deadline unavailable: ' . $e->getMessage());
            }

            return view('organisasi.index', compact('total', 'revisi', 'disetujui', 'ditolak', 'deadline'));
        })->name('index');
        Route::get('/beranda-mahasiswa', fn() => view('organisasi.beranda_mahasiswa'))->name('beranda_mahasiswa');
        Route::get('/proposal/export', function () {
            $statusStyles = [
                'Menunggu' => 'waiting',
                'Revisi' => 'revisi',
                'Disetujui' => 'done',
                'Ditolak' => 'rejected',
            ];

            try {
                $proposals = \App\Models\ProposalKegiatan::select(
                    'id_proposal',
                    'ajuan_triwulan',
                    'nama_kegiatan',
                    'waktu_kegiatan',
                    'besar_ajuan',
                    'anggaran_disetujui',
                    'status'
                )
                    ->orderBy('id_proposal')
                    ->get();
            } catch (\Throwable $e) {
                \Log::warning('Proposal export unavailable: ' . $e->getMessage());
                $proposals = collect();
            }

            return view('organisasi.proposal_export_pdf', compact('proposals', 'statusStyles'));
        })->name('proposal_export');
        Route::get(
            "/create",
            fn() => view("organisasi.create"),
        )->name("create");
        Route::get(
            "/create_lpj",
            function () {
                $user = auth()->user();
                $proposals = \App\Models\ProposalKegiatan::with(['lpj'])
                    ->where('id_user', $user->id_user)
                    ->whereIn('status', ['Disetujui', 'Selesai'])
                    ->get()
                    ->map(function ($p) {
                        $lpj = $p->lpj->first();
                        return [
                            'id' => $p->id_proposal,
                            'nama_kegiatan' => $p->nama_kegiatan,
                            'tw' => $p->ajuan_triwulan,
                            'status' => $p->status,
                            'lpj_status' => $lpj ? $lpj->status_lpj : 'Belum Upload',
                            'lpj_file' => $lpj ? $lpj->file_lpj : null,
                            'lpj_notes' => $lpj ? $lpj->catatan_admin : null,
                        ];
                    });
                return view("organisasi.lpj_index", compact("proposals"));
            },
        )->name("create_lpj");
        Route::get(
            "/publikasi",
            function () {
                $user = auth()->user();
                $publikasiItems = \App\Models\PublikasiKegiatan::where('id_user', $user->id_user)
                    ->orderBy('created_at', 'desc')
                    ->get();
                return view("organisasi.publikasi", compact("publikasiItems"));
            }
        )->name("publikasi");
        Route::get(
            "/publikasi/export-pdf",
            function () {
                $publikasiItems = collect([]);
                return view("organisasi.publikasi_export_pdf", compact("publikasiItems"));
            },
        )->name("publikasi_export");
        Route::get(
            "/publikasi/create",
            function () {
                $weekCount = \App\Models\PublikasiKegiatan::where('id_user', auth()->user()->id_user)
                    ->where('created_at', '>=', now()->startOfWeek())
                    ->count();
                return view("organisasi.publikasi_create", compact("weekCount"));
            }
        )->name("publikasi_create");
        Route::post(
            "/publikasi/create",
            function (Illuminate\Http\Request $request) {
                $request->validate([
                    'judul' => 'required|string|max:255',
                    'ormawa' => 'required|string|max:255',
                    'caption' => 'required|string',
                    'link' => 'nullable|string',
                    'poster' => 'required|image|max:5120',
                ]);

                $filePath = null;
                if ($request->hasFile('poster')) {
                    $file = $request->file('poster');
                    $originalName = str_replace(' ', '_', $file->getClientOriginalName());
                    $fileName = time() . '_' . $originalName;
                    $filePath = $file->storeAs('posters', $fileName, 'public');
                }

                \App\Models\PublikasiKegiatan::create([
                    'id_user' => auth()->user()->id_user,
                    'judul' => $request->judul,
                    'ormawa' => $request->ormawa,
                    'caption' => $request->caption,
                    'link' => $request->link,
                    'poster' => $filePath,
                    'status' => 'Menunggu',
                ]);

                return redirect()
                    ->route("organisasi.publikasi")
                    ->with("success", "Publikasi berhasil dikirim dan menunggu verifikasi.");
            }
        );
        Route::get(
            "/template-dokumen",
            fn() => view("organisasi.template_dokumen"),
        )->name("template_dokumen");
        Route::post(
            "/",
            function (Illuminate\Http\Request $request) {
                $user = auth()->user();

                $request->validate([
                    'ajuan_tw' => 'required|string',
                    'resiko_proposal' => 'required|string',
                    'no_pic' => 'required|string',
                    'nama_kegiatan' => 'required|string|max:255',
                    'mulai_kegiatan' => 'required|date',
                    'tempat_kegiatan' => 'required|string|max:255',
                    'besar_ajuan' => 'required|numeric',
                    'nomor_rekening' => 'required|string',
                    'nama_bank' => 'required|string',
                    'nama_rekening' => 'required|string',
                    'honor_pelatih' => 'required|string',
                    'proposal' => 'required|file|mimes:pdf|max:10240',
                    'category' => 'nullable|string',
                ]);

                $filePath = null;
                if ($request->hasFile('proposal')) {
                    $file = $request->file('proposal');
                    $originalName = str_replace(' ', '_', $file->getClientOriginalName());
                    $fileName = time() . '_' . $originalName;
                    $filePath = $file->storeAs('proposals', $fileName, 'public');
                }

                $modelClass = \App\Models\ProposalKegiatan::class;
                
                if ($user->isMahasiswa()) {
                    $modelClass = \App\Models\ProposalPrestasiMahasiswa::class;
                } elseif ($request->category === 'Prestasi') {
                    $modelClass = \App\Models\ProposalPrestasiOrmawa::class;
                }

                $modelClass::create([
                    'id_user' => $user->id_user,
                    'ajuan_triwulan' => $request->ajuan_tw,
                    'risiko_proposal' => $request->resiko_proposal,
                    'no_telepon' => $request->no_pic,
                    'nama_kegiatan' => $request->nama_kegiatan,
                    'waktu_kegiatan' => $request->mulai_kegiatan,
                    'tempat_kegiatan' => $request->tempat_kegiatan,
                    'besar_ajuan' => $request->besar_ajuan,
                    'nomor_rekening' => $request->nomor_rekening,
                    'nama_bank' => $request->nama_bank,
                    'nama_rekening' => $request->nama_rekening,
                    'honor_pelatih' => $request->honor_pelatih,
                    'file' => $filePath,
                    'status' => 'Menunggu',
                    'category' => $request->category ?? ($user->isMahasiswa() ? 'Prestasi' : 'Ormawa'),
                ]);

                return redirect()
                    ->route("organisasi.index")
                    ->with("success", "Kegiatan berhasil ditambahkan.");
            },
        )->name("store");
        Route::get(
            "/{id}",
            function ($id) {
                $proposal = \App\Models\ProposalKegiatan::with('lpj')->findOrFail($id);
                return view("organisasi.show", compact("proposal"));
            },
        )->name("show");
        Route::get(
            "/{id}/edit",
            function ($id) {
                $proposal = \App\Models\ProposalKegiatan::findOrFail($id);
                return view("organisasi.revisi", compact("proposal"));
            },
        )->name("edit");
        Route::put(
            "/{id}",
            function (Illuminate\Http\Request $request, $id) {
                // Try to find the proposal in all possible tables
                $proposal = \App\Models\ProposalPrestasiMahasiswa::find($id) 
                         ?? \App\Models\ProposalPrestasiOrmawa::find($id) 
                         ?? \App\Models\ProposalKegiatan::findOrFail($id);
                
                $data = [
                    'ajuan_triwulan' => $request->ajuan_tw,
                    'risiko_proposal' => $request->resiko_proposal,
                    'nama_kegiatan' => $request->nama_kegiatan,
                    'waktu_kegiatan' => $request->waktu_kegiatan,
                    'besar_ajuan' => $request->besar_ajuan,
                    'honor_pelatih' => $request->honor_pelatih,
                    'status' => 'Menunggu', // Reset status to waiting after revision
                ];

                if ($request->hasFile('proposal')) {
                    $file = $request->file('proposal');
                    $originalName = str_replace(' ', '_', $file->getClientOriginalName());
                    $fileName = time() . '_' . $originalName;
                    $filePath = $file->storeAs('proposals', $fileName, 'public');
                    $data['file'] = $filePath;
                }

                $proposal->update($data);

                return redirect()
                    ->route("organisasi.index")
                    ->with("success", "Kegiatan berhasil diperbarui.");
            },
        )->name("update");
        Route::get("/{id}/lpj", function ($id) {
            $proposal = \App\Models\ProposalKegiatan::with('lpj')->findOrFail($id);
            return view("organisasi.create_lpj", compact("proposal"));
        })->name("lpj");
        Route::post("/{id}/lpj", function (Illuminate\Http\Request $request, $id) {
            $proposal = \App\Models\ProposalKegiatan::findOrFail($id);
            if ($request->hasFile('laporan')) {
                $file = $request->file('laporan');
                $originalName = str_replace(' ', '_', $file->getClientOriginalName());
                $fileName = time() . '_' . $originalName;
                $filePath = $file->storeAs('lpj_kegiatan', $fileName, 'public');
                \App\Models\LpjKegiatan::updateOrCreate(
                    ['id_proposal' => $proposal->id_proposal],
                    [
                        'file_lpj' => $filePath,
                        'status_lpj' => 'Menunggu',
                        'catatan_admin' => null,
                        'tanggal_upload' => now(),
                    ]
                );
                $proposal->status = 'Cek LPJ';
                $proposal->save();
            }
            return redirect()->route('organisasi.create_lpj')->with('success', 'LPJ Kegiatan berhasil diupload.');
        })->name("lpj.store");
        Route::delete(
            "/{id}",
            fn() => redirect()
                ->route("organisasi.index")
                ->with("success", "Kegiatan berhasil dihapus."),
        )->name("destroy");
    });

// ── Prestasi Mahasiswa ────────────────────────────────────────────────────────
Route::prefix("prestasi")
    ->middleware('auth')
    ->name("prestasi.")
    ->group(function () {
        Route::get("/", fn() => view("prestasi.index"))->name("index");
        Route::get("/input-proposal", fn() => view("prestasi.input_proposal"))->name("input_proposal");
        Route::post("/input-proposal", function (Illuminate\Http\Request $request) {
            $user = auth()->user();

            $request->validate([
                'nama_kegiatan' => 'required|string|max:255',
                'tanggal_pelaksanaan' => 'required|date',
                'total_anggaran' => 'required',
                'nama_bank' => 'required|string',
                'nomor_rekening' => 'required|string',
                'atas_nama_rekening' => 'required|string',
                'proposal' => 'required|file|mimes:pdf|max:10240',
            ]);

            $filePath = null;
            if ($request->hasFile('proposal')) {
                $file = $request->file('proposal');
                $originalName = str_replace(' ', '_', $file->getClientOriginalName());
                $fileName = time() . '_' . $originalName;
                $filePath = $file->storeAs('proposals', $fileName, 'public');
            }

            // Map UI fields to database columns
            \App\Models\ProposalKegiatan::create([
                'id_user' => $user->id_user,
                'ajuan_triwulan' => 'I', // Default
                'risiko_proposal' => 'Sedang', // Default
                'no_telepon' => $request->nomor_whatsapp ?? '-',
                'nama_kegiatan' => $request->nama_kegiatan,
                'waktu_kegiatan' => $request->tanggal_pelaksanaan,
                'tempat_kegiatan' => $request->penyelenggara_event ?? '-',
                'besar_ajuan' => (float) str_replace(['Rp', '.', ','], '', $request->total_anggaran),
                'nomor_rekening' => $request->nomor_rekening,
                'nama_bank' => $request->nama_bank,
                'nama_rekening' => $request->atas_nama_rekening,
                'honor_pelatih' => 'Tidak',
                'file' => $filePath,
                'status' => 'Menunggu',
            ]);

            return redirect()->route('prestasi.index')->with('success', 'Proposal berhasil diunggah dan sedang menunggu verifikasi.');
        })->name("input_proposal.post");
        Route::get("/upload-lpj", fn() => view("prestasi.upload_lpj"))->name("upload_lpj");
        Route::get("/template-dokumen", fn() => view("prestasi.template_dokumen"))->name("template_dokumen");
        Route::get("/laporan-prestasi/biodata", fn() => view("prestasi.laporan_prestasi.biodata"))->name("laporan_prestasi.biodata");
        Route::get("/laporan-prestasi/detail-kompetisi", fn() => view("prestasi.laporan_prestasi.detail_kompetisi"))->name("laporan_prestasi.detail_kompetisi");
        Route::get("/laporan-prestasi/capaian-prestasi", fn() => view("prestasi.laporan_prestasi.capaian_prestasi"))->name("laporan_prestasi.capaian_prestasi");
        Route::get("/laporan-prestasi/informasi-dosen-pembimbing", fn() => view("prestasi.laporan_prestasi.informasi_dosen_pembimbing"))->name("laporan_prestasi.informasi_dosen_pembimbing");
        Route::get("/laporan-prestasi/evidance", fn() => view("prestasi.laporan_prestasi.evidance"))->name("laporan_prestasi.evidance");
            Route::get("/kartu-prestasi/{nim}", function (string $nim) {
                $user = \App\Models\User::where('nim', $nim)->first();

                if (!$user) {
                    abort(404, 'Data mahasiswa tidak ditemukan.');
                }

                $prestasis = \App\Models\Prestasi::with('dokumen')
                    ->where('id_user', $user->id_user)
                    ->orderBy('created_at', 'desc')
                    ->get();

                $namaLengkap = trim($user->nama_depan . ' ' . ($user->nama_belakang ?? ''));
                $totalPrestasi = $prestasis->count();
                $totalDokumen = $prestasis->sum(fn ($prestasi) => $prestasi->dokumen->count());
                $tanggalLulus = now()->format('d-m-Y');
                $jenjangPendidikan = 'S1';

                return view('prestasi.kartu_prestasi', compact(
                    'user',
                    'namaLengkap',
                    'prestasis',
                    'totalPrestasi',
                    'totalDokumen',
                    'tanggalLulus',
                    'jenjangPendidikan'
                ));
            })->name('kartu_prestasi');

        Route::get("/transkrip-prestasi", function () {
            $user = auth()->user();

            if (!$user) {
                return redirect()->route('login');
            }

            $userId = $user->id_user ?? null;
            $prestasis = collect([]);
            
            if ($userId) {
                $prestasis = \App\Models\Prestasi::where('id_user', $userId)
                    ->orderBy('created_at', 'desc')
                    ->get();
            }

            $nim = trim((string) ($user->nim ?? ''));
            $cardUrl = $nim !== ''
                ? request()->getSchemeAndHttpHost() . route('prestasi.kartu_prestasi', ['nim' => $nim], false)
                : null;
            $qrCodeUrl = $cardUrl !== null
                ? 'https://api.qrserver.com/v1/create-qr-code/?size=240x240&margin=8&data=' . urlencode($cardUrl)
                : null;
            
            return view('prestasi.transkrip_prestasi', compact('user', 'prestasis', 'nim', 'cardUrl', 'qrCodeUrl'));
        })->name('transkrip_prestasi');
        Route::get(
            "/create",
            fn() => redirect()
                ->route("prestasi.index")
                ->with("error", "Halaman belum tersedia."),
        )->name("create");
        Route::post(
            "/",
            fn() => redirect()
                ->route("prestasi.index")
                ->with("success", "Prestasi berhasil ditambahkan."),
        )->name("store");
        Route::get("/{id}", fn() => redirect()->route("prestasi.index"))->name(
            "show",
        );
        Route::get(
            "/{id}/edit",
            fn() => redirect()->route("prestasi.index"),
        )->name("edit");
        Route::put(
            "/{id}",
            fn() => redirect()
                ->route("prestasi.index")
                ->with("success", "Prestasi berhasil diperbarui."),
        )->name("update");
        Route::delete(
            "/{id}",
            fn() => redirect()
                ->route("prestasi.index")
                ->with("success", "Prestasi berhasil dihapus."),
        )->name("destroy");
    });

