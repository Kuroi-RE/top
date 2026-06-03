## Authentication

### Default Test Users (Seeder)

**Super Admin:**

```
Username: superadmin
Password: superadmin123
Email: superadmin@top-kema.com
Role: Super Admin
```

**Kemahasiswaan:**

```
Username: kemahasiswaan
Password: kemahasiswaan123
Email: kemahasiswaan@top-kema.com
Role: Kemahasiswaan
```

**DPMBEM:**

```
Username: dpmbem
Password: dpmbem123
Email: dpmbem@top-kema.com
Role: DPMBEM
```

**Ormawa Institusi (UKM):**

```
ukm1 (BEMF)   / password123 / ukm1@top-kema.com
ukm2 (WELL)   / password123 / ukm2@top-kema.com
ukm3 (Menara) / password123 / ukm3@top-kema.com
```

**Ormawa Prodi (Himpunan):**

```
prodi1 (HIMA TIF) / password123 / himpunan1@top-kema.com
prodi2 (HIMA TE)  / password123 / himpunan2@top-kema.com
prodi3 (HIMA MI)  / password123 / himpunan3@top-kema.com
```

**Mahasiswa:** Auto-generated (5 users) dengan password: `password`

---

### 1. Register - User Baru (Public Endpoint)

**Endpoint:** `POST /api/v1/auth/register`

**Deskripsi:** Register pengguna baru dengan default role Mahasiswa. Username **di-generate otomatis** dari email. User langsung bisa login tanpa verifikasi email.

**Request Body:**

```json
{
    "nim": "12345678901",
    "nama_depan": "John",
    "nama_belakang": "Doe",
    "prodi": "Teknik Informatika",
    "email": "john123@example.com",
    "password": "password123",
    "password_confirmation": "password123"
}
```

**Request Parameters:**
| Field | Type | Required | Description |
|-------|------|----------|-------------|
| nim | string | Yes | NIM mahasiswa (unique, max 20 karakter) |
| nama_depan | string | Yes | Nama depan (max 100 karakter) |
| nama_belakang | string | Yes | Nama belakang (max 100 karakter) |
| prodi | string | Yes | Program studi (max 100 karakter) |
| email | string | Yes | Email (unique) |
| password | string | Yes | Password minimal 8 karakter |
| password_confirmation | string | Yes | Konfirmasi password |

**Auto-Generated Fields:**
| Field | Generated From | Example |
|-------|---|---|
| username | Email (sebelum @) | `john123` (dari john123@example.com) |

**Response (Success - 201):**

```json
{
    "status": "success",
    "message": "Registrasi berhasil",
    "data": {
        "user": {
            "id_user": 21,
            "username": "john123",
            "nim": "12345678901",
            "nama_depan": "John",
            "nama_belakang": "Doe",
            "prodi": "Teknik Informatika",
            "email": "john123@example.com",
            "role": "Mahasiswa",
            "is_active": true,
            "ormawa_type": null,
            "ormawa_name": null,
            "created_at": "2024-01-01T00:00:00.000000Z"
        },
        "token": "1|eyJ0eXAiOiJKV1QiLCJhbGc..."
    }
}
```

**Response (Validation Error - 422):**

```json
{
    "message": "Validation failed",
    "errors": {
        "email": ["Email sudah terdaftar"],
        "nim": ["NIM sudah terdaftar"],
        "nama_depan": ["Nama depan tidak boleh kosong"],
        "prodi": ["Program studi tidak boleh kosong"],
        "password": ["The password must be at least 8 characters."]
    }
}
```

**Response (Server Error - 500):**

```json
{
    "status": "error",
    "message": "Registrasi gagal: [error message]"
}
```

---

### 2. Login

**Endpoint:** `POST /api/v1/auth/login`

**Request Body:**

```json
{
    "username": "john123",
    "password": "password123"
}
```

**Response (Success - 200):**

```json
{
    "status": "success",
    "message": "Login berhasil",
    "data": {
        "user": {
            "id_user": 1,
            "username": "superadmin",
            "email": "superadmin@top-kema.com",
            "role": "Super Admin",
            "is_active": true,
            "ormawa_type": null,
            "ormawa_name": null,
            "created_at": "2024-01-01T00:00:00.000000Z"
        },
        "token": "1|eyJ0eXAiOiJKV1QiLCJhbGc..."
    }
}
```

---

### 3. Logout

**Endpoint:** `POST /api/v1/auth/logout`

**Headers:** `Authorization: Bearer {token}`

**Response (Success - 200):**

```json
{
    "status": "success",
    "message": "Logout berhasil"
}
```

---

### 4. Get Current User

**Endpoint:** `GET /api/v1/auth/me`

**Headers:** `Authorization: Bearer {token}`

**Response (Success - 200):**

```json
{
    "status": "success",
    "message": "Data pengguna",
    "data": {
        "id_user": 1,
        "username": "superadmin",
        "email": "superadmin@top-kema.com",
        "role": "Super Admin",
        "is_active": true,
        "ormawa_type": null,
        "ormawa_name": null
    }
}
```

---

### 5. Forgot Password (Public Endpoint)

**Endpoint:** `POST /api/v1/auth/forgot-password`

**Deskripsi:** Meminta tautan reset password untuk email yang terdaftar. Sistem akan mengirimkan email berupa link reset password (atau mencatat ke log pada lingkungan pengembangan).

**Request Body:**

```json
{
    "email": "mahasiswa@student.telkomuniversity.ac.id"
}
```

**Request Parameters:**
| Field | Type | Required | Description |
|-------|------|----------|-------------|
| email | string | Yes | Email pengguna yang terdaftar |

**Response (Success - 200):**

```json
{
    "status": "success",
    "message": "Link reset password telah dikirim ke email Anda"
}
```

**Response (Validation Error - 422):**

```json
{
    "message": "Validation failed",
    "errors": {
        "email": ["Email tidak terdaftar dalam sistem."]
    }
}
```

---

### 6. Reset Password (Public Endpoint)

**Endpoint:** `POST /api/v1/auth/reset-password`

**Deskripsi:** Melakukan pembaruan password menggunakan token reset password yang valid dari email.

**Request Body:**

```json
{
    "token": "token-reset-password-dari-email",
    "email": "mahasiswa@student.telkomuniversity.ac.id",
    "password": "passwordbaru123",
    "password_confirmation": "passwordbaru123"
}
```

**Request Parameters:**
| Field | Type | Required | Description |
|-------|------|----------|-------------|
| token | string | Yes | Token reset password yang diterima dari email |
| email | string | Yes | Email pengguna |
| password | string | Yes | Password baru (min 8 karakter) |
| password_confirmation | string | Yes | Konfirmasi password baru |

**Response (Success - 200):**

```json
{
    "status": "success",
    "message": "Password Anda berhasil diperbarui."
}
```

**Response (Validation Error - 422):**

```json
{
    "status": "error",
    "message": "Token reset password tidak valid."
}
```

---