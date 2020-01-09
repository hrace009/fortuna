<template>
    <div>
        <div :id="id" ref="mpButton"></div>
        <a
                href="#"
                @click.prevent="generateBillet"
                class="btn m-btn--pill m-btn--air btn-info m-btn--wide btn-lg btn-block"
        >Pague com Mercado Pago</a>
        <loading :active.sync="isLoading"
                 :can-cancel="false"
                 :is-full-page="true"/>
    </div>
</template>

<script>
    import {mapGetters} from "vuex";
    import Form from "vform";
    import shortid from "shortid";
    // Import component
    import Loading from 'vue-loading-overlay';
    // Import stylesheet
    import 'vue-loading-overlay/dist/vue-loading.css';

    export default {
        name: "MercadopagoCheckout",

        props: {
            orderData: {
                type: Object,
                required: false
            }
        },

        components: {Loading},

        data: () => ({
            id: shortid.generate(),
            form: new Form({
                order: null,
                gateway: "mercadopago"
            }),
            isLoading: false,
        }),

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

                    const { success, url } = response.data;

                    window.open(url, 'blank');

                    this.isLoading = false;

                    this.$bus.$emit('payment:success', success);
                });
            },
        }
    };
</script>
