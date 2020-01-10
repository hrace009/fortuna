<template>
  <div>
      <form @submit.prevent="register" @keydown="form.onKeydown($event)">
          <!-- Name -->
          <div class="form-group row">
              <label class="col-md-3 col-form-label text-md-right">Login</label>
              <div class="col-md-7">
                  <input v-model="form.name" type="text" name="name" class="form-control m-input"
                         :class="{ 'is-invalid': form.errors.has('name') }" @keyup="loginLowerCase($event)" placeholder="'Login'">
                  <has-error :form="form" field="name"></has-error>
              </div>
          </div>
          <!-- Password -->
          <div class="form-group row">
              <label class="col-md-3 col-form-label text-md-right">Senha</label>
              <div class="col-md-7">
                  <input v-model="form.password" type="password" name="password" class="form-control m-input"
                         :class="{ 'is-invalid': form.errors.has('password') }"  placeholder="'Senha'">
                  <has-error :form="form" field="password"></has-error>
              </div>
          </div>

          <!-- Password Confirmation -->
          <div class="form-group row">
              <label class="col-md-3 col-form-label text-md-right">Confirme a Senha</label>
              <div class="col-md-7">
                  <input v-model="form.password_confirmation" type="password" name="password_confirmation" class="form-control m-input"
                         :class="{ 'is-invalid': form.errors.has('password_confirmation') }" placeholder="'Confirme a Senha'">
                  <has-error :form="form" field="password_confirmation"></has-error>
              </div>
          </div>

          <!-- reCAPTCHA -->
          <div class="form-group row m--margin-top-20">
              <label class="col-md-3 col-form-label text-md-right"></label>
              <div class="col-md-7">
                  <vue-recaptcha ref="invisibleRecaptcha" @verify="onVerify" size="invisible" :sitekey="siteKey"></vue-recaptcha>
                  <v-button :class="'btn m-btn--pill m-btn--air btn-primary m--margin-top-10'" :loading="form.busy">
                     Cadastrar
                  </v-button>
                  <has-error :form="form" field="g_recaptcha_response"></has-error>
              </div>
          </div>

          <!-- Button -->
      </form>
  </div>
</template>

<script>
import Form from 'vform'
import VueRecaptcha from 'vue-recaptcha';
const { recaptcha } = window.config

export default {


   name: 'RegisterGame',

  middleware: 'auth',

  metaInfo () {
    return { title: 'Criar Conta de Jogo' }
  },

  data: () => ({
    form: new Form({
      name: '',
      password: '',
      password_confirmation: '',
      g_recaptcha_response: '',
    }),
    button: true,
    siteKey: recaptcha
  }),


  components: { VueRecaptcha },

  methods: {
    register () {
      this.$refs.invisibleRecaptcha.execute();
      // Register the user.
      this.form.post('/api/game_accounts/create_game_account')
        .then(async () => {
           await this.$notify({
               type: 'success',
               text: 'Conta de jogo criada com sucesso.'
           });
            this.$router.push({ name: 'game.accounts' });
        })
        .catch((error) => {
             const { status } = error.response;

          if (status === 422) return;
            this.$notify({
            title: 'Oops!',
            type: 'danger',
            text: error.response.data.error,
          });
        })
    },

     loginLowerCase(event) {
        return this.form.name = event.target.value.toLowerCase();
     },

    onVerify(response) {
        console.log('Verify: ' + response)
        this.form.g_recaptcha_response = response;
    }
  },

}
</script>
