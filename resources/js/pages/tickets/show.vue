<template>
  <div class="row">
    <ticket-details :ticket="ticket" :loading="loading"></ticket-details>
    <div class="col-md-9">
        <div class=" kt-inbox">
            <portlet :wrapper="'d-flex kt-grid__item kt-grid__item--fluid  kt-inbox__view kt-inbox__view--shown-'"
                     :body="`kt-portlet__body--fit-x`">
                <template v-if="loading">
                    <div class="d-flex justify-content-center">
                        <div class="kt-spinner kt-spinner--v2 kt-spinner--lg kt-spinner--info"></div>
                    </div>
                </template>
                <template v-else>
                    <!-- begin::Inbox-->
                    <div class="kt-inbox__subject">
                        <div class="kt-inbox__title">
                            <div class="kt-inbox__text">
                                [#{{ ticket.id }}] {{ ticket.subject }}
                            </div>
                        </div>
                    </div>
                    <!-- messages -->
                    <div class="kt-inbox__messages">
                        <div class="kt-inbox__message kt-inbox__message--expanded">
                            <div class="kt-inbox__head">
                                <span class="kt-media"  data-toggle="expand" :style="`background-image: url(${ticket.owner.avatar})`">
                                    <span></span>
                                </span>
                                <div class="kt-inbox__info">
                                    <div class="kt-inbox__author">
                                        <span class="kt-inbox__name">{{ ticket.owner.name }}</span>
                                        <div class="kt-inbox__status">
                                            {{ ticket.created_at | formatDateDistance }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="kt-inbox__body">
                                <div class="kt-inbox__text">
                                    {{ ticket.message }}
                                </div>
                                <ticket-files :files="ticket.attachments" />
                            </div>
                        </div>
                        <ticket-replies :replies="ticket.replies" />
                    </div>
                    
                    <!-- end::Inbox -->
                    <div class="kt-inbox__reply kt-inbox__reply--on">
                        <ticket-reply-form :submit="addReply" :form="form" />
                    </div>
                </template>
            </portlet>
        </div>
    </div>
  </div>
</template>

<script>
import Form from "vform";
import { mapGetters } from "vuex";
import axios from "axios";

export default {
  middleware: "auth",

  data: () => {
    return {
      form: new Form({
        message: "",
        attachments: ""
      }),
      ticket: [],
      loading: true
    };
  },
  
  computed: mapGetters({
    user: "auth/user"
  }),

  created() {
    this.fetchTicketInformation();
  },

   methods: {

    addReply() {
      this.loading = true;
      this.form
        .post(`/api/tickets/${this.$route.params.id}/reply`)
        .then(response => {
          this.loading = false;
          this.ticket.replies.push(response.data);
          this.fetchTicketInformation();
          this.$notify({
            title: "Ótimo!",
            type: "success",
            text: "Sua mensagem foi adicionada com sucesso."
          });
        })
        .catch(error => {
          this.loading = false;
        });
    },

    replyFile(event) {
      let userFile = event.target.files[0];
      this.form.file = userFile;
    },

    // Update ticket information on (re)load and
    // when new answer is submmitted
    async fetchTicketInformation() {
      await axios
        .get(`/api/tickets/${this.$route.params.id}`)
        .then(response => {
          this.loading = false;
          this.ticket = response.data.data;
        })
        .catch(error => {
          this.$router.push({
            name: "tickets"
          });
          this.$notify({
            title: "Oops!",
            type: "error",
            text: error.response.data.error
          });
        });
    }
  }
};
</script>

<style>
.m-border--dark {
  border: 1px solid #2c2e3e;
  background: #2c2d3a;
  color: #fff !important;
}

.m-bg--grey {
  background: #f4f5f8;
  border: 1px solid #f4f5f8;
}

.m-widget3__signature {
  display: block;
}
</style>