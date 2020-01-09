<template>
  <portlet :title="`Visualizando detalhes da conta ${account.name}`">
    <div
      class="alert alert-danger m-alert"
      v-show="ApiNotAvailable"
    >
      <div class="alert-text">
        <p>
          Nao foi possivel se comunicar com o servidor de jogo, os dados dos personagem podem estar desatualizados. As acoes
          nao estao disponiveis no momento. Tente novamente mais tarde.
        </p>
      </div>
    </div>
    <characters
      :characters="characters"
    />
  </portlet>
</template>

<script>
import Form from 'vform';
import Characters from './modules/characters';

export default {
  middleware: 'auth',

  components: {
    Characters,
  },

  data: () => ({
    form: new Form({
      message: '',
      file: '',
    }),
    charactersForm: new Form({
      user_id: '',
    }),
    account: [],
    characters: [],
    ApiNotAvailable: false,
  }),

  created() {
    this.getAccount();
  },

  mounted() {
    this.$bus.$on('characters-load', () => {
      console.log('Updating characters...');
      this.getAccount();
    });
  },

  methods: {
    async getAccount() {
      await axios
        .get(`/api/game_accounts/account/${this.$route.params.account_id}`)
        .then((response) => {
          this.account = response.data.data;
        })
        .then(async () => {
          await this.verifyGameStatus();
          await this.getCharacters();
        })
        .catch((error) => {
          this.$router.push({ name: 'game.accounts' });
          this.$notify({
            title: 'Oops!',
            type: 'error',
            text: error.response.data.error,
          });
        });
    },

    async verifyGameStatus() {
      await axios.get('/api/game/check_status')
        .then(() => this.ApiNotAvailable = false)
        .catch((error) => {
          this.ApiNotAvailable = true;
          this.$notify({
            type: 'danger',
            text: error.response.data.description,
          });
        });
    },

    async getCharacters() {
      this.charactersForm.user_id = this.account.id;
      await this.charactersForm
        .post('/api/game/characters')
        .then((response) => {
          this.characters = response.data.data;

          if (response.data.error) {
            this.$notify({
              title: 'Oops!',
              type: 'info',
              text: response.data.error,
            });
          }
        })
        .catch((error) => {
          this.$notify({
            title: 'Oops!',
            type: 'error',
            text: error.response.data.error,
          });
        });
    },
  },
};
</script>
