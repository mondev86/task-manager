import axios from 'axios';
import Echo from 'laravel-echo';

window.axios = axios;

try {
    window.Echo = new Echo({
        broadcaster: 'reverb',
        host: window.location.hostname + ':8080'
    });
    console.log('Echo conectado');
} catch (error) {
    console.error('Error inicializando Echo:', error);
}
