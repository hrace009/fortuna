<template>
  <div>
    <a
      href="#"
      @click.prevent="generateBillet"
      class="btn m-btn--pill m-btn--air btn-primary m-btn--wide btn-lg btn-block"
    >Pagar com PagHiper</a>
    <loading :active.sync="isLoading"
             :can-cancel="false"
             :is-full-page="true"/>
  </div>
</template>

<script>
import { mapGetters } from "vuex";
import Form from "vform";
// Import component
import Loading from 'vue-loading-overlay';
// Import stylesheet
import 'vue-loading-overlay/dist/vue-loading.css';

export default {
  name: "PaghiperCheckout",

  data: () => ({
    form: new Form({
      order: null,
      gateway: "paghiper"
    }),
    isLoading: false,
  }),

  components: {Loading},

  computed: {
    ...mapGetters({
      order: "orders/order"
    })
  },

  methods: {
    async generateBillet() {
      if (this.form.busy === true) {
        return;
      }

      this.form.order_id = this.order.id;
      this.isLoading = true;
      await this.form.patch("/api/payments").then(response => {
        this.isLoading = false;
        this.$bus.$emit("payment:success", response.data.success);
      });
    }
  }
};
</script>
