<template>
  <div class>
    <div class="d-flex flex-row m--margin-bottom-15">
      <h5 class="m--margin-bottom-20 flex-grow-1">
        Meus Personagens
      </h5>
      <div v-if="characters.length">
        <button
          @click="upateGameCharacters"
          title="Ultima atualização 16/10/2019, 8:45"
          v-tippy
          class="btn btn-sm btn-brand"
          :disabled="updateBusy"
          v-if="!updateBusy"
        >
          Atualizar personagens
        </button>
        <div
          class="m-loader m-loader--brand m--padding-10"
          v-if="updateBusy"
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
                  class="btn btn-sm btn-rounded btn-grey"
                  :disabled="busy"
                  @click="gameService(role.id, role.game_account, service)"
                >
                  <i :class="service.icon" />
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
                v-if="updateBusy"
              />
            </td>
          </tr>
        </template>
      </tbody>
    </table>
  </div>
</template>

<script>
import Form from 'vform';
import { mapGetters } from 'vuex';

export default {
  middleware: 'auth',

  name: 'Characters',

  props: {
    characters: {
      type: Array,
      default: Function,
    },
  },

  data: () => ({
    form: new Form({
      role_id: '',
    }),
    services: [],
    busy: false,
    updateBusy: false,
  }),

  computed: mapGetters({
    user: 'auth/user',
  }),

  created() {
    const serviceToken = this.$route.query.service_token;
    if (serviceToken) {
      this.processGameService(serviceToken);
    }
    this.getAvailableServices();
  },

  methods: {
    async getAvailableServices() {
      await axios
        .get('/api/game/game_service/available_services')
        .then(({ data }) => {
          this.services = data.data;
        });
    },

    upateGameCharacters() {
      this.updateBusy = true;
      axios.post('/api/game/update_characters', { user_id: this.$route.params.account_id })
        .then(({ data }) => {
          this.updateBusy = false;
          this.$bus.$emit('characters-load', { sucess: true });
          this.$notify({
            type: 'success',
            text: data.message,
          });
        });
    },

    async gameService(role, account, service) {
      if (this.busy) {
        return;
      }
      if (service.route) {
        this.$router.push({
          name: `game.logservice.${service.service}`,
          params: { role_id: role },
        });
      } else {
        this.busy = true;
        await axios
          .post('/api/game/game_service/execute_service', {
            role_id: role,
            game_account: account,
            service: service.service,
          })
          .then((response) => {
            setTimeout(() => {
              this.busy = false;
            }, 5000);
            this.$notify({
              title: '',
              type: 'success',
              text: response.data.message,
            });
          })
          .catch((error) => {
            setTimeout(() => {
              this.busy = false;
            }, 5000);
            this.$notify({
              title: '',
              type: 'error',
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
            type: 'error',
            text: error.response.data.message,
          });
        });
    },
  },
};
</script>
