<?php

use Illuminate\Support\Facades\Route;

Route::get("/", fn() => view("landing"))->name("landing");
Route::get("/home", fn() => redirect()->route("login"));

// ── Guest ─────────────────────────────────────────────────────────────────────
Route::middleware("guest")->group(function () {
    Route::get("/login", fn() => view("auth.login"))->name("login");

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
                "password" => "password",
                "role" => "ormawa",
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

        $matched = collect($dummyUsers)->first(
            fn($u) => $u["username"] === $credentials["username"] &&
                $u["password"] === $credentials["password"],
        );

        if ($matched) {
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

Route::get("/admin/beranda_ormawa", function () {
    $user = session("dummy_user");

    if (!$user || $user["role"] !== "kemahasiswaan") {
        return redirect()->route("login");
    }

    return view("admin.beranda_ormawa");
})->name("admin.beranda_ormawa");

Route::get("/admin/prestasi_mahasiswa", function () {
    $user = session("dummy_user");

    if (!$user || $user["role"] !== "kemahasiswaan") {
        return redirect()->route("login");
    }

    return view("admin.prestasi_mahasiswa");
})->name("admin.prestasi_mahasiswa");

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

// ── Organisasi Mahasiswa ──────────────────────────────────────────────────────
Route::prefix("organisasi")
    ->name("organisasi.")
    ->group(function () {
        Route::get("/", fn() => view("organisasi.index"))->name("index");
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

