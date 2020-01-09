<template>
  <div class="kt-portlet" :class="wrapper">
    <div v-if="title" class="kt-portlet__head">
        <div class="kt-portlet__head-label">            
            <span v-if="icon" class="kt-portlet__head-icon">
                <i :class="icon"/>
            </span>
            <h3 class="kt-portlet__head-title">{{ title }}</h3>
        </div>
        <div class="kt-portlet__head-toolbar">
            <slot name="button" v-if="button"/>
        </div>
    </div>
    <div class="kt-portlet__body" :class="body">
        <slot/>
    </div>
  </div>
</template>

<script>
export default {
  name: 'Portlet',

  props: ['title', 'subtitle',  'icon', 'button', 'body', 'wrapper']
}
</script>
