import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

/**
 * Panggil konfigurasi Pusher dari file echo.js
 */
import './echo';