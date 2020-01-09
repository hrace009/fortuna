<template>
    <portlet :title="'Itens coletados'">
        <div class="table-responsive">
            <table class="table table-striped m-table table-wmdev">
                <thead>
                    <tr>
                        <th>Data</th>
                        <th>Personagem (Dropou)</th>
                        <th>Item</th>
                        <th>Personagem (Coletou)</th>
                    </tr>
                </thead>
                <tbody>
                    <template v-if="!loading">
                        <tr v-for="(item, index) in items" :key="index">
                            <td>
                                {{ item.date | formatDate }}
                            </td>
                            <td>
                            {{ item.role_name_dropped }}
                            </td>
                            <td>
                                 <div class="pw__item" v-tippy="{ html: `#tooltip_${item.item_id}`, arrow: true, animation: 'perspective', theme: 'dark' }">
                                    <img :src="item.item_icon" class="pw__item--icon">
                                    <span class="pw__item--amount">{{ item.item_count }}</span>
                                </div>
                                <div :id="`tooltip_${item.item_id}`" style="display: none">
                                   
                                </div>
                            </td>
                            <td>
                                {{ item.role_name_collected }}
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
        return `/api/game/logs_service/game_loots/${this.$route.params.role_id}?page=${page}`;
      },

      refresh({data}) {
          this.dataSet = data;
          this.items = data.data;
      }
  },
}
</script>
