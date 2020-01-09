<template>
<portlet :title="'Minhas contas de jogo'" :button="button">
        <div
            slot="button"
            class="kt-portlet__head-actions"
        >
            <div class="btn-group m-btn-group">
                <a  data-toggle="modal" data-target="#create_game_account" class="btn btn-label-primary btn-bold btn-icon-h kt-margin-l-10">Criar nova conta</a>
            </div>
        </div>

    <!-- begin:: Create a new game account Modal -->
    <div class="modal fade" id="create_game_account" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Criar nova conta de jogo</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <div class="modal-body">
                    <register-game />   
                </div>
            </div>
        </div>
    </div>
    <!-- end:: Create game account Modal -->
    <template v-if="!loading">
        <template v-if="!loading && items.length > 0">
            <table class="table m-table m-table--head-no-border">
                <thead>
                    <tr>
                        <th class="m--align-left">Conta</th>
                        <th>&nbsp;</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(user, index) in items" :key="index">
                        <td class="m--align-left m--padding-top-20">
                            <!--<router-link :to="{ name: 'game.view', params: { account_id: user.id }}" :class="'m-link m-font-bold'">
                                {{ user.name }}
                            </router-link>-->
                            {{ user.name }}
                        </td>
                        <td>
                            <router-link :to="{ name: 'game.password', params: { account: user.id } }" class="m-link">
                                Alterar Senha
                            </router-link>
                            <!-- <router-link :to="{ name: 'game.guildicon', params: { account_id: user.id } }" class="m-link">
                               Emblema de Clã
                             </router-link>-->
                        </td>
                    </tr>
                </tbody>
            </table>
        </template>
        <template v-else>
            <div class="kt-align-center">
                <p class="kt-padding-t-20 kt-font-bold">
                    Você ainda não criou contas de jogo.
                </p>
            </div>
        </template>
    </template>
    <template v-else>
        <div class="d-flex justify-content-center">
            <div class="kt-spinner kt-spinner--v2 kt-spinner--lg kt-spinner--info"></div>
        </div>
    </template>
</portlet>
</template>

<script>
import collection from '~/mixins/collection'
import RegisterGame from '~/pages/game/register'

import {
  mapGetters
} from 'vuex'

export default {
  middleware: 'auth',

  mixins: [collection],

  data: () => ({
    dataSet: false,
    button: true,
    accounts: [],
    loading: false
  }),

   components: {
       RegisterGame
   },

  computed: {
    ...mapGetters({
      user: 'auth/user'
    })
  },

  created () {
    this.fetch()
  },

  methods: {
    fetch (page) {
      this.loading = true
      axios.get(this.url(page)).then(this.refresh)
    },

    url (page) {
      if (!page) {
        let query = location.search.match(/page=(\d+)/)
        page = query ? query[1] : 1
      }

      return `/api/game_accounts/all/paginate?page=${page}`
    },
    refresh ({
      data
    }) {
      this.dataSet = data
      this.items = data.data
      this.loading = false
    },

    async send (user) {
      this.$notify({
        title: 'Oops!',
        type: 'error',
        text: 'Indisponível no momento'
      })
    }
  }
}
</script>
