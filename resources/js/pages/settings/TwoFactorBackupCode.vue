<template>
<div class="m--margin-top-20" v-if="backupCodes.length">
    <h4 class="text-bold text-uppercase">Códigos de recuperação</h4>
    <p>Estes códigos permitirão que você consiga acessar sua conta caso algo aconteça com seu celular. Cada código só pode
        ser usado uma vez!
    </p>
    <div class="m--margin-top-20">
        <ul class="list-unstyled" style="flex: 1 1 auto;">
            <li v-for="(item, index) in backupCodes" :key="index">
                <label class="m-checkbox m-checkbox--square">
                    <input type="checkbox" :checked="item.consumed ? 'checked' : null" disabled> {{ item.code }}
                    <span></span>
                </label>
            </li>
        </ul>
    </div>
</div>
</template>

<script>
export default {
    props: {
        backupCodes: {
            type: Array,
            required: true,
        }
    }
}
</script>
