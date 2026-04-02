<?php

use Illuminate\Support\Facades\Route;

Route::get("/", fn() => redirect()->route("login"));

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
                "role" => "admin",
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

// ── Organisasi Mahasiswa ──────────────────────────────────────────────────────
Route::prefix("organisasi")
    ->name("organisasi.")
    ->group(function () {
        Route::get("/", fn() => view("organisasi.index"))->name("index");
        Route::get(
            "/create",
            fn() => redirect()
                ->route("organisasi.index")
                ->with("error", "Halaman belum tersedia."),
        )->name("create");
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
            fn() => redirect()->route("organisasi.index"),
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
