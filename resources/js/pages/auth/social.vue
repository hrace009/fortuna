<template>
    <div>        
        <!--begin::Divider-->
        <div class="kt-login__divider">
            <div class="kt-divider">
                <span></span>
                <span>OU</span>
                <span></span>
            </div>
        </div>
        <!--end::Divider-->
        <div class="kt-login__options">
            <!-- Facebook Register Button -->
            <a href="/oauth/facebook" class="btn btn-facebook">
                <fa :icon="['fab', 'facebook-f']"/>
                <span>Facebook</span>
            </a>
            <!-- Facebook Register Button -->
            <a href="/oauth/google" class="btn btn-google">
                <fa :icon="['fab', 'google']"/>
                <span>Google</span>
            </a>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'SocialAuth'
    }
</script>