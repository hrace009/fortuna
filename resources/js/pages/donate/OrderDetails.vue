<template>
  <div>
    <portlet :title="'Detalhes do pedido'">
      <div class="d-flex flex-wrap">
        <!-- Media -->
        <div class="d-flex flex-grow-1">
          <img
            src="https://pw4fun-painel.s3-sa-east-1.amazonaws.com/assets/svg/coins.svg"
            width="64"
          >
          <div class="flex-1 kt-margin-l-15 kt-padding-t-20">
            <h4 class="font-weight-bold text-primary">
               {{ order.product }} ({{ order.value_cash }} Cubi-Gold)
            </h4>
            <span>
              <span class="m--font-bold">Vai ser depositado na conta: <strong><u>{{ order.game_account }}</u></strong></span>
            </span>
          </div>
          <div class="m--padding-top-20">
            <h3>{{ order.value }}</h3>
            <small><a
              href="#"
              class="m-link underline float-right"
              @click.prevent="cancelOrder"
            >cancelar</a></small>
          </div>
        </div>
      </div>
    </portlet>
  </div>
</template>


<script>
export default {
  middleware: 'auth',

  props: ['order'],

  methods: {
    async cancelOrder() {
      await axios
        .delete(`/api/donate/${this.order.id}`)
        .then((response) => {
          this.$store.dispatch('orders/updateOrder', {
            order: null,
          });
          this.$notify({
            title: 'Sucesso!',
            type: 'success',
            text: 'Pedido cancelado com sucesso.',
          });
        })
        .catch((error) => {
          const { status } = error.response;

          if (status === 422) return;

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

<style lang="scss">
.text-featured {
  font-size: 1em;
}

.order--details {
  margin-bottom: 10px;

  > div {
    margin-bottom: 5px;
  }
  padding-left: 1em;
  padding-right: 1em;
  .title {
    font-weight: 600;
  }
  .order--value {
    font-size: 1.8em;
  }
}
</style>
