import { mapGetters } from "vuex";

export default {

    computed: {
        ...mapGetters({
            user: "auth/user"
        })
    },

    methods: {
        lastActivityText(ticket) {
            if (ticket.closed) {
                return 'Fechado'
            }

            if (!ticket.last_reply) {
                return "Aberto"
            }

            let owner = ticket.last_reply.owner.id
            if (owner === this.user.id) {
                return "Você respondeu";
            } else if (owner !== this.user.id) {
                return "Equipe respondeu"
            } 
        }
    }
}