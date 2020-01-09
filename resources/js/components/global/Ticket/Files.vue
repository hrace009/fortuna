<template>
  <div class="attachments kt-margin-t-20 kt-margin-b-10" v-if="files">
    <div
      v-for="(file, index) in files"
      :key="index"
      class="attachment d-inline-flex m--margin-right-10"
    >
      <i class="flaticon-attachment"></i>
      <div class="d-flex flex-1 flex-column">
        <div class="file-name">{{ file.name }}</div>
        <div class="file-size">{{ file.size }}</div>
      </div>
      <div class="d-flex attachment-options" title="Visualizar anexo" v-tippy>
        <a
          :href="file.url"
          target="_blank"
          class="btn btn-light btn-circle btn-icon"
        >
          <i class="la la-eye"></i>
        </a>
      </div>
    </div>
  </div>
</template>


<script>
export default {
  name: "TicketFiles",

  props: {
    files: {
      type: Array,
      required: true
    }
  }
};
</script>
