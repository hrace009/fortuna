<template>
  <portlet>
    <div class>
      <div class="d-flex flex-row m--margin-bottom-15">
        <h5 class="m--margin-bottom-20 flex-grow-1">
          Meus Personagens
        </h5>
        <div>
          <button
            @click="upateGameCharacters"
            title="Ultima atualização 16/10/2019, 8:45"
            v-tippy
            class="btn btn-sm btn-brand"
            :disabled="busy"
          >
            Atualizar personagens
          </button>
          <div
            class="m-loader m-loader--brand m--padding-10"
            v-if="busy"
          />
        </div>
      </div>

      <table class="table table-wmdev m--align-center">
        <thead>
          <tr>
            <th class="m--align-left">
              Nome
            </th>
            <th class="m--align-left">
              Nível
            </th>
            <th class="m--align-left">
              Classe
            </th>
            <th class="m--align-left">
              Cultivo
            </th>
            <th class="m--align-left">
              Opções
            </th>
          </tr>
        </thead>
        <tbody>
          <template v-if="!busy">
            <template v-if="characters.length">
              <tr
                v-for="(role, index) in characters"
                :key="index"
              >
                <td class="m--align-left">
                  {{ role.name }}
                </td>
                <td class="m--align-left">
                  {{ role.level }}
                </td>
                <td class="m--align-left">
                  {{ role.class }}
                </td>
                <td class="m--align-left">
                  {{ role.cultivation }}
                </td>
                <td class="m--align-left">
                  <a
                    v-for="(service, id) in services"
                    :key="id"
                    v-tippy="{ position: 'top' }"
                    :title="service.name"
                    class="btn btn-sm btn-rounded btn-light"
                    :disabled="busy"
                    @click="gameService(role.id, role.game_account, service, id)"
                  >


                    <div>
                      <fa
                        :icon="service.icon"
                        size="4x"
                        v-if="!processingService"
                      />
                      <div
                        class="m-loader m-loader--brand"
                        v-else
                      />
                    </div>
                  </a>
                </td>
              </tr>
            </template>
            <template v-else>
              <tr>
                <td colspan="5">
                  Nenhum personagem encontrado.
                </td>
              </tr>
            </template>
          </template>
          <template v-else>
            <tr>
              <td colspan="5">
                <div
                  class="m-loader m-loader--brand m--padding-10"
                  v-if="busy"
                />
              </td>
            </tr>
          </template>
        </tbody>
      </table>
    </div>
  </portlet>
</template>

<script>

import shortid from 'shortid';

export default {

  data: () => {
    const id = shortid.generate();
    return {
      loaderId: id,
      characters: [],
      busy: false,
      processingService: false,
      services: [],  
    };
  },
  
  mounted() {
      this.getCharacters()
  },  

  created() {
    // Game Services
    const serviceToken = this.$route.query.service_token;
    if (serviceToken) {
      this.processGameService(serviceToken);
    }
    this.getAvailableServices();

    this.$bus.$on('update-game-characters.running', () => {
        this.busy = true
    });
    
    this.$bus.$on('update-game-characters.updated', () => {
        this.busy = false;
      this.getCharacters();
    });
  },

  methods: {

    loader(id) {
      const divLoader = document.createElement('div');
      divLoader.setAttribute('class', 'm-loader m-loader--brand');
      document.getElementById(id).appendChild(divLoader);
    },
    getCharacters() {
      this.busy = true;
      axios.post('/api/game/characters/', {
        user_id: this.$route.params.account_id,
      })
        .then(({ data }) => {
          this.characters = data.data;
          this.busy = false;

          if (data.error) {
            this.$notify({
              title: 'Oops!',
              type: 'info',
              text: data.error,
            });
          }
        })
        .catch((err) => {
          this.busy = false;
          this.$notify({
            title: 'Oops!',
            type: 'error',
            text: err.response.data.error,
          });
        });
    },

    upateGameCharacters() {
       this.busy = true;
      axios.post('/api/game/update_characters', { user_id: this.$route.params.account_id })
        .then(({ data }) => {
            this.busy = false;
          this.$notify({
            type: 'success',
            text: data.message,
          });
        })
        .catch(err => {
            this.busy = false;
            this.$notify({
                title: '',
                type: 'danger',
                text: err.response.data.message,
            });
        })
    },

    getAvailableServices() {
      axios
        .get('/api/game/game_service/available_services')
        .then(({ data }) => {
          this.services = data.data;
        });
    },

    async gameService(role, account, service, id) {
      if (this.processingService) {
        return;
      }
      if (service.route) {
        this.$router.push({
          name: `game.logservice.${service.service}`,
          params: { role_id: role },
        });
      } else {
        this.processingService = true;
        await axios
          .post('/api/game/game_service/execute_service', {
            role_id: role,
            game_account: account,
            service: service.service,
          })
          .then((response) => {
            this.processingService = false;
            this.$notify({
              title: '',
              type: 'success',
              text: response.data.message,
            });
          })
          .catch((error) => {
            this.processingService = false;
            this.$notify({
              title: '',
              type: 'danger',
              text: error.response.data.message,
            });
          });
      }
    },

    async processGameService(serviceToken) {
      await axios
        .post('/api/game/game_service/process_service', {
          service_token: serviceToken,
        })
        .then((response) => {
          this.$notify({
            title: 'Sucesso!',
            type: 'success',
            text: response.data.message,
          });
        })
        .catch((error) => {
          this.$notify({
            title: '',
            type: 'danger',
            text: error.response.data.message,
          });
        });
    },

  },

};
</script>
