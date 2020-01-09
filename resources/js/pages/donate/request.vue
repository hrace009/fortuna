<template>
  <div class="row">
    <template>
      <div
        class="col-md-12"
        v-if="!order && !orderProcessed"
      >
        <document v-if="! this.user.document" />
        <portlet v-else>
          <form
            @submit.prevent="submit"
            @keydown="form.onKeydown($event)"
            :class="'kt-form kt-form--state'"
          >
            <!-- begin:: order-success -->
            <!-- end:: order-success -->
            <!-- being:: order-details -->
            <!-- end:: order-details -->
            <!-- begin:: order-items -->
            <div
              class="kt-form__section kt-form__section--first"
              v-if="!order && !orderProcessed "
            >
              <div class="kt-form__heading">
                <h3 class="kt-form__heading-title">
                  Nova Doação
                </h3>
              </div>
              <div class="row kt-margin-t-20">                
                <!-- GOLDs -->
                <div class="col-md-4">
                  <div class="form-group kt-form__group">
                    <label>Escolha o pacote: <span class="text-danger">*</span></label>
                   <multiselect
                    v-model="selectedGoldPackage"
                    :options="goldPackages"
                    track-by="name"
                    :custom-label="customLabel"
                    placeholder="Selecione o pacote que deseja adquirir"
                    open-direction="bottom"
                    @select="updateSelectedGoldPackage"
                  />
                  </div>
                </div>
              </div>
              <!-- Account -->
              <div
                class="kt-form__section kt-form__section--second"
                v-if="! order"
              >
                <!--- Conta -->
                <div
                  class="form-group kt-form__group"
                  :class="{ 'has-danger': form.errors.has('game_account') }"
                >
                  <label>
                    Escolha a conta
                    <span class="text-danger">*</span>
                  </label>
                  <multiselect
                    v-model="form.game_account"
                    :options="accounts"
                    track-by="name"
                    :custom-label="accountLabel"
                    placeholder="Selecione a conta que vai receber os golds"
                    open-direction="bottom"
                  />
                  <has-error
                    :form="form"
                    field="game_account"
                  />
                </div>
              </div>
            </div>
            <!-- end:: order->items -->
            <!-- begin:: payment-options -->
            <div
              class="kt--align-right m--margin-top-30"
              v-if="!orderProcessed"
            >
              <v-button
                :class="'btn m-btn--pill m-btn--air btn-primary m-btn--icon m-btn--wide btn-lg'"
                @click.once="submit"
                :loading="form.busy"
                v-if="!order"
              >
                Continuar
                <fa
                  icon="arrow-right"
                  fixed-width
                />
              </v-button>
            </div>
            <!-- end:: payment-options -->
          </form>
        </portlet>
      </div>
      <div class="col-md-8">
        <order-details
          :order="order"
          v-if="order && !orderProcessed"
        />
      </div>
      <new-payment
        :form="form"
        v-if="order && !orderProcessed"
      />
      <order-success v-if="orderProcessed" />
    </template>
  </div>
</template>

<script>
import Form from 'vform';
import 'vue-multiselect/dist/vue-multiselect.min.css';
import Multiselect from 'vue-multiselect';
import { mapGetters } from 'vuex';
import OrderDetails from './OrderDetails.vue';
import OrderSuccess from './OrderSuccess.vue';
import FetchGameAccounts from '~/mixins/game_accounts';
import NewPayment from './NewPayment.vue';
import Document from './document.vue';
import currencyFormatter from 'currency-formatter';


export default {
  middleware: 'auth',

  components: {
    OrderDetails,
    OrderSuccess,
    NewPayment,
    Multiselect,
    Document,
  },

  mixins: [FetchGameAccounts],

  metaInfo() {
    return {
      title: 'Nova Doação',
    };
  },

  data: () => ({
    form: new Form({
      gold_package: null,
      game_account: null,
    }),
    goldPackages: [],
    selectedGoldPackage: null,
    orderProcessed: false,
    display: false,
  }),

  computed: {
    ...mapGetters({
      user: 'auth/user',
      order: 'orders/order',
    }),
  },

  created() {
    this.getOrder();
    this.getGoldPackages();
  },

  mounted() {
    this.$bus.$on('payment:success', (success) => {
      if (success) {
        this.orderProcessed = true;
        this.$store.dispatch('orders/updateOrder', {
          order: null,
        });
      }
    });
  },

  methods: {
    
    getOrder() {
      axios.get('/api/donate/create')
        .then((response) => {
          const { data } = response.data;

          if (!data) {
            this.$store.dispatch('orders/updateOrder', { order: null });
            return;
          }

          if (!this.order) {
            this.$store.dispatch('orders/updateOrder', {
              order: data,
            });
          }
          this.form.gateway = response.data.data.gateway_slug;
        });
    },

    async getGoldPackages() {
      axios.get('/api/gold_packages')
      .then(({ data}) => {
          this.goldPackages = data.data;
      })
      .catch((err) => {
         this.$notify({
            title: 'Oops!',
            type: 'danger',
            text: 'Ocorreu um erro ao obter os pacotes de gold.',
          });
      })
    },

    async submit() {
      if (this.form.busy === true) {
        return;
      }

      if (this.order) {
        return;
      }

      this.form
        .post('/api/donate')
        .then((response) => {
          // If the vue data is empty, we'll update the data
          if (!this.order) {
            this.form.gateway = response.data.data.gateway_slug;
            this.$store.dispatch('orders/updateOrder', {
              order: response.data.data,
            });

            window.analytics.track('Checkout Started', {
              product_id: this.order.id,
              name: `${this.order.value_cash} Cubi Gold`,
              price: this.order.value,
              quantity: 1,
            });
          }
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

    customLabel({ name, price, golds }) {
        return `${name} - ${this.currency(price)} - ${this.golds(golds)}`
    },

    accountLabel({ name }) {
      return name;
    },

    updateSelectedGoldPackage({ id, name, price, golds }) {
       this.form.gold_package = id;
    },

    currency (value) {
      return new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(value)
    },

    golds (value) {
      return new Intl.NumberFormat('pt-BR', { style: 'decimal' }).format(value)
    }
  },
};
</script>