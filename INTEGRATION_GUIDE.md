# Frontend-Backend Integration Guide

## Setup Konfigurasi

### 1. **Backend (Laravel API)**
- **Port**: 8000
- **Base URL**: `http://localhost:8000`
- **API Base URL**: `http://localhost:8000/api/v1`

### 2. **Frontend (Vite)**
- **Port**: 5173
- **Base URL**: `http://localhost:5173`

### 3. **CORS Configuration**
- File: `config/cors.php`
- Allowed origins: 
  - `http://localhost:5173`
  - `http://127.0.0.1:5173`
  - `http://localhost:3000`
  - `http://127.0.0.1:3000`

## Running the Application

### Option 1: Run Both (Frontend + Backend) Together
```bash
npm run dev-all
```

### Option 2: Run Separately
**Terminal 1 - Backend:**
```bash
npm run server
# or
php artisan serve --host=localhost --port=8000
```

**Terminal 2 - Frontend:**
```bash
npm run dev
# or
npx vite
```

## Using the API Client

### Setup
The API client is already configured in `resources/js/api.js` and exposed globally as `window.api`.

### Example Usage in JavaScript

```javascript
// Login
const loginResponse = await window.api.auth.login('email@example.com', 'password');
const token = loginResponse.data.data.token;
window.axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;

// Get Proposals
const proposals = await window.api.proposal.list();
console.log(proposals.data);

// Create Proposal
const newProposal = await window.api.proposal.create({
    nama_kegiatan: 'Nama Kegiatan',
    deskripsi: 'Deskripsi...',
    // ... other fields
});

// Get Single Proposal
const proposal = await window.api.proposal.get(1);

// Update Proposal
const updated = await window.api.proposal.update(1, {
    nama_kegiatan: 'Nama Kegiatan Baru',
});

// Delete Proposal
await window.api.proposal.delete(1);
```

### Available API Methods

#### Authentication
- `api.auth.login(email, password)`
- `api.auth.register(data)`
- `api.auth.logout()`
- `api.auth.me()` - Get current user
- `api.auth.generateToken()`

#### Proposal
- `api.proposal.list()`
- `api.proposal.get(id)`
- `api.proposal.create(data)`
- `api.proposal.update(id, data)`
- `api.proposal.delete(id)`
- `api.proposal.checkStatus(id)`
- `api.proposal.submitRevision(id, data)`
- `api.proposal.verify(id, data)`

#### LPJ
- `api.lpj.list()`
- `api.lpj.get(id)`
- `api.lpj.create(data)`
- `api.lpj.submitRevision(id, data)`
- `api.lpj.verify(id, data)`

#### Prestasi
- `api.prestasi.list()`
- `api.prestasi.get(id)`
- `api.prestasi.create(data)`
- `api.prestasi.update(id, data)`
- `api.prestasi.delete(id)`
- `api.prestasi.checkStatus(id)`
- `api.prestasi.verify(id, data)`
- `api.prestasi.addAnggota(id, data)`
- `api.prestasi.deleteAnggota(id, anggotaId)`
- `api.prestasi.addDosen(id, data)`
- `api.prestasi.deleteDosen(id, dosenId)`

#### Template
- `api.template.list()`
- `api.template.get(id)`
- `api.template.create(data)`
- `api.template.update(id, data)`
- `api.template.delete(id)`

#### Informasi (Public)
- `api.informasi.list()`
- `api.informasi.get(id)`

#### User
- `api.user.list()`
- `api.user.get(id)`
- `api.user.update(id, data)`
- `api.user.delete(id)`

#### Monitoring
- `api.monitoring.dashboard()`

## Setting Authentication Token

After login, set the token in axios headers:

```javascript
const response = await window.api.auth.login(email, password);
const token = response.data.data.token;

// Set token for all subsequent requests
window.axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;

// Or store in localStorage and retrieve on page load
localStorage.setItem('auth_token', token);
```

## Error Handling

```javascript
try {
    const response = await window.api.proposal.list();
    console.log(response.data);
} catch (error) {
    if (error.response) {
        // Server responded with error status
        console.error('Error:', error.response.data.message);
        console.error('Status:', error.response.status);
    } else if (error.request) {
        // Request made but no response
        console.error('No response received');
    } else {
        // Error in request setup
        console.error('Error:', error.message);
    }
}
```

## CORS Issues?

If you encounter CORS errors:
1. Make sure backend is running on `http://localhost:8000`
2. Check `config/cors.php` for correct origins
3. Verify CORS middleware is enabled in `bootstrap/app.php`
4. Clear browser cache and restart both servers

## Database Setup

Before running, make sure:
1. MySQL is running
2. Database `top_db` exists (or update `DB_DATABASE` in `.env`)
3. Run migrations: `php artisan migrate`
4. Run seeders (if any): `php artisan db:seed`

---

Happy coding! 🚀
