<template>
  <div>
    <!-- <avatar></avatar> -->
    <portlet :title="'Seus dados cadastrais'">
      <form
        class="m-form m-form--state"
        @submit.prevent="update"
        @keydown="form.onKeydown($event)"
      >
        <alert-success
          :form="form"
          :message="'Seus dados cadastrais foram atualizados com sucesso!'"
        />

        <!-- Name/E-mail -->
        <div class="form-group m-form__group row">
          <!-- Name -->
          <div class="col-lg-12">
            <label>Nome</label>
            <input
              v-model="form.name"
              type="text"
              name="name"
              class="form-control m-input"
              placeholder="Como você se chama?"
              :class="{ 'is-invalid': form.errors.has('name') }"
            >
            <has-error
              :form="form"
              field="name"
            />
          </div>
        </div>

        <div class="form-group m-form__group row">
          <!-- Email -->
          <div class="col-lg-6">
            <label>E-mail
            </label>
            <input
                    type="email"
                    class="form-control"
                    :value="this.user.email"
                    v-tippy
                    title="Não é possível alterar o email da conta."
                    readonly                   
            >
            <has-error
                    :form="form"
                    field="email"
            />
          </div>
          <!-- Document -->
          <div class="col-lg-6">
            <label>CPF</label>
            <input
                    :value="this.user.document"
                    type="text"
                    class="form-control"
                    v-tippy
                    title="Não é possível alterar o CPF."
                    readonly
                  
            >
            <has-error
                    :form="form"
                    field="document"
            />
          </div>
        </div>

        <!-- Gender/Birthday  -->
        <div class="form-group m-form__group row">
          <!-- Gender -->
          <div class="col-lg-6">
            <label>Gênero</label>
            <select
              v-model="form.gender"
              name="gender"
              class="form-control"
              :class="{ 'is-invalid': form.errors.has('gender') }"
            >
              <option value="male">Homem</option>
              <option value="female">Mulher</option>
            </select>
            <has-error
              :form="form"
              field="gender"
            />
          </div>
          <!-- Birthday -->
          <div class="col-lg-6">
            <label>Data de Aniversário</label>
            <input
              v-model="form.birthday"
              v-mask="'##/##/####'"
              type="text"
              name="birthday"
              :class="{ 'is-invalid': form.errors.has('birthday') }"
              class="form-control"
            >
            <has-error
              :form="form"
              field="birthday"
            />
          </div>
        </div>
        <v-button :class="'btn btn-primary'" :loading="form.busy">Atualizar</v-button>
      </form>
    </portlet>
  </div>
</template>

<script>
import Form from 'vform'
import { mapGetters } from 'vuex'

export default {
  middleware: 'auth',

  scrollToTop: false,

  metaInfo () {
    return { title: 'Minha Conta' }
  },

  data: () => ({
    form: new Form({
      name: '',
      gender: '',
      birthday: '',
      document: '',
    })
  }),

  computed: mapGetters({
    user: 'auth/user'
  }),

  created () {
    // Fill the form with user data.
    if (!this.user) {
      return;
    }
    this.form.keys().forEach(key => {
      this.form[key] = this.user[key]
    })
  },

  methods: {
    async update () {
      const { data } = await this.form.patch('/api/settings/profile')

      this.$store.dispatch('auth/updateUser', { user: data.data })
    } 
  }
}
</script>
