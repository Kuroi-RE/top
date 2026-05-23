## Error Handling

### Common Error Responses

**401 Unauthorized - Invalid Token:**

```json
{
    "message": "Unauthenticated."
}
```

**403 Forbidden - Email Belum Diverifikasi:**

```json
{
    "status": "error",
    "message": "Email belum diverifikasi. Silakan cek email Anda."
}
```

**403 Forbidden - Akun Dinonaktifkan:**

```json
{
    "status": "error",
    "message": "Akun Anda telah dinonaktifkan"
}
```

**403 Forbidden - Insufficient Permissions:**

```json
{
    "status": "error",
    "message": "Unauthorized - Insufficient permissions",
    "errors": {
        "role": "User role is not authorized to access this resource"
    }
}
```

**422 Validation Error:**

```json
{
    "message": "Validation failed",
    "errors": {
        "email": ["Email sudah terdaftar"],
        "password": ["Password minimal 8 karakter"]
    }
}
```

---

## Testing Endpoints

### Using cURL

**Register:**

```bash
curl -X POST http://localhost:8000/api/v1/auth/register \
  -H "Content-Type: application/json" \
  -d '{
    "nim": "12345678901",
    "nama_depan": "John",
    "nama_belakang": "Doe",
    "prodi": "Teknik Informatika",
    "email": "test@telkomuniversity.ac.id",
    "password": "password123",
    "password_confirmation": "password123"
  }'
```

**Verify Email:**

```bash
curl -X POST http://localhost:8000/api/v1/auth/verify-email \
  -H "Content-Type: application/json" \
  -d '{"token": "TOKEN_DARI_EMAIL"}'
```

**Login:**

```bash
curl -X POST http://localhost:8000/api/v1/auth/login \
  -H "Content-Type: application/json" \
  -d '{
    "username": "superadmin",
    "password": "superadmin123"
  }'
```

**Get Users:**

```bash
curl -X GET http://localhost:8000/api/v1/users \
  -H "Authorization: Bearer TOKEN_HERE"
```

**Assign Role:**

```bash
curl -X PATCH http://localhost:8000/api/v1/users/10/assign-role \
  -H "Authorization: Bearer TOKEN_HERE" \
  -H "Content-Type: application/json" \
  -d '{
    "role": "Ormawa",
    "ormawa_type": "institusi",
    "ormawa_name": "BEMF"
  }'
```

### Using Postman

1. Import collection atau buat request baru
2. Set Base URL: `http://localhost:8000/api/v1`
3. Untuk Protected Routes: Add Header `Authorization: Bearer {token}`
4. Test register endpoint dulu untuk mendapat token
5. Gunakan token tersebut untuk endpoint lain

---

## Database Schema

### Users Table

```sql
CREATE TABLE users (
  id_user BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  username VARCHAR(50) UNIQUE,
  email VARCHAR(255) UNIQUE NOT NULL,
  password VARCHAR(255) NOT NULL,
  role ENUM('Super Admin', 'Kemahasiswaan', 'DPMBEM', 'Ormawa', 'Mahasiswa') DEFAULT 'Mahasiswa',
  ormawa_type ENUM('institusi', 'prodi') NULLABLE,
  ormawa_name VARCHAR(100) NULLABLE,
  is_active BOOLEAN DEFAULT TRUE,
  created_at TIMESTAMP,
  updated_at TIMESTAMP
);
```

---

## Additional Resources

- **API Base URL:** `http://localhost:8000/api/v1`
- **API Prefix:** `/api/v1`
- **Authentication Method:** Bearer Token (Laravel Sanctum)
- **Default Date Format:** ISO 8601 (YYYY-MM-DDTHH:MM:SS.sssZ)
- **Timezone:** UTC

---

**Last Updated:** Mei 2026
**Version:** 3.0