<template>
<div class="m-grid__item m-grid__item--order-tablet-and-mobile-2 m-login__aside  m-login--1 m-login-1--skin-1">
      <div class="m-stack m-stack--hor m-stack--desktop">
          <div class="m-stack__item m-stack__item--fluid">
              <div class="m-login__wrapper">
                  <div class="">
                      <form @submit.prevent="reset" @keydown="form.onKeydown($event)" :class="'m-login__form m-form'">
                        <alert-success :form="form" :message="status" />
                        <div class="form-group m-form__group">
                          <input v-model="form.email" type="name" name="email" class="form-control"
                            :class="{ 'is-invalid': form.errors.has('email') }" :placeholder="$t('email')" readonly>
                          <has-error :form="form" field="email"></has-error>
                        </div>
                         <div class="form-group m-form__group">
                          <input v-model="form.passwd" type="password" name="passwd" class="form-control"
                          :class="{ 'is-invalid': form.errors.has('passwd') }" :placeholder="$t('passwd')">
                          <has-error :form="form" field="passwd"></has-error>
                        </div>
                         <div class="form-group m-form__group">
                          <input v-model="form.password_confirmation" type="password" name="password_confirmation" class="form-control"
                            :class="{ 'is-invalid': form.errors.has('password_confirmation') }" :placeholder="$t('password_confirmation')">
                          <has-error :form="form" field="password_confirmation"></has-error>
                        </div>
                        <div class="m-login__form-action">
                            <v-button :loading="form.busy" :class="'btn btn-focus m-btn m-btn--pill m-btn--custom m-btn--air'">{{ $t('reset_password') }}</v-button>
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
  middleware: 'auth',

  metaInfo () {
    return { title: this.$t('reset_password') }
  },

  data: () => ({
    status: '',
    form: new Form({
      token: '',
      email: '',
      passwd: '',
      password_confirmation: ''
    })
  }),


  created () {
    this.form.email = this.$route.query.name
    this.form.token = this.$route.params.token
  },


  methods: {
    async reset () {

      const { data } = await this.form.post('/api/game/password/reset')

      this.status = data.status

      this.form.reset()
    }
  }
}
</script>

