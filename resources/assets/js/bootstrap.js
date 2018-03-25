
window._ = require('lodash');

/**
 * We'll load jQuery and the Bootstrap jQuery plugin which provides support
 * for JavaScript based Bootstrap features such as modals and tabs. This
 * code may be modified to fit the specific needs of your application.
 */

try {
    window.$ = window.jQuery = require('jquery');

    require('bootstrap-sass');
    require('bootstrap-toggle');

    window.alertify = require('alertify-js');

    window.bootbox = require('bootbox');

} catch (e) {}

/**
 * Vue is a modern JavaScript library for building interactive web interfaces
 * using reactive data binding and reusable components. Vue's API is clean
 * and simple, leaving you to focus on building your next great project.
 */

// window.Vue = require('vue');
// require('vue-resource');

/**
 * Next we will register the CSRF Token as a common header with Axios so that
 * all outgoing HTTP requests automatically have it attached. This is just
 * a simple convenience so we don't have to attach every token manually.
*/

 let token = document.head.querySelector('meta[name="csrf-token"]');

 if (token) {
    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': token.content } });
 } else {
     console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
 }

// Vue.http.interceptors.push((request, next) => {
//     request.headers.set('X-CSRF-TOKEN', Laravel.csrfToken);
//
//     next();
// });

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

import Echo from "laravel-echo";

window.Pusher = require('pusher-js');

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: 'f2b29fa8e3ac12226a44',
    cluster: 'ap1',
    encrypted: true
});