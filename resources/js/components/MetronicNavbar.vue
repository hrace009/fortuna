<template>
    <div class="kt-header-menu-wrapper" id="kt_header_menu_wrapper">
        <div
            id="kt_header_menu"
            class="kt-header-menu kt-header-menu-mobile  "
        >
            <ul class="kt-menu__nav">
                <router-link
                    :to="{ name: 'home' }"
                    class="kt-menu__item m  kt-menu__item m "
                    tag="li"
                    active-class="kt-menu__item--here"
                >
                    <router-link
                        :to="{ name: 'home' }"
                        class="kt-menu__link"
                    >
                        <span class="kt-menu__item kt-here" />
                        <span class="kt-menu__link-text">Home</span>
                    </router-link>
                </router-link>
                <router-link
                    :to="{ name: 'tickets' }"
                    class="kt-menu__item m  kt-menu__item m "
                    tag="li"
                    active-class="kt-menu__item--here"
                >
                    <router-link
                        :to="{ name: 'tickets' }"
                        class="kt-menu__link"
                    >
                        <span class="kt-menu__item kt-here" />
                        <span class="kt-menu__link-text">Suporte</span>
                    </router-link>
                </router-link>
                <router-link
                    :to="{ name: 'donate.make' }"
                    class="kt-menu__item m  kt-menu__item m "
                    tag="li"
                    active-class="kt-menu__item--here"
                >
                    <router-link
                        :to="{ name: 'donate.make' }"
                        class="kt-menu__link"
                    >
                        <span class="kt-menu__item kt-here" />
                        <span class="kt-menu__link-text">Fazer Doação</span>
                    </router-link>
                </router-link>
                <router-link
                    :to="{ name: 'donate.history' }"
                    class="kt-menu__item m  kt-menu__item m "
                    tag="li"
                    active-class="kt-menu__item--here"
                >
                    <router-link
                        :to="{ name: 'donate.history' }"
                        class="kt-menu__link"
                    >
                        <span class="kt-menu__item kt-here" />
                        <span class="kt-menu__link-text">Minhas Doações</span>
                    </router-link>
                </router-link>
                <router-link
                    :to="{ name: 'settings.profile' }"
                    class="kt-menu__item m  kt-menu__item m "
                    tag="li"
                    active-class="kt-menu__item--here"
                >
                    <router-link
                        :to="{ name: 'settings.profile' }"
                        class="kt-menu__link"
                    >
                        <span class="kt-menu__item kt-here" />
                        <span class="kt-menu__link-text">Minha Conta</span>
                    </router-link>
                </router-link>
                <router-link
                    :to="{ name: 'game.accounts' }"
                    class="kt-menu__item m"
                    tag="li"
                    active-class="kt-menu__item--here"
                >
                    <router-link
                        :to="{ name: 'game.accounts' }"
                        class="kt-menu__link"
                    >
                        <span class="kt-menu__item kt-here" />
                        <span class="kt-menu__link-text">Contas de jogo</span>
                    </router-link>
                </router-link>
            </ul>
        </div>
    </div>
</template>

<script>
import { mapGetters } from 'vuex';

export default {
  computed: mapGetters({
    user: 'auth/user',
  }),
};
</script>
