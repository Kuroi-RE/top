<?php

use Illuminate\Support\Facades\Route;
use Barryvdh\DomPDF\Facade\Pdf;

Route::get("/", fn() => view("landing"))->name("landing");
Route::get("/home", fn() => redirect()->route("login"));

// ── Guest ─────────────────────────────────────────────────────────────────────
Route::middleware("guest")->group(function () {
    Route::get("/login", fn() => view("auth.login"))->name("login");

    Route::get("/register", fn() => view("auth.register"))->name("register");

    Route::post("/register", function (\Illuminate\Http\Request $request) {
        $request->validate([
            "name"                  => ["required", "string", "max:255"],
            "nim"                   => ["required", "string", "max:20"],
            "prodi"                 => ["required", "string"],
            "email"                 => ["required", "email"],
            "password"              => ["required", "string", "min:8", "confirmed"],
        ], [
            "name.required"         => "Nama lengkap tidak boleh kosong.",
            "nim.required"          => "NIM tidak boleh kosong.",
            "prodi.required"        => "Program studi harus dipilih.",
            "email.required"        => "Email tidak boleh kosong.",
            "email.email"           => "Format email tidak valid.",
            "password.required"     => "Password tidak boleh kosong.",
            "password.min"          => "Password minimal 8 karakter.",
            "password.confirmed"    => "Konfirmasi password tidak cocok.",
        ]);

        // TODO: simpan ke database
        return redirect()->route("login")->with("success", "Akun berhasil dibuat! Silakan masuk.");
    })->name("register.post");

    Route::post("/login", function (\Illuminate\Http\Request $request) {
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

        // Dummy users — ganti dengan Auth::attempt() atau integrasi SSO
        $dummyUsers = [
            [
                "username" => "manggala",
                "password" => "password123",
                "role" => "ormawa",
                "id_user" => 20,
            ],
            [
                "username" => "bem",
                "password" => "password",
                "role" => "DPMBEM",
            ],
            [
                "username" => "admin",
                "password" => "password",
                "role" => "kemahasiswaan",
            ],
            [
                "username" => "ketua",
                "password" => "password",
                "role" => "ketua_institusi",
            ],
        ];

        $matched = null;
        foreach ($dummyUsers as $user) {
            if ($user["username"] === $credentials["username"] && $user["password"] === $credentials["password"]) {
                $matched = $user;
                break;
            }
        }

        if ($matched) {
            $matched["display_name"] = $credentials["username"];
            $request->session()->put("dummy_user", $matched);
            $request->session()->regenerate();

            if ($matched["role"] === "kemahasiswaan") {
                return redirect()
                    ->route("admin.beranda_ormawa")
                    ->with(
                        "success",
                        "Selamat datang kembali, " . $matched["username"] . "!",
                    );
            }

            if ($matched["role"] === "DPMBEM") {
                return redirect()
                    ->route("organisasi.index")
                    ->with(
                        "success",
                        "Selamat datang kembali, " . $matched["username"] . "!",
                    );
            }

            if ($matched["role"] === "ormawa") {
                return redirect()
                    ->route("organisasi.index")
                    ->with(
                        "success",
                        "Selamat datang kembali, " . $matched["username"] . "!",
                    );
            }

            return redirect()
                ->route("organisasi.index")
                ->with(
                    "success",
                    "Selamat datang kembali, " . $matched["username"] . "!",
                );
        }

        return back()
            ->withErrors(["username" => "Username atau password salah."])
            ->withInput($request->only("username"));
    })->name("login.post");
});

// ── Logout ────────────────────────────────────────────────────────────────────
Route::post("/logout", function (\Illuminate\Http\Request $request) {
    $request->session()->forget("dummy_user");
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect()->route("login");
})->name("logout");

Route::get("/logout", fn() => redirect()->route("login"));

// ── Admin ────────────────────────────────────────────────────────────────────
Route::get("/admin/form_verifikasi", function () {
    $user = session("dummy_user");

    if (!$user || $user["role"] !== "kemahasiswaan") {
        return redirect()->route("login");
    }

    return view("admin.form_verifikasi");
})->name("admin.form_verifikasi");

Route::get("/admin/input_template_dokumen", function () {
    $user = session("dummy_user");

    if (!$user || $user["role"] !== "kemahasiswaan") {
        return redirect()->route("login");
    }

    return view("admin.input_template_dokumen");
})->name("admin.input_template_dokumen");

Route::get("/admin/template_proposal", function () {
    $user = session("dummy_user");

    if (!$user || $user["role"] !== "kemahasiswaan") {
        return redirect()->route("login");
    }

    return view("admin.template_proposal");
})->name("admin.template_proposal");

Route::get("/admin/kontrol_akun", function () {
    $user = session("dummy_user");

    if (!$user || $user["role"] !== "kemahasiswaan") {
        return redirect()->route("login");
    }

    return view("admin.kontrol_akun");
})->name("admin.kontrol_akun");

Route::get("/admin/monitoring-anggaran", function () {
    $user = session("dummy_user");

    if (!$user || !in_array($user["role"], ["kemahasiswaan", "DPMBEM"], true)) {
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
    $user = session("dummy_user");

    if (!$user || !in_array($user["role"], ["kemahasiswaan", "DPMBEM"], true)) {
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
    $user = session("dummy_user");

    if (!$user || $user["role"] !== "kemahasiswaan") {
        return redirect()->route("login");
    }

    return view("admin.beranda_ormawa");
})->name("admin.beranda_ormawa");

Route::get("/admin/beranda_ormawa/export-pdf", function () {
    $user = session("dummy_user");

    if (!$user || $user["role"] !== "kemahasiswaan") {
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
    $user = session("dummy_user");

    if (!$user || $user["role"] !== "kemahasiswaan") {
        return redirect()->route("login");
    }

    return view("admin.prestasi_mahasiswa");
})->name("admin.prestasi_mahasiswa");

Route::get("/admin/prestasi_mahasiswa/export-pdf", function () {
    $user = session("dummy_user");

    if (!$user || $user["role"] !== "kemahasiswaan") {
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

Route::get("/admin/detail_prestasi", function () {
    $user = session("dummy_user");

    if (!$user || $user["role"] !== "kemahasiswaan") {
        return redirect()->route("login");
    }

    return view("admin.detail_prestasi");
})->name("admin.detail_prestasi");

Route::get("/admin/prestasi_ormawa", function () {
    $user = session("dummy_user");

    if (!$user || $user["role"] !== "kemahasiswaan") {
        return redirect()->route("login");
    }

    return view("admin.prestasi_ormawa");
})->name("admin.prestasi_ormawa");

Route::get("/admin/atur-deadline", function () {
    $user = session("dummy_user");

    if (!$user || $user["role"] !== "kemahasiswaan") {
        return redirect()->route("login");
    }

    $deadline = \App\Models\Deadline::where('is_active', true)->latest()->first();
    return view("admin.atur_deadline", compact('deadline'));
})->name("admin.atur_deadline");

Route::post("/admin/atur-deadline", function (\Illuminate\Http\Request $request) {
    $user = session("dummy_user");

    if (!$user || $user["role"] !== "kemahasiswaan") {
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
    $user = session("dummy_user");

    if (!$user || $user["role"] !== "kemahasiswaan") {
        return redirect()->route("login");
    }

    \App\Models\Deadline::where('is_active', true)->delete();

    return redirect()->route("admin.atur_deadline")->with("success", "Deadline berhasil dihapus.");
})->name("admin.atur_deadline.delete");

Route::get("/admin/prestasi_ormawa/export-pdf", function () {
    $user = session("dummy_user");

    if (!$user || $user["role"] !== "kemahasiswaan") {
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
    ->middleware('check.dummy_user')
    ->name("organisasi.")
    ->group(function () {
        Route::get('/', function () {
            try {
                $total = \App\Models\ProposalKegiatan::count();
                $revisi = \App\Models\ProposalKegiatan::where('status', 'Revisi')->count();
                $disetujui = \App\Models\ProposalKegiatan::where('status', 'Disetujui')->count();
                $ditolak = \App\Models\ProposalKegiatan::where('status', 'Ditolak')->count();
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
            fn() => view("organisasi.create_lpj"),
        )->name("create_lpj");
        Route::get(
            "/publikasi",
            fn() => view("organisasi.publikasi"),
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
            fn() => view("organisasi.publikasi_create"),
        )->name("publikasi_create");
        Route::get(
            "/template-dokumen",
            fn() => view("organisasi.template_dokumen"),
        )->name("template_dokumen");
        Route::post(
            "/",
            fn() => redirect()
                ->route("organisasi.index")
                ->with("success", "Kegiatan berhasil ditambahkan."),
        )->name("store");
        Route::get(
            "/{id}",
            fn() => redirect()->route("organisasi.index"),
        )->name("show");
        Route::get(
            "/{id}/edit",
            fn($id) => view("organisasi.revisi", ["id" => $id]),
        )->name("edit");
        Route::put(
            "/{id}",
            fn() => redirect()
                ->route("organisasi.index")
                ->with("success", "Kegiatan berhasil diperbarui."),
        )->name("update");
        Route::delete(
            "/{id}",
            fn() => redirect()
                ->route("organisasi.index")
                ->with("success", "Kegiatan berhasil dihapus."),
        )->name("destroy");
    });

// ── Prestasi Mahasiswa ────────────────────────────────────────────────────────
Route::prefix("prestasi")
    ->name("prestasi.")
    ->group(function () {
        Route::get("/", fn() => view("prestasi.index"))->name("index");
        Route::get("/input-proposal", fn() => view("prestasi.input_proposal"))->name("input_proposal");
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
            $sessionUser = session('dummy_user');
            
            if (!$user && $sessionUser) {
                $user = \App\Models\User::where('username', $sessionUser['username'])->first();
                if (!$user) {
                    $user = (object)$sessionUser;
                }
            }
            
            if (!$user) {
                return redirect()->route('login');
            }
            
            $userId = $user->id_user ?? $user->id ?? null;
            $prestasis = collect([]);
            
            if ($userId) {
                $prestasis = \App\Models\Prestasi::where('id_user', $userId)
                    ->orderBy('created_at', 'desc')
                    ->get();
            }

            $nim = trim((string) ($user->nim ?? ($sessionUser['nim'] ?? '')));
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

