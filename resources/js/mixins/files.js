export default {
    methods: {
        openFileDialog() {
            this.$refs.input.click()
        },

        addFile() {
            this.form.attachments = this.$refs.input.files[0];
        },
    }
  }
  