    <script>
        export default {
            middleware: 'auth',

            created () {
                this.update();
            },

            methods: {
                async update() {
                    const { data } = await axios.post('/api/email/confirm/' + this.$route.params.token)
                    this.$store.dispatch('auth/updateUser', { user: data })
                    window.location.href = '/home'
                }
            }
        }
    </script>
