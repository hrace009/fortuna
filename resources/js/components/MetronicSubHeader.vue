<template>
  <div class="kt-subheader__main">
      <h3 class="kt-subheader__title">{{ this.$route.meta.title }}</h3>
    <router-link
      v-if="this.$route.meta.link"
      :to="{ name: this.$route.meta.link.name }"
      class="btn kt-btn--pill kt-btn--air btn-primary kt-btn kt-btn--icon kt-btn--custom"
    >
      <span>
        <i :class="this.$route.meta.link.icon" />
        <span>{{ this.$route.meta.link.title }}</span>
      </span>
    </router-link>
  </div>
</template>
