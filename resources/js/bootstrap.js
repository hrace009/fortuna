import Vue from 'vue';

/**
 * We'll load jQuery and the Bootstrap jQuery plugin which provides support
 * for JavaScript based Bootstrap features such as modals and tabs. This
 * code may be modified to fit the specific needs of your application.
 */

try {
    window.Popper = require('popper.js').default;
    window.$ = window.jQuery = require('jquery');

    require('bootstrap');
} catch (e) {}

window.axios = require('axios');

// Sentry
/*import * as Sentry from '@sentry/browser';
import * as Integrations from '@sentry/integrations';

Sentry.init({
    dsn: 'https://131589d9f2eb458e87f513052ba623f0@sentry.io/1779503',
    integrations: [new Integrations.Vue({ Vue, attachProps: true })],
});*/

// Bootstrap Vue
import { BModal, VBModal } from 'bootstrap-vue';

Vue.component('b-modal', BModal);
Vue.directive('b-modal', VBModal);

// Mask
import VueMask from 'v-mask'
Vue.use(VueMask);

// Notifications
import Notifications from 'vue-notification'
Vue.use(Notifications);

// Pagination
Vue.component('pagination', require('laravel-vue-pagination'));