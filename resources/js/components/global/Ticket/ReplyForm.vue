<template>
  <div>
      <form
          @submit.prevent="submit"
          @keydown="form.onKeydown($event)"
          class="m-form m-form--fit m-form--label-align-right m--margin-top-20"
      >
          <div class="kt-inbox__body">
              <div class="kt-inbox__editor">
                  <div class="form-group" :class="{'has-danger': form.errors.has('message')}">
                        <textarea
                            name="enter-message"
                            class="form-control content-group"
                            rows="4"
                            cols="1"
                            :placeholder="placeholder"
                            v-model="form.message"
                            :class="{ 'is-invalid': form.errors.has('message') }"
                        ></textarea>
                      <has-error :form="form" field="message"></has-error>
                  </div>
              </div>
          </div>
          <div class="kt-inbox__foot">
              <div
                  class="kt-inbox__primary"
              >
                <!-- Attachments -->
                  <attachment-upload @attachmentAdd="handleAttachmentAdd" @attachmentRemoved="handleAttachmentRemoved"/>
                  <v-button :class="'btn btn-brand btn-bold'" :loading="form.busy">Responder</v-button>
              </div>
          </div>
      </form>
  </div>
</template>

<script>
import files from "~/mixins/files";

export default {
  name: "TicketReplyForm",

  mixins: [files],

  props: {
    submit: {
      type: Function,
      required: true
    },
    form: {
      type: Object,
      required: true
    },
    placeholder: {
      type: String,
      default: "Digite sua mensagem..."
    }
  },

  methods: {
    
    handleAttachmentAdd(file) {
       this.form.attachments = file.serverId;
    },

    handleAttachmentRemoved() {
        this.form.attachments = null;
    },
    
  }
};
</script>
