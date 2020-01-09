import Vue from 'vue'
import Echo from "laravel-echo"

window.Pusher = require('pusher-js');

Vue.prototype.$echo = new Echo({
    broadcaster: "pusher",
    key: 'db043e8db3ab24be84dc',
    cluster: 'us2',
    forceTLS: true
});