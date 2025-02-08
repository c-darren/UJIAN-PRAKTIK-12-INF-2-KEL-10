// import Echo from 'laravel-echo';
// import Pusher from 'pusher-js';

// window.Pusher = Pusher;

// window.Echo = new Echo({
//     broadcaster: 'reverb',
//     key: import.meta.env.VITE_REVERB_APP_KEY,
//     wsHost: import.meta.env.VITE_REVERB_HOST,
//     wsPort: import.meta.env.VITE_REVERB_PORT,
//     forceTLS: false,
//     enabledTransports: ['ws', 'wss'],
//     cluster: 'mt1'
// });

// // Use connector.pusher instead of connector.socket
// window.Echo.connector.pusher.connection.bind('connected', () => {
//     console.log('WebSocket Connected!');
// });

// window.Echo.connector.pusher.connection.bind('error', (error) => {
//     console.error('WebSocket Error:', error);
// });

// // Optional: Add disconnected event handler
// window.Echo.connector.pusher.connection.bind('disconnected', () => {
//     console.log('WebSocket Disconnected!');
// });