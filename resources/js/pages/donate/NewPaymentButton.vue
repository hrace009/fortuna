<template>
  <div class="d-flex m--margin-top-20">
    <div v-if="paymentMethodChanged">
        <fa spin :icon="['fas', 'spinner']" :size="'4x'" />
      </div>
      <div v-else>
        <paypal-checkout v-if="order.gateway_slug === 'paypal'" :order="order" />
        <paghiper-checkout v-if="order.gateway_slug === 'paghiper'" />
        <mercadopago-checkout v-if="order.gateway_slug === 'mercadopago'" />
      </div>
  </div>
</template>

<script>
import PaypalCheckout from "./gateways/Paypal";
import PaghiperCheckout from "./gateways/Paghiper";
import MercadopagoCheckout from "./gateways/MercadoPago";
import { mapGetters } from "vuex";

export default {
  components: {
    PaypalCheckout,
    PaghiperCheckout,
    MercadopagoCheckout,
  },

  data: () => ({
    paymentMethodChanged: false
  }),

  computed: {
    ...mapGetters({
      user: "auth/user",
      order: "orders/order"
    })
  },

  methods: {

    async cancelOrder() {
      await axios
        .delete(`/api/donate/${this.order.id}`)
        .then(response => {
          this.$store.dispatch("orders/updateOrder", {
            order: null
          });
          this.$notify({
            title: "Sucesso!",
            type: "success",
            text: "Pedido cancelado com sucesso."
          });
        })
        .catch(error => {
          const { status } = error.response;

          if (status === 422) return;

          this.$notify({
            title: "Oops!",
            type: "error",
            text: error.response.data.error
          });
        });
    }
  }
};
</script>
