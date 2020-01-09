<template>
  <portlet :title="'Alterar Senha'">
    <form @submit.prevent="update" @keydown="form.onKeydown($event)">
      <alert-success :form="form" :message="'Sua senha foi atualizada com sucesso!'"></alert-success>

      <!-- Current Password -->
      <div class="form-group row">
        <label class="col-md-3 col-form-label text-md-right">Senha atual</label>
        <div class="col-md-7">
          <input v-model="form.current_password" type="password" name="current_password" class="form-control "
            :class="{ 'is-invalid': form.errors.has('current_password') }">
          <has-error :form="form" field="current_password"></has-error>
        </div>
      </div>

      <!-- Password -->
      <div class="form-group row">
        <label class="col-md-3 col-form-label text-md-right">Nova senha</label>
        <div class="col-md-7">
          <input v-model="form.password" type="password" name="password" class="form-control "
            :class="{ 'is-invalid': form.errors.has('password') }">
          <has-error :form="form" field="password"></has-error>
        </div>
      </div>

      <!-- Password Confirmation -->
      <div class="form-group row">
        <label class="col-md-3 col-form-label text-md-right">Confirme a nova senha</label>
        <div class="col-md-7">
          <input v-model="form.password_confirmation" type="password" name="password_confirmation" class="form-control "
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
    return { title: 'Alterar Senha' }
  },

  data: () => ({
    form: new Form({
      password: '',
      password_confirmation: ''
    })
  }),

  methods: {
    async update () {
      await this.form.patch('/api/settings/password')

      this.form.reset()
    }
  }
}
</script>
