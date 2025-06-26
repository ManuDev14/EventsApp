import axios from 'axios';

const axiosInstance = axios.create({
    baseURL: 'http://127.0.0.1:8000',
    headers: {
        Accept: 'application/json',
        'X-Requested-With': 'XMLHttpRequest',
    },
    withCredentials: true,
});

const token = document.head.querySelector('meta[name="csrf-token"]');

if (token) {
    axiosInstance.defaults.headers.common['X-CSRF-TOKEN'] = token.getAttribute('content') || '';
} else {
    console.warn('CSRF token not found: make sure you have <meta name="csrf-token" content="..."> in your HTML.');
}

export default axiosInstance;
