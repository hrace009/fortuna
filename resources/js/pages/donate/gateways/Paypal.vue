<template>
  <div>
    <div
      :id="id"
      class="paypal-button"
    />
    <loading
      :active.sync="isExecuting"
      :can-cancel="false"
      :is-full-page="true"
    >
      <template slot="after">
        <span class="loader-text">Processando...</span>
      </template>
    </loading>
  </div>
</template>

<script>
import shortid from 'shortid';
import paypal from 'paypal-checkout';

// Import component
import Loading from 'vue-loading-overlay';
// Import stylesheet
import 'vue-loading-overlay/dist/vue-loading.css';

const { env, sandbox, live } = window.config.paypal;

export default {
  name: 'PaypalCheckout',

  props: {
    order: {
      type: Object,
      required: true,
    },
  },

  components: { Loading },

  data: () => {
    const id = shortid.generate();
    return {
      id,
      token: '',
      isExecuting: false,
    };
  },

  mounted() {
    const self = this;
    // const buttonstyle = validator(this.buttonstyle, styleOptions, styleDefault)
    paypal.Button.render(
      {
        env,

        style: {
          label: 'pay',
          size: 'large', // tiny, small, medium
          color: 'blue', // orange, blue, silver
          shape: 'pill', // pill, rect
        },

        client: {
          sandbox,
          production: live,
        },

        commit: true,

        payment: () => new paypal.Promise((resolve, reject) => {
          axios
            .patch('/api/payments', {
              order_id: this.order.id,
              gateway: 'paypal',
            })
            .then((response) => {
              this.token = response.data.token;
              resolve(response.data.code);
            })
            .catch((err) => {
              console.log(err);
              const {
                status,
                data: { name, message },
              } = err.response;

              if (status === 400) {
                this.$notify({
                  type: 'error',
                  title: name,
                  text: message,
                });
              }
              reject(err)
            });
        }),

        onAuthorize: (data) => {
          this.isExecuting = true;
          axios
            .post('/api/paypal/execute', {
              paymentID: data.orderID,
              payerID: data.payerID,
              token: this.token,
              gateway: 'paypal',
            })
            .then(({ data }) => {
              this.$bus.$emit('payment:success', data .success);
              this.isExecuting = false;
            })
            .catch((err) => {
              this.isExecuting = false;
              const {
                status,
                data: { name, message },
              } = err.response;
              if (status === 400) {
                this.$notify({
                  type: 'error',
                  title: name,
                  text: message,
                });
              }
            });
        },
        onCancel: () => {
          this.$notify({
            type: 'info',
            text: 'Você cancelou a operação de pagamento.',
          });
        },
      },
      this.id,
    );
  },
};
</script>

<style>
  .loader-text {
    display: flex;
    justify-content: center;
    text-align: center;
    position: absolute;
    width: 100%;
    margin-top: 15px;
  }
</style>
