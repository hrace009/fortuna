import Vue from 'vue';
import { library } from '@fortawesome/fontawesome-svg-core';
import {
  faBan, faSpinner, faMoneyBillAlt, faTruck, faArrowRight, faShoppingBag, faCreditCard, faInfoCircle, faUpload,
  faMapMarkedAlt, faLockOpen, faExclamationCircle,
} from '@fortawesome/free-solid-svg-icons';
import { faFacebookF, faGoogle } from '@fortawesome/free-brands-svg-icons';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';

Vue.component('fa', FontAwesomeIcon);
library.add(
  faBan, faSpinner, faFacebookF, faGoogle, faMoneyBillAlt, faTruck, faArrowRight, faShoppingBag, faCreditCard,
  faInfoCircle,
  faUpload,
  faMapMarkedAlt,
  faLockOpen,
  faExclamationCircle,
);
