<template>
    <div class="row">
        <div class="col-md-12">
            <portlet :title="'Histórico de segurança'" :subtitle="'Os eventos mais recentes da sua conta'">
                <template v-if="!loading">
                    <template v-if="!loading && items.length > 0">
                        <div class="table-responsive">
                            <table class="table m-table m-table--head-no-border">
                                <thead>
                                <tr>
                                    <th class="m--align-left">Ação</th>
                                    <th class="m--align-left">Endereço IP</th>
                                    <th class="m--align-right">Data</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="(audit, index) in items" :key="index" style="cursor: pointer;">
                                    <td class="m--align-left">{{ audit.name }}</td>
                                    <td class="m--align-left">{{ audit.actor_ip }}</td>
                                    <td class="m--align-right" :title="audit.created_at | formatDateWithHours"
                                        v-tippy="{position: 'bottom'}">{{ audit.created_at | formatDateDistance }}
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-lg-6 offset-lg-3 d-flex">
                            <pagination :class="'mx-auto pagination-md'" :data="dataSet.meta" :limit=3
                                        v-on:pagination-change-page="audit"></pagination>
                        </div>
                    </template>
                </template>
                <template v-else>
                    <div class="d-flex justify-content-center">
                        <div class="kt-spinner kt-spinner--v2 kt-spinner--lg kt-spinner--info"></div>
                    </div>
                </template>
            </portlet>
        </div>
    </div>
</template>
<script>
    import Form from "vform";
    import {mapGetters} from "vuex";
    import collection from "~/mixins/collection";
    import pagination from "laravel-vue-pagination";
    import TwoFactorAuth from "./TwoFactorAuth";

    export default {
        middleware: "auth",

        mixins: [collection],

        data: () => ({
            form: new Form({
                type: "primary",
                method: "authenticator"
            }),
            formBackup: new Form({
                type: "backup",
                method: "recovery_codes"
            }),
            formVerify: new Form({
                password: ""
            }),
            tfaQrCode: "",
            recoveryCodes: [],
            dataSet: {},
            loading: false
        }),

        components: {
            TwoFactorAuth
        },

        computed: {
            ...mapGetters({
                user: "auth/user"
            }),

            primaryMethod() {
                //
            }
        },

        created() {
            this.audit();
        },

        methods: {
            audit(page) {
                this.loading = true;
                axios.get(this.url(page)).then((response) => {
                    this.refresh(response);
                    this.loading = false;
                });
            },

            url(page) {
                if (!page) {
                    let query = location.search.match(/page=(\d+)/);
                    page = query ? query[1] : 1;
                }
                return `/api/audit_actions?page=${page}`;
            },

            refresh({data}) {
                this.dataSet = data;
                this.items = data.data;
            },

            async process() {
                //    let self = this;
                //    await this.form.post('/api/two_factor_auth/select_method')
                //   .then(function (response) {
                //        self.tfaQrCode = response.data.qrcode;
                //     })
            },

            getRecoveryCodes: function () {
                // this.formBackup.post('/api/two_factor_auth/select_method')
                // .then((response) => {
                //         this.recoveryCodes = JSON.parse(JSON.stringify(response.data.recovery_codes));
                //         console.log(response);
                // });
            }
        }
    };
</script>
