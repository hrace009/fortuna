<template>
  <div v-if="form.attachments" class="attachments m--margin-bottom-10">
    <div class="attachment d-inline-flex m--margin-top-20 m--margin-right-10">
      <i class="flaticon-attachment" />
      <div class="d-flex flex-1 flex-column">
        <div class="file-name">{{ form.attachments.name }}</div>
        <div class="file-size"></div>
      </div>
      <div class="d-flex attachment-options" title="Remover anexo" v-tippy>
        <a
          href="#"
          target="_blank"
          class="btn m--font-metal m-btn--hover-metal m-btn--pill m-btn m-btn--icon m-btn--icon-only"
          @click="removeFile()"
        >
          <i class="fal fa-trash-alt"></i>
        </a>
      </div>
    </div>
  </div>
</template>
