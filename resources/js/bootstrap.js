import axios from 'axios';
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: import.meta.env.VITE_REVERB_APP_KEY,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
    wsHost: import.meta.env.VITE_REVERB_HOST,
    wsPort: import.meta.env.VITE_REVERB_PORT,
    forceTLS: false,
    disableStats: true,
    encrypted: false,
    enabledTransports: ['ws'], // evita sockjs e o erro de CORS
});


console.log('Echo instanciado:', window.Echo);

window.Echo.connector.pusher.connection.bind('connected', () => {
  console.log('✅ Conectado ao WebSocket com sucesso');
});