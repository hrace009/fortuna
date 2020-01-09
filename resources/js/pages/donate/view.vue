<template>
    <portlet>
        <div>
    <template v-if="loading">
        <div class="m-loader m-loader--brand m--padding-10"></div>
    </template>
    <template v-else>
        <h4 class="m--margin-bottom-20">Detalhes</h4>
        <table class="table table-striped m-table">
            <tbody>
                <tr>
                    <th>n° do Pedido</th>
                    <th> {{ order.id }} </th>
                </tr>
                <tr>
                    <th>Data do pedido</th>
                    <th>{{ order.created_at }}</th>
                </tr>
                 <tr v-if="order.delivered_at">
                    <th>Data entrega</th>
                    <th>{{ order.delivered_at }}</th>
                </tr>
                <tr>
                    <th>Status do pedido</th>
                    <th>
                        <span
                  :class="`m-badge m-badge--${order.status.color} m-badge--wide`"
                >{{ order.status.label }}</span>
                    </th>
                </tr>
                <tr>
                    <th>Valor em GOLD(s)</th>
                    <th>{{ order.value_cash }} GOLD</th>
                </tr>
                <tr>
                    <th>Valor pago</th>
                    <th>{{ order.value }}</th>
                </tr>
                <tr>
                    <th>Forma de pagamento</th>
                    <th>{{ order.gateway }}</th>
                </tr>
                <tr>
                    <th>Código de transação</th>
                    <th>{{ order.transaction_code }}</th>
                </tr>
            </tbody>
        </table>
        <h4 class="m--margin-top-30 m--margin-bottom-20">Histórico de eventos do pedido</h4>
        <div class="kt-timeline-v2">
            <div class="kt-timeline-v2__items  kt-padding-top-25 kt-padding-bottom-30">
                <div v-for="(audit, index) in order.history" :key="index" class="kt-timeline-v2__item">
                    <div class="kt-timeline-v2__item-time kt-font-sm d-flex">
                        {{ audit.created_at }}
                    </div>
                    <div class="kt-timeline-v2__item-cricle">
                        <i :class="`fa fa-genderless kt-font-${audit.type}`"/>
                    </div>
                    <div class="kt-timeline-v2__item-text  kt-padding-top-5" v-html="audit.event"></div>
                </div>
            </div>
        </div>
        <b-modal id="boleto-modal" centered hide-footer v-if="order.billet">
                <div slot="modal-header">
                    <h5 class="modal-title">Boleto #{{ order.id}}</h5>
                </div>
                <p class="text-center">Copie e cole no campo para pagamento no site de seu banco:</p>
                <div class="border py-2 px-3 text-center">{{ order.billet.digitable_line }}</div>
                <div class="m-divider m--margin-top-10 m--margin-bottom-5">
                    <span></span>
                    <span>OU</span>
                    <span></span>
                </div>
                <div class="d-flex justify-content-center">
                     <a :href="order.billet.billet" class="btn btn-secondary" target="_blank">Imprimir Boleto</a>
                </div>
        </b-modal>
    </template>
</div>
    </portlet>
</template>

<script>

export default {
  middleware: 'auth',

   data: () => ({
     order: {},
     loading: true,
  }),

  created() {
      this.getOrder();
  },

  methods: {
      async getOrder() {
          await axios.get(`/api/donate/${this.$route.params.id}`)
          .then((response) => {
              this.loading = false;
              this.order = response.data.data;
          }).catch(error => {
               this.$notify({ title: 'Oops!', type: 'error', text: error.response.data.error });
               this.$router.push({ name: 'donate.history' })
          })
      },
  },

  computed: {
      getPaymentMethod () {

          if (! this.order) {
              return;
          }

          let paymentMethod = this.order.payment_method;
          let paymentMethodType = this.order.payment_method_type
          return `
            ${this.order.installments}x no ${this.order.payment_method_type.toLowerCase()} <img width="32px" height="24px" src="${paymentMethod.url}" alt="${paymentMethod.name}">
            ${this.displayBillet}
          `
      },

      displayBillet() {
          if (! this.order || ! this.order.billet) {
              return '';
          }

          if (this.order.billet) {
              return `<a href="#" data-toggle="modal" data-target="#boleto-modal" class="m-link">(Ver boleto)</a>`
          }
      },
  },
}
</script>
