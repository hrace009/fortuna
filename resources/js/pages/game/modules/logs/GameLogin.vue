<template>
    <portlet :title="'Histórico de login no jogo'">
        <div class="table-responsive">
            <table class="table table-striped m-table">
                <thead>
                    <tr>
                        <th>Data</th>
                        <th>Endereço IP</th>
                    </tr>
                </thead>
                <tbody>
                    <template v-if="!loading">
                        <tr v-for="(login, index) in items" :key="index">
                            <td>
                                {{ login.date | formatDate }}
                            </td>
                            <td>
                                {{ login.ip_address }}
                            </td>
                        </tr>
                    </template>
                    <template v-else>
                        <tr>
                            <td colspan="7">
                                <div class="m-loader m-loader--brand m--padding-10"></div>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
            <div class="col-lg-6 offset-lg-3 d-flex">
                <pagination :class="'mx-auto pagination-md'" :data="dataSet.meta" :limit=3 v-on:pagination-change-page="fetch"></pagination>
            </div>
        </div>
    </portlet>
</template>

<script>

import collection from '~/mixins/collection'
import pagination from 'laravel-vue-pagination'

export default {
   middleware: 'auth',

   mixins: [collection],

   data:() => ({
       dataSet: [],
       loading: false,
   }),

   created() {
      this.fetch()
  },

  methods: {
      fetch(page) {
          this.loading = true;
          axios.get(this.url(page)).then(this.refresh)
          this.loading = false;
      },

      url(page) {
        if (! page) {
            let query = location.search.match(/page=(\d+)/);
            page = query ? query[1] : 1;
        }
        return `/api/game/game_logs/logins/${this.$route.params.user_id}?page=${page}`;
      },

      refresh({data}) {
          this.dataSet = data;
          this.items = data.data;
      }
  },
}
</script>

<style>
    .pw__item {
        display: inline-block;
        position: relative;
    }

    .pw__item--icon {
        height: 32px;
        width: 32px;
    }

    .pw__item--amount {
        font-size: 10px;
        color: #fff;
        position: absolute;
        bottom: 2px;
        right: 2px;
        background-color: rgba(51,51,51,.65);
        padding: 2px;
        border-radius: 3px;
        min-width: 12px;
        text-align: right;
    }

</style>