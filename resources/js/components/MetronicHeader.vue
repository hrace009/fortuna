<template>
    <!-- BEGIN: Header -->
    <div id="kt_header" class="kt-header kt-grid__item  kt-header--fixed "  data-ktheader-minimize="on" ref="ktHeader">
        <div class="kt-header__top">
            <div class="kt-container">
                <!-- begin:: Brand -->
                <div class="kt-header__brand   kt-grid__item">
                    <a href="/" class="kt-header__brand-logo">
                        <img :alt="`Logo ${appName}`" src="https://painel-pw4fun.s3.sa-east-1.amazonaws.com/logo.png">
                    </a>
                </div>
                <!-- end:: Brand -->
                <!-- begin:: Header Topbar -->
                <div class="kt-header__topbar">
                    <notifications v-if="user"/>
                    <div class="kt-header__topbar-item kt-header__topbar-item--user">
                        <div class="kt-header__topbar-wrapper" data-toggle="dropdown" data-offset="10px,10px"
                             aria-expanded="false">
                            <span class="kt-header__topbar-welcome" v-if="user">Ola,&nbsp</span>
                            <span class="kt-header__topbar-username">{{ firstName }}</span>
                            <img :src="user.avatar" class="kt--img-rounded kt--marginless kt--img-centered"
                                 :alt="user.name" v-if="user">
                        </div>
                        <div
                            class="dropdown-menu dropdown-menu-fit dropdown-menu-right dropdown-menu-anim dropdown-menu-xl">
                            <!--begin: Head -->
                            <div class="kt-user-card kt-user-card--skin-light kt-notification-item-padding-x">
                                <div class="kt-user-card__avatar">
                                    <img alt="Pic" :src="user.avatar"/>
                                </div>
                                <div class="kt-user-card__name">
                                    {{ firstName }}
                                </div>
                            </div>
                            <!--end: Head -->
                            <!--begin: Navigation -->
                            <div class="kt-notification">
                                <router-link
                                    :to="{ name: tab.route}" v-for="(tab, index) in tabs"
                                    class="kt-notification__item"
                                    :key="index"
                                >
                                    <div class="kt-notification__item-icon">
                                        <i class="kt-nav__link-icon" :class="`${tab.icon} ${tab.color}`"/>
                                    </div>
                                    <div class="kt-notification__item-details">
                                        <div class="kt-notification__item-title kt-font-bold">
                                            {{ tab.name }}
                                        </div>
                                        <div class="kt-notification__item-time">
                                            {{ tab.description }}
                                        </div>
                                    </div>
                                </router-link>
                                <div class="kt-notification__custom kt-space-between">
                                    <a @click.prevent="logout" class="btn btn-label btn-label-brand btn-sm btn-bold">
                                        Deslogar
                                    </a>
                                </div>
                            </div>
                            <!--end: Navigation -->
                        </div>
                    </div>
                </div>
                <!-- end:: Header Topbar -->
            </div>
        </div>
        <div class="kt-header__bottom" v-if="user">
            <div class="kt-container ">
                <button class="kt-header-menu-wrapper-close" id="kt_header_menu_mobile_close_btn">
                    <i class="la la-close"/>
                </button>
                <metronic-navbar/>
            </div>
        </div>
    </div>
    <!-- END: Header -->
</template>

<script>
    import {mapGetters} from 'vuex'
    import MetronicNavbar from './MetronicNavbar'
    import Notifications from './Notifications1'
    import objectPath from "object-path";
    
    export default {
        data: () => ({
            appName: window.config.appName,
            servers: window.config.servers
        }),

        components: {
            MetronicNavbar,
            Notifications
        },

        computed: {
            ...mapGetters({
                user: "auth/user"
            }),

            firstName() {
                const {name} = this.user;

                return name;
            },

            tabs() {
                return [
                    {
                        icon: "flaticon2-calendar-3",
                        color: "kt-font-success",
                        name: 'Minha Conta',
                        description: "Configurações e dados da conta",
                        route: "settings.profile"
                    },
                    {
                        icon: "flaticon2-protected",
                        color: "kt-font-danger",
                        name: 'Segurança',
                        description: "Histórico de ações",
                        route: "settings.security"
                    },
                    {
                        icon: "flaticon2-group",
                        color: "kt-font-info",
                        name: "Contas de jogo",
                        description: "Contas que você usa pra logar no jogo",
                        route: "game.accounts"
                    }
                ];
            }
        },

        mounted () {
            let headerRef = this.$refs.ktHeader;
            
            let options = {
                classic: {
                    desktop: true,
                    mobile: false
                }
            };
            
            if (headerRef.getAttribute("data-ktheader-minimize") === "on") {
                objectPath.set(options, "minimize", {
                    desktop: {
                        on: "kt-header--minimize"
                    },
                    mobile: {
                        on: "kt-header--minimize"
                    }
                });
                objectPath.set(options, "offset", {
                    desktop: 200,
                    mobile: 150
                });
            }            
           new KTHeader(headerRef, options);
        },
        
        methods: {
            async logout() {
                // Log out the user.
                await this.$store.dispatch("auth/logout");

                // Redirect to login.
                this.$router.push({
                    name: "login"
                });
            }
        }
    };
</script>