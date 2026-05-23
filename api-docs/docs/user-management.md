### User Management - Assign Role (Super Admin / Kemahasiswaan)

#### List All Users

**Endpoint:** `GET /api/v1/users`

**Headers:** `Authorization: Bearer {token}`

**Auth Required:** Yes (Super Admin, Kemahasiswaan)

**Query Parameters:**

- `role`: Filter by role (Super Admin, Kemahasiswaan, DPMBEM, Ormawa, Mahasiswa)
- `is_active`: Filter by status (true/false)
- `per_page`: Jumlah data per halaman (default: 15)

**Response (Success - 200):**

```json
{
    "status": "success",
    "message": "Daftar pengguna",
    "data": [
        {
            "id_user": 1,
            "username": "superadmin",
            "email": "superadmin@top-kema.com",
            "role": "Super Admin",
            "is_active": true,
            "ormawa_type": null,
            "ormawa_name": null,
            "created_at": "2024-01-01T00:00:00.000000Z"
        },
        {
            "id_user": 4,
            "username": "ukm1",
            "email": "ukm1@top-kema.com",
            "role": "Ormawa",
            "is_active": true,
            "ormawa_type": "institusi",
            "ormawa_name": "BEMF",
            "created_at": "2024-01-01T00:00:00.000000Z"
        }
    ],
    "pagination": {
        "total": 14,
        "per_page": 15,
        "current_page": 1,
        "total_pages": 1
    }
}
```

---

#### Assign Role to User

**Endpoint:** `PATCH /api/v1/users/{id}/assign-role`

**Headers:** `Authorization: Bearer {token}`

**Auth Required:** Yes (Super Admin, Kemahasiswaan)

**URL Parameters:**

- `id`: User ID (path parameter)

**Request Body - For Ormawa Role:**

```json
{
    "role": "Ormawa",
    "ormawa_type": "institusi",
    "ormawa_name": "BEMF"
}
```

**Request Body - For Other Roles:**

```json
{
    "role": "Mahasiswa"
}
```

**Response (Success - 200):**

```json
{
    "status": "success",
    "message": "Role pengguna berhasil diubah",
    "data": {
        "id_user": 10,
        "username": "mahasiswa1",
        "email": "mahasiswa1@example.com",
        "role": "Ormawa",
        "is_active": true,
        "ormawa_type": "institusi",
        "ormawa_name": "BEMF",
        "created_at": "2024-01-01T00:00:00.000000Z"
    }
}
```

**Response (Permission Error - 403):**

```json
{
    "status": "error",
    "message": "Unauthorized - Insufficient permissions",
    "errors": {
        "role": "Role adalah not valid atau Anda tidak memiliki izin untuk assign role tersebut"
    }
}
```

---

#### Toggle User Access

**Endpoint:** `PATCH /api/v1/users/{id}/toggle-akses`

**Headers:** `Authorization: Bearer {token}`

**Auth Required:** Yes (Super Admin, Kemahasiswaan)

**Deskripsi:** Mengaktifkan/Menonaktifkan akun user. User yang tidak aktif tidak bisa login.

**Response (Success - 200):**

```json
{
    "status": "success",
    "message": "Status akses pengguna berhasil diubah",
    "data": {
        "id_user": 10,
        "username": "mahasiswa1",
        "email": "mahasiswa1@example.com",
        "role": "Mahasiswa",
        "is_active": false
    }
}
```

---

### Spatie Role & Permissions Management

Endpoint untuk mengelola daftar roles dan permissions secara dinamis dari database.

> **PENTING (ARSITEKTUR PBAC):**
> Sistem tidak lagi menggunakan *Role* sebagai sumber hak akses, melainkan menggunakan **Direct Permissions** (Permission-Based Access Control). 
> - *Role* murni digunakan sebagai identitas (Mahasiswa, Ormawa, dll).
> - Saat registrasi atau di-assign *Role* baru, *permissions* template dari config/permissions.php akan disalin langsung ke **User**.
> - Ini memungkinkan Admin Kemahasiswaan untuk mencentang/menghilangkan fitur spesifik (misal: Create Proposal Kegiatan) per individu tanpa mengganggu pengguna lain.

#### 1. Daftar Roles

**Endpoint:** `GET /api/v1/users/spatie/roles`

**Headers:** `Authorization: Bearer {token}`

**Auth Required:** Yes (Super Admin, Kemahasiswaan)

**Response (Success - 200):**

```json
{
    "status": "success",
    "message": "Daftar Roles",
    "data": [
        {
            "id": 1,
            "name": "Super Admin"
        },
        {
            "id": 2,
            "name": "Kemahasiswaan"
        }
    ]
}
```

---

#### 2. Daftar Permissions

**Endpoint:** `GET /api/v1/users/spatie/permissions`

**Headers:** `Authorization: Bearer {token}`

**Auth Required:** Yes (Super Admin, Kemahasiswaan)

**Response (Success - 200):**

```json
{
    "status": "success",
    "message": "Daftar Permissions",
    "data": [
        {
            "id": 1,
            "name": "Create Proposal"
        },
        {
            "id": 2,
            "name": "View Proposal"
        }
    ]
}
```

---

#### 3. Lihat Direct Permissions User

**Endpoint:** `GET /api/v1/users/{id}/permissions`

**Headers:** `Authorization: Bearer {token}`

**Auth Required:** Yes (Super Admin, Kemahasiswaan)

**Response (Success - 200):**

```json
{
    "status": "success",
    "message": "Permissions User",
    "data": [
        "Create Proposal",
        "View Proposal",
        "Create LPJ",
        "View LPJ"
    ]
}
```

---

#### 4. Sync Direct Permissions User

Menyinkronkan ulang seluruh permissions spesifik yang dimiliki oleh user (di luar izin bawaan dari Role utamanya).

**Endpoint:** `PATCH /api/v1/users/{id}/permissions`

**Headers:** `Authorization: Bearer {token}`

**Auth Required:** Yes (Super Admin, Kemahasiswaan)

**Request Body:**

```json
{
    "permissions": ["Create Proposal", "View Proposal", "Create LPJ", "View LPJ"]
}
```

**Response (Success - 200):**

```json
{
    "status": "success",
    "message": "Permissions berhasil diperbarui",
    "data": [
        "Create Proposal",
        "View Proposal",
        "Create LPJ",
        "View LPJ"
    ]
}
```