<template>
  <portlet :title="`Emblema de Clã`">
    <form class="m-form m-form--state" @submit.prevent="submit" @keydown="form.onKeydown($event)">
      <div v-if="guilds.length" class="m-alert m-alert--icon m-alert--outline alert alert-brand">
        <div class="m-alert__icon">
          <i class="la la-question-circle"/>
        </div>
        <div
          class="m-alert__text"
        >Utilize essa página para adicionar um emblema ao seu clã, ele estará disponível no launcher em no máximo 5 minutos.
          <br>
          <br>
          <strong>Observação:</strong>
          <br>1 – Apenas o Marechal pode enviar o emblema;
          <br>2 – O clã precisa estar no Nível 3 e ter pelo menos 10 membros;
          <br>2 – O emblema não poderá ser alterado dentro de um período de 7 dias;
        </div>
      </div>
      <div v-if="guilds.length" class="m-alert m-alert--icon m-alert--outline alert alert-info">
        <div class="m-alert__icon">
          <i class="la la-question-circle"/>
        </div>
        <div class="m-alert__text">
          Caso algum personagem seja marechal de um clã, mas não apareça na lista lista,
          <a href="#" class="m-link" @click.prevent="getCharacters()">clique aqui</a>
          para atualizar.
        </div>
      </div>
      <div v-if="guilds.length" class="form-group m-form__group">
        <label>Clã</label>
        <select v-model="form.guild" class="form-control">
          <option :value="null">Selecione um clã</option>
          <option
            v-for="guild in guilds"
            :key="guild.faction_id"
            :value="guild.faction_id"
          >{{ guild.faction_name }} (Marechal {{ guild.leader }})</option>
        </select>
      </div>
      <div v-else>
        <div class="m-alert m-alert--icon m-alert--outline alert alert-danger">
          <div class="m-alert__icon">
            <i class="la la-exclamation-circle"/>
          </div>
          <div class="m-alert__text">
            Essa conta não possui nenhum personagem como marechal. Caso você seja marechal mas esteja vendo essa mensagem,
            <a
              href="#"
              @click.prevent="getCharacters()"
            >clique aqui</a>
            para atualizar a sua lista de personagens.
          </div>
        </div>
      </div>
      <div
        v-if="form.guild"
        class="form-group m-form__group"
        :class="{ 'has-danger': form.errors.has('icon') }"
      >
        <label>Emblema</label>
        <input
          ref="input"
          v-validate="'dimensions:16,16'"
          data-vv-as="image"
          name="icon"
          type="file"
          style="display: none;"
          accept=".jpg, .jpeg, .png, .gif"
          :class="{ 'is-invalid': form.errors.has('icon') }"
          @change="addFile()"
        >
        <a class="btn btn-secondary m-btn m-btn--icon d-block" @click="openFileDialog">
          <i class="fal fa-image"/>
          Adc. Emblema
        </a>
        <has-error :form="form" field="icon"/>
        <span
          class="m-form__help"
        >A imagem precisa ter 16x16 e no máximo 50kb. É aceito formatos .jpg, .png ou .gif (envie em .png ou .gif para fundo transparente.)</span>
      </div>
      <div v-if="form.icon" class="attachments m--margin-bottom-10">
        <div class="attachment d-inline-flex m--margin-top-20 m--margin-right-10">
          <i class="fas fa-image" v-if="!previewIcon"/>
          <img :src="previewIcon" v-else>
          <div class="d-flex flex-1 flex-column">
            <div class="file-name">{{ form.icon.name }}</div>
            <div class="file-size">{{ iconSize(form.icon.size) }}</div>
          </div>
        </div>
      </div>
      <v-button
        v-if="form.guild && form.icon"
        :class="'btn btn-primary m-btn m-btn--custom'"
        :loading="form.busy"
      >Enviar</v-button>
    </form>
  </portlet>
</template>

<script>
import Form from "vform";
import filesize from "filesize";

export default {
  data: () => ({
    form: new Form({
      guild: null,
      icon: null
    }),
    previewIcon: "",
    guilds: []
  }),

  created() {
    this.getGuilds();
  },

  methods: {
    async getGuilds() {
      await axios
        .get(
          `/api/game/faction_icon/user_factions?account=${
            this.$route.params.account_id
          }`
        )
        .then(response => {
          const { data } = response;
          this.guilds = data;
        })
        .catch(error => {
          console.log(error);
        });
    },

    async submit() {
      await this.form
        .post("/api/game/faction_icon/user_factions")
        .then(response => {})
        .catch(error => {
          const { data } = error.response;
          console.log(data.error);
          this.$notify({
            type: "error",
            text: data.error
          });
        });
    },

    openFileDialog() {
      this.$refs.input.click();
    },

    addFile() {
      this.form.icon = this.$refs.input.files[0];
      let reader = new FileReader();
      let self = this;
      reader.onload = function(e) {
        self.previewIcon = e.target.result;
      };
      reader.readAsDataURL(this.$refs.input.files[0]);
    },

    iconSize(size) {
      console.log(size);
      let filesize = require("filesize");
      return filesize(size, { bits: true });
    },

    async getCharacters() {
      await axios
        .post("/api/game/characters", {
          user_id: this.$route.params.account_id
        })
        .then(response => {
          this.getGuilds();
          this.$notify({
            title: "",
            type: "success",
            text: "Lista atualizada com sucesso."
          });
          if (response.data.error) {
            this.$notify({
              title: "Oops!",
              type: "info",
              text: response.data.error
            });
          }
        })
        .catch(error => {
          this.$notify({
            title: "Oops!",
            type: "error",
            text: error.response.data.error
          });
        });
    }
  }
};
</script>
