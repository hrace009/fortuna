<template>
  <div>
    <portlet :title="'Novo Ticket'" v-if="!ticketSubmitted">
      <form @submit.prevent="submit" @keydown="form.onKeydown($event)" class="m-form m-form--state">
        <!-- Category -->
        <div
          class="form-group m-form__group"
          :class="{ 'has-danger': form.errors.has('category_id') }"
        >
          <label>
           Categoria
            <span class="text-danger">*</span>
          </label>
          <select
            v-model="form.category_id"
            name="category"
            class="form-control"
            :class="{ 'is-invalid': form.errors.has('category_id') }"
          >
            <option :value="null">Selecione uma categoria</option>
            <option v-for="cat in categories" :value="cat.id" :key="cat.id">{{ cat.name }}</option>
          </select>
          <has-error :form="form" field="category_id"></has-error>
        </div>

        <!-- Subject -->
        <div class="form-group m-form__group" :class="{ 'has-danger': form.errors.has('subject') }">
          <label>
           Assunto
            <span class="text-danger">*</span>
          </label>
          <input
            v-model="form.subject"
            type="text"
            name="subject"
            class="form-control"
            :class="{ 'is-invalid': form.errors.has('subject') }"
          >
          <has-error :form="form" field="subject"></has-error>
        </div>

        <!-- Message -->
        <div class="form-group m-form__group" :class="{ 'has-danger': form.errors.has('message') }">
          <label>
            Mensagem
            <span class="text-danger">*</span>
          </label>
          <textarea
            v-model="form.message"
            name="message"
            class="form-control"
            rows="10"
            :class="{ 'is-invalid': form.errors.has('message') }"
          ></textarea>
          <!-- <vue-editor v-model="form.message"></vue-editor> -->
          <has-error :form="form" field="message"></has-error>
        </div>

        <!-- Attachments -->
        <attachment-upload @attachmentAdd="handleAttachmentAdd" @attachmentRemoved="handleAttachmentRemoved"/>

        <!-- Submit Button -->
        <div class="form-group m-form__group">
          <div
            class="m--margin-top-20 m--display-flex-center mt-15 flex-column-reverse flex-md-row align-items-start align-items-md-center"
          >
            <div class="flex">
              <v-button :class="'btn btn-primary m-btn'" :loading="form.busy">
                  Enviar
              </v-button>
            </div>            
          </div>
        </div>
      </form>
    </portlet>
    <portlet v-if="ticketSubmitted">
      <div class="m-portlet-body">
        <div class="m--align-center">
          <div class="la la-check m--font-success m-30" style="font-size: 70px;"></div>
          <p class="m--padding-top-20 m--font-bold lead">Ticket
            <router-link
              :to="{ name: 'tickets.view', params: { id: ticket.id } }"
              class="m-link m-link-brand"
            >#{{ ticket.id }}</router-link>foi criado com sucesso!
            <br>Em breve entraremos em contato, e não se surpreenda caso a resposta chegue antes do que você esperava!
          </p>
          <router-link
            class="btn m-btn--pill m-btn--air btn-brand m-btn--wide m--margin-top-5"
            :to="{ name: 'tickets.view', params: { id: ticket.id } }"
          >Ver Ticket</router-link>
        </div>
      </div>
    </portlet>
  </div>
</template>

<script>
import { mapGetters } from "vuex";
import Form from "vform";


export default {
  middleware: "auth",

  metaInfo() {
    return { title: "Novo Ticket" };
  },

  
  data () { 
    return {
      form: new Form({
        category_id: null,
        subject: "",
        message: "",
        attachments: ""
      }),  
      categories: {},
      ticket: {},
      ticketSubmitted: false,
    }
  },

  computed: {
    ...mapGetters({
      user: "auth/user"
    })
  },

  created() {
    this.fetchCategories();

    this.$bus.$on('tickets:attachmentAdd', (id) => {
      console.log('Id attachment ', id);
    })
  },
  
  methods: {

    handleAttachmentAdd(file) {
       this.form.attachments = file.serverId;
    },

    handleAttachmentRemoved() {
        this.form.attachments = null;
    },

    async submit() {
      if (this.form.busy === true) {
        return;
      }

      await this.form
        .post("/api/tickets")
        .then(response => {
          this.$notify({
            title: "Ótimo!",
            type: "success",
            text: "Seu ticket foi criado com sucesso!"
          });
          this.ticket = response.data.data;
          this.ticketSubmitted = true;
        })
        .catch(error => {
          const { status, data } = error.response;
          if (status === 422) {
            return;
          }
          this.$notify({
            title: "Oops!",
            type: "error",
            text: data.message
          });
        });
    },

    async fetchCategories() {
      await axios.get("/api/ticket/categories").then(({ data }) => {
        this.categories = data.data;
      });
    },

    openFileDialog() {
      this.$refs.input.click();
    },
  }
};
</script>

<style lang="scss" scoped>
.z-1000 {
  z-index: 1000;
}
.hover {
  background-color: #007bff;
  color: #fff;
}

</style