<template>
 <div class="m-grid__item m-grid__item--order-tablet-and-mobile-2 m-login__aside m-auto">
          <div class="m-stack m-stack--hor m-stack--desktop">
              <div class="m-stack__item m-stack__item--fluid">
                  <div class="m-login__wrapper">
                      <div class="">
                        <div class="m-login__head">
                          <h3 class="m-login__title">Esqueceu a senha?</h3>
                          <div class="m-login__desc">Insira seu e-mail para redefinir sua senha:</div>
                        </div>
                          <form @submit.prevent="send" @keydown="form.onKeydown($event)" :class="'m-login__form m-form'">
                            <alert-success :form="form" :message="status" />
                            <div class="form-group m-form__group">
                               <input v-model="form.email" type="email" name="email" class="form-control" :class="{ 'is-invalid': form.errors.has('email') }">
                              <has-error :form="form" field="email"></has-error>
                            </div>
                            <div class="m-login__form-action">
                               <v-button :loading="form.busy" :class="'btn btn-focus m-btn m-btn--pill m-btn--custom m-btn--air'">Confirmar</v-button>
                               <router-link :to="{ name: 'login' }" class="btn btn-outline-focus  m-btn m-btn--pill m-btn--custom">{{ $t('back') }}</router-link>
                            </div>
                        </form>
                      </div>
                  </div>
              </div>
              <div class="m-stack__item m-stack__item--center m--margin-top-30">
                  <div class="m-login__account">
                      <div class="m-login__account-msg">
                          Ainda não possui uma conta?
                          <router-link :to="{ name: 'register' }" class="m-link m-link--focus m-login__account-link">Cadastre-se!</router-link>
                      </div>
                  </div>
              </div>
          </div>
</div>
</template>

<script>
import Form from 'vform'

export default {
  middleware: 'guest',

  metaInfo () {
    return { title: 'Resetar senha' }
  },

  data: () => ({
    status: '',
    form: new Form({
      email: ''
    })
  }),

  methods: {
    async send () {
      const { data } = await this.form.post('/api/password/email')

      this.status = data.status

      this.form.reset()
    }
  }
}
</script>
