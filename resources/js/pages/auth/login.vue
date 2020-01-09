<template>
    <div class="kt-grid__item kt-grid__item--fluid  kt-grid__item--order-tablet-and-mobile-1  kt-login__wrapper">
        <div class="kt-login__head">
            <span class="kt-login__signup-label">
                Ainda não possui uma conta?
                <router-link
                    :to="{ name: 'register' }"
                    class="kt-link kt-login__signup-link"
                >
                  Cadastre-se agora!
                </router-link>
            </span>
        </div>
        <!-- begin::body--->
        <div class="kt-login__body">
            <div class="kt-login__form">
                <div class="kt-login__title">
                    <h3>Boas-vindas de volta!</h3>
                </div>
                <!--begin::Form-->
                <form
                    @submit.prevent="login"
                    @keydown="form.onKeydown($event)"
                    class="kt-form"
                >
                    <div
                        :class="`alert alert-${message.type} alert-dismissible fade show`"
                        role="alert"
                        v-if="message.text"
                        v-html="message.text"
                    />
                    <div
                        class="alert alert-primary alert-dismissible fade show"
                        role="alert"
                        v-if="status"
                    >
                        {{ status }}
                    </div>
                    <!-- Email -->
                    <div
                        class="form-group"
                        :class="{ 'has-danger': form.errors.has('email') }"
                    >
                        <input
                            v-model="form.email"
                            type="text"
                            name="email"
                            class="form-control"
                            :class="{ 'is-invalid': form.errors.has('email') }"
                            placeholder="E-mail da conta"
                        >
                        <has-error
                            :form="form"
                            field="email"
                        />
                    </div>
                    <!-- Password -->
                    <div
                        class="form-group"
                        :class="{ 'has-danger': form.errors.has('password') }"
                    >
                        <input
                            v-model="form.password"
                            type="password"
                            name="password"
                            class="form-control"
                            :class="{ 'is-invalid': form.errors.has('password') }"
                            :placeholder="'Senha'"
                            autocomplete="off"
                        >
                        <has-error
                            :form="form"
                            field="password"
                        />
                    </div>
                    <!-- Button -->
                    <div class="kt-login__actions">
                        <a
                            href="#"
                            @click.prevent="forgotPassword()"
                            class="kt-link kt-login__link-forgot"
                        >Esqueci a senha</a>
                        <a href="#">
                            <v-button
                                :class="'btn btn-primary btn-elevate kt-login__btn-primary'"
                                :loading="form.busy"
                            >
                                Entrar
                            </v-button>
                        </a>
                    </div>
                </form>
                <!--end::Form-->
                <!--begin::Social Auth-->
                <social-auth v-if="socialAuthEnabled"/>
                <!--end::Social Auth-->
                <b-modal
                    ref="forgotModal"
                    centered
                    title="Instruções enviadas"
                >
                    Enviamos instruções para alterar sua senha para <strong>{{ form.email }}</strong>, verifique sua caixa de entrada e pasta de spam.
                    <div slot="modal-footer">
                        <b-btn
                            variant="primary"
                            @click="hideModal"
                        >
                            Okay
                        </b-btn>
                    </div>
                </b-modal>
            </div>
        </div>
        <!-- end::body-->
    </div>
</template>

<script>
import Form from 'vform';
import Cookies from 'js-cookie';
import SocialAuth from '~/pages/auth/social';

const { appName, socialLogin } = window.config;

export default {
  middleware: 'guest',

  metaInfo() {
    return { title: 'Autenticação' };
  },

  components: { SocialAuth },

  data: () => ({
    form: new Form({
      email: '',
      password: '',
    }),
    remember: false,
    message: {},
    status: null,
    serverName: appName,
    socialAuthEnabled: socialLogin,
    showForgotModal: false,
  }),

  created() {
    this.activate();
    if (Cookies.get('twofactor_token')) {
      return this.$router.push({ name: 'auth.security' });
    }
  },

  methods: {
    login() {
      // Submit the form.
      this.form
        .post('/api/login')
        .then((response) => {
          // Save the token.
          this.$store.dispatch('auth/saveToken', {
            token: response.data.token,
            remember: this.remember,
          });

          // Fetch the user.
          this.$store.dispatch('auth/fetchUser');

          this.$router.push({ name: 'home' });

          // Check if user has twofactor enabled
          /* if (response.data.twofactor_token) {
            this.$store.dispatch('auth/saveTwoFactorToken', {
              twofactor_token: '123',
            });
            // Log out the user.
            this.$store.dispatch('auth/logout');

            // Redirect to twofactor page
            this.$router.push({ name: 'auth.security' });
          } else {
            this.$router.push({ name: 'home' });
          } */
        })
        .catch((error) => {
          this.message = error.response.data;
          return this.$router.push({ name: 'login' });
        });
    },

    async activate() {
      const { confirmation_token } = this.$route.query;

      if (!confirmation_token) {
        return;
      }

      await axios
        .patch(`/api/register/activate/${confirmation_token}`)
        .then((response) => {
          this.message = response.data;
          return this.$router.push({ name: 'login' });
        })
        .catch((error) => {
          this.message = error.response.data;
          return this.$router.push({ name: 'login' });
        });
    },

    async forgotPassword() {
      const { data } = await this.form.post('/api/auth/forgot');

      this.$refs.forgotModal.show();
    },

    hideModal() {
      this.$refs.forgotModal.hide();
    },

  },
};
</script>
