<template>
  <portlet :title="'Meus pedidos'">
    <div class="wrapper">
      <template v-if="!loading">
      <template v-if="!loading && items.length > 0">
        <table class="table">
          <thead>
            <tr>
              <th class="text-left">Protocolo</th>
              <th>Produto</th>
              <th>Valor Pago</th>
              <th class="text-center">Meio de Pagamento</th>
              <th>Status</th>
              <th>Data</th>
              <th>Data Entrega</th>
              <th>&nbsp;</th>
            </tr>
          </thead>
          <tbody class="body" style="height: 520px; max-height: 520px">
            <tr v-for="(order, $index) in items" :key="$index">
              <td class="text-left">{{ order.id.slice(0, 8) }}</td>
              <td class="kt-font-bold">{{ order.product }} ({{ order.value_cash }} GOLDs)</td>
              <td> {{ order.value }}</td>
              <td class="text-center">{{ order.gateway }}</td>
              <td>
                <span
                  :class="`btn btn-bold btn-sm btn-font-sm  btn-label-${order.status.color}`"
                >{{ order.status.label }}</span>
              </td>
              <td>{{ order.created_at | formatDate }}</td>
              <td v-if="order.delivered_at">{{ order.delivered_at | formatDateWithHours }}</td>
                <td v-else>–</td>
              <td>
               <!-- <router-link
                  :to="{ name: 'donate.view', params: { id: order.id } }"
                  :class="`btn m&#45;&#45;font-metal m-btn&#45;&#45;hover-metal m-btn&#45;&#45;pill m-btn m-btn&#45;&#45;icon m-btn&#45;&#45;icon-only has-tooltip`"
                  v-tippy="{ position: 'top' }"
                  title="Ver detalhes"
                >
                  <i class="la la-eye"></i>
                </router-link>-->
                <span v-if="order && order.billet">
                  <a
                    href="#"
                    @click="displayBillet(order.billet.link)"
                    class="btn btn-sm btn-light btn-icon btn-icon-md"
                    title="Ver boleto"
                    v-tippy
                  >
                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon kt-svg-icon--dark">
                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                            <rect x="0" y="0" width="24" height="24"/>
                            <rect fill="#000000" opacity="0.3" x="2" y="5" width="20" height="14" rx="2"/>
                            <rect fill="#000000" x="2" y="8" width="20" height="3"/>
                            <rect fill="#000000" opacity="0.3" x="16" y="14" width="4" height="2" rx="1"/>
                        </g>
                    </svg>
                  </a>
                </span>
              </td>
            </tr>
          </tbody>
        </table>
        <div class="col-lg-6 offset-lg-3 d-flex">
            <pagination
              :class="'mx-auto pagination-md'"
              :data="dataSet.meta"
              :limit="3"
              v-on:pagination-change-page="getOrders"
            ></pagination>
          </div>
      </template>
      <template v-else>
        <div class="m--align-center">
          <img
            src="https://pw4fun-painel.s3.sa-east-1.amazonaws.com/assets/svg/billing.svg"
            width="280px"
            height="190px"
          />
          <p
            class="m--padding-top-20 m--font-bold"
          >Você ainda não fez nenhuma doação para o servidor.</p>
          <router-link
            class="btn m-btn--pill m-btn--air btn-primary m-btn--wide m--margin-top-5"
            :to="{ name: 'donate.make'}"
          >Fazer Doação</router-link>
        </div>
      </template>
      </template>
      <template v-else>
          <div class="d-flex justify-content-center">
              <div class="kt-spinner kt-spinner--v2 kt-spinner--lg kt-spinner--info"></div>
          </div>
      </template>
    </div>
  </portlet>
</template>

<script>
import collection from "~/mixins/collection";
import { mapGetters } from "vuex";

export default {
  middleware: "auth",

  mixins: [collection],

  data: () => ({
    dataSet: false,
    loading: true
  }),

  computed: {
    ...mapGetters({
      user: "auth/user"
    })
  },

  created() {
    this.$bus.$on("payment-completed", this.getOrders);
    this.getOrders();
  },

  methods: {
    getOrders(page) {
      this.loading = true;
      axios.get(this.url(page)).then((response) => {
        this.refresh(response);
        this.loading = false;
      });
    },

    url(page) {
      if (!page) {
        let query = location.search.match(/page=(\d+)/);
        page = query ? query[1] : 1;
      }

      return `/api/donate?page=${page}`;
    },
    refresh({ data }) {
      this.dataSet = data;
      this.items = data.data;
      window.scrollTo(0, 0);
    },

    displayBillet(url) {
      var h = window.innerHeight;
      var w = window.innerWidth;
      window.open(url, "_blank", "height=" + h + ",width=" + w / 2);
    }
  }
};
</script>

<style>
.details {
  padding-top: 1.5rem;
  padding-bottom: 3rem;
  padding-left: 3rem;
  padding-right: 3rem;
  background-color: #05191d;
}
</style>
