<template>
    <portlet :title="'Foto do Perfil'">
        <form class="m-form">
            <div class="form-group m-form__group row">
                <label class="col-md-4 col-form-label">&nbsp;</label>
                <div class="col-md-6">
                    <img :src="user.avatar" :alt="user.name" class="photo-preview">
                </div>
            </div>
            <!-- Atualizar -->
            <div class="form-group m-form__group row">
                <label class="col-md-4 col-form-label">&nbsp;</label>
                <div class="col-md-6">
                    <file-uploader @upload-success="updateUser($event)" :url="uploadAvatarUrl" file-key="avatar"></file-uploader>
                </div>
            </div>
        </form>
    </portlet>
</template>

<script>
import Form from "vform";
import Inputmask from "inputmask";
import { mapGetters } from "vuex";

export default {
  middleware: "auth",

  computed: {
    ...mapGetters({
      user: "auth/user"
    }),
    uploadAvatarUrl () {
        return '/api/settings/profile/avatar'
    } 
  },

  methods: {
    async updateUser(data) {
      this.$store.dispatch("auth/updateUser", {
        user: data.data
      });
    }
  }
};
</script>