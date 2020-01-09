<template>
    <div class="kt-header__topbar-item dropdown">
        <div class="kt-header__topbar-wrapper"  @click="show=!show" data-toggle="dropdown" data-offset="10px,10px" aria-expanded="false">
          <span class="kt-header__topbar-icon" ref="linkIcon">
            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon">
                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                        <path d="M17,12 L18.5,12 C19.3284271,12 20,12.6715729 20,13.5 C20,14.3284271 19.3284271,15 18.5,15 L5.5,15 C4.67157288,15 4,14.3284271 4,13.5 C4,12.6715729 4.67157288,12 5.5,12 L7,12 L7.5582739,6.97553494 C7.80974924,4.71225688 9.72279394,3 12,3 C14.2772061,3 16.1902508,4.71225688 16.4417261,6.97553494 L17,12 Z" fill="#000000"/>
                        <rect fill="#000000" opacity="0.3" x="10" y="16" width="4" height="4" rx="2"/>
                    </g>
                </svg>	
              <span class="kt-pulse__ring"/>
          </span>
        </div>
        <div class="dropdown-menu dropdown-menu-fit dropdown-menu-right dropdown-menu-anim dropdown-menu-xl">
            <div class="kt-head kt-head--skin-light kt-head--fit-x kt-head--fit-b">
                <h3 class="kt-head__title kt-padding-b-20">
                    Notificacacoes
                    &nbsp;
                    <span class="btn btn-success btn-sm btn-bold btn-font-md">{{ offset }}</span>
                </h3>
            </div>
            <div class="tab-content kt-padding-t-10">
                <div
                    class="tab-pane active"
                    id="topbar_notifications_notifications"
                    role="tabpanel"
                >
                    <div>
                        <div class="kt-list-timeline kt-list-timeline--skin-light">
                            <VuePerfectScrollbar class="scroll">
                                <div
                                    class="kt-notification kt-margin-t-10 kt-margin-b-10"
                                    v-if="offset > 0"
                                >
                                    <div
                                        class="kt-notification__item"
                                        v-for="(notification, index) in notifications"
                                        :key="index"
                                    >
                                        <router-link
                                            :to="{ name: notification.data.path.name, params: { id: notification.data.body.id } }"
                                            :class="'notification__item kt--link'"
                                            @click.native="markAsRead(notification.id)"
                                        >
                                            <div class="d-flex flex-column kt--margin-left-10 flex-1">
                                                <!-- Body -->
                                                <div class="kt-notification__item-details">
                                                    <p class="kt-notification__item-title kt-font-bold">
                                                        <strong>{{ notification.data.author.name }}</strong>
                                                        {{ notification.data.type }}
                                                        <a
                                                            href
                                                            class="kt-link"
                                                        >#{{ notification.data.body.id }}</a>
                                                    </p>
                                                    <p
                                                        class="kt-notification__item-title"
                                                        v-if="notification.data.comment.message"
                                                    >
                                                        <em>{{ notification.data.comment.message }}</em>
                                                    </p>
                                                </div>
                                                <!-- Meta -->
                                                <div class="kt-notification__item-time">
                                                    <i
                                                        class="la"
                                                        :class="notification.data.icon"
                                                    />
                                                    <span
                                                        class="kt--margin-left-5"
                                                        v-tippy
                                                        :title="notification.created_at | formatDateWithHours"
                                                    >{{ notification.created_at | formatDateDistance}}</span>
                                                </div>
                                            </div>
                                        </router-link>
                                    </div>
                                </div>
                                <div
                                    class="user-notifications empty"
                                    v-else
                                >
                                    <div class="kt--padding-top-50 kt--padding-bottokt-40">
                                        <div>
                                            Tudo limpo!
                                            <br>Não há novas notificações.
                                        </div>
                                        <div class="kt--margin-top-15">
                                            <!-- <router-link
                                               :to="{ name: 'user.notifications' }"
                                               :class="'btn btn-sm btn-outline-primary'"
                                             >
                                               Ver todas
                                             </router-link>-->
                                        </div>
                                    </div>
                                </div>
                            </VuePerfectScrollbar>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { mapGetters } from 'vuex';
import VuePerfectScrollbar from 'vue-perfect-scrollbar';
import { setInterval } from 'timers';
import axios from 'axios';
import store from '~/store'

export default {
  name: 'Notifications',

  data: () => ({
    Echo: null,
    limit: 20,
    offset: 0,
    notifications: [],
    hasNewNotification: false,
    show: false,
    position: { scrollTop: 0, scrollLeft: 0 },
    loading: false,
  }),

  components: {
    VuePerfectScrollbar,
  },

  computed: {
    ...mapGetters({
      user: 'auth/user',
    }),

    hasNotifications() {
      return this.notifications.length;
    },
  },

  created() {
    this.getNofications();
    this.listen();
    this.animateIcon();
  },

  methods: {
    listen() {
      this.$echo.private(`App.User.${this.user.id}`).notification(
        (notification) => {
          this.$refs.notifySound.play();
          this.notifications.length++;
          this.hasNewNotification = true;
          this.offset = 0;
          this.animateIcon();
        },
      )

      this.$echo
        .private(`User.GameServer.Characters.${this.user.id}`)
        .listen('.running', (e) => {
            this.$bus.$emit('update-game-characters.running', {
                success: true,
            });
          this.$notify({
            type: 'info',
            text: 'Atualizando personagens...',
          });
        })
        .listen('.finished', () => {
          this.$notify({
            type: 'sucess',
            text: 'Personagens atualizados com sucesso!',
          });
          this.$bus.$emit('update-game-characters.updated', {
            success: true,
          });
        });
    },

    getNofications() {
      axios
        .get(`/api/notifications/${this.offset}/${this.limit}`)
        .then(({ data }) => {
          this.notifications = this.offset
            ? this.notifications.concat(data)
            : data;
          this.offset = this.notifications.length;
          this.animateIcon();
        });
    },

    async markAsRead(notification) {
      await axios.patch(`/api/notifications/${notification}`).then((response) => {
        this.offset = this.offset > 0 ? --this.offset : this.offset;
        this.notifications.indexOf(notification) > -1
          ? this.notifications.splice(notification, 1)
          : null;
      });
    },

    async markAllAsRead(notification) {
      await axios
        .delete('/api/notifications/mark_all_as_read')
        .then((response) => {
          this.offset = 0;
          this.notifications = [];
        });
    },

    hide() {
      this.show = false;
    },

    onScroll(e, position) {
      this.position = position;
    },

    animateIcon() {
      if (!this.notifications.length) {
        return;
      }

      const elIcon = this.$refs.linkIcon;

      setInterval(() => {
        elIcon.classList.add('kt-animate-shake');
        elIcon.classList.add('kt-pulse');
        elIcon.classList.add('kt-pulse--brand');
      }, 3000);

      setInterval(() => {
          elIcon.classList.remove('kt-animate-shake');
          elIcon.classList.remove('kt-pulse');
          elIcon.classList.remove('kt-pulse--brand');
      }, 6000);
    },
  },

  watch: {
    show: {
      handler() {
        if (this.show) {
          this.getNofications();
        }
      },
    },

    offset(value) {
      this.offset = value;
    },

    hasNewNotification(notification) {
      if (notification) this.getNofications();
    },
  },
};
</script>

<style lang="scss">
.scroll {
  height: 250px;
}

.user-notifications .notification__itekt--link {
  display: inline-flex;
  text-decoration: none !important;
}

.user-notifications .notification__item {
  padding: 10px 20px;
  text-decoration: none;
  border-bottom: 1px solid #f3f1f8;
}

.user-notifications .notification__item:hover {
  background-color: #f3f1f8;
}

.user-notifications .notification__item .notification__itekt--text {
  font-size: 14px;
  color: #575962;
  line-height: 20px;
}

.user-notifications .notification__itekt--meta {
  font-size: 0.85rem;
}

.user-notifications--footer {
  background-color: #fbfbfb;
}

.empty {
  -webkit-box-pack: center;
  -ms-flex-pack: center;
  justify-content: center;
  text-align: center;
}

.kt--height-40 {
  height: 40px !important;
  min-height: 40px !important;
}
</style>
