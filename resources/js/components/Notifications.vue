<template>
  <div
    class="kt-header__topbar-item dropdown"
    data-toggle="dropdown" 
    data-offset="10px,10px">
  >
    <a
      href="#"
      @click="show=!show"
      class="kt-nav__link kt-dropdown__toggle"
      id="topbar-notification"
    >
      <span
        class="kt-nav__link-badge kt-badge kt-badge--dot kt-badge--dot-small kt-badge--danger"
        ref="linkBadge"
      />
      <span
        class="kt-nav__link-icon"
        ref="linkIcon"
      >
        <span class="kt-nav__link-icon-wrapper">
          <i class="flaticon-music-2" />
        </span>
      </span>
    </a>
    <div class="kt-dropdown__wrapper">
      <span class="kt-dropdown__arrow kt-dropdown__arrow--center" />
      <div class="kt-dropdown__inner">
        <div class="kt-dropdown__header kt--align-center">
          <span class="kt-dropdown__header-title">{{ offset }}</span>
          <span class="kt-dropdown__header-subtitle">Notificações</span>
          <a
            href
            @click.prevent="markAllAsRead()"
            class="kt-link mt-2 kt--icon-font-size-sm4"
            v-if="offset > 0"
          >Marcar tudo como lido</a>
        </div>
        <div class="kt-dropdown__body p-0">
          <div class="kt-dropdown__content">
            <div class="tab-content">
              <div
                class="tab-pane active"
                id="topbar_notifications_notifications"
                role="tabpanel"
              >
                <div>
                  <div class="kt-list-timeline kt-list-timeline--skin-light">
                    <VuePerfectScrollbar class="scroll">
                      <div
                        class="user-notifications"
                        v-if="offset > 0"
                      >
                        <div
                          class="notification__item"
                          v-for="(notification, index) in notifications"
                          :key="index"
                        >
                          <router-link
                            :to="{ name: notification.data.path.name, params: { id: notification.data.body.id } }"
                            :class="'notification__itekt--link'"
                            @click.native="markAsRead(notification.id)"
                          >
                            <span class="avatar-sm flex-none">
                              <img
                                :src="notification.data.author.avatar"
                                class="kt--img-rounded kt--marginless kt--img-centered"
                              >
                            </span>
                            <div class="d-flex flex-column kt--margin-left-10 flex-1">
                              <!-- Body -->
                              <div class="notification__itekt--text">
                                <p class="kt--margin-bottokt-5">
                                  <strong>{{ notification.data.author.name }}</strong>
                                  {{ notification.data.type }}
                                  <a
                                    href
                                    class="kt-link"
                                  >#{{ notification.data.body.id }}</a>
                                </p>
                                <p
                                  class="kt--margin-bottokt-0"
                                  v-if="notification.data.comment.message"
                                >
                                  <em>{{ notification.data.comment.message }}</em>
                                </p>
                              </div>
                              <!-- Meta -->
                              <div class="kt--margin-top-5 notification__itekt--meta">
                                <i
                                  class="la"
                                  :class="notification.data.icon"
                                />
                                <span
                                  class="kt--margin-left-5"
                                >{{ notification.created_at | formatDateDistance }}</span>
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
          <div
            class="user-notifications--footer text-center kt--height-40 kt--padding-top-10"
            v-if="offset > 0"
          >
            <router-link :to="{ name: 'user.notifications' }">
              Ver todas
            </router-link>
          </div>
        </div>
      </div>
    </div>
    <audio
      ref="notifySound"
      src="https://notificationsounds.com/soundfiles/dd458505749b2941217ddd59394240e8/file-sounds-1111-to-the-point.mp3"
    />
  </div>
</template>

<script>
import { mapGetters } from 'vuex';
import VuePerfectScrollbar from 'vue-perfect-scrollbar';
import { setInterval } from 'timers';
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
      const elBadge = this.$refs.linkBadge;

      setInterval(() => {
        elIcon.classList.add('kt-animate-shake');
        elBadge.classList.add('kt-animate-blink');
      }, 3000);

      setInterval(() => {
        elIcon.classList.remove('kt-animate-shake');
        elBadge.classList.remove('kt-animate-blink');
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
