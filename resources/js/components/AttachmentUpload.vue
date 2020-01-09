<template>
    <div class="form-group m-form__group">
        <label for="">Anexos (opcional):</label>
        <file-pond
        name="attachments"
        ref="pond"
        label-idle="<span class='filepond--label-action kt-font-brand kt-font-bold'>Adicione o arquivo</span> ou arraste aqui"
        :allow-multiple="false"
        accepted-file-types="image/jpeg, image/png"
        :server="server"
        @processfile="handleProcessFile"
        @removefile="handleRemoveFile"
        />
    </div>
</template>

<script>

import vueFilePond from 'vue-filepond';
import 'filepond/dist/filepond.min.css';
import 'filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.css';
import FilePondPluginFileValidateType from 'filepond-plugin-file-validate-type';
import FilePondPluginImagePreview from 'filepond-plugin-image-preview';

const FilePond = vueFilePond(FilePondPluginFileValidateType, FilePondPluginImagePreview);

export default {

    name: 'AttachmentUpload',

   components: {
        FilePond
    },

    data () {
        return {
            server: {
                process (fieldName, file, metadata, load, error, progress, abort, transfer, options) {
                
                    const formData = new FormData();
                    formData.append(fieldName, file, file.name);

                    axios.post('/api/media_uploads', formData, {
                    onUploadProgress: (e) => {
                        progress(e.lengthComputable, e.loaded, e.total);
                    }
                    })
                    .then((response) => {
                        
                        const { status, data } = response;

                        load(data);

                    })
                    .catch((error) => {
                        console.log(error)
                    })
                },
                revert (uniqueFileId, load, error) {
                axios.delete('/api/media_uploads', { data: uniqueFileId })
                .then(() => load())
                .catch(() => error('oh my godnesss'))
                }
            }
        }
    },

    methods: {
        handleProcessFile(event, file) {
            this.$emit('attachmentAdd', file);
        },

        handleRemoveFile() {
            this.$emit('attachmentRemoved', { success: true })
        }
    }
    
}
</script>