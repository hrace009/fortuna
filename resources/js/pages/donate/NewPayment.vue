<template>
  <div class="col-md-4">
    <portlet :title="'Selecione a forma de pagamento'">
      <template
        class="d-flex align-items-center"
        v-if="loading"
      >
        <div class="m-loader m-loader--brand m--padding-10" />
      </template>
      <template v-else>
        <div
          v-for="(gateway, index) in gateways"
          :key="index"
        >
          <label class="kt-option">
            <span class="kt-option__control">
              <span class="kt-radio kt-radio--bold kt-radio--brand">
                <input
                  v-model="form.gateway"
                  :value="gateway.slug"
                  type="radio"
                  name="payment_method"
                  @change="update()"
                >
                <span />
              </span>
            </span>
            <span class="kt-option__label">
              <span class="kt-option__head">
                <span class="kt-option__title">{{ gateway.name }}</span>
              </span>
              <span class="kt-option__body">
                <div class="text-info kt--padding-left-10 kt--padding-bottom-10">{{ gateway.description }}</div>
                <span class="d-block kt-padding-t-10 kt-padding-b-10">{{ gateway.delivery_time }}</span>
                <img
                  :src="`https://pw4fun-painel.s3-sa-east-1.amazonaws.com/assets/pgto/${icon}.svg`"
                  v-for="(icon, index) in gateway.payment_methods"
                  :key="index"
                  class="pgto-icon"
                >
              </span>
            </span>
          </label>
        </div>
        <div class="kt--padding-10 text-center">
          <fa icon="exclamation-circle" /> Ao realizar uma doação você concorda com a <u>Política de Doações</u>
        </div>
        <div
          class="kt-loader kt-loader--brand kt--padding-10"
          v-if="paymentMethodChanged"
        />
        <new-payment-button v-else />
      </template>
    </portlet>
  </div>
</template>

<script>
import { mapGetters } from 'vuex';
import NewPaymentButton from './NewPaymentButton';

export default {
  props: ['form'],

  components: {
    NewPaymentButton,
  },

  data: () => ({
    paymentMethodChanged: false,
    gateways: [],
    loading: false,
  }),

  computed: {
    ...mapGetters({
      user: 'auth/user',
      order: 'orders/order',
    }),
  },

  created() {
    this.getAvailablePaymentGateways();
  },

  methods: {

    async getAvailablePaymentGateways() {
      this.loading = true;
      const { data } = await axios.get('/api/payment_gateways');
      this.gateways = data.data;
      this.loading = false;
    },

    async update() {
      this.paymentMethodChanged = true;
      await axios
        .patch(`/api/payment/${this.order.id}`, { gateway: this.form.gateway })
        .then(({ data: { data: transaction } }) => {
          this.$store.dispatch('orders/updateOrder', { order: transaction });
          this.paymentMethodChanged = false;
        })
        .catch((error) => {
          this.$notify({ type: 'danger', text: error.response.data.error });
          window.location.reload();
        });
    },

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
            type: 'danger',
            text: error.response.data.error,
          });
        });
    },
  },
};
</script>

<style>
.pgto-icon {
  width: 48px;
height: 32px;
margin-right: 5px;
}
</style>
