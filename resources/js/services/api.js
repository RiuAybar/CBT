import axios from 'axios';
import { jwtDecode } from 'jwt-decode';
import router from '../router';

const api = axios.create({
  baseURL: '/api',
  headers: {
    'Content-Type': 'application/json',
    Accept: 'application/json',
  }
});

// 👉 Verifica si el token expirará en los próximos 30 minutos (1800 segundos)
function willTokenExpireSoon(token, bufferSeconds = 1800) {
  try {
    const decoded = jwtDecode(token);
    const now = Date.now() / 1000;
    return (decoded.exp - now) < bufferSeconds;
  } catch (e) {
    return true;
  }
}

// 👉 Renueva el token si está por expirar
async function refreshToken() {
  try {
    const currentToken = localStorage.getItem('token');

    const res = await axios.post('/api/refresh', {}, {
      headers: {
        Authorization: `Bearer ${currentToken}`,
        Accept: 'application/json'
      }
    });

    const newToken = res.data.token;
    localStorage.setItem('token', newToken);
    setAuthToken(newToken);
    return newToken;
  } catch (e) {
    // 🔴 Si falla el refresh, redirige al login
    localStorage.removeItem('token');
    delete api.defaults.headers.common['Authorization'];
    router.push({ name: 'login' });
    throw e;
  }
}

// ✅ Interceptor de request: verifica y renueva token si es necesario
api.interceptors.request.use(async config => {
  let token = localStorage.getItem('token');

  if (token && willTokenExpireSoon(token)) {
    try {
      token = await refreshToken();
    } catch (e) {
      return Promise.reject(e);
    }
  }

  if (token) {
    config.headers['Authorization'] = `Bearer ${token}`;
  }

  return config;
}, error => Promise.reject(error));

// ❌ Interceptor de respuesta: detecta token inválido
api.interceptors.response.use(
  response => response,
  error => {
    if (error.response && error.response.data?.message === 'Unauthenticated.') {
      localStorage.removeItem('token');
      delete api.defaults.headers.common['Authorization'];
      router.push({ name: 'login' });
    }
    return Promise.reject(error);
  }
);

// 👉 Establece el token manualmente
export function setAuthToken(token) {
  if (token) {
    api.defaults.headers.common['Authorization'] = `Bearer ${token}`;
  } else {
    delete api.defaults.headers.common['Authorization'];
  }
}

export default api;
