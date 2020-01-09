<template>
    <div>
        <!-- begin:: welcome -->
        <div class="kt-portlet">
            <!-- begin head -->
            <div class="kt-portlet__body d-flex">
                <div class="kt-padding-t-20 flex-1">
                    <h5>Bem vindo {{ firstName }}!</h5>
                    <p class="kt-margin-t-20 kt-margin-b-10 lead-md">
                        Desenvolvemos este painel para simplificar a maneira como
                        você administra sua conta.
                    </p>
                    <p class="lead-md">
                        Você agora tem uma <strong>conta mestre</strong>, com ela, você pode
                        <mark>
                            <router-link
                                :to="{ name: 'game.accounts' }"
                                class="kt-link"
                            >criar
                            </router-link>
                        </mark>
                        contas de jogo ilimitadas, e gerenciar
                        todas as informações da sua conta em <strong>único lugar.</strong>
                    </p>
                </div>
            </div>
        </div>
        <!-- end:: welcome -->
        <!-- begin:: last donation -->
        <div class="row">
            <div class="col-md-6">
                <div class="kt-portlet">
                    <!-- begin head -->
                    <div class="kt-portlet__body">
                        <div class="flex-1">
                            <h4>Última doação</h4>
                            <div
                                class="kt-loader kt-loader--brand kt--padding-10"
                                v-if="busy"
                            />
                            <div v-else>
                                <template v-if="!order">
                                    <div class="kt--align-center">
                                        <img
                                            src="https://pw4fun-painel.s3.sa-east-1.amazonaws.com/assets/svg/billing.svg"
                                            width="280px"
                                            height="190px"
                                        />
                                        <p
                                            class="kt--padding-top-20 kt--font-bold"
                                        >Você ainda não fez nenhuma doação para o servidor.</p>
                                        <router-link
                                            class="btn kt-btn--pill kt-btn--air btn-primary kt-btn--wide kt--margin-top-5"
                                            :to="{ name: 'donate.make'}"
                                        >Fazer Doação
                                        </router-link>
                                    </div>
                                </template>
                                <template v-else>
                                    <div
                                        class="latest-order"
                                    >
                                        <div class="d-flex justify-content-between latest-order__header">
                                            <span>{{ this.order.id.slice(0, 8) }}</span>
                                            <span class="text-right">{{ this.order.created_at | formatDateDistance }}</span>
                                        </div>
                                        <img
                                            src="https://pw4fun-painel.s3.sa-east-1.amazonaws.com/assets/svg/pig.svg"
                                            width="180"
                                            style="margin-top: 30px;"
                                        >
                                        <div class="latest-order__details">
                                            <div class="justify-content-start">
                                                <h4 class="title">
                                                    Você doou
                                                </h4>
                                                <span class="value">{{ this.order.value }}</span>
                                            </div>
                                            <div class="kt--margin-left-50">
                                                <h4 class="title">
                                                    convertidos em
                                                </h4>

                                                <span class="value">
                          {{ this.order.value_cash }}
                          <small class="kt--font-sm">cubi gold</small>
                        </span>
                                            </div>
                                        </div>
                                        <hr class="kt--margin-top-10">
                                        <router-link
                                            :to="{ name: 'donate.history' }"
                                            class="kt-link kt-link-brand"
                                        >
                                            Acesse o histórico de doações
                                        </router-link>
                                    </div>
                                </template>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- end:: last donation -->
    </div>
</template>

<script>
    import {mapGetters} from 'vuex';
    import  axios from 'axios';

    export default {
        middleware: 'auth',

        metaInfo() {
            return {
                title: 'Página Inicial',
            };
        },

        data: () => ({
            button: true,
            projectName: window.config.appName,
            busy: false,
            order: {},
        }),

        created() {
            this.getLatestOrder();
        },

        mounted() {
            window.analytics.identify(this.user.id, {
                name: this.user.name,
                email: this.user.email,
            });
        },

        computed: {
            ...mapGetters({
                user: 'auth/user',
            }),

            firstName() {
                if (!this.user) {
                    return;
                }
                return this.user.first_name;
            },
        },

        methods: {
            async getLatestOrder() {
                this.busy = true;
                await axios.get('/api/user/latest_order').then((response) => {
                    this.order = response.data.data;
                    this.busy = false;
                });
            },
        },
    };
</script>
