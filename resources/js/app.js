import './bootstrap';
import api, { tokenManager } from './api';

// Expose ke window agar bisa diakses dari Blade inline scripts
window.api = api;
window.tokenManager = tokenManager;

// Inisialisasi: muat token dari localStorage ke axios header
tokenManager.init();
