<template>
    <div class="kt-grid__item kt-grid__item--fluid  kt-grid__item--order-tablet-and-mobile-1  kt-login__wrapper">
            <!-- begin:: login head -->
                <div class="kt-login__head">
                    <span class="kt-login__signup-label">Já possui uma conta?</span>
                    <router-link
                        :to="{ name: 'login' }"
                        class="kt-link kt-login__signup-link"
                    >
                        Faça o login!
                    </router-link>
                </div>
                <!-- end:: login head -->
                <!-- begin::body--->
                <div class="kt-login__body">
                    <div class="kt-login__form">
                        <div class="kt-login__title">
                            <h3>Crie sua conta</h3>
                        </div>                    
                        <form @submit.prevent="register" @keydown="form.onKeydown($event)"  class="kt-form">
                        <div :class="`alert alert-success alert-dismissible fade show`" role="alert" v-if="message" v-html="message"></div>
                        <div class="row">
                            <div class="col-md-6">
                                <!-- First Name -->
                                <div class="form-group m-form__group" :class="{ 'has-danger': form.errors.has('first_name') }">
                                    <input v-model="form.first_name" type="text" name="first_name" class="form-control m-input input-lg"
                                           :class="{ 'is-invalid': form.errors.has('first_name') }" placeholder="Nome">
                                    <has-error :form="form" field="first_name"></has-error>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <!-- Last Name -->
                                <div class="form-group m-form__group" :class="{ 'has-danger': form.errors.has('last_name') }">
                                    <input v-model="form.last_name" type="text" name="last_name" class="form-control m-input input-lg"
                                           :class="{ 'is-invalid': form.errors.has('last_name') }" placeholder="Sobrenome">
                                    <has-error :form="form" field="last_name"></has-error>
                                </div>
                            </div>
                        </div>
                      
                        <!-- Email -->
                        <div class="form-group m-form__group" :class="{ 'has-danger': form.errors.has('email') }">
                            <input v-model="form.email" type="email" name="email" class="form-control m-input input-lg"
                                   :class="{ 'is-invalid': form.errors.has('email') }" placeholder="E-mail">
                            <has-error :form="form" field="email"></has-error>
                        </div>
                        <!-- Password -->
                        <div class="form-group m-form__group" :class="{ 'has-danger': form.errors.has('password') }">
                            <input v-model="form.password" type="password" name="password" class="form-control m-input input-lg"
                                   :class="{ 'is-invalid': form.errors.has('password') }" placeholder="Senha" autocomplete="true">
                            <has-error :form="form" field="password"></has-error>
                        </div>

                        <!-- Button -->
                        <div class="kt-login__actions">
                            <router-link :to="{ name: 'login' }" class="kt-link kt-login__link-forgot">Voltar</router-link>
                            <vue-recaptcha ref="invisibleRecaptcha" theme="dark" @verify="onVerify" size="invisible" sitekey=""></vue-recaptcha>
                            <v-button
                                :class="'btn btn-primary btn-elevate kt-login__btn-primary'"
                                :loading="form.busy"
                            >
                                Continuar
                            </v-button>
                        </div>
                    </form>
                    <!--begin::Social Auth-->
                    <social-auth v-if="socialAuthEnabled" />
                    <!--end::Social Auth-->
                    </div>
                </div>
        </div>
</template>

<script>
import Form from 'vform'
import VueRecaptcha from 'vue-recaptcha';
import SocialAuth from '~/pages/auth/social'

const { appName, socialLogin } = window.config

export default {
  middleware: 'guest',

  metaInfo () {
    return { title: 'Cadastro' }
  },

  components: { VueRecaptcha, SocialAuth },

  data: () => ({
    form: new Form({
      first_name: '',
      last_name: '',  
      email: '',
      password: '',
      g_recaptcha_response: ''
    }),
    message: '',
    siteKey: window.config.recaptcha,
    serverName: appName,    
    socialAuthEnabled: socialLogin,
  }),

  methods: {
    async register () {
      // Register the user.
      await this.form.post('/api/register')
      .then(({ data }) => {
          const { email } = data.data;
        this.message = `Cadastro realizado com sucesso. Abra o email enviado à <strong>${email}</strong> para concluir.`;
        // Redirect to login.
        // this.$router.push({ name: 'login' })
      }).catch(error => {
          this.$refs.recaptcha.reset()
      })
    },

    onVerify(response) {
        this.form.g_recaptcha_response = response
    }
  }
}
</script>
