<template>
    <div class="m-portlet">
        <!-- begin head -->
        <div class="m-portlet__body">
            <div class="m--align-center">
                <img src="https://pw4fun-painel.s3.sa-east-1.amazonaws.com/assets/svg/play.svg" width="280px" height="190px" />
                <div class="m--padding-top-20 m--font-bold">
                    <p v-if="accounts.length">Deseja criar novas contas de jogo? =)</p>
                    <p v-else>
                        <strong>Pera aí!</strong> Parece que você ainda não criou uma conta de jogo
                    </p>
                </div>
            </div>
            <div v-if="accounts.length">
                <hr class="m--margin-top-10">
                <router-link
                        :to="{ name: 'game.accounts' }"
                        class="m-link m-link-brand"
                >Ver todas minhas contas de jogo</router-link>
            </div>
        </div>
    </div>
</template>

<script>

    import fetchGameAccounts from '~/mixins/game_accounts'

    export default {
        name: 'GameAccounts',

        mixins: [fetchGameAccounts],

        data: () => ({})
    }
</script>