import Echo from "laravel-echo";
import Pusher from "pusher-js";
window.Pusher = Pusher;

window.Echo = new Echo({
    // broadcaster: "reverb",
    // key: 'kds15c8yiaqzrzauk1o7',
    // wsHost: '127.0.0.1',
    // wsPort: 8081,
    // // INI YANG PALING PENTING:
    // forceTLS: false, // Paksa matikan TLS/HTTPS
    // disableStats: true,
    // enabledTransports: ["ws"], // Paksa hanya gunakan 'ws://', abaikan 'wss://'

    broadcaster: "pusher",
    key: 'bdad83b91853b465a3eb',
    cluster: 'ap1',
    forceTLS: true,
});
