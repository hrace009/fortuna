<template>
    <portlet :title="'Completar cadastro'">
        <div>
            <div class="alert alert-solid-brand alert-bold">
              <div class="alert-text">
                  Antes de realizar uma doação, você precisa informar o seu CPF, ele é obrigatório
                  para garantir a segurança da transação (somente você e o Meio de Pagamento tem acesso).
              </div>
            </div>
            <form class="m-form m-form--state"  @submit.prevent="submit"
                  @keydown="form.onKeydown($event)">
                <div
                        class="form-group m-form__group"
                        :class="{ 'has-danger': form.errors.has('document') }"
                >
                    <label>CPF
                        <span class="text-danger">*</span>
                    </label>
                    <input
                            v-model="form.document"
                            type="text"
                            class="form-control"
                            :class="{ 'is-invalid': form.errors.has('document') }"
                    >
                    <has-error :form="form" field="document"></has-error>
                </div>
                <v-button
                        :class="'btn m-btn--air btn-primary m-btn--wide'"
                        :loading="form.busy"
                >
                   Confirmar
                </v-button>
            </form>
        </div>
    </portlet>
</template>

<script>

    import Form from 'vform'
    import { mapGetters } from 'vuex'

    export default {
        name: "document",

        data: () => ({
            form: new Form({
                document: ''
            })
        }),

        computed: mapGetters({
            user: 'auth/user'
        }),

        methods: {
            submit() {
                this.form.patch('/api/user/update_document')
                .then((response) => {
                    this.$store.dispatch('auth/updateUser', { user: response.data.data })

                    this.$notify({
                        type: "success",
                        text: "CPF atualizado com sucesso!"
                    });
                })
            }
        }

    }
</script>

<style scoped>

</style>