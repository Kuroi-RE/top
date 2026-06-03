<?php

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Route;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Carbon\Carbon;

Route::get("/", function () {
    $publikasis = \App\Models\PublikasiKegiatan::where('status', 'Approved')
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
        } elseif ($user->isDpmbem()) {
            $redirectRoute = 'admin.beranda_dpmbem';
        } elseif ($user->isMahasiswa()) {
            $redirectRoute = 'organisasi.beranda_mahasiswa';
        } else {
            // Default for Ormawa Institusi, Ormawa Prodi, etc.
            $redirectRoute = 'organisasi.index';
        }
        
        return redirect()->route($redirectRoute);
    }
    return redirect()->route("login");
});

// ── Guest ─────────────────────────────────────────────────────────────────────
Route::middleware("guest")->group(function () {
    $sendTwoFactorCode = function (User $user, Request $request, string $purpose, string $redirectRoute) {
        $code = (string) random_int(100000, 999999);

        $request->session()->put('pending_2fa', [
            'user_id' => $user->id_user,
            'email' => $user->email,
            'purpose' => $purpose,
            'code_hash' => Hash::make($code),
            'expires_at' => now()->addMinutes(10)->timestamp,
            'redirect_route' => $redirectRoute,
        ]);

        // If mailer is configured to `log`, write the OTP to the application log
        // instead of attempting a real SMTP send. This avoids throwing during
        // development when SMTP creds are not available.
        if (config('mail.default') === 'log') {
            Log::info(sprintf('2FA code for %s (purpose=%s): %s', $user->email, $purpose, $code));
            return;
        }

        Mail::raw(
            "Kode verifikasi TOPKEMA Anda: {$code}\n\nKode ini berlaku selama 10 menit. Jika Anda tidak merasa meminta kode ini, abaikan email ini.",
            function ($message) use ($user, $purpose) {
                $message->to($user->email)
                    ->subject($purpose === 'register' ? 'Verifikasi Registrasi TOPKEMA' : 'Kode Verifikasi Login TOPKEMA');
            }
        );
    };

    Route::get("/login", fn() => view("auth.login"))->name("login");

    Route::get("/register", function () {
        return view("auth.register", ['require_telkom' => env('REQUIRE_TELKOM_EMAIL', true)]);
    })->name("register");

    Route::post("/register", function (Request $request) use ($sendTwoFactorCode) {
        // Build validation rules conditionally based on env setting
        $requireTelkom = env('REQUIRE_TELKOM_EMAIL', true);

        $emailRules = ["required", "email", "max:255", "unique:users,email"];
        if ($requireTelkom) {
            $emailRules[] = "regex:/@(student\.)?telkomuniversity\.ac\.id$/i";
        }

        $validated = $request->validate([
            "name"               => ["required", "string", "max:255"],
            "nim"                => ["required", "string", "max:20", "unique:users,nim"],
            "prodi"              => ["required", "string", "max:100"],
            "email"              => $emailRules,
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
        ] + ($requireTelkom ? [
            'email.regex' => 'Email harus dari @student.telkomuniversity.ac.id atau @telkomuniversity.ac.id.'
        ] : []));

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
            'is_active' => false,
        ]);

        try {
            $user->assignRole('Mahasiswa');
            $user->syncPermissions(config('permissions.role_defaults.Mahasiswa', []));
        } catch (\Throwable $e) {
            Log::warning('Unable to assign Mahasiswa role on web register: ' . $e->getMessage());
        }

        try {
            Log::info('Register: about to send 2FA to ' . $user->email);
            $sendTwoFactorCode($user, $request, 'register', 'organisasi.beranda_mahasiswa');
            Log::info('Register: sendTwoFactorCode returned for ' . $user->email);
        } catch (\Throwable $e) {
            Log::warning('Unable to send register 2FA code: ' . $e->getMessage());
            return back()->withErrors(['email' => $e->getMessage() ?: 'Gagal mengirim kode verifikasi. Silakan coba lagi.'])->withInput($request->only('name', 'nim', 'prodi', 'email'));
        }

        return redirect()->route('twofactor.verify')->with('success', 'Akun berhasil dibuat. Kode verifikasi telah dikirim ke email Anda.');
    })->name("register.post");

    Route::post("/login", function (Request $request) use ($sendTwoFactorCode) {
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

        if ($user->isAdmin() || $user->isSuperAdmin()) {
            $redirectRoute = 'admin.beranda_ormawa';
        } elseif ($user->isMahasiswa()) {
            $redirectRoute = 'organisasi.beranda_mahasiswa';
        } else {
            // Default for DPMBEM, Ormawa Institusi, Ormawa Prodi, etc.
            $redirectRoute = 'organisasi.index';
        }

        Auth::login($user);
        $request->session()->regenerate();
        $request->session()->put('dummy_user', array_merge(
            $user->only(['id_user', 'username', 'nim', 'nama_depan', 'nama_belakang', 'prodi', 'email', 'role', 'is_active']),
            ['display_name' => trim($user->nama_depan . ' ' . $user->nama_belakang)]
        ));

        return redirect()->route($redirectRoute)->with('success', 'Login berhasil.');
    })->name("login.post");

    Route::get('/verify-2fa', function (Request $request) {
        $pending = $request->session()->get('pending_2fa');

        if (!$pending) {
            return redirect()->route('login')->with('error', 'Sesi verifikasi belum tersedia. Silakan login atau register terlebih dahulu.');
        }

        if (($pending['expires_at'] ?? 0) < now()->timestamp) {
            $request->session()->forget('pending_2fa');
            return redirect()->route('login')->with('error', 'Kode verifikasi sudah kedaluwarsa. Silakan login atau register ulang.');
        }

        return view('auth.verify_2fa', [
            'email' => $pending['email'] ?? null,
            'purpose' => $pending['purpose'] ?? 'login',
        ]);
    })->name('twofactor.verify');

    Route::post('/verify-2fa', function (Request $request) {
        $validated = $request->validate([
            'code' => ['required', 'string', 'digits:6'],
        ], [
            'code.required' => 'Kode verifikasi wajib diisi.',
            'code.digits' => 'Kode verifikasi harus 6 digit.',
        ]);

        $pending = $request->session()->get('pending_2fa');

        if (!$pending) {
            return redirect()->route('login')->with('error', 'Sesi verifikasi tidak ditemukan. Silakan ulangi proses login atau register.');
        }

        if (($pending['expires_at'] ?? 0) < now()->timestamp) {
            $request->session()->forget('pending_2fa');
            return redirect()->route('login')->with('error', 'Kode verifikasi sudah kedaluwarsa. Silakan ulangi proses login atau register.');
        }

        if (!Hash::check($validated['code'], $pending['code_hash'] ?? '')) {
            return back()->withErrors(['code' => 'Kode verifikasi salah.'])->withInput();
        }

        $user = User::find($pending['user_id'] ?? null);
        if (!$user) {
            $request->session()->forget('pending_2fa');
            return redirect()->route('login')->with('error', 'Akun tidak ditemukan. Silakan login ulang.');
        }

        if (($pending['purpose'] ?? '') === 'register') {
            $user->update(['is_active' => true]);
        }

        Auth::login($user);
        $request->session()->regenerate();
        $request->session()->forget('pending_2fa');
        $request->session()->put('dummy_user', array_merge(
            $user->only(['id_user', 'username', 'nim', 'nama_depan', 'nama_belakang', 'prodi', 'email', 'role', 'is_active']),
            ['display_name' => trim($user->nama_depan . ' ' . $user->nama_belakang)]
        ));

        $redirectRoute = $pending['redirect_route'] ?? ($user->isAdmin() || $user->isSuperAdmin() ? 'admin.beranda_ormawa' : ($user->isMahasiswa() ? 'organisasi.beranda_mahasiswa' : 'organisasi.index'));

        return redirect()->route($redirectRoute)->with('success', 'Verifikasi 2FA berhasil.');
    })->name('twofactor.verify.post')->middleware('throttle:5,1');

    // Rate-limited: max 3 resend per 5 minutes
    Route::post('/verify-2fa/resend', function (Request $request) use ($sendTwoFactorCode) {
        $pending = $request->session()->get('pending_2fa');

        if (!$pending) {
            return redirect()->route('login')->with('error', 'Sesi verifikasi tidak ditemukan. Silakan login atau register ulang.');
        }

        if (($pending['expires_at'] ?? 0) < now()->timestamp) {
            $request->session()->forget('pending_2fa');
            return redirect()->route('login')->with('error', 'Kode verifikasi sudah kedaluwarsa. Silakan login atau register ulang.');
        }

        $user = User::find($pending['user_id'] ?? null);

        if (!$user) {
            $request->session()->forget('pending_2fa');
            return redirect()->route('login')->with('error', 'Akun tidak ditemukan. Silakan login atau register ulang.');
        }

        try {
            $sendTwoFactorCode($user, $request, $pending['purpose'] ?? 'login', $pending['redirect_route'] ?? 'organisasi.index');
        } catch (\Throwable $e) {
            Log::warning('Unable to resend 2FA code: ' . $e->getMessage());
            return back()->withErrors(['code' => 'Gagal mengirim ulang kode verifikasi. Silakan coba lagi.']);
        }

        return back()->with('success', 'Kode verifikasi baru telah dikirim ke email Anda.');
    })->name('twofactor.verify.resend')->middleware('throttle:3,5');

    // ── Forgot / Reset Password ───────────────────────────────────────────────
    Route::get('/forgot-password', fn() => view('auth.forgot_password'))->name('password.request');

    Route::post('/forgot-password', function (Request $request) {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ], [
            'email.required' => 'Email tidak boleh kosong.',
            'email.email' => 'Format email tidak valid.',
            'email.exists' => 'Email tidak terdaftar dalam sistem.',
        ]);

        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return back()->withErrors(['email' => 'User tidak ditemukan.']);
        }

        $token = Str::random(60);

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $user->email],
            [
                'token' => Hash::make($token),
                'created_at' => now(),
            ]
        );

        $resetUrl = URL::temporarySignedRoute(
            'password.reset',
            now()->addMinutes(30),
            ['token' => $token, 'email' => $user->email]
        );

        if (config('mail.default') === 'log') {
            Log::info(sprintf('Reset Password link for %s: %s', $user->email, $resetUrl));
        } else {
            try {
                Mail::raw(
                    "Halo {$user->nama_depan},\n\nAnda menerima email ini karena kami menerima permintaan reset password untuk akun Anda.\n\nSilakan klik link di bawah ini untuk mereset password Anda (Link ini berlaku selama 30 menit):\n\n{$resetUrl}\n\nJika Anda tidak meminta reset password, abaikan email ini.",
                    function ($message) use ($user) {
                        $message->to($user->email)
                            ->subject('Reset Password Akun TOPKEMA Anda');
                    }
                );
            } catch (\Throwable $e) {
                Log::warning('Unable to send reset password email: ' . $e->getMessage());
                return back()->withErrors(['email' => 'Gagal mengirim email reset password. Silakan coba lagi nanti.']);
            }
        }

        return back()->with('success', 'Link reset password telah dikirim ke email Anda.');
    })->name('password.email');

    Route::get('/reset-password/{token}', function (Request $request, $token) {
        if (! $request->hasValidSignature()) {
            return redirect()->route('login')->with('error', 'Link reset password tidak valid atau telah kedaluwarsa.');
        }

        return view('auth.reset_password', [
            'token' => $token,
            'email' => $request->query('email')
        ]);
    })->name('password.reset');

    Route::post('/reset-password', function (Request $request) {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'token.required' => 'Token tidak boleh kosong.',
            'email.required' => 'Email tidak boleh kosong.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Password tidak boleh kosong.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        $record = DB::table('password_reset_tokens')->where('email', $request->email)->first();

        if (! $record || ! Hash::check($request->token, $record->token)) {
            return redirect()->route('login')->with('error', 'Token reset password tidak valid.');
        }

        if (Carbon::parse($record->created_at)->addMinutes(30)->isPast()) {
            DB::table('password_reset_tokens')->where('email', $request->email)->delete();
            return redirect()->route('login')->with('error', 'Token reset password sudah kedaluwarsa.');
        }

        $user = User::where('email', $request->email)->first();
        if ($user) {
            $user->update([
                'password' => $request->password
            ]);
            DB::table('password_reset_tokens')->where('email', $request->email)->delete();
            return redirect()->route('login')->with('success', 'Password Anda berhasil diperbarui. Silakan login.');
        }

        return back()->withErrors(['email' => 'Pengguna tidak ditemukan.']);
    })->name('password.update');
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

// ── API Token Generator (Web Session → Sanctum Token) ────────────────────────
// Memungkinkan JavaScript di halaman Blade mendapatkan Sanctum token
// berdasarkan session web yang sudah aktif.
Route::get('/api/token', function (Request $request) {
    if (!Auth::check()) {
        return response()->json(['error' => 'Unauthenticated'], 401);
    }

    $user = Auth::user();

    // Cek jika token sudah ada di web session dan masih valid di database
    $token = session()->get('web_session_api_token');
    $tokenIsValid = false;

    if ($token) {
        $accessToken = \Laravel\Sanctum\PersonalAccessToken::findToken($token);
        if ($accessToken && $accessToken->tokenable_id === $user->id_user) {
            $tokenIsValid = true;
        }
    }

    if (!$tokenIsValid) {
        // Hapus token lama jika ada
        $user->tokens()->where('name', 'web-session-token')->delete();

        // Buat token baru
        $token = $user->createToken('web-session-token', ['*'])->plainTextToken;

        // Simpan plaintext token di web session
        session()->put('web_session_api_token', $token);
    }

    return response()->json([
        'token' => $token,
        'user'  => [
            'id'         => $user->id_user,
            'username'   => $user->username,
            'nama_depan' => $user->nama_depan,
            'nama_belakang' => $user->nama_belakang,
            'email'      => $user->email,
            'role'       => $user->role,
            'is_active'  => $user->is_active,
        ],
    ]);
})->middleware('auth')->name('api.token');

// ── Admin ────────────────────────────────────────────────────────────────────
Route::middleware(['auth'])->prefix('admin')->group(function () {

    // Monitoring Anggaran allows Admin, Super Admin, and DPMBEM
    Route::group(['middleware' => [function ($request, $next) {
        $user = auth()->user();
        if (!$user || (!$user->isAdmin() && !$user->isSuperAdmin() && !$user->isDpmbem())) {
            return redirect()->route("login");
        }
        return $next($request);
    }]], function () {
        Route::get("/monitoring-anggaran", function () {
            try {
                $totalProposal = \App\Models\ProposalKegiatan::count();
                $totalDiajukan = \App\Models\ProposalKegiatan::sum('besar_ajuan');
                $totalDisetujui = \App\Models\ProposalKegiatan::where('status', 'Approved')->sum('anggaran_disetujui');
                $totalLpj = \App\Models\LpjKegiatan::count();
                $lpjDisetujui = \App\Models\LpjKegiatan::where('status_lpj', 'Approved')->count();
                $lpjRevisi = \App\Models\LpjKegiatan::where('status_lpj', 'Revision')->count();

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

        Route::get("/monitoring-anggaran/export-pdf", function () {
            $api = \App\Services\ApiService::getClient();
            $response = $api->get('/monitoring/anggaran/export-pdf');

            if ($response->successful()) {
                $filename = "monitoring_anggaran_" . now()->format('YmdHis') . ".pdf";
                return response($response->body(), 200, [
                    'Content-Type' => 'application/pdf',
                    'Content-Disposition' => 'attachment; filename="' . $filename . '"'
                ]);
            }

            return redirect()->back()->with('error', 'Gagal menghasilkan PDF dari API.');
        })->name("admin.monitoring_anggaran.export_pdf");

        // DPMBEM dashboard
        Route::get("/beranda-dpmbem", function () {
            try {
                $api = \App\Services\ApiService::getClient();
                
                // Fetch proposals
                $proposalResponse = $api->get('/proposal', ['per_page' => 100]);
                $proposals = [];
                if ($proposalResponse->successful()) {
                    $proposals = $proposalResponse->json('data') ?? [];
                }

                // Filter proposals of category 'Ormawa'
                $proposals = collect($proposals)->filter(function ($p) {
                    return ($p['category'] ?? 'Ormawa') === 'Ormawa';
                })->values();

                $total = count($proposals);

                // Fetch LPJs
                $lpjResponse = $api->get('/lpj');
                $lpjs = [];
                $lpjCount = 0;
                if ($lpjResponse->successful()) {
                    $lpjs = $lpjResponse->json('data') ?? [];
                    $lpjCount = count($lpjs);
                }

                $lpjByProposalId = [];
                foreach ($lpjs as $lpj) {
                    $lpjByProposalId[$lpj['id_proposal']] = $lpj;
                }

                // Fetch publications
                $publikasiResponse = $api->get('/publikasi');
                $publikasiCount = 0;
                if ($publikasiResponse->successful()) {
                    $publikasiCount = count($publikasiResponse->json('data') ?? []);
                }

                $statusMap = [
                    'Pending' => 'Menunggu',
                    'Revision' => 'Revisi',
                    'Approved' => 'Disetujui',
                    'Rejected' => 'Ditolak',
                    'Cek LPJ' => 'Cek LPJ',
                    'Revisi LPJ' => 'Revisi LPJ',
                    'Selesai' => 'Selesai',
                ];

                // Collection level filters from request query parameters
                if (request('jenis_ormawa')) {
                    $proposals = $proposals->filter(function ($p) {
                        return strtolower($p['user']['jenis_organisasi'] ?? '') === strtolower(request('jenis_ormawa'));
                    });
                }

                if (request('nama_ormawa')) {
                    $q = strtolower(request('nama_ormawa'));
                    $proposals = $proposals->filter(function ($p) use ($q) {
                        return str_contains(strtolower($p['user']['nama_belakang'] ?? $p['user']['username'] ?? ''), $q);
                    });
                }

                // Map proposals to $activities structure for the view
                $activities = $proposals->take(10)->map(function ($p) use ($statusMap, $lpjByProposalId) {
                    $rawStatus = $p['status'] ?? '';
                    $mappedStatus = $statusMap[$rawStatus] ?? $rawStatus;

                    $lpj = $lpjByProposalId[$p['id_proposal']] ?? null;

                    // If proposal is Disetujui and LPJ is Pending, display status as Cek LPJ
                    if ($mappedStatus === 'Disetujui' && $lpj && ($lpj['status_lpj'] ?? '') === 'Pending') {
                        $mappedStatus = 'Cek LPJ';
                    }

                    $formattedDate = isset($p['waktu_kegiatan']) 
                        ? \Carbon\Carbon::parse($p['waktu_kegiatan'])->format('d/m/Y') 
                        : '-';

                    return [
                        'tw' => $p['ajuan_triwulan'] ?? '-',
                        'ormawa' => $p['user']['nama_belakang'] ?? $p['user']['username'] ?? 'Ormawa',
                        'nama_kegiatan' => $p['nama_kegiatan'] ?? '-',
                        'resiko' => $p['risiko_proposal'] ?? '-',
                        'waktu' => $formattedDate,
                        'ajuan' => 'Rp ' . number_format((float) ($p['besar_ajuan'] ?? 0), 0, ',', '.'),
                        'anggaran' => 'Rp ' . number_format((float) ($p['anggaran_disetujui'] ?? 0), 0, ',', '.'),
                        'status' => $mappedStatus,
                        'id' => $p['id_proposal'],
                        'lpj_keu' => $p['file_lpj_keuangan'] ?? null,
                        'lpj_keg' => $lpj ? $lpj['file_lpj'] : null,
                    ];
                });

            } catch (\Throwable $e) {
                $total = $lpjCount = $publikasiCount = 0;
                $activities = collect();
                \Log::error('API Error in DPMBEM dashboard: ' . $e->getMessage());
            }

            return view("admin.beranda_dpmbem", compact('total', 'lpjCount', 'publikasiCount', 'activities'));
        })->name("admin.beranda_dpmbem");

        // Detail Kegiatan - moved so DPMBEM also has access to view details of a proposal/activity
        Route::get("/detail_kegiatan/{id}", function ($id) {
            $proposal = \App\Models\ProposalKegiatan::with('lpj')->findOrFail($id);
            return view("organisasi.show", compact("proposal"));
        })->name("admin.detail_kegiatan");
    });

    // All other admin routes allow only Admin and Super Admin
    Route::group(['middleware' => [function ($request, $next) {
        $user = auth()->user();
        if (!$user || (!$user->isAdmin() && !$user->isSuperAdmin())) {
            return redirect()->route("login");
        }
        return $next($request);
    }]], function () {
        Route::get("/form_verifikasi/{id}", function ($id) {
            $type = request('type');
            if ($type === 'mahasiswa') {
                $proposal = \App\Models\ProposalPrestasiMahasiswa::with('user')->findOrFail($id);
            } elseif ($type === 'ormawa') {
                $proposal = \App\Models\ProposalKegiatan::with('user')->findOrFail($id);
            } else {
                $proposal = \App\Models\ProposalKegiatan::with('user')->find($id);
                if (!$proposal) {
                    $proposal = \App\Models\ProposalPrestasiMahasiswa::with('user')->findOrFail($id);
                }
            }
            return view("admin.form_verifikasi", compact("proposal"));
        })->name("admin.form_verifikasi");

        Route::post("/form_verifikasi/{id}", function (Illuminate\Http\Request $request, $id) {
            $api = \App\Services\ApiService::getClient();
            $type = $request->input('type', $request->query('type'));
            
            $payload = [
                'status' => $request->status,
                'catatan_admin' => $request->revisi,
                'anggaran_disetujui' => $request->besar_anggaran
            ];

            if ($type) {
                $payload['type'] = $type;
            }

            // Setup multipart if file exists
            $apiReq = $api;
            if ($request->hasFile('lpj_keuangan')) {
                $file = $request->file('lpj_keuangan');
                $apiReq = $apiReq->attach(
                    'file_lpj_keuangan',
                    file_get_contents($file->getRealPath()),
                    $file->getClientOriginalName()
                );
            }

            // Fetch proposal with type parameter to avoid collision
            $queryParams = [];
            if ($type) {
                $queryParams['type'] = $type;
            }
            $proposalResponse = $api->get("/proposal/{$id}", $queryParams);
            
            if ($proposalResponse->successful()) {
                $data = $proposalResponse->json('data');
                $isLpjPhase = in_array($data['status'], ['Disetujui', 'Approved', 'Selesai', 'Cek LPJ', 'Revisi LPJ']) && !empty($data['lpj']);
                
                if ($isLpjPhase) {
                    $lpjId = $data['lpj'][0]['id_lpj'] ?? null;
                    if ($lpjId) {
                        $res = $apiReq->patch("/lpj/{$lpjId}/verifikasi", $payload);
                    } else {
                        return back()->withErrors(['message' => 'LPJ tidak ditemukan.'])->withInput();
                    }
                } else {
                    $res = $apiReq->patch("/proposal/{$id}/verifikasi", $payload);
                }
                
                if (isset($res) && $res->successful()) {
                    $redirectRoute = $type === 'mahasiswa' ? 'admin.prestasi_mahasiswa' : 'admin.beranda_ormawa';
                    return redirect()->route($redirectRoute)->with('success', 'Verifikasi kegiatan berhasil disimpan.');
                }
                
                $errMsg = isset($res) ? ($res->json('message') ?? 'Gagal menyimpan verifikasi via API.') : 'Gagal memproses request.';
                $errors = isset($res) ? ($res->json('errors') ?? []) : [];
                return back()->withErrors(array_merge(['message' => $errMsg], $errors))->withInput();
            }
            
            // If proposal not found, it might be a prestasi mahasiswa
            $res = $apiReq->patch("/prestasi/{$id}/verifikasi", [
                'status_verifikasi' => $request->status,
                'catatan_admin' => $request->revisi,
            ]);
            
            if ($res->successful()) {
                return redirect()->route('admin.prestasi_mahasiswa')->with('success', 'Verifikasi prestasi berhasil disimpan.');
            }
            
            $errMsg = $res->json('message') ?? 'Gagal memverifikasi prestasi via API.';
            $errors = $res->json('errors') ?? [];
            return back()->withErrors(array_merge(['message' => $errMsg], $errors))->withInput();
        })->name("admin.form_verifikasi.update");

        Route::get("/input_template_dokumen", function () {
            return view("admin.input_template_dokumen");
        })->name("admin.input_template_dokumen");

        Route::get("/template_proposal", function () {
            return view("admin.template_proposal");
        })->name("admin.template_proposal");

        Route::get("/kontrol_akun", function () {
            return view("admin.kontrol_akun");
        })->name("admin.kontrol_akun");

        Route::get("/users", [App\Http\Controllers\Admin\UserController::class, "index"])->name("admin.users.index");
        Route::post("/users/{user}/role", [App\Http\Controllers\Admin\UserController::class, "updateRole"])->name("admin.users.update_role");
        Route::post("/users/{user}/toggle-active", [App\Http\Controllers\Admin\UserController::class, "toggleActive"])->name("admin.users.toggle_active");

        Route::get("/beranda_ormawa", function () {
            try {
                $api = \App\Services\ApiService::getClient();
                
                // Fetch proposals
                $proposalResponse = $api->get('/proposal', ['per_page' => 100]);
                $proposals = [];
                if ($proposalResponse->successful()) {
                    $proposals = $proposalResponse->json('data') ?? [];
                }

                // Filter proposals of category 'Ormawa'
                $proposals = collect($proposals)->filter(function ($p) {
                    return ($p['category'] ?? 'Ormawa') === 'Ormawa';
                })->values();

                // Collection level filters from request query parameters
                $jenisOrmawa = request('jenis_ormawa');
                if ($jenisOrmawa) {
                    $proposals = collect($proposals)->filter(function ($p) use ($jenisOrmawa) {
                        $role = strtolower($p['user']['role'] ?? '');
                        return str_contains($role, strtolower($jenisOrmawa));
                    })->values();
                }

                $namaOrmawa = request('nama_ormawa');
                if ($namaOrmawa) {
                    $proposals = collect($proposals)->filter(function ($p) use ($namaOrmawa) {
                        $name = strtolower(($p['user']['nama_depan'] ?? '') . ' ' . ($p['user']['nama_belakang'] ?? '') . ' ' . ($p['user']['username'] ?? ''));
                        return str_contains($name, strtolower($namaOrmawa));
                    })->values();
                }

                $total = $proposals->count();

                // Fetch LPJs
                $lpjResponse = $api->get('/lpj');
                $lpjs = [];
                if ($lpjResponse->successful()) {
                    $lpjs = $lpjResponse->json('data') ?? [];
                }
                
                $lpjByProposalId = [];
                foreach ($lpjs as $lpj) {
                    $lpjByProposalId[$lpj['id_proposal']] = $lpj;
                }

                $lpjCount = collect($lpjs)->filter(function ($lpj) use ($proposals) {
                    return $proposals->pluck('id_proposal')->contains($lpj['id_proposal']);
                })->count();

                // Fetch Publications
                $publikasiResponse = $api->get('/publikasi');
                $publikasiCount = 0;
                if ($publikasiResponse->successful()) {
                    $publikasiCount = count($publikasiResponse->json('data') ?? []);
                }

                $statusMap = [
                    'Pending' => 'Menunggu',
                    'Revision' => 'Revisi',
                    'Approved' => 'Disetujui',
                    'Rejected' => 'Ditolak',
                    'Cek LPJ' => 'Cek LPJ',
                    'Revisi LPJ' => 'Revisi LPJ',
                    'Selesai' => 'Selesai',
                ];

                // Map proposals to $activities structure for the view
                $activities = $proposals->take(10)->map(function ($p) use ($statusMap, $lpjByProposalId) {
                    $rawStatus = $p['status'] ?? '';
                    $mappedStatus = $statusMap[$rawStatus] ?? $rawStatus;

                    $lpj = $lpjByProposalId[$p['id_proposal']] ?? null;

                    // If proposal is Disetujui and LPJ is Pending, display status as Cek LPJ
                    if ($mappedStatus === 'Disetujui' && $lpj && ($lpj['status_lpj'] ?? '') === 'Pending') {
                        $mappedStatus = 'Cek LPJ';
                    }

                    $formattedDate = isset($p['waktu_kegiatan']) 
                        ? \Carbon\Carbon::parse($p['waktu_kegiatan'])->format('d/m/Y') 
                        : '-';

                    return [
                        'tw' => $p['ajuan_triwulan'] ?? '-',
                        'ormawa' => $p['user']['nama_belakang'] ?? $p['user']['username'] ?? 'Ormawa',
                        'nama_kegiatan' => $p['nama_kegiatan'] ?? '-',
                        'resiko' => $p['risiko_proposal'] ?? '-',
                        'waktu' => $formattedDate,
                        'ajuan' => 'Rp ' . number_format((float) ($p['besar_ajuan'] ?? 0), 0, ',', '.'),
                        'anggaran' => 'Rp ' . number_format((float) ($p['anggaran_disetujui'] ?? 0), 0, ',', '.'),
                        'status' => $mappedStatus,
                        'id' => $p['id_proposal'],
                        'lpj_keu' => $p['file_lpj_keuangan'] ?? null,
                        'lpj_keg' => $lpj ? $lpj['file_lpj'] : null,
                    ];
                });

            } catch (\Throwable $e) {
                $total = $lpjCount = $publikasiCount = 0;
                $activities = collect();
                \Log::error('API Error in admin beranda ormawa: ' . $e->getMessage());
            }

            return view("admin.beranda_ormawa", compact('total', 'lpjCount', 'publikasiCount', 'activities'));
        })->name("admin.beranda_ormawa");

        Route::get("/beranda_ormawa/export-pdf", function () {
            $api = \App\Services\ApiService::getClient();
            
            $params = [];
            if (request('jenis_ormawa')) $params['jenis_ormawa'] = request('jenis_ormawa');
            if (request('nama_ormawa')) $params['nama_ormawa'] = request('nama_ormawa');
            
            $response = $api->get('/monitoring/beranda_ormawa/export-pdf', $params);

            if ($response->successful()) {
                $filename = "beranda_ormawa_" . now()->format('YmdHis') . ".pdf";
                return response($response->body(), 200, [
                    'Content-Type' => 'application/pdf',
                    'Content-Disposition' => 'attachment; filename="' . $filename . '"'
                ]);
            }

            return redirect()->back()->with('error', 'Gagal menghasilkan PDF dari API.');
        })->name("admin.beranda_ormawa.export_pdf");

        Route::get("/prestasi_mahasiswa", function () {
            return view("admin.prestasi_mahasiswa");
        })->name("admin.prestasi_mahasiswa");

        Route::get("/prestasi_mahasiswa/export-pdf", function () {
            $api = \App\Services\ApiService::getClient();
            
            $params = ['mewakili_ormawa' => 'tidak'];
            if (request('tingkat')) $params['tingkat'] = request('tingkat');
            if (request('search')) $params['search'] = request('search');
            
            $response = $api->get('/prestasi/export-pdf', $params);

            if ($response->successful()) {
                $filename = "prestasi_mahasiswa_" . now()->format('YmdHis') . ".pdf";
                return response($response->body(), 200, [
                    'Content-Type' => 'application/pdf',
                    'Content-Disposition' => 'attachment; filename="' . $filename . '"'
                ]);
            }

            return redirect()->back()->with('error', 'Gagal menghasilkan PDF dari API.');
        })->name("admin.prestasi_mahasiswa.export_pdf");

        Route::get("/detail_prestasi/{id}", function ($id) {
            return view("admin.detail_prestasi", ['id' => $id]);
        })->name("admin.detail_prestasi");

        Route::post("/prestasi_mahasiswa/{id}/verify", function (Illuminate\Http\Request $request, $id) {
            $api = \App\Services\ApiService::getClient();
            $response = $api->patch("/prestasi/{$id}/verifikasi", [
                'status_verifikasi' => $request->status,
                'catatan_admin' => $request->catatan,
            ]);

            if ($response->successful()) {
                return redirect()->route('admin.prestasi_mahasiswa')->with('success', 'Status prestasi berhasil diperbarui.');
            }
            return back()->withErrors(['message' => 'Gagal memverifikasi prestasi via API.']);
        })->name("admin.prestasi_mahasiswa.verify");

        Route::delete("/prestasi_mahasiswa/{id}", function ($id) {
            $api = \App\Services\ApiService::getClient();
            $response = $api->delete("/prestasi/{$id}");

            if ($response->successful()) {
                return redirect()->route('admin.prestasi_mahasiswa')->with('success', 'Data prestasi berhasil dihapus.');
            }
            return back()->withErrors(['message' => 'Gagal menghapus prestasi via API.']);
        })->name("admin.prestasi_mahasiswa.delete");

        Route::get("/prestasi_ormawa", function () {
            return view("admin.prestasi_ormawa");
        })->name("admin.prestasi_ormawa");

        Route::get("/verifikasi-publikasi", function () {
            $api = \App\Services\ApiService::getClient();
            $response = $api->get('/publikasi');
            $publikasis = [];
            if ($response->successful()) {
                // Convert array to objects for blade compatibility if needed, 
                // or the blade might just expect arrays if we refactor it.
                // Assuming blade uses $p->judul, we cast it to object.
                $data = $response->json('data') ?? [];
                $publikasis = collect($data)->map(function ($item) {
                    return json_decode(json_encode($item));
                });
            }
            return view("admin.verifikasi_publikasi", compact("publikasis"));
        })->name("admin.verifikasi_publikasi");

        Route::post("/verifikasi-publikasi/{id}", function (Illuminate\Http\Request $request, $id) {
            $api = \App\Services\ApiService::getClient();
            
            $mappedStatus = match($request->status) {
                'Disetujui' => 'Approved',
                'Revisi' => 'Revision',
                'Ditolak' => 'Rejected',
                default => $request->status,
            };

            $payload = [
                'status' => $mappedStatus,
                'catatan_admin' => $request->catatan,
            ];

            if ($mappedStatus === 'Approved' && $request->has('placement')) {
                $payload['placement'] = $request->placement;
            }

            $response = $api->patch("/publikasi/{$id}/verifikasi", $payload);

            if ($response->successful()) {
                return redirect()->route("admin.verifikasi_publikasi")->with("success", "Status publikasi berhasil diperbarui.");
            }
            
            $errMsg = $response->json('message') ?? 'Gagal memverifikasi publikasi via API.';
            $errors = $response->json('errors') ?? [];
            return back()->withErrors(array_merge(['message' => $errMsg], $errors))->withInput();
        })->name("admin.verifikasi_publikasi.update");

        Route::get("/atur-deadline", function () {
            $api = \App\Services\ApiService::getClient();
            $response = $api->get('/deadline');
            $deadline = null;
            if ($response->successful()) {
                $deadlineData = $response->json('data');
                if ($deadlineData) {
                    $deadline = (object) $deadlineData;
                }
            }
            return view("admin.atur_deadline", compact('deadline'));
        })->name("admin.atur_deadline");

        Route::post("/atur-deadline", function (\Illuminate\Http\Request $request) {
            $request->validate([
                'title' => 'required|string|max:255',
                'deadline_at' => 'required|date',
            ]);

            $api = \App\Services\ApiService::getClient();
            $response = $api->post('/deadline', [
                'title' => $request->title,
                'deadline_at' => $request->deadline_at,
            ]);

            if ($response->successful()) {
                return redirect()->route("admin.atur_deadline")->with("success", "Deadline berhasil diperbarui.");
            }
            return redirect()->route("admin.atur_deadline")->withErrors(['message' => 'Gagal memperbarui deadline via API.']);
        })->name("admin.atur_deadline.post");

        Route::delete("/atur-deadline", function () {
            // Need to get the active deadline ID first
            $api = \App\Services\ApiService::getClient();
            $response = $api->get('/deadline');
            if ($response->successful() && $response->json('data')) {
                $id = $response->json('data.id');
                $deleteResponse = $api->delete("/deadline/{$id}");
                if ($deleteResponse->successful()) {
                    return redirect()->route("admin.atur_deadline")->with("success", "Deadline berhasil dihapus.");
                }
            }
            return redirect()->route("admin.atur_deadline")->withErrors(['message' => 'Gagal menghapus deadline via API.']);
        })->name("admin.atur_deadline.delete");

        Route::get("/prestasi_ormawa/export-pdf", function () {
            $api = \App\Services\ApiService::getClient();
            
            $params = ['mewakili_ormawa' => 'ya'];
            if (request('tingkat')) $params['tingkat'] = request('tingkat');
            if (request('search')) $params['search'] = request('search');
            
            $response = $api->get('/prestasi/export-pdf', $params);

            if ($response->successful()) {
                $filename = "prestasi_ormawa_" . now()->format('YmdHis') . ".pdf";
                return response($response->body(), 200, [
                    'Content-Type' => 'application/pdf',
                    'Content-Disposition' => 'attachment; filename="' . $filename . '"'
                ]);
            }

            return redirect()->back()->with('error', 'Gagal menghasilkan PDF dari API.');
        })->name("admin.prestasi_ormawa.export_pdf");
    });
});

// ── Organisasi Mahasiswa ──────────────────────────────────────────────────────
Route::prefix("organisasi")
    ->middleware('auth')
    ->name("organisasi.")
    ->group(function () {
        Route::get('/', function () {
            try {
                $api = \App\Services\ApiService::getClient();
                
                // Fetch proposals
                $proposalResponse = $api->get('/proposal', ['per_page' => 100]);
                $proposals = [];
                $total = 0;
                $revisi = 0;
                $disetujui = 0;
                $ditolak = 0;
                
                $statusMap = [
                    'Pending' => 'Menunggu',
                    'Revision' => 'Revisi',
                    'Approved' => 'Disetujui',
                    'Rejected' => 'Ditolak',
                    'Cek LPJ' => 'Cek LPJ',
                    'Revisi LPJ' => 'Revisi LPJ',
                    'Selesai' => 'Selesai',
                ];

                if ($proposalResponse->successful()) {
                    $proposals = $proposalResponse->json('data') ?? [];
                    $total = count($proposals);
                    foreach ($proposals as $p) {
                        $rawStatus = $p['status'] ?? '';
                        $mappedStatus = $statusMap[$rawStatus] ?? $rawStatus;
                        if ($mappedStatus === 'Revisi') {
                            $revisi++;
                        } elseif ($mappedStatus === 'Disetujui') {
                            $disetujui++;
                        } elseif ($mappedStatus === 'Ditolak') {
                            $ditolak++;
                        }
                    }
                }

                // Fetch LPJs to correlate
                $lpjResponse = $api->get('/lpj');
                $lpjs = [];
                $lpjCount = 0;
                if ($lpjResponse->successful()) {
                    $lpjs = $lpjResponse->json('data') ?? [];
                    $lpjCount = count($lpjs);
                }

                $lpjByProposalId = [];
                foreach ($lpjs as $lpj) {
                    $lpjByProposalId[$lpj['id_proposal']] = $lpj;
                }

                // Fetch publications (using /api/v1/publikasi)
                $publikasiResponse = $api->get('/publikasi');
                $publikasiCount = 0;
                if ($publikasiResponse->successful()) {
                    $publikasiCount = count($publikasiResponse->json('data') ?? []);
                }

                // Fetch active deadline
                $deadlineResponse = $api->get('/deadline');
                $deadline = null;
                if ($deadlineResponse->successful()) {
                    $deadlineData = $deadlineResponse->json('data');
                    if ($deadlineData) {
                        $deadline = (object)[
                            'id' => $deadlineData['id'],
                            'title' => $deadlineData['title'],
                            'deadline_at' => isset($deadlineData['deadline_at']) ? \Carbon\Carbon::parse($deadlineData['deadline_at']) : null,
                        ];
                    }
                }

                // Map proposals to $activities structure for the view
                $activities = collect($proposals)->map(function ($p, $index) use ($statusMap, $lpjByProposalId) {
                    $rawStatus = $p['status'] ?? '';
                    $mappedStatus = $statusMap[$rawStatus] ?? $rawStatus;

                    $lpj = $lpjByProposalId[$p['id_proposal']] ?? null;

                    // If proposal is Disetujui and LPJ is Pending, display status as Cek LPJ
                    if ($mappedStatus === 'Disetujui' && $lpj && ($lpj['status_lpj'] ?? '') === 'Pending') {
                        $mappedStatus = 'Cek LPJ';
                    }

                    $formattedDate = isset($p['waktu_kegiatan']) 
                        ? \Carbon\Carbon::parse($p['waktu_kegiatan'])->format('d/m/Y') 
                        : '-';

                    // Parse file_lpj path from url if possible
                    $lpjKeuanganPath = null;
                    if (isset($p['file_lpj_keuangan_url'])) {
                        // Extract relative path from URL (e.g. storage/lpj_keuangan/xyz.pdf -> lpj_keuangan/xyz.pdf)
                        $parts = explode('/storage/', $p['file_lpj_keuangan_url']);
                        $lpjKeuanganPath = count($parts) > 1 ? $parts[1] : null;
                    }

                    return [
                        'no' => $index + 1,
                        'tw' => $p['ajuan_triwulan'] ?? '-',
                        'nama_kegiatan' => $p['nama_kegiatan'] ?? '-',
                        'pelaksanaan' => $formattedDate,
                        'ajuan_dana' => 'Rp ' . number_format((float) ($p['besar_ajuan'] ?? 0), 0, ',', '.'),
                        'anggaran' => 'Rp ' . number_format((float) ($p['anggaran_disetujui'] ?? 0), 0, ',', '.'),
                        'status' => $mappedStatus,
                        'lpj_keuangan' => $lpjKeuanganPath,
                        'lpj_kegiatan_file' => $lpj ? (object)[
                            'file_lpj' => $lpj['file_lpj']
                        ] : null,
                        'lpj_kegiatan_status' => $lpj ? ($statusMap[$lpj['status_lpj']] ?? $lpj['status_lpj']) : null,
                        'lpj_kegiatan_notes' => $lpj['catatan_admin'] ?? null,
                        'catatan_admin' => $p['catatan_admin'] ?? null,
                        'id' => $p['id_proposal'],
                    ];
                });

            } catch (\Throwable $e) {
                $total = $revisi = $disetujui = $ditolak = $lpjCount = $publikasiCount = 0;
                $deadline = null;
                $activities = collect();
                \Log::warning('Proposal counts or deadline unavailable: ' . $e->getMessage());
            }

            return view('organisasi.index', compact('total', 'revisi', 'disetujui', 'ditolak', 'deadline', 'lpjCount', 'publikasiCount', 'activities'));
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
            function (Illuminate\Http\Request $request) {
                try {
                    $api = \App\Services\ApiService::getClient();
                    $type = $request->input('type');
                    
                    // Fetch proposals
                    $proposalResponse = $api->get('/proposal', ['per_page' => 100, 'type' => $type]);
                    $proposalsData = [];
                    if ($proposalResponse->successful()) {
                        $proposalsData = $proposalResponse->json('data') ?? [];
                    }

                    // Fetch LPJs
                    $lpjResponse = $api->get('/lpj', ['type' => $type]);
                    $lpjs = [];
                    if ($lpjResponse->successful()) {
                        $lpjs = $lpjResponse->json('data') ?? [];
                    }

                    $lpjByProposalId = [];
                    foreach ($lpjs as $lpj) {
                        $lpjByProposalId[$lpj['id_proposal']] = $lpj;
                    }

                    $statusMap = [
                        'Pending' => 'Menunggu',
                        'Revision' => 'Revisi',
                        'Approved' => 'Disetujui',
                        'Rejected' => 'Ditolak',
                        'Cek LPJ' => 'Cek LPJ',
                        'Revisi LPJ' => 'Revisi LPJ',
                        'Selesai' => 'Selesai',
                    ];

                    $proposals = collect($proposalsData)
                        ->filter(function ($p) use ($statusMap) {
                            $rawStatus = $p['status'] ?? '';
                            $mappedStatus = $statusMap[$rawStatus] ?? $rawStatus;
                            return in_array($mappedStatus, ['Disetujui', 'Selesai', 'Cek LPJ', 'Revisi LPJ']);
                        })
                        ->map(function ($p) use ($statusMap, $lpjByProposalId) {
                            $lpj = $lpjByProposalId[$p['id_proposal']] ?? null;
                            $rawStatus = $p['status'] ?? '';
                            $mappedStatus = $statusMap[$rawStatus] ?? $rawStatus;

                            // If proposal is Disetujui and LPJ is Pending, display status as Cek LPJ
                            if ($mappedStatus === 'Disetujui' && $lpj && ($lpj['status_lpj'] ?? '') === 'Pending') {
                                $mappedStatus = 'Cek LPJ';
                            }

                            return [
                                'id' => $p['id_proposal'],
                                'nama_kegiatan' => $p['nama_kegiatan'] ?? '-',
                                'tw' => $p['ajuan_triwulan'] ?? '-',
                                'status' => $mappedStatus,
                                'lpj_status' => $lpj ? ($statusMap[$lpj['status_lpj']] ?? $lpj['status_lpj']) : null,
                            ];
                        });

                } catch (\Throwable $e) {
                    \Log::error('API Error in lpj index: ' . $e->getMessage());
                    $proposals = collect();
                }

                return view("organisasi.lpj_index", compact("proposals"));
            }
        )->name("create_lpj");
        Route::get(
            "/publikasi",
            function () {
                try {
                    $api = \App\Services\ApiService::getClient();
                    $response = $api->get('/publikasi', ['per_page' => 100]);
                    $itemsData = [];
                    if ($response->successful()) {
                        $itemsData = $response->json('data') ?? [];
                    }

                    $statusMap = [
                        'Pending' => 'Menunggu',
                        'Revision' => 'Revisi',
                        'Approved' => 'Disetujui',
                        'Rejected' => 'Ditolak',
                    ];

                    $publikasiItems = collect($itemsData)->map(function ($p) use ($statusMap) {
                        $rawStatus = $p['status'] ?? '';
                        $mappedStatus = $statusMap[$rawStatus] ?? $rawStatus;

                        $posterPath = null;
                        if (isset($p['poster_url'])) {
                            $parts = explode('/storage/', $p['poster_url']);
                            $posterPath = count($parts) > 1 ? $parts[1] : null;
                        }

                        return new \Illuminate\Support\Fluent([
                            'id_publikasi' => $p['id_publikasi'],
                            'judul' => $p['judul'],
                            'caption' => $p['caption'],
                            'link' => $p['link'],
                            'status' => $mappedStatus,
                            'catatan_admin' => $p['catatan_admin'],
                            'poster' => $posterPath,
                            'created_at' => isset($p['created_at']) ? \Carbon\Carbon::parse($p['created_at']) : now(),
                        ]);
                    });

                } catch (\Throwable $e) {
                    \Log::error('API Error in publikasi list: ' . $e->getMessage());
                    $publikasiItems = collect();
                }

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
                try {
                    $api = \App\Services\ApiService::getClient();
                    $response = $api->get('/publikasi', ['per_page' => 100]);
                    $itemsData = [];
                    if ($response->successful()) {
                        $itemsData = $response->json('data') ?? [];
                    }

                    $weekCount = collect($itemsData)->filter(function ($p) {
                        $createdAt = isset($p['created_at']) ? \Carbon\Carbon::parse($p['created_at']) : null;
                        return $createdAt && $createdAt->greaterThanOrEqualTo(now()->startOfWeek());
                    })->count();

                } catch (\Throwable $e) {
                    \Log::error('API Error in publikasi quota check: ' . $e->getMessage());
                    $weekCount = 0;
                }

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

                try {
                    $api = \App\Services\ApiService::getClient();

                    $response = $api->attach(
                        'poster',
                        file_get_contents($request->file('poster')->getRealPath()),
                        $request->file('poster')->getClientOriginalName(),
                        ['Content-Type' => $request->file('poster')->getMimeType()]
                    )->post('/publikasi', [
                        'judul' => $request->judul,
                        'ormawa' => $request->ormawa,
                        'caption' => $request->caption,
                        'link' => $request->link,
                    ]);

                    if ($response->status() === 422) {
                        return back()->withErrors($response->json('errors') ?? ['error' => $response->json('message')])->withInput();
                    }

                    if (!$response->successful()) {
                        return back()->withErrors(['error' => $response->json('message') ?? 'Gagal membuat publikasi via API'])->withInput();
                    }

                } catch (\Throwable $e) {
                    \Log::error('API Error in publikasi store: ' . $e->getMessage());
                    return back()->withErrors(['error' => 'Terjadi kesalahan sistem: ' . $e->getMessage()])->withInput();
                }

                return redirect()
                    ->route("organisasi.publikasi")
                    ->with("success", "Publikasi berhasil dikirim dan menunggu verifikasi.");
            }
        );

        Route::get("/publikasi/{id}/edit", function ($id) {
            try {
                $api = \App\Services\ApiService::getClient();
                $response = $api->get("/publikasi/{$id}");
                
                if (!$response->successful()) {
                    abort(404, 'Publikasi tidak ditemukan');
                }
                
                $p = $response->json('data') ?? [];

                $posterPath = null;
                if (isset($p['poster_url'])) {
                    $parts = explode('/storage/', $p['poster_url']);
                    $posterPath = count($parts) > 1 ? $parts[1] : null;
                }

                $publikasi = new \Illuminate\Support\Fluent([
                    'id_publikasi' => $p['id_publikasi'],
                    'judul' => $p['judul'],
                    'ormawa' => $p['ormawa'],
                    'caption' => $p['caption'],
                    'link' => $p['link'],
                    'status' => $p['status'],
                    'poster' => $posterPath,
                ]);

            } catch (\Throwable $e) {
                \Log::error('API Error in publikasi edit: ' . $e->getMessage());
                abort(500, 'Gagal mengambil data dari API');
            }

            return view("organisasi.publikasi_edit", compact("publikasi"));
        })->name("publikasi_edit");

        Route::post("/publikasi/{id}/edit", function (Illuminate\Http\Request $request, $id) {
            $request->validate([
                'judul' => 'required|string|max:255',
                'ormawa' => 'required|string|max:255',
                'caption' => 'required|string',
                'link' => 'nullable|string',
                'poster' => 'nullable|image|max:5120',
            ]);

            try {
                $api = \App\Services\ApiService::getClient();

                $params = [
                    '_method' => 'PUT',
                    'judul' => $request->judul,
                    'ormawa' => $request->ormawa,
                    'caption' => $request->caption,
                    'link' => $request->link,
                ];

                if ($request->hasFile('poster')) {
                    $response = $api->attach(
                        'poster',
                        file_get_contents($request->file('poster')->getRealPath()),
                        $request->file('poster')->getClientOriginalName(),
                        ['Content-Type' => $request->file('poster')->getMimeType()]
                    )->post("/publikasi/{$id}", $params);
                } else {
                    $response = $api->put("/publikasi/{$id}", [
                        'judul' => $request->judul,
                        'ormawa' => $request->ormawa,
                        'caption' => $request->caption,
                        'link' => $request->link,
                    ]);
                }

                if ($response->status() === 422) {
                    return back()->withErrors($response->json('errors') ?? ['error' => $response->json('message')])->withInput();
                }

                if (!$response->successful()) {
                    return back()->withErrors(['error' => $response->json('message') ?? 'Gagal memperbarui publikasi via API'])->withInput();
                }

            } catch (\Throwable $e) {
                \Log::error('API Error in publikasi update: ' . $e->getMessage());
                return back()->withErrors(['error' => 'Terjadi kesalahan sistem: ' . $e->getMessage()])->withInput();
            }

            return redirect()
                ->route("organisasi.publikasi")
                ->with("success", "Publikasi berhasil diperbarui.");
        })->name("publikasi_update");

        Route::delete("/publikasi/{id}", function ($id) {
            try {
                $api = \App\Services\ApiService::getClient();
                $response = $api->delete("/publikasi/{$id}");

                if (!$response->successful()) {
                    return back()->withErrors(['error' => $response->json('message') ?? 'Gagal menghapus publikasi via API']);
                }

            } catch (\Throwable $e) {
                \Log::error('API Error in publikasi delete: ' . $e->getMessage());
                return back()->withErrors(['error' => 'Terjadi kesalahan sistem: ' . $e->getMessage()]);
            }

            return redirect()
                ->route("organisasi.publikasi")
                ->with("success", "Publikasi berhasil dihapus.");
        })->name("publikasi_destroy");

        Route::get(
            "/template-dokumen",
            fn() => view("organisasi.template_dokumen"),
        )->name("template_dokumen");
        Route::post(
            "/",
            function (Illuminate\Http\Request $request) {
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
                ]);

                try {
                    $api = \App\Services\ApiService::getClient();

                    $response = $api->attach(
                        'file',
                        file_get_contents($request->file('proposal')->getRealPath()),
                        $request->file('proposal')->getClientOriginalName(),
                        ['Content-Type' => 'application/pdf']
                    )->post('/proposal', [
                        'ajuan_triwulan' => $request->ajuan_tw,
                        'risiko_proposal' => $request->resiko_proposal,
                        'no_telepon' => $request->no_pic,
                        'nama_kegiatan' => $request->nama_kegiatan,
                        'waktu_kegiatan' => $request->mulai_kegiatan,
                        'tempat_kegiatan' => $request->tempat_kegiatan,
                        'besar_ajuan' => $request->besar_ajuan,
                        'nomor_rekening' => $request->nomor_rekening,
                        'nama_rekening' => $request->nama_rekening,
                        'nama_bank' => $request->nama_bank,
                        'honor_pelatih' => $request->honor_pelatih,
                    ]);

                    if ($response->status() === 422) {
                        return back()->withErrors($response->json('errors') ?? ['error' => $response->json('message')])->withInput();
                    }

                    if (!$response->successful()) {
                        return back()->withErrors(['error' => $response->json('message') ?? 'Gagal menyimpan proposal via API'])->withInput();
                    }

                } catch (\Throwable $e) {
                    \Log::error('API Error in proposal store: ' . $e->getMessage());
                    return back()->withErrors(['error' => 'Terjadi kesalahan sistem: ' . $e->getMessage()])->withInput();
                }

                return redirect()
                    ->route("organisasi.index")
                    ->with("success", "Kegiatan berhasil ditambahkan.");
            },
        )->name("store");
        Route::get(
            "/{id}",
            function ($id) {
                try {
                    $api = \App\Services\ApiService::getClient();
                    $response = $api->get("/proposal/{$id}");
                    
                    if (!$response->successful()) {
                        abort(404, 'Proposal tidak ditemukan');
                    }
                    
                    $proposalData = $response->json('data') ?? [];
                    
                    // Fetch LPJ to correlate
                    $lpjResponse = $api->get('/lpj');
                    $lpjKeg = null;
                    if ($lpjResponse->successful()) {
                        $lpjs = $lpjResponse->json('data') ?? [];
                        foreach ($lpjs as $lpj) {
                            if ((int)$lpj['id_proposal'] === (int)$id) {
                                $lpjKeg = (object)[
                                    'file_lpj' => $lpj['file_lpj']
                                ];
                                break;
                            }
                        }
                    }

                    // Extract relative path from file_lpj_keuangan_url if present
                    $lpjKeuanganPath = null;
                    if (isset($proposalData['file_lpj_keuangan_url'])) {
                        $parts = explode('/storage/', $proposalData['file_lpj_keuangan_url']);
                        $lpjKeuanganPath = count($parts) > 1 ? $parts[1] : null;
                    }

                    // Map proposal data to an object so it behaves exactly like an Eloquent model
                    $proposal = (object)[
                        'id_proposal' => $proposalData['id_proposal'],
                        'nama_kegiatan' => $proposalData['nama_kegiatan'] ?? '-',
                        'ajuan_triwulan' => $proposalData['ajuan_triwulan'] ?? '-',
                        'waktu_kegiatan' => $proposalData['waktu_kegiatan'] ?? null,
                        'risiko_proposal' => $proposalData['risiko_proposal'] ?? '-',
                        'besar_ajuan' => (float)($proposalData['besar_ajuan'] ?? 0),
                        'anggaran_disetujui' => isset($proposalData['anggaran_disetujui']) ? (float)$proposalData['anggaran_disetujui'] : null,
                        'file' => $proposalData['file'] ?? null,
                        'file_lpj_keuangan' => $lpjKeuanganPath,
                        'lpj' => collect($lpjKeg ? [$lpjKeg] : [])
                    ];

                } catch (\Throwable $e) {
                    \Log::error('API Error in proposal detail: ' . $e->getMessage());
                    abort(500, 'Gagal mengambil data detail proposal dari API: ' . $e->getMessage());
                }

                return view("organisasi.show", compact("proposal"));
            },
        )->name("show");
        Route::get(
            "/{id}/edit",
            function ($id) {
                try {
                    $api = \App\Services\ApiService::getClient();
                    $response = $api->get("/proposal/{$id}");
                    
                    if (!$response->successful()) {
                        abort(404, 'Proposal tidak ditemukan');
                    }
                    
                    $proposalData = $response->json('data') ?? [];
                    
                    $proposal = (object)[
                        'id_proposal' => $proposalData['id_proposal'],
                        'nama_kegiatan' => $proposalData['nama_kegiatan'] ?? '-',
                        'ajuan_triwulan' => $proposalData['ajuan_triwulan'] ?? '-',
                        'waktu_kegiatan' => $proposalData['waktu_kegiatan'] ?? null,
                        'risiko_proposal' => $proposalData['risiko_proposal'] ?? '-',
                        'besar_ajuan' => (float)($proposalData['besar_ajuan'] ?? 0),
                        'honor_pelatih' => $proposalData['honor_pelatih'] ?? 'Tidak',
                        'catatan_admin' => $proposalData['catatan_admin'] ?? null,
                        'file' => $proposalData['file'] ?? null,
                        'category' => $proposalData['category'] ?? 'Ormawa',
                    ];

                } catch (\Throwable $e) {
                    \Log::error('API Error in proposal edit: ' . $e->getMessage());
                    abort(500, 'Gagal mengambil data proposal dari API: ' . $e->getMessage());
                }

                return view("organisasi.revisi", compact("proposal"));
            },
        )->name("edit");
        Route::put(
            "/{id}",
            function (Illuminate\Http\Request $request, $id) {
                try {
                    $api = \App\Services\ApiService::getClient();
                    
                    // If file is uploaded, attach it and do method spoofing POST with _method=PUT
                    if ($request->hasFile('proposal')) {
                        $response = $api->attach(
                            'file',
                            file_get_contents($request->file('proposal')->getRealPath()),
                            $request->file('proposal')->getClientOriginalName(),
                            ['Content-Type' => 'application/pdf']
                        )->post("/proposal/{$id}", [
                            '_method' => 'PUT',
                            'ajuan_triwulan' => $request->ajuan_tw,
                            'risiko_proposal' => $request->resiko_proposal,
                            'nama_kegiatan' => $request->nama_kegiatan,
                            'waktu_kegiatan' => $request->waktu_kegiatan,
                            'besar_ajuan' => $request->besar_ajuan,
                            'honor_pelatih' => $request->honor_pelatih,
                        ]);
                    } else {
                        // If no file, do a direct PUT request
                        $response = $api->put("/proposal/{$id}", [
                            'ajuan_triwulan' => $request->ajuan_tw,
                            'risiko_proposal' => $request->resiko_proposal,
                            'nama_kegiatan' => $request->nama_kegiatan,
                            'waktu_kegiatan' => $request->waktu_kegiatan,
                            'besar_ajuan' => $request->besar_ajuan,
                            'honor_pelatih' => $request->honor_pelatih,
                        ]);
                    }

                    if ($response->status() === 422) {
                        return back()->withErrors($response->json('errors') ?? ['error' => $response->json('message')])->withInput();
                    }

                    if (!$response->successful()) {
                        return back()->withErrors(['error' => $response->json('message') ?? 'Gagal memperbarui proposal via API'])->withInput();
                    }

                } catch (\Throwable $e) {
                    \Log::error('API Error in proposal update: ' . $e->getMessage());
                    return back()->withErrors(['error' => 'Terjadi kesalahan sistem: ' . $e->getMessage()])->withInput();
                }

                return redirect()
                    ->route("organisasi.index")
                    ->with("success", "Kegiatan berhasil diperbarui.");
            },
        )->name("update");
        Route::get("/{id}/lpj", function (Illuminate\Http\Request $request, $id) {
            try {
                $api = \App\Services\ApiService::getClient();
                $type = $request->input('type');
                $response = $api->get("/proposal/{$id}", ['type' => $type]);
                
                \Log::info("GET LPJ /proposal/{$id} result: Status = " . $response->status() . " Body = " . $response->body());
                
                if (!$response->successful()) {
                    abort($response->status(), $response->json('message') ?? 'Proposal tidak ditemukan');
                }
                
                $proposalData = $response->json('data') ?? [];
                
                // Fetch LPJs
                $lpjResponse = $api->get('/lpj', ['type' => $type]);
                
                \Log::info("GET LPJ /lpj result: Status = " . $lpjResponse->status() . " Body = " . $lpjResponse->body());
                
                $lpjs = [];
                if ($lpjResponse->successful()) {
                    $lpjs = collect($lpjResponse->json('data') ?? [])
                        ->filter(fn($lpj) => (int)$lpj['id_proposal'] === (int)$id)
                        ->values()
                        ->all();
                }

                $proposal = (object)[
                    'id_proposal' => $proposalData['id_proposal'],
                    'nama_kegiatan' => $proposalData['nama_kegiatan'] ?? '-',
                    'ajuan_triwulan' => $proposalData['ajuan_triwulan'] ?? '-',
                    'lpj' => collect($lpjs)->map(fn($l) => (object)[
                        'id_lpj' => $l['id_lpj'],
                        'file_lpj' => $l['file_lpj'],
                        'status_lpj' => $l['status_lpj'],
                        'catatan_admin' => $l['catatan_admin'] ?? null,
                    ]),
                ];

            } catch (\Symfony\Component\HttpKernel\Exception\HttpException $e) {
                throw $e;
            } catch (\Throwable $e) {
                \Log::error('API Error in GET LPJ route: ' . $e->getMessage());
                abort(500, 'Gagal mengambil data dari API: ' . $e->getMessage());
            }

            return view("organisasi.create_lpj", compact("proposal"));
        })->name("lpj");

        Route::post("/{id}/lpj", function (Illuminate\Http\Request $request, $id) {
            $request->validate([
                'laporan' => 'required|file|mimes:pdf|max:10240',
            ]);

            try {
                $api = \App\Services\ApiService::getClient();
                $type = $request->input('type');
                
                $response = $api->attach(
                    'file_lpj',
                    file_get_contents($request->file('laporan')->getRealPath()),
                    $request->file('laporan')->getClientOriginalName(),
                    ['Content-Type' => 'application/pdf']
                )->post('/lpj', [
                    'id_proposal' => $id,
                    'tanggal_upload' => now()->toDateString(),
                    'type' => $type,
                ]);

                if ($response->status() === 422) {
                    return back()->withErrors($response->json('errors') ?? ['error' => $response->json('message')]);
                }

                if (!$response->successful()) {
                    return back()->withErrors(['error' => $response->json('message') ?? 'Gagal mengupload LPJ via API']);
                }

            } catch (\Throwable $e) {
                \Log::error('API Error in LPJ store: ' . $e->getMessage());
                return back()->withErrors(['error' => 'Terjadi kesalahan sistem: ' . $e->getMessage()]);
            }

            return redirect()->route('organisasi.create_lpj', ['type' => $type])->with('success', 'LPJ Kegiatan berhasil diupload.');
        })->name("lpj.store");

        Route::get("/lpj/{id}/revisi", function (Illuminate\Http\Request $request, $id) {
            try {
                $api = \App\Services\ApiService::getClient();
                $type = $request->input('type');
                $response = $api->get("/proposal/{$id}", ['type' => $type]);
                
                if (!$response->successful()) {
                    abort($response->status(), $response->json('message') ?? 'Proposal tidak ditemukan');
                }
                
                $proposalData = $response->json('data') ?? [];

                // Fetch LPJs
                $lpjResponse = $api->get('/lpj', ['type' => $type]);
                $lpjKeg = null;
                if ($lpjResponse->successful()) {
                    $lpjs = $lpjResponse->json('data') ?? [];
                    foreach ($lpjs as $lpj) {
                        if ((int)$lpj['id_proposal'] === (int)$id) {
                            $lpjKeg = (object)[
                                'id_lpj' => $lpj['id_lpj'],
                                'file_lpj' => $lpj['file_lpj'],
                                'status_lpj' => $lpj['status_lpj'],
                                'catatan_admin' => $lpj['catatan_admin'] ?? null,
                            ];
                            break;
                        }
                    }
                }

                $proposal = (object)[
                    'id_proposal' => $proposalData['id_proposal'],
                    'nama_kegiatan' => $proposalData['nama_kegiatan'] ?? '-',
                    'ajuan_triwulan' => $proposalData['ajuan_triwulan'] ?? '-',
                    'lpj' => collect($lpjKeg ? [$lpjKeg] : [])
                ];

            } catch (\Symfony\Component\HttpKernel\Exception\HttpException $e) {
                throw $e;
            } catch (\Throwable $e) {
                \Log::error('API Error in LPJ revisi view: ' . $e->getMessage());
                abort(500, 'Gagal mengambil data dari API: ' . $e->getMessage());
            }

            return view("organisasi.revisi_lpj", compact("proposal"));
        })->name("lpj.revisi");

        Route::put("/lpj/{id}/revisi", function (Illuminate\Http\Request $request, $id) {
            $request->validate([
                'laporan' => 'required|file|mimes:pdf|max:10240',
            ]);

            try {
                $api = \App\Services\ApiService::getClient();
                $type = $request->input('type');
                
                // Fetch LPJs to find the correct id_lpj
                $lpjResponse = $api->get('/lpj', ['type' => $type]);
                $idLpj = null;
                if ($lpjResponse->successful()) {
                    $lpjs = $lpjResponse->json('data') ?? [];
                    foreach ($lpjs as $lpj) {
                        if ((int)$lpj['id_proposal'] === (int)$id) {
                            $idLpj = $lpj['id_lpj'];
                            break;
                        }
                    }
                }

                if (!$idLpj) {
                    return back()->withErrors(['error' => 'LPJ untuk proposal ini tidak ditemukan.']);
                }

                $response = $api->attach(
                    'file_lpj',
                    file_get_contents($request->file('laporan')->getRealPath()),
                    $request->file('laporan')->getClientOriginalName(),
                    ['Content-Type' => 'application/pdf']
                )->post("/lpj/{$idLpj}/revisi", [
                    'tanggal_upload' => now()->toDateString(),
                    'type' => $type,
                ]);

                if ($response->status() === 422) {
                    return back()->withErrors($response->json('errors') ?? ['error' => $response->json('message')]);
                }

                if (!$response->successful()) {
                    return back()->withErrors(['error' => $response->json('message') ?? 'Gagal mengupload revisi LPJ via API']);
                }

            } catch (\Throwable $e) {
                \Log::error('API Error in LPJ revision update: ' . $e->getMessage());
                return back()->withErrors(['error' => 'Terjadi kesalahan sistem: ' . $e->getMessage()]);
            }

            return redirect()->route('organisasi.create_lpj', ['type' => $type])->with('success', 'Revisi LPJ berhasil dikirim.');
        })->name("lpj.update");

        Route::delete(
            "/{id}",
            function ($id) {
                try {
                    $api = \App\Services\ApiService::getClient();
                    $response = $api->delete("/proposal/{$id}");
                    
                    if (!$response->successful()) {
                        return back()->withErrors(['error' => $response->json('message') ?? 'Gagal menghapus proposal via API']);
                    }
                } catch (\Throwable $e) {
                    \Log::error('API Error in proposal delete: ' . $e->getMessage());
                    return back()->withErrors(['error' => 'Terjadi kesalahan sistem: ' . $e->getMessage()]);
                }

                return redirect()
                    ->route("organisasi.index")
                    ->with("success", "Kegiatan berhasil dihapus.");
            }
        )->name("destroy");
    });

// ── Prestasi Mahasiswa ────────────────────────────────────────────────────────
Route::prefix("prestasi")
    ->middleware('auth')
    ->name("prestasi.")
    ->group(function () {
        Route::get("/", fn() => view("prestasi.index"))->name("index");
        Route::get("/input-proposal", fn() => view("prestasi.input_proposal"))->name("input_proposal");
        Route::get("/upload-lpj", fn() => view("prestasi.upload_lpj"))->name("upload_lpj");
        Route::get("/template-dokumen", fn() => view("prestasi.template_dokumen"))->name("template_dokumen");
        Route::get("/laporan-prestasi", fn() => view("prestasi.laporan_prestasi"))->name("laporan_prestasi");
        Route::get("/laporan-prestasi/biodata", fn() => redirect()->route("prestasi.laporan_prestasi"))->name("laporan_prestasi.biodata");
        Route::get("/laporan-prestasi/detail-kompetisi", fn() => redirect()->route("prestasi.laporan_prestasi"))->name("laporan_prestasi.detail_kompetisi");
        Route::get("/laporan-prestasi/capaian-prestasi", fn() => redirect()->route("prestasi.laporan_prestasi"))->name("laporan_prestasi.capaian_prestasi");
        Route::get("/laporan-prestasi/informasi-dosen-pembimbing", fn() => redirect()->route("prestasi.laporan_prestasi"))->name("laporan_prestasi.informasi_dosen_pembimbing");
        Route::get("/laporan-prestasi/evidance", fn() => redirect()->route("prestasi.laporan_prestasi"))->name("laporan_prestasi.evidance");
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

        Route::get("/kartu-prestasi/{nim}/download-pdf", function (string $nim) {
            $controller = app(\App\Http\Controllers\Api\CetakPrestasiController::class);
            return $controller->cetakKartu(request(), $nim);
        })->name('kartu_prestasi.download_pdf');

        Route::get("/transkrip-prestasi/download-pdf", function () {
            $controller = app(\App\Http\Controllers\Api\CetakPrestasiController::class);
            return $controller->cetakTranskrip(request());
        })->name('transkrip_prestasi.download_pdf');

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

        Route::get("/revisi/{id}", function ($id) {
            return view("prestasi.revisi", compact("id"));
        })->name("revisi");

        Route::get(
            "/create",
            fn() => view("prestasi.laporan_prestasi"),
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

