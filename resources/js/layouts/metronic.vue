<template>
  <!-- begin:: Page -->
  <div v-fragment>
    <!-- begin:: Header -->
    <metronic-header v-if="user" />
    <!--- end:: Header -->
    <!-- begin::Body -->
    <template v-if="user">
      <div class="kt-body kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor kt-grid--stretch" id="kt_body">
        <div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kd_content">
          <div class="kt-grid__item kt--fluid kt-wrapper">
            <div class="kt-subheader   kt-grid__item">
                <div class="kt-container">
                    <metronic-sub-header />
                </div>
            </div>
            <div class="kt-container  kt-grid__item kt-grid__item--fluid">
              <notifications>
                <template
                  slot="body"
                  slot-scope="props"
                >
                  <div
                    class="alert kt-alert animated fadeIn"
                    :class="'alert-' + props.item.type"
                  >
                    <div
                      class="notification-content alert-text"
                      v-html="props.item.text"
                    />
                  </div>
                </template>
              </notifications>
              <child />
            </div>
          </div>
        </div>
      </div>
    </template>
    <template v-else>
      <div class="kt-grid kt-grid--hor kt-grid--root  kt-login kt-login--v1">
        <div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--desktop kt-grid--ver-desktop kt-grid--hor-tablet-and-mobile">
            <!--begin::Content-->
            <child />
            <!--end::Content-->
        </div>
      </div>
    </template>
    <!-- END: Body -->
    <footer class="kt-footer  kt-footer--extended  kt-grid__item">
        <div class="kt-footer__bottom">
            <div class="kt-container ">
                <div class="kt-footer__wrapper">
                    <div class="kt-footer__logo">
                        <a href="#">
                            <img alt="Logo" src="https://pw4fun-painel.s3.sa-east-1.amazonaws.com/logo-light.svg" width="150">
                        </a>
                        <div class="kt-footer__copyright">
                            2019&nbsp;©&nbsp;
                            <a
                                :href="siteUrl"
                                class="kt-link"
                            >{{ appName }}</a>
                        </div>
                    </div>
                    <div class="kt-footer__menu">
                        <a href="http://keenthemes.com/metronic" target="_blank">Purchase License</a>
                        <a href="http://keenthemes.com/metronic" target="_blank">Team</a>
                        <a href="http://keenthemes.com/metronic" target="_blank">Contact</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>
  </div>
  <!-- end:: Page -->
</template>

<script>

import { mapGetters } from 'vuex';
import Form from 'vform';
import MetronicHeader from '~/components/MetronicHeader';
import MetronicSubHeader from '~/components/MetronicSubHeader';
import Footer from '~/components/Footer';

// we keep the configurable flag on, so we can delete the property later to bring it back to normal
const freeze = (child, parent) => {
  Object.defineProperty(child, 'parentNode', {
    configurable: true,
    writable: false,
    value: parent,
  });
};

// we force-delete for unfreeze, and recreate parent as null
const unfreeze = (child) => {
  Object.defineProperty(child, 'parentNode', {
    configurable: true,
    writable: true,
    value: null,
  });
};

export default {
  name: 'Metronic',

  data: () => ({
    form: new Form({}),
    displayMetronicSidebar: false,
    appName: window.config.appName,
    siteUrl: window.config.siteUrl,
  }),

  beforeMount() {
    document.getElementById('app').setAttribute('class', 'kt-grid kt-grid--hor kt-grid--root kt-page');
  },

  computed: {
    ...mapGetters({
      user: 'auth/user',
    }),

    showSidebar() {
      if (this.user.role[0] === 'admin') {
        return true;
      }
      return false;
    },
  },

  directives: {
    fragment: {
      inserted(element) {
        const fragment = document.createDocumentFragment();
        const children = Array.from(element.childNodes);
        const parent = element.parentNode;
        children.forEach((child) => fragment.appendChild(child));
        parent.insertBefore(fragment, element);
        parent.removeChild(element);
        children.forEach((child) => freeze(child, element));
        element.__hooks__ = {
          appendChild: element.appendChild,
          insertBefore: element.insertBefore,
          removeChild: element.removeChild,
        };
        element.appendChild = function (child) {
          const op = parent.appendChild(child);

          if (child.parentNode !== element) { freeze(child, element); }

          return op;
        };
        element.insertBefore = function (child, reference) {
          const op = parent.insertBefore(child, reference);

          if (child.parentNode !== element) { freeze(child, element); }

          return op;
        };
        element.removeChild = function (child) {
          unfreeze(child);
          return parent.removeChild(child);
        };
      },
      unbind(element) {
        if (element.__hooks__) {
          Object.keys(element.__hooks__).forEach((hook) => {
            element[hook] = element.__hooks__[hook];
          });

          delete element.__hooks__;
        }
      },
    },
  },

  components: {
    MetronicHeader,
    MetronicSubHeader,
    Footer,
  },
};
</script>
