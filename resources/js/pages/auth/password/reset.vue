<template>
<div class="m-grid__item m-grid__item--order-tablet-and-mobile-2 m-login__aside m-auto">
      <div class="m-stack m-stack--hor m-stack--desktop">
          <div class="m-stack__item m-stack__item--fluid">
              <div class="m-login__wrapper">
                  <div class="">
                      <form @submit.prevent="reset" @keydown="form.onKeydown($event)" :class="'m-login__form m-form'">
                        <alert-success :form="form" :message="status" />
                        <div class="form-group m-form__group">
                          <input v-model="form.email" type="email" name="email" class="form-control"
                            :class="{ 'is-invalid': form.errors.has('email') }" placeholder="E-mail" readonly>
                          <has-error :form="form" field="email"></has-error>
                        </div>
                         <div class="form-group m-form__group">
                          <input v-model="form.password" type="password" name="password" class="form-control"
                          :class="{ 'is-invalid': form.errors.has('password') }" placeholder="Senha">
                          <has-error :form="form" field="password"></has-error>
                        </div>
                         <div class="form-group m-form__group">
                          <input v-model="form.password_confirmation" type="password" name="password_confirmation" class="form-control"
                            :class="{ 'is-invalid': form.errors.has('password_confirmation') }" placeholder="Confirme a senha">
                          <has-error :form="form" field="password_confirmation"></has-error>
                        </div>
                        <div class="m-login__form-action">
                            <v-button :loading="form.busy" :class="'btn btn-focus m-btn m-btn--pill m-btn--custom m-btn--air'">Resetar</v-button>
                        </div>
                    </form>
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
      token: '',
      email: '',
      password: '',
      password_confirmation: ''
    })
  }),


  created () {
    this.form.email = this.$route.query.email
    this.form.token = this.$route.params.token
  },


  methods: {
    async reset () {

      const { data } = await this.form.post('/api/password/reset')

      this.status = data.status

      this.form.reset()
    }
  }
}
</script>

