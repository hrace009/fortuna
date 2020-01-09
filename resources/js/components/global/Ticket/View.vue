<template>
  <div class="m-widget3">
    <div class="m-widget3__item">
      <div class="m-widget3__content">
        <div class="m-widget3__header">
          <div class="m-widget3__user-img">
            <img
              class="m-widget3__img"
              :src="ticket.owner.avatar"
              alt
            >
          </div>
          <div class="m-widget3__info">
            <span class="m-widget3__username">{{ ticket.owner.name }}</span>
            <br>
            <span class="m-widget3__time">{{ ticket.created_at }}</span>
          </div>
        </div>
        <div class="m-widget3__body">
          <p class="m-widget3__text">
            {{ ticket.message }}
          </p>
        </div>
        <!-- .ticket-attachments -->
        <ticket-files :files="ticket.attachments" />
      </div>
    </div>
    <ticket-replies :replies="ticket.replies" />
  </div>
</template>

<script>
export default {
  name: 'TicketView',

  props: {
    ticket: {
      type: Object,
      required: true,
    },
  },

  /* data: () => ({
    loading: false,
    ticket: {},
  }),

  created() {
    this.getTicket();
  },

  methods: {
    getTicket() {
      axios.get(`/api/tickets/${this.$route.params.id}?include=replies,file`)
        .then(({ data }) => {
          this.ticket = data.data;
        });
    },
  }, */

};
</script>
