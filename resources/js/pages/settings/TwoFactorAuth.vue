<template>
<div>
    <portlet :title="'Autenticação de dois fatores'">
        <div class="row">
            <div class="col-md-12">
                <p v-if="!twoFactorEnabled">
                    Proteja sua conta com uma camada extra de segurança à sua conta.
                    Para fazer o login, você precisará fornecer um código de verificação de seu dispositivo móvel,
                    junto
                    com seu e-mail e senha.
                    Isso nos permite saber que é realmente você.
                </p>
                <p v-else>
                    <strong class="text-success text-uppercase">
                        <fa icon="lock" fixed-width /> Autenticação de dois fatores ativada
                    </strong>
                    <br>
                    Quando você efetuar o login, será necessário inserir o código gerado no seu aplicativo autenticador.
                </p>
            </div>
            <div class="col-md-12" v-if="!twoFactorEnabled">
                <button type="button" @click="process" class="btn btn-primary text-uppercase" data-toggle="modal"
                    data-target="#tfaModal">
                    Habilitar autenticação de dois fatores
                </button>              
            </div>
            <div class="col-md-12" v-else>
                 <button type="button" @click="getBackupCodes" class="btn btn-primary text-uppercase">
                    Ver códigos de recuperação
                </button>        
                <button type="button" @click="disableTwoFactor" class="btn btn-outline-danger text-uppercase">
                    Desabilitar autenticação de dois fatores
                </button>
            </div>
            <div class="col-md-12">
                <two-factor-backup-codes :backupCodes="backupCodes"></two-factor-backup-codes>
            </div>
        </div>
    </portlet>
    <!-- MODAL TFA -->
    <div class="modal fade" id="tfaModal" tabindex="-1" role="dialog" aria-labelledby="tfaModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Ativar autenticação de dois fatores</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>
                        Baixe e instale 
                        <a href="https://support.google.com/accounts/answer/1066447?hl=pt-BR" target="_blank" class="text-bold">Google Authenticator</a>
                        ou
                        <a href="https://www.authy.com/" target="_blank" class="text-bold">Authy</a> no seu celular.
                        Abra o app autenticador e escaneie a imagem abaixo,
                        usando a câmera do seu celular.
                    </p>
                    <div class="m--align-center">
                        <img :src="qrCode">
                    </div>
                </div>
                <form role="form" v-on:submit.prevent="authenticatorVerify" v-on:keydown="form.errors.clear($event.target.name)">
                    <!-- Name -->
                    <div class="col-md-12">
                        <form class="form-horizontal">
                            <div class="form-group" :class="{'has-error': form.errors.has('one_time_password')}">
                                <input type="text" class="form-control input-md" placeholder="Digite o código de 6 digitos"
                                    maxlength="6" v-model="form.one_time_password">
                                <has-error :form="form" field="one_time_password"></has-error>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <v-button :class="'btn btn-primary m-btn'" :loading="form.busy">Ativar</v-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</template>

<script>
import Form from "vform";
import { mapGetters } from "vuex";
import swal from "sweetalert2";
import TwoFactorBackupCodes from './TwoFactorBackupCode'

export default {
  data: () => ({
    form: new Form({
      one_time_password: "",
      method: "authenticator"
    }),
    qrCode: null,
    showBackupCodes: false,
    backupCodes: [],
  }),

  components: {
      TwoFactorBackupCodes
  },

  computed: {
    ...mapGetters({
      user: "auth/user"
    }),
    twoFactorEnabled() {
      if (!this.user) {
        return;
      }
      return this.user.twofactor.is_enabled;
    }
  },

  methods: {
    async process() {
      await this.form
        .post("/api/two_factor_auth/select_method")
        .then(({ data }) => {
          this.qrCode = data.qrCode;
        });
    },

    async getBackupCodes() {
        await axios.get('/api/two_factor_auth/backup_codes')
        .then(( { data }) => {
            this.backupCodes = data;
        })
    },

    async authenticatorVerify() {
      const { data } = await this.form.patch(
        "/api/two_factor_auth/authenticator_verify"
      );
      this.$store.dispatch("auth/updateUser", { user: data });
      this.$router.push({ name: "settings.security" });
    },

    async disableTwoFactor() {
      swal({
        title: "Desativar a autenticação de dois fatores?",
        text:
          "Isso reduz o nível de segurança da sua conta e não é recomendado. Você tem certeza que quer continuar?",
        type: "error",
        showCancelButton: true,
        confirmButtonText: "Sim, desativar.",
        cancelButtonText: "Melhor não.",
        reverseButtons: true
      }).then(result => {
        if (result) {
          const { data } = axios.delete("/api/two_factor_auth");
          this.$store.dispatch("auth/updateUser", { user: data });
          this.$router.push({ name: "settings.security" });
        }
      });
    }
  }
};
</script>
