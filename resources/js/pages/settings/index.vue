<template>
  <div class="row">
    <div class="col-md-3">
      <portlet :class="'m-portlet--full-height'">
        <!-- Begin:: Navigation -->  
        <div class="kt-widget kt-widget--user-profile-1">
            <div class="kt-widget__head">
                <div class="kt-widget__media">
                    <img :src="user.avatar" alt="image">
                </div>
                <div class="kt-widget__content">
                    <div class="kt-widget__section">
                        <span class="kt-widget__username">
                            {{ user.name }}
                        </span>
                    </div>
                </div>
            </div>
           <div class="kt-widget__body">
               <div class="kt-widget__content"></div>
               <div class="kt-widget__items">
                   <router-link :to="{ name: tab.route}"
                                v-for="tab in tabs"
                                class="kt-widget__item"
                                active-class="kt-widget__item--active"
                                :key="tab.id"
                   >
                       <div class="kt-widget__section">
                           <span class="kt-widget__icon">
                               <picture>

                                  <source :src="tab.icon" :srcset="tab.icon" type="image/svg+xml" class="kt-svg-icon">
                                
                                </picture>
                           </span>
                           <span class="kt-widget__desc">
                               {{ tab.name }}
                           </span>
                       </div>
                   </router-link>
               </div>
           </div>
        </div>
        <!-- End:: Navigation -->
      </portlet>
    </div>

    <div class="col-md-9">
        <router-view></router-view>
    </div>
  </div>
</template>

<script>
import { mapGetters } from "vuex";
const svgAssetsUrl = 'https://pw4fun-painel.s3-sa-east-1.amazonaws.com/assets/svg';
export default {
  middleware: "auth",

  methods: {
    async updateUser(data) {
     await this.$store.dispatch("auth/updateUser", {
        user: data.data
      });
    },      
  },
    
  directives: {
      xlinkhref (el, value) {
        return  el.setAttributeNS('http://www.w3.org/1999/xlink', 'xlink:href', value.value)
    }
  },  

  computed: {
    ...mapGetters({
      user: "auth/user"
    }),

    uploadAvatarUrl() {
      return "/api/settings/profile/avatar";
    },

    tabs() {
      return [
        {
          icon: `${svgAssetsUrl}/General/User.svg`,
          name: 'Minha Conta',
          route: "settings.profile"
        },
        {
          icon: `${svgAssetsUrl}/Code/Lock-circle.svg`,
          name: 'Alterar Senha',
          route: "settings.password"
        },
        {
          icon: `${svgAssetsUrl}/General/Shield-check.svg`,
          name: 'Segurança',
          route: "settings.security"
        },
        {
          icon: `${svgAssetsUrl}/General/Star.svg`,
          name: "Contas de jogo",
          route: "game.accounts"
        }
      ];
    }
  }
};
</script>

<style>
.settings-card .card-body {
  padding: 0;
}
</style>