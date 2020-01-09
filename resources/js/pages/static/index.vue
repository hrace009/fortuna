<template>
<div>
<div class="m-portlet" v-if="user">
    <div class="m-portlet__body">
        <div class="article-wrapper">
            <article class="legal">
                <h1>{{ page.name }}</h1>
                <p class="m--margin-top-20">
                    <strong>Última atualização, {{ page.updated_at | monthDayYear }}</strong>
                </p>
                <span v-html="page.body"></span>
            </article>
        </div>
    </div>
</div>
<div class="m-grid__item m-grid__item--fluid m-grid m-grid--hor-desktop m-grid--desktop m-body" v-if="!user">
    <div class="m-grid__item m-grid__item--fluid  m-grid m-grid--ver m-container m-container--responsive m-container--xxl m-page__container">
        <div class="m-grid__item m-grid__item--fluid m-wrapper">
            <div class="m-portlet">
                <div class="m-portlet__body">
                    <div class="article-wrapper">
                        <article class="legal">
                            <h1>{{ page.name }}</h1>
                            <p class="m--margin-top-20">
                                <strong>Última atualização, {{ page.updated_at | monthDayYear }}</strong>
                            </p>
                            <span v-html="page.body"></span>
                        </article>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</template>

<script>
    import { mapGetters } from 'vuex'

    export default {
        data: () => ({
            page: {},
        }),

        computed: {
            ...mapGetters({
                user: 'auth/user'
            }),
        },

        created () {
            this.getPage()
        },

        watch: {
          '$route.path': function (id) {
             this.getPage()
            }
        },

        methods: {
            async getPage() {
                let path = this.$route.path.replace(/\//g, '')
                let page = this.$route.params.page ? this.$route.params.page : path
                await axios.get(`/api/static_pages/${page}`)
                .then(( { data } ) => {
                    this.page = data;
                })
                .catch (error => {
                    this.$notify({ title: 'OOps', type: 'danger', text: '' })
                })
            }
        },
    }
</script>

<style>
    .article-wrapper {
        line-height: 1.5;
        font-weight: 400;
        font-size: 14px;
        padding: 30px 45px;
    }

    article.legal {
        text-align: justify;
    }
</style>
