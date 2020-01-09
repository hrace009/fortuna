<template>
  <div class="m-form__section m-form__section--last">
    <div class="m-form__heading">
      <h3 class="m-form__heading-title">
        <fa icon="credit-card" fixed-width/>Formas de Pagamento
      </h3>
    </div>
    <!-- begin:: Payment Options -->
    <div :class="{ 'has-danger': form.errors.has('gateway') }" class="m-form__group form-group row">
      <div v-for="(gateway, index) in order.available_payments" :key="index" class="col-lg-6">
        <label class="m-option">
          <span class="m-option__control">
            <span class="m-radio m-radio--brand m-radio--check-bold">
              <input
                v-model="form.gateway"
                :value="gateway.name.toLowerCase()"
                type="radio"
                name="payment_method"
                @change="update()"
              >
              <span/>
            </span>
          </span>
          <span class="m-option__label">
            <span class="m-option__head">
              <span class="m-option__title">{{ gateway.name }}</span>
            </span>
            <span class="m-option__body">
              <i
                v-for="(icon, index) in gateway.payment_methods"
                :class="`payment-icon-${icon}`"
                class="payment-icon"
              />
            </span>
          </span>
        </label>
      </div>
    </div>
    <!-- end:: Payment Options -->
    <!-- begin:: Payment Buttons -->
    <div class="d-flex m--margin-top-10">
      <div class="justify-content-start">
        <button
          :class="'btn m-btn--pill  btn-outline-danger btn-outline m-btn--icon btn-lg m-btn--wide m--margin-right-10'"
          @click.once="cancelOrder"
        >Cancelar</button>
      </div>
      <div class="d-flex flex-1 justify-content-end">
        <div v-if="paymentMethodChanged">
          <fa spin :icon="['fas', 'spinner']" :size="'4x'"/>
        </div>
        <div v-else>
          <pagseguro-checkout v-if="order.gateway === 'pagseguro'" :order="order"/>
          <paypal-checkout v-if="order.gateway === 'paypal'" :order="order"/>
          <paghiper-checkout v-if="order.gateway === 'pagarhip'"/>
          <picpay-checkout v-if="order.gateway === 'picpay'" :order="order"/>
          <xsolla-checkout v-if="order.gateway === 'xsolla'" :order="order"/>
        </div>
      </div>
    </div>
    <!-- end:: Payment Buttons -->
  </div>
</template>

<script>
import PaypalCheckout from "./gateways/Paypal";
import PaghiperCheckout from "./gateways/Paghiper";
import { mapGetters } from "vuex";

export default {
  components: {
    PaypalCheckout,
    PagseguroCheckout,
    PaghiperCheckout,
    PicpayCheckout,
    XSollaCheckout,
  },

  props: {
    form: {
      required: true,
      type: Object
    }
  },

  data: () => ({
    gateways: [],
    paymentMethodChanged: false
  }),

  computed: {
    ...mapGetters({
      user: "auth/user",
      order: "orders/order"
    })
  },

  methods: {
    async update() {
      this.paymentMethodChanged = true;
      await axios
        .patch(`/api/payment/${this.order.id}`, { gateway: this.form.gateway })
        .then(({ data }) => {
          this.$store.dispatch("orders/updateOrder", { order: data });
          this.paymentMethodChanged = false;
        });
    },

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
