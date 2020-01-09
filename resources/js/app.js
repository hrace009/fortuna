import Vue from 'vue'
import store from '~/store'
import router from '~/router'
import App from '~/components/App'

import '~/plugins'
import '~/filters'
import '~/components'
import '~/components/global'

import './bootstrap'
import './theme'

Vue.config.productionTip = false;

Vue.use({
  install(V) {
    const bus = new Vue();
    V.prototype.$bus = bus;
    V.bus = bus;
  },
});

new Vue({
  store,
  router,
  ...App,
});
