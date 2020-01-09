<template>
    <portlet :title="`Alterar senha da conta (${account.name})`">
       <form @submit.prevent="update" @keydown="form.onKeydown($event)" class="m-form m-form--state">
            <alert-success :form="form" message="'Senha foi alterada com sucesso!"></alert-success>

            <!-- Password -->
            <div class="form-group row" :class="{ 'has-danger': form.errors.has('password') }">
                <label class="col-md-3 col-form-label text-md-right">Nova Senha</label>
                <div class="col-md-7">
                <input v-model="form.password" type="password" name="password" class="form-control"
                    :class="{ 'is-invalid': form.errors.has('password') }">
                <has-error :form="form" field="password"></has-error>
                </div>
            </div>

            <!-- Password Confirmation -->
            <div class="form-group row" :class="{ 'has-danger': form.errors.has('confirm_password') }">
                <label class="col-md-3 col-form-label text-md-right">Confirme a Senha</label>
                <div class="col-md-7">
                <input v-model="form.password_confirmation" type="password" name="password_confirmation" class="form-control"
                    :class="{ 'is-invalid': form.errors.has('password_confirmation') }">
                <has-error :form="form" field="password_confirmation"></has-error>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="form-group row">
                <div class="col-md-9 ml-md-auto">
                <v-button :class="'btn m-btn--pill m-btn--air btn-primary m-btn m-btn--custom'" :loading="form.busy">Confirmar</v-button>
                </div>
            </div>
            </form>
    </portlet>
</template>

<script>
import Form from 'vform'

export default {

    middleware: 'auth',

  scrollToTop: false,

  metaInfo () {
    return { title: 'Alterar Senha do Jogo' }
  },

  data: () => ({
    form: new Form({
      password: '',
      password_confirmation: ''
    }),
    account: {},
  }),

  created () {
    this.geAccount();
   },

  methods: {
    async geAccount() {
        await axios.get(`/api/game_accounts/account/${this.$route.params.account}`)
        .then((response) => {
            this.account = response.data;
        })
        .catch(error => {
            this.$notify({ title: 'Oops!', type: 'error', text: error.response.data.error })
            this.$router.push({ name: 'game.accounts' });
        });
    },
    async update () {
       await this.form.patch(`/api/game_accounts/account/${this.$route.params.account}`)

       this.form.reset()
    }
  }
}
</script>

